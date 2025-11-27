<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('specialty'); // Especialidad principal
            $table->json('specialties')->nullable(); // Otras especialidades (JSON)
            $table->text('curriculum')->nullable(); // Hoja de vida/resumen
            $table->integer('years_experience')->default(0);
            $table->string('academic_degree')->nullable(); // Grado académico
            $table->boolean('is_active')->default(true);
            $table->text('availability_notes')->nullable(); // Notas sobre disponibilidad
            $table->json('weekly_availability')->nullable(); // Disponibilidad semanal (JSON)
            $table->text('special_assignments')->nullable(); // Asignaciones especiales
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Relación con usuario
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};