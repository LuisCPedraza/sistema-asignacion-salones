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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código único del estudiante
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->foreignId('group_id')->constrained('student_groups')->onDelete('cascade'); // Grupo al que pertenece
            $table->enum('estado', ['activo', 'inactivo', 'retirado'])->default('activo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
