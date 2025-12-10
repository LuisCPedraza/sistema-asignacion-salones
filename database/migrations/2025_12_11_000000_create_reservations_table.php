<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('requester_name');
            $table->string('requester_email')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada', 'cancelada'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['classroom_id', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
