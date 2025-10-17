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
        Schema::create('salones', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->string('codigo', 60)->unique();  // Código único
            $table->integer('capacidad')->unsigned()->check('capacidad > 0');  // Capacidad > 0
            $table->string('ubicacion', 160)->nullable();  // Ubicación
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->index('codigo');  // Índice para búsquedas rápidas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salones');
    }
};
