<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            if (!Schema::hasColumn('houses', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('description')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            if (Schema::hasColumn('houses', 'is_archived')) {
                $table->dropColumn('is_archived');
            }
        });
    }
};
