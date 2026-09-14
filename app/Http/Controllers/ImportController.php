<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Unit;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    protected function checkSuperAdmin()
    {
        abort_unless(auth()->check() && auth()->user()->isSuperAdmin(), 403, 'Acceso denegado. Solo el Super Administrador puede importar datos.');
    }

    public function show()
    {
        $this->checkSuperAdmin();
        return view('import');
    }

    public function downloadTemplate()
    {
        $this->checkSuperAdmin();
        $headers = [
            'propiedad_nombre',
            'propiedad_direccion',
            'propiedad_descripcion',
            'unidad_nombre',
            'unidad_tipo',
            'unidad_habitaciones',
            'unidad_banos',
            'unidad_renta_mensual',
            'inquilino_nombre',
            'inquilino_telefono',
            'inquilino_email',
            'inquilino_fecha_inicio',
            'inquilino_dia_pago'
        ];

        $exampleRow = [
            'Casa del Sol',
            'Av. Solidaridad 456, Col. Centro',
            'Propiedad residencial con varios departamentos',
            'Depto 101',
            'apartment',
            '2',
            '1',
            '4500.00',
            'Juan Pérez',
            '5551234567',
            'juan@example.com',
            '2026-08-01',
            '5'
        ];

        $callback = function() use ($headers, $exampleRow) {
            $file = fopen('php://output', 'w');
            
            // Write UTF-8 BOM to make Excel open it with correct accents
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $headers);
            fputcsv($file, $exampleRow);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=plantilla_carga_masiva.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    public function upload(Request $request)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // 1. Read file lines to detect delimiter
        $fileHandle = fopen($path, 'r');
        $firstLine = fgets($fileHandle);
        fclose($fileHandle);

        if (!$firstLine) {
            return redirect()->back()->with('error', 'El archivo subido está vacío o es inválido.');
        }

        // Count commas vs semicolons
        $commas = substr_count($firstLine, ',');
        $semicolons = substr_count($firstLine, ';');
        $delimiter = $semicolons > $commas ? ';' : ',';

        // 2. Parse CSV
        $fileHandle = fopen($path, 'r');
        
        // Skip UTF-8 BOM if present
        $bom = fread($fileHandle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($fileHandle);
        }

        $headers = fgetcsv($fileHandle, 0, $delimiter);
        
        // Clean headers (remove white space, lowercase)
        if ($headers) {
            $headers = array_map(function($header) {
                return trim(strtolower($header));
            }, $headers);
        } else {
            fclose($fileHandle);
            return redirect()->back()->with('error', 'No se pudieron leer las cabeceras del archivo.');
        }

        // Map header titles to expected columns
        $expectedHeaders = [
            'propiedad_nombre',
            'propiedad_direccion',
            'propiedad_descripcion',
            'unidad_nombre',
            'unidad_tipo',
            'unidad_habitaciones',
            'unidad_banos',
            'unidad_renta_mensual',
            'inquilino_nombre',
            'inquilino_telefono',
            'inquilino_email',
            'inquilino_fecha_inicio',
            'inquilino_dia_pago'
        ];

        // Check if required headers exist (at least propiedad_nombre)
        if (!in_array('propiedad_nombre', $headers)) {
            fclose($fileHandle);
            return redirect()->back()->with('error', 'El archivo no contiene la columna requerida: propiedad_nombre.');
        }

        $logs = [];
        $totals = [
            'houses_created' => 0,
            'units_created' => 0,
            'tenants_created' => 0,
            'errors_count' => 0,
        ];

        $rowNumber = 1; // header is row 1, data starts at 2
        while (($row = fgetcsv($fileHandle, 0, $delimiter)) !== FALSE) {
            $rowNumber++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Combine headers and row data
            $rowData = [];
            foreach ($headers as $index => $header) {
                if (isset($row[$index])) {
                    $rowData[$header] = trim($row[$index]);
                } else {
                    $rowData[$header] = null;
                }
            }

            // Normalize fields
            $propNombre = $rowData['propiedad_nombre'] ?? null;
            $propDireccion = $rowData['propiedad_direccion'] ?? '';
            $propDesc = $rowData['propiedad_descripcion'] ?? null;

            $unitNombre = $rowData['unidad_nombre'] ?? null;
            $unitTipo = $rowData['unidad_tipo'] ?? 'apartment';
            $unitHabitaciones = isset($rowData['unidad_habitaciones']) ? (int)$rowData['unidad_habitaciones'] : 0;
            $unitBanos = isset($rowData['unidad_banos']) ? (int)$rowData['unidad_banos'] : 0;
            $unitRenta = isset($rowData['unidad_renta_mensual']) ? (float)$rowData['unidad_renta_mensual'] : 0;

            $inquNombre = $rowData['inquilino_nombre'] ?? null;
            $inquTelefono = $rowData['inquilino_telefono'] ?? null;
            $inquEmail = $rowData['inquilino_email'] ?? null;
            $inquFechaInicio = $rowData['inquilino_fecha_inicio'] ?? null;
            $inquDiaPago = isset($rowData['inquilino_dia_pago']) ? (int)$rowData['inquilino_dia_pago'] : 5;

            // Validation check for row
            if (!$propNombre) {
                $logs[] = [
                    'row' => $rowNumber,
                    'status' => 'error',
                    'message' => 'Falta "propiedad_nombre", que es requerido para identificar o crear una casa.'
                ];
                $totals['errors_count']++;
                continue;
            }

            try {
                DB::beginTransaction();

                // 1. House Processing
                $house = House::where('name', $propNombre)->first();
                $houseCreated = false;
                if (!$house) {
                    $house = House::create([
                        'name' => $propNombre,
                        'address' => $propDireccion ?: 'Dirección no especificada (importada)',
                        'description' => $propDesc,
                        'photo_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80',
                    ]);
                    $houseCreated = true;
                    $totals['houses_created']++;
                }

                // 2. Unit Processing
                $unit = null;
                $unitCreated = false;
                if ($unitNombre) {
                    $unit = Unit::where('house_id', $house->id)->where('name', $unitNombre)->first();
                    if (!$unit) {
                        $unit = Unit::create([
                            'house_id' => $house->id,
                            'name' => $unitNombre,
                            'type' => in_array($unitTipo, ['apartment', 'commercial', 'house']) ? $unitTipo : 'apartment',
                            'rooms' => $unitHabitaciones,
                            'bathrooms' => $unitBanos,
                            'base_rent_cost' => $unitRenta,
                            'status' => 'vacant',
                        ]);
                        $unitCreated = true;
                        $totals['units_created']++;
                    }
                }

                // 3. Tenant Processing
                $tenantCreated = false;
                $warningMsg = null;
                if ($unit && $inquNombre) {
                    if ($unit->status === 'occupied') {
                        $warningMsg = "La unidad \"{$unitNombre}\" ya está ocupada; se omitió el registro del inquilino \"{$inquNombre}\".";
                    } else {
                        // Validate date format
                        $startDate = date('Y-m-d');
                        if ($inquFechaInicio) {
                            $parsedDate = date_parse($inquFechaInicio);
                            if ($parsedDate['error_count'] == 0 && $parsedDate['warning_count'] == 0) {
                                $startDate = $inquFechaInicio;
                            }
                        }

                        Tenant::create([
                            'unit_id' => $unit->id,
                            'full_name' => $inquNombre,
                            'phone' => $inquTelefono,
                            'email' => $inquEmail,
                            'start_date' => $startDate,
                            'payment_due_day' => ($inquDiaPago >= 1 && $inquDiaPago <= 31) ? $inquDiaPago : 5,
                            'is_active' => true,
                        ]);

                        $unit->update(['status' => 'occupied']);
                        $tenantCreated = true;
                        $totals['tenants_created']++;
                    }
                }

                DB::commit();

                // Log details
                $actions = [];
                if ($houseCreated) $actions[] = "Casa creada";
                else $actions[] = "Casa encontrada";

                if ($unitCreated) $actions[] = "Unidad creada";
                elseif ($unitNombre) $actions[] = "Unidad encontrada";

                if ($tenantCreated) $actions[] = "Inquilino registrado";

                $status = 'success';
                $msg = implode(', ', $actions) . '.';
                if ($warningMsg) {
                    $status = 'warning';
                    $msg .= ' ' . $warningMsg;
                }

                $logs[] = [
                    'row' => $rowNumber,
                    'status' => $status,
                    'message' => "Propiedad: {$propNombre} | " . $msg
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                $logs[] = [
                    'row' => $rowNumber,
                    'status' => 'error',
                    'message' => "Error en fila: " . $e->getMessage()
                ];
                $totals['errors_count']++;
            }
        }

        fclose($fileHandle);

        return view('import', compact('logs', 'totals'));
    }
}
