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
        Schema::create('profesores', function (Blueprint $table) {
            $table->char('id', 36)->primary();  # UUID PK
            $table->unsignedBigInteger('usuario_id')->unique();  # Cambiado: unsignedBigInteger for FK to users.id (unsigned bigint)
            $table->string('especialidades', 255);  # Especialidades
            $table->boolean('activo')->default(true);  # Activo default true
            $table->timestamps();  # created_at, updated_at

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');  # FK to users.id (unsigned bigint)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
