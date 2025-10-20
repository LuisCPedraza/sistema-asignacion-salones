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
        Schema::create('grupos', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->string('nombre', 120)->unique();  // Nombre único
            $table->enum('nivel', ['basico', 'intermedio', 'avanzado'])->default('basico');  // Nivel enum
            $table->integer('num_estudiantes')->unsigned()->check('num_estudiantes > 0');  // Num estudiantes >0
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->index('nivel');  // Índice para búsquedas por nivel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
