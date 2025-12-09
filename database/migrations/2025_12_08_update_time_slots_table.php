<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si la tabla ya existe, agregar las columnas que falten
        if (Schema::hasTable('time_slots')) {
            Schema::table('time_slots', function (Blueprint $table) {
                // Agregar schedule_type si no existe
                if (!Schema::hasColumn('time_slots', 'schedule_type')) {
                    $table->enum('schedule_type', ['day', 'night'])->default('day')->after('end_time');
                }
                
                // Agregar duration_minutes si no existe
                if (!Schema::hasColumn('time_slots', 'duration_minutes')) {
                    $table->integer('duration_minutes')->default(120)->after('schedule_type');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropColumn(['schedule_type', 'duration_minutes']);
        });
    }
};
