<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Eliminar la restricción única en (code, career_id)
            try {
                $table->dropUnique('subjects_code_career_unique');
            } catch (\Throwable $e) {
                // Ignorar si no existe (SQLite puede nombrar índices automáticamente)
            }
            // Agregar un índice no único para búsqueda eficiente
            try {
                $table->index(['code', 'career_id'], 'subjects_code_career_index');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Quitar el índice
            try {
                $table->dropIndex('subjects_code_career_index');
            } catch (\Throwable $e) {
            }
            // Restaurar la restricción única si se requiere
            try {
                $table->unique(['code', 'career_id'], 'subjects_code_career_unique');
            } catch (\Throwable $e) {
            }
        });
    }
};
