<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;

class DebugAlgorithm extends Command
{
    protected $signature = 'algorithm:debug';
    protected $description = 'Debug detallado del algoritmo de asignación';

    public function handle()
    {
        $this->info('🐛 DEBUG DETALLADO DEL ALGORITMO');

        $this->checkData();
        $this->testAlgorithmStepByStep();
        
        return Command::SUCCESS;
    }

    private function checkData()
    {
        $this->info('📊 VERIFICACIÓN DE DATOS DETALLADA:');

        // Grupos
        $groups = StudentGroup::active()->with('academicPeriod')->get();
        $this->line("   - Grupos activos: {$groups->count()}");
        foreach ($groups as $group) {
            $this->line("     * {$group->name}: {$group->number_of_students} estudiantes, Nivel: {$group->level}");
        }

        // Profesores y disponibilidades
        $teachers = Teacher::active()->with('availabilities')->get();
        $this->line("   - Profesores activos: {$teachers->count()}");
        foreach ($teachers as $teacher) {
            $this->line("     * {$teacher->first_name} {$teacher->last_name}: {$teacher->availabilities->count()} disponibilidades");
            foreach ($teacher->availabilities as $avail) {
                $this->line("       → {$avail->day} {$avail->start_time}-{$avail->end_time}");
            }
        }

        // Salones y disponibilidades
        $classrooms = Classroom::active()->with('availabilities')->get();
        $this->line("   - Salones activos: {$classrooms->count()}");
        foreach ($classrooms as $classroom) {
            $this->line("     * {$classroom->name}: Capacidad {$classroom->capacity}, {$classroom->availabilities->count()} disponibilidades");
            foreach ($classroom->availabilities as $avail) {
                $this->line("       → {$avail->day} {$avail->start_time}-{$avail->end_time}");
            }
        }
    }

    private function testAlgorithmStepByStep()
    {
        $this->info('🔄 PROBANDO ALGORITMO PASO A PASO:');

        $algorithm = new AssignmentAlgorithm();
        
        // Probar diferentes umbrales
        $thresholds = [0.6, 0.5, 0.4, 0.3, 0.2, 0.1];
        
        foreach ($thresholds as $threshold) {
            $this->line("   - Probando con umbral: " . ($threshold * 100) . "%");
            $assignments = $algorithm->generateAssignments($threshold);
            
            if (count($assignments) > 0) {
                $this->info("     ✅ Asignaciones generadas: " . count($assignments));
                foreach ($assignments as $assignment) {
                    $this->line("       * {$assignment['group_name']} → {$assignment['classroom_name']}");
                    $this->line("         Profesor: {$assignment['teacher_name']}");
                    $this->line("         Score: " . round($assignment['score'] * 100, 2) . "%");
                }
                break;
            } else {
                $this->error("     ❌ Cero asignaciones");
            }
        }

        // Si todavía no hay asignaciones, hacer debug manual
        if (count($assignments) === 0) {
            $this->manualDebug();
        }
    }

    private function manualDebug()
    {
        $this->info('🔍 DEBUG MANUAL:');

        $groups = StudentGroup::active()->get();
        $teachers = Teacher::active()->with('availabilities')->get();
        $classrooms = Classroom::active()->with('availabilities')->get();

        $this->line("   - Grupos: {$groups->count()}");
        $this->line("   - Profesores: {$teachers->count()}");  
        $this->line("   - Salones: {$classrooms->count()}");

        // Verificar coincidencias manualmente
        foreach ($groups as $group) {
            $this->line("   - Grupo: {$group->name} ({$group->number_of_students} estudiantes)");
            
            foreach ($teachers as $teacher) {
                foreach ($teacher->availabilities as $teacherAvail) {
                    foreach ($classrooms as $classroom) {
                        foreach ($classroom->availabilities as $classroomAvail) {
                            // Verificar si coinciden día y horario
                            if ($teacherAvail->day === $classroomAvail->day &&
                                $teacherAvail->start_time === $classroomAvail->start_time &&
                                $teacherAvail->end_time === $classroomAvail->end_time) {
                                
                                $this->info("     ✅ COINCIDENCIA ENCONTRADA:");
                                $this->line("       * Día: {$teacherAvail->day}");
                                $this->line("       * Horario: {$teacherAvail->start_time}-{$teacherAvail->end_time}");
                                $this->line("       * Profesor: {$teacher->first_name} {$teacher->last_name}");
                                $this->line("       * Salón: {$classroom->name} (Capacidad: {$classroom->capacity})");
                                $this->line("       * Grupo: {$group->name} ({$group->number_of_students} estudiantes)");
                                
                                // Verificar capacidad
                                if ($classroom->capacity >= $group->number_of_students) {
                                    $this->info("       ✓ Capacidad adecuada");
                                } else {
                                    $this->error("       ❌ Capacidad insuficiente");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}