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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->string('key', 100)->unique();  // Clave única (e.g., 'horarios_default')
            $table->json('value');  // Valor JSON (parámetros, e.g., {"horarios": "Lun-Vie 8-18"})
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
