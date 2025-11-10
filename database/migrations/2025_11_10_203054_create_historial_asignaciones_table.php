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
        Schema::create('historial_asignaciones', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->char('asignacion_id', 36);  // FK to asignaciones.id (UUID)
            $table->unsignedBigInteger('user_id');  // FK to users.id (int)
            $table->enum('accion', ['create', 'update', 'delete']);  // Acción enum
            $table->json('cambios');  // Cambios JSON (e.g., {"old_estado": "propuesta", "new_estado": "confirmada"})
            $table->timestamp('fecha');  // Fecha del cambio
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->foreign('asignacion_id')->references('id')->on('asignaciones')->onDelete('cascade');  // FK asignación
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // FK usuario
            $table->index(['asignacion_id', 'fecha']);  // Composite index for queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_asignaciones');
    }
};
