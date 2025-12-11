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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Identificación del modelo
            $table->string('model')->comment('Nombre del modelo (ej: User, StudentGroup)');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID del registro afectado');
            
            // Tipo de acción
            $table->enum('action', ['create', 'update', 'delete', 'restore', 'export'])->comment('Tipo de operación');
            
            // Cambios realizados
            $table->longText('old_values')->nullable()->comment('Valores anteriores en JSON');
            $table->longText('new_values')->nullable()->comment('Valores nuevos en JSON');
            
            // Contexto
            $table->string('description')->nullable()->comment('Descripción amigable del cambio');
            $table->string('ip_address')->nullable()->comment('IP del usuario');
            $table->string('user_agent')->nullable()->comment('User agent del navegador');
            
            // Auditoría de auditoría
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index(['model', 'model_id']);
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
