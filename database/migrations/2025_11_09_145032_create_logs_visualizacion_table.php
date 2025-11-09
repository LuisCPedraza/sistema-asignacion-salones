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
        Schema::create('logs_visualizacion', function (Blueprint $table) {
            $table->char('id', 36)->primary();  // UUID PK
            $table->unsignedBigInteger('user_id');  // Cambiado: unsignedBigInteger for FK to users.id (int)
            $table->json('filtro');  // Filtros JSON
            $table->date('fecha');  // Fecha de visualización
            $table->boolean('activo')->default(true);  // Activo default true
            $table->timestamps();  // created_at, updated_at

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // FK constraint
            $table->index('fecha');  // Index for fecha queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_visualizacion');
    }
};
