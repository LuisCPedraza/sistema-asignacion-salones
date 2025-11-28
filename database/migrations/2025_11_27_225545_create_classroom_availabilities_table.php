<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classroom_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->text('notes')->nullable(); // Notas específicas para este horario
            $table->enum('availability_type', ['regular', 'maintenance', 'reserved', 'special_event'])->default('regular');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['classroom_id', 'day_of_week', 'start_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_availabilities');
    }
};