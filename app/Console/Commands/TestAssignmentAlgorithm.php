<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;

class TestAssignmentAlgorithm extends Command
{
    protected $signature = 'test:algorithm';
    protected $description = 'Probar el algoritmo de asignación con detalles';

    public function handle()
    {
        $this->info("🔍 INICIANDO PRUEBA DEL ALGORITMO DE ASIGNACIÓN");
        
        // Mostrar datos existentes
        $this->info("\n📊 DATOS EXISTENTES:");
        $this->info("Grupos activos: " . StudentGroup::active()->count());
        $this->info("Profesores: " . Teacher::count());
        $this->info("Salones activos: " . Classroom::active()->count());
        
        $teachersWithAvailabilities = Teacher::has('availabilities')->count();
        $classroomsWithAvailabilities = Classroom::has('availabilities')->count();
        
        $this->info("Profesores con disponibilidades: " . $teachersWithAvailabilities);
        $this->info("Salones con disponibilidades: " . $classroomsWithAvailabilities);

        // Probar algoritmo
        $this->info("\n🔄 EJECUTANDO ALGORITMO...");
        $algorithm = new AssignmentAlgorithm();
        $results = $algorithm->generateAssignments();
        
        $this->info("Asignaciones generadas: " . count($results));
        
        if (count($results) > 0) {
            $this->info("\n✅ ASIGNACIONES ENCONTRADAS:");
            foreach ($results as $index => $item) {
                // generateAssignments devuelve IDs; si viene un array usa los datos tal cual
                $assignment = is_int($item) ? Assignment::with(['group','teacher','classroom'])->find($item) : (object) $item;
                if (!$assignment) {
                    $this->warn("--- Asignación " . ($index + 1) . " no encontrada");
                    continue;
                }

                $this->info("--- Asignación " . ($index + 1) . " ---");
                $this->info("Grupo: " . ($assignment->group->name ?? $assignment->student_group_id));
                $this->info("Profesor: " . ($assignment->teacher->full_name ?? $assignment->teacher_id));
                $this->info("Salón: " . ($assignment->classroom->name ?? $assignment->classroom_id));
                $this->info("Día: " . ($assignment->day ?? 'N/A'));
                $this->info("Horario: " . (($assignment->start_time ?? '') . " - " . ($assignment->end_time ?? '')));
                $score = property_exists($assignment, 'score') ? $assignment->score : ($assignment->score ?? 0);
                $this->info("Score: " . round($score * 100, 2) . "%");
            }
        } else {
            $this->warn("\n❌ NO SE ENCONTRARON ASIGNACIONES");
            $this->info("Posibles causas:");
            $this->info("- No hay coincidencias de disponibilidad");
            $this->info("- El score mínimo (60%) no se alcanza");
            $this->info("- Faltan datos de disponibilidades");
        }
        
        $this->info("\n🎯 SUGERENCIAS:");
        $this->info("1. Verificar que existan disponibilidades para profesores y salones");
        $this->info("2. Revisar que los horarios de disponibilidad coincidan");
        $this->info("3. Probar con el umbral más bajo temporalmente");
        
        return Command::SUCCESS;
    }
}