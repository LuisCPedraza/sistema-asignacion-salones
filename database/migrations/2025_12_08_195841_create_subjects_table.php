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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código de la materia (ej: MAT101)
            $table->string('name'); // Nombre de la materia
            $table->text('description')->nullable();
            $table->string('specialty'); // Especialidad requerida (Programación, BD, etc)
            $table->integer('credit_hours')->default(3); // Horas crédito
            $table->integer('lecture_hours')->default(3); // Horas teóricas
            $table->integer('lab_hours')->default(0); // Horas prácticas
            $table->integer('semester_level')->nullable(); // En qué semestre se dicta (1-7)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
