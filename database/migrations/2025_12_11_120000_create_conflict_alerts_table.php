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
        Schema::create('conflict_alerts', function (Blueprint $table) {
            $table->id();
            
            // Tipo de conflicto
            $table->enum('conflict_type', [
                'room_double_booking',      // Salón con múltiples asignaciones
                'teacher_overlap',          // Profesor en dos lugares al mismo tiempo
                'room_unavailable',         // Salón sin disponibilidad
                'capacity_exceeded',        // Capacidad de salón excedida
                'teacher_unavailable'       // Profesor sin disponibilidad
            ])->comment('Tipo de conflicto detectado');
            
            // Severidad
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])
                  ->default('medium')
                  ->comment('Nivel de severidad del conflicto');
            
            // Entidades involucradas
            $table->foreignId('assignment_id')->nullable()->constrained('assignments')->onDelete('cascade');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->foreignId('student_group_id')->nullable()->constrained('student_groups')->onDelete('set null');
            
            // Detalles del conflicto
            $table->text('description')->comment('Descripción del conflicto');
            $table->json('conflict_details')->nullable()->comment('Detalles adicionales en JSON');
            
            // Información temporal
            $table->string('day')->nullable()->comment('Día de la semana');
            $table->time('start_time')->nullable()->comment('Hora de inicio del conflicto');
            $table->time('end_time')->nullable()->comment('Hora de fin del conflicto');
            
            // Estado
            $table->enum('status', ['pending', 'notified', 'resolved', 'ignored'])
                  ->default('pending')
                  ->comment('Estado de la alerta');
            
            $table->timestamp('notified_at')->nullable()->comment('Fecha de notificación');
            $table->timestamp('resolved_at')->nullable()->comment('Fecha de resolución');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable()->comment('Notas de resolución');
            
            $table->timestamps();
            
            // Índices
            $table->index('conflict_type');
            $table->index('severity');
            $table->index('status');
            $table->index('created_at');
            $table->index(['classroom_id', 'day', 'start_time']);
            $table->index(['teacher_id', 'day', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conflict_alerts');
    }
};
