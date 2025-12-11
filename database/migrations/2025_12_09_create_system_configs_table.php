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
        Schema::create('system_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Clave única de configuración (ej: institution.name)');
            $table->text('value')->nullable()->comment('Valor de configuración');
            $table->enum('type', ['string', 'integer', 'boolean', 'json', 'datetime'])->default('string')->comment('Tipo de dato');
            $table->text('description')->nullable()->comment('Descripción del parámetro');
            $table->timestamps();

            // Índice para búsquedas rápidas
            $table->index('key');
        });

        // Insertar configuraciones por defecto
        DB::table('system_configs')->insert([
            // Institución
            [
                'key' => 'institution.name',
                'value' => 'Universidad Ejemplo',
                'type' => 'string',
                'description' => 'Nombre de la institución educativa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'institution.code',
                'value' => 'UNIV-001',
                'type' => 'string',
                'description' => 'Código único de la institución',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Horarios
            [
                'key' => 'schedule.work_start_time',
                'value' => '08:00:00',
                'type' => 'string',
                'description' => 'Hora de inicio de jornada laboral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'schedule.work_end_time',
                'value' => '17:00:00',
                'type' => 'string',
                'description' => 'Hora de fin de jornada laboral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'schedule.lunch_start_time',
                'value' => '12:00:00',
                'type' => 'string',
                'description' => 'Hora de inicio de almuerzo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'schedule.lunch_end_time',
                'value' => '13:00:00',
                'type' => 'string',
                'description' => 'Hora de fin de almuerzo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Algoritmo
            [
                'key' => 'algorithm.min_score_threshold',
                'value' => '0.6',
                'type' => 'string',
                'description' => 'Puntuación mínima aceptable para asignaciones (0-1)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'algorithm.max_attempts',
                'value' => '15',
                'type' => 'integer',
                'description' => 'Máximo número de intentos en el algoritmo de búsqueda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Auditoría
            [
                'key' => 'audit.enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Habilitar registro de auditoría',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'audit.retention_days',
                'value' => '90',
                'type' => 'integer',
                'description' => 'Días de retención de logs de auditoría',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};
