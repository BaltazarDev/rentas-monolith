<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;

class DatabaseBackupController extends Controller
{
    public function download()
    {
        abort_unless(auth()->check() && auth()->user()->isSuperAdmin(), 403, 'Acceso denegado. Solo el Super Administrador puede exportar la base de datos.');

        $driver = DB::connection()->getDriverName();
        $filename = 'backup_' . config('database.connections.' . DB::connection()->getName() . '.database', 'rentas') . '_' . date('Y-m-d_H-i-s');

        try {
            if ($driver === 'sqlite') {
                $path = DB::connection()->getDatabaseName();
                if (!file_exists($path)) {
                    throw new Exception("El archivo de base de datos SQLite no existe.");
                }
                return response()->download($path, $filename . '.sqlite');
            }

            if ($driver === 'mysql') {
                return $this->downloadMysql($filename);
            }

            if ($driver === 'pgsql') {
                return $this->downloadPgsql($filename);
            }

            throw new Exception("Driver de base de datos no soportado para exportación automática: " . $driver);
        } catch (Exception $e) {
            return back()->with('error', 'Error al exportar la base de datos: ' . $e->getMessage());
        }
    }

    protected function downloadMysql($filename)
    {
        $headers = [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.sql"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $out = fopen('php://output', 'w');
            
            // Disable foreign key checks for import ease
            fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($out, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($out, "SET time_zone = \"+00:00\";\n\n");

            // Get tables
            $tables = DB::select("SHOW TABLES");
            $pdo = DB::connection()->getPdo();

            foreach ($tables as $table) {
                $prop = array_keys((array)$table)[0];
                $tableName = $table->$prop;
                
                // Get Table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableSql = $createTable[0]->{'Create Table'} ?? $createTable[0]->{'create table'} ?? null;
                
                if ($createTableSql) {
                    fwrite($out, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                    fwrite($out, "{$createTableSql};\n\n");
                }

                // Get Table data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    fwrite($out, "LOCK TABLES `{$tableName}` WRITE;\n");
                    
                    // Chunk rows to insert in groups of 100 to make SQL file smaller and faster to import
                    $chunks = $rows->chunk(100);
                    foreach ($chunks as $chunk) {
                        $insertSql = "INSERT INTO `{$tableName}` VALUES ";
                        $values = [];
                        foreach ($chunk as $row) {
                            $rowValues = [];
                            foreach ((array)$row as $val) {
                                if (is_null($val)) {
                                    $rowValues[] = 'NULL';
                                } else {
                                    $rowValues[] = $pdo->quote($val);
                                }
                            }
                            $values[] = "(" . implode(", ", $rowValues) . ")";
                        }
                        $insertSql .= implode(", ", $values) . ";\n";
                        fwrite($out, $insertSql);
                    }
                    fwrite($out, "UNLOCK TABLES;\n\n");
                }
            }

            fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function downloadPgsql($filename)
    {
        $headers = [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.sql"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $out = fopen('php://output', 'w');
            
            fwrite($out, "-- Respaldar Base de Datos PostgreSQL (Solo datos)\n");
            fwrite($out, "SET CONSTRAINTS ALL DEFERRED;\n\n");

            // Get tables
            $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
            $pdo = DB::connection()->getPdo();

            foreach ($tables as $table) {
                $tableName = $table->tablename;

                // Select all rows
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    fwrite($out, "TRUNCATE TABLE \"{$tableName}\" CASCADE;\n");
                    
                    $columns = Schema::getColumnListing($tableName);
                    $columnsStr = implode(", ", array_map(fn($c) => "\"{$c}\"", $columns));

                    foreach ($rows as $row) {
                        $rowValues = [];
                        foreach ($columns as $column) {
                            $val = $row->$column;
                            if (is_null($val)) {
                                $rowValues[] = 'NULL';
                            } else {
                                $rowValues[] = $pdo->quote($val);
                            }
                        }
                        fwrite($out, "INSERT INTO \"{$tableName}\" ({$columnsStr}) VALUES (" . implode(", ", $rowValues) . ");\n");
                    }
                    fwrite($out, "\n");
                }
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
