<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Lunes 8:00-10:00"
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->time('start_time'); // 08:00:00
            $table->time('end_time');   // 10:00:00
            $table->enum('shift', ['morning', 'afternoon', 'night']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['day', 'shift']);
            $table->index(['start_time', 'end_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_slots');
    }
};
