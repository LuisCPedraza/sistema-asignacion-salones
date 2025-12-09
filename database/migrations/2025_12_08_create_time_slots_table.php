<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip creation if the table already exists (earlier migration creates it)
        if (Schema::hasTable('time_slots')) {
            return;
        }

        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Bloque 1", "Bloque 2"
            $table->time('start_time'); // Hora de inicio
            $table->time('end_time'); // Hora de fin
            $table->enum('schedule_type', ['day', 'night'])->default('day'); // day = 8:00-18:00, night = 18:00-22:00
            $table->integer('duration_minutes')->default(120); // Duración en minutos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
