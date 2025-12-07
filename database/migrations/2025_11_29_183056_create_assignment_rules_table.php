<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('parameter'); // capacity, proximity, teacher_preference, etc.
            $table->decimal('weight', 3, 2); // Peso en el algoritmo (0.00 a 1.00)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insertar reglas predeterminadas
        DB::table('assignment_rules')->insert([
            ['name' => 'Capacidad del Salón', 'parameter' => 'capacity', 'weight' => 0.30, 'is_active' => true],
            ['name' => 'Disponibilidad del Profesor', 'parameter' => 'teacher_availability', 'weight' => 0.25, 'is_active' => true],
            ['name' => 'Disponibilidad del Salón', 'parameter' => 'classroom_availability', 'weight' => 0.20, 'is_active' => true],
            ['name' => 'Proximidad entre Clases', 'parameter' => 'proximity', 'weight' => 0.15, 'is_active' => true],
            ['name' => 'Preferencia de Recursos', 'parameter' => 'resources', 'weight' => 0.10, 'is_active' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_rules');
    }
};