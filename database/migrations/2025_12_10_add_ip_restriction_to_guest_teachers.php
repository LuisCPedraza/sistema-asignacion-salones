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
        Schema::table('teachers', function (Blueprint $table) {
            // Agregar campo para restringir acceso por IP
            $table->string('ip_address_allowed')->nullable()->comment('IP o IPs permitidas (separadas por coma, soporta wildcards ej: 192.168.1.*)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('ip_address_allowed');
        });
    }
};
