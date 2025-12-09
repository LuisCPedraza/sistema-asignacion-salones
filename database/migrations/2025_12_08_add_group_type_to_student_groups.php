<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_groups', function (Blueprint $table) {
            // Grupo A (Diurno) o Grupo B (Nocturno)
            $table->enum('group_type', ['A', 'B'])->default('A')->after('semester_id');
            
            // Tipo de horario: day (Diurno 8:00-18:00) o night (Nocturno 18:00-22:00)
            $table->enum('schedule_type', ['day', 'night'])->default('day')->after('group_type');
        });
    }

    public function down(): void
    {
        Schema::table('student_groups', function (Blueprint $table) {
            $table->dropColumn(['group_type', 'schedule_type']);
        });
    }
};
