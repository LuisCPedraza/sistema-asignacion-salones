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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->char('grupo_id', 36);  // FK to grupos.id (UUID)
            $table->char('salon_id', 36);  // FK to salones.id (UUID)
            $table->char('profesor_id', 36);  // FK to profesores.id (UUID)
            $table->enum('dia_semana', ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['propuesta', 'confirmada', 'cancelada'])->default('propuesta');  // Estado
            $table->decimal('score', 5, 2)->nullable();  // Score (null si no calculado)
            $table->json('conflictos')->nullable();  // Conflictos JSON
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->foreign('salon_id')->references('id')->on('salones')->onDelete('cascade');
            $table->foreign('profesor_id')->references('id')->on('profesores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
