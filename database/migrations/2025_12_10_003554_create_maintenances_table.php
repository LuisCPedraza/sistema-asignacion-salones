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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->enum('type', ['preventivo', 'correctivo'])->default('correctivo');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->enum('status', ['pendiente', 'en_progreso', 'completado', 'cancelado'])->default('pendiente')->index();
            $table->date('scheduled_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('responsible')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['classroom_id', 'status']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
