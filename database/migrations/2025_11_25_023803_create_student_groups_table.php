<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // e.g., "Matemáticas 101"
            $table->string('level');  // e.g., "Bachillerato"
            $table->integer('student_count')->unsigned();  // Número de estudiantes
            $table->text('special_features')->nullable();  // Características específicas
            $table->boolean('is_active')->default(true);  // Auditoría: soft-delete
            $table->foreignId('academic_period_id')->nullable()->constrained()->onDelete('set null');  // Relación futura con períodos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_groups');
    }
};
