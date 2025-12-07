<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1. TEACHER_AVAILABILITIES
         */
        Schema::table('teacher_availabilities', function (Blueprint $table) {
            // Primero eliminamos el índice que incluía day_of_week
            $table->dropUnique('teacher_availabilities_teacher_id_day_of_week_start_time_unique');
        });

        Schema::table('teacher_availabilities', function (Blueprint $table) {
            // Ahora sí, eliminar la columna
            $table->dropColumn('day_of_week');
        });


        /**
         * 2. CLASSROOM_AVAILABILITIES
         */
        Schema::table('classroom_availabilities', function (Blueprint $table) {
            // Si esta tabla tiene índices con day_of_week, elimínalos aquí.
            // Solo si ya existe un índice, elimínalo primero.
            // Si no existe, esto no se ejecuta y no hace daño.
            try {
                $table->dropUnique('classroom_availabilities_classroom_id_day_of_week_start_time_unique');
            } catch (\Throwable $e) {
                // Si no existe el índice, simplemente seguimos
            }
        });

        Schema::table('classroom_availabilities', function (Blueprint $table) {
            // Y ahora eliminamos la columna
            if (Schema::hasColumn('classroom_availabilities', 'day_of_week')) {
                $table->dropColumn('day_of_week');
            }
        });
    }

    public function down(): void
    {
        // Restaurar en caso de rollback

        Schema::table('teacher_availabilities', function (Blueprint $table) {
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
                  ->after('teacher_id');

            $table->unique(['teacher_id', 'day_of_week', 'start_time']);
        });

        Schema::table('classroom_availabilities', function (Blueprint $table) {
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
                  ->after('classroom_id');

            $table->unique(['classroom_id', 'day_of_week', 'start_time']);
        });
    }
};
