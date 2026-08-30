<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('receipt_url')->nullable()->after('notes');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->string('receipt_url')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('receipt_url');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('receipt_url');
        });
    }
};
