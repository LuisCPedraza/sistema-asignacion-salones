<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixAvailabilityTests extends Command
{
    protected $signature = 'test:fix-availability';
    protected $description = 'Diagnóstico y solución para tests de disponibilidad';

    public function handle()
    {
        $this->info('🔧 DIAGNÓSTICO Y SOLUCIÓN PARA TESTS DE DISPONIBILIDAD');

        // Verificar estructura de tablas
        $this->checkTableStructure('teacher_availabilities');
        $this->checkTableStructure('classroom_availabilities');

        // Verificar datos de ejemplo
        $this->checkSampleData();

        $this->info('✅ Diagnóstico completado');
        
        return Command::SUCCESS;
    }

    private function checkTableStructure(string $tableName): void
    {
        $this->info("📊 Verificando tabla: {$tableName}");

        // Evitar problemas con nombres reservados o caracteres
        $safeTable = str_replace('`', '', $tableName);
        $columns = DB::select("PRAGMA table_info(`{$safeTable}`)");

        if (empty($columns)) {
            $this->error("   ❌ No se pudo obtener información de la tabla (puede no existir)");
            return;
        }

        $hasDayColumn = false;

        foreach ($columns as $column) {
            $nullable = $column->notnull ? 'NO' : 'YES';
            $this->line("   - {$column->name} ({$column->type}) - Nullable: {$nullable}");

            if ($column->name === 'day') {
                $hasDayColumn = true;
            }
        }

        // Validación
        if (!$hasDayColumn) {
            $this->error("   ❌ La tabla NO tiene columna 'day'");
        } else {
            $this->info("   ✅ La tabla tiene columna 'day'");
        }

        // Verificar datos existentes de forma segura
        try {
            $count = DB::table($safeTable)->count();
            $this->line("   - Registros totales: {$count}");
        } catch (\Exception $e) {
            $this->error("   ❌ Error consultando registros: " . $e->getMessage());
        }
    }

    private function checkSampleData()
    {
        $this->info("🎯 Verificando datos de ejemplo:");

        // Intentar crear datos de prueba directamente
        $teacherId = DB::table('teachers')->insertGetId([
            'first_name' => 'Test',
            'last_name' => 'Teacher',
            'email' => 'test@example.com',
            'specialty' => 'Test',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $classroomId = DB::table('classrooms')->insertGetId([
            'name' => 'Test Room',
            'code' => 'TEST',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Probar inserción directa en teacher_availabilities
        $teacherAvailId = DB::table('teacher_availabilities')->insertGetId([
            'teacher_id' => $teacherId,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'is_available' => true,
            'notes' => 'Test insertion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($teacherAvailId) {
            $this->info("   ✅ Inserción directa en teacher_availabilities: EXITOSA");
        } else {
            $this->error("   ❌ Inserción directa en teacher_availabilities: FALLÓ");
        }

        // Probar inserción directa en classroom_availabilities
        $classroomAvailId = DB::table('classroom_availabilities')->insertGetId([
            'classroom_id' => $classroomId,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'is_available' => true,
            'availability_type' => 'regular',
            'notes' => 'Test insertion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($classroomAvailId) {
            $this->info("   ✅ Inserción directa en classroom_availabilities: EXITOSA");
        } else {
            $this->error("   ❌ Inserción directa en classroom_availabilities: FALLÓ");
        }

        // Limpiar datos de prueba
        DB::table('teacher_availabilities')->where('id', $teacherAvailId)->delete();
        DB::table('classroom_availabilities')->where('id', $classroomAvailId)->delete();
        DB::table('teachers')->where('id', $teacherId)->delete();
        DB::table('classrooms')->where('id', $classroomId)->delete();
    }
}
