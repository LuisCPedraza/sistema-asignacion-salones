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
        Schema::create('restricciones_asignacion', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->enum('recurso_type', ['salon', 'profesor']);  // Tipo recurso
            $table->char('recurso_id', 36);  // FK to salones.id or profesores.id (UUID)
            $table->enum('tipo_restriccion', ['horario', 'capacidad', 'especial']);  // Tipo restricción
            $table->json('valor');  // Valor JSON (e.g., {"dias": ["lun", "mie"], "max_cap": 30})
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->index(['recurso_type', 'recurso_id']);  // Composite index for queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restricciones_asignacion');
    }
};