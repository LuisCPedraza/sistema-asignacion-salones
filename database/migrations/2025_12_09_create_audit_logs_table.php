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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Campos compatibles con n8n
            $table->string('event')->comment('Evento disparado (ej: assignment.created, assignment.updated)');
            $table->unsignedBigInteger('entity_id')->nullable()->comment('ID del registro afectado');
            $table->string('entity_type')->comment('Tipo de entidad (ej: Assignment, Teacher)');
            
            // Identificación del modelo (legacy, mantener compatibilidad)
            $table->string('model')->nullable()->comment('Nombre del modelo (ej: User, StudentGroup)');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID del registro afectado');
            
            // Tipo de acción
            $table->enum('action', ['create', 'update', 'delete', 'restore', 'export'])->nullable()->comment('Tipo de operación');
            
            // Cambios realizados (JSONB para PostgreSQL, TEXT para SQLite)
            $table->json('changes')->nullable()->comment('Cambios realizados en formato JSON');
            $table->longText('old_values')->nullable()->comment('Valores anteriores en JSON');
            $table->longText('new_values')->nullable()->comment('Valores nuevos en JSON');
            
            // Contexto
            $table->string('description')->nullable()->comment('Descripción amigable del cambio');
            $table->string('ip_address')->nullable()->comment('IP del usuario');
            $table->string('user_agent')->nullable()->comment('User agent del navegador');
            $table->string('source')->default('system')->comment('Origen del evento: system, n8n, webhook');
            
            // Auditoría de auditoría
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index(['model', 'model_id']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('event');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
