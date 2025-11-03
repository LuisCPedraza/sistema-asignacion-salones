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
        Schema::create('propuestas_asignacion', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->char('asignacion_id', 36);  // FK to asignaciones.id (UUID)
            $table->decimal('score', 5, 2);  // Score 0-100.00
            $table->json('conflictos')->nullable();  // Conflictos JSON (e.g., ["horario", "salon"])
            $table->integer('orden');  // Orden de propuesta (1, 2, 3...)
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->foreign('asignacion_id')->references('id')->on('asignaciones')->onDelete('cascade');  // FK constraint
            $table->unique(['asignacion_id', 'orden']);  // Unique per asignación and order
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propuestas_asignacion');
    }
};
