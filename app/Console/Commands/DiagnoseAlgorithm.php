<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;

class DiagnoseAlgorithm extends Command
{
    protected $signature = 'algorithm:diagnose';
    protected $description = 'Diagnóstico detallado del algoritmo de asignación';

    public function handle()
    {
        $this->info('🔍 DIAGNÓSTICO DETALLADO DEL ALGORITMO');

        // 1. Verificar datos básicos
        $this->checkBasicData();

        // 2. Verificar coincidencias específicas
        $this->checkSpecificMatches();

        // 3. Probar algoritmo con parámetros detallados
        $this->testAlgorithmWithDetails();

        return Command::SUCCESS;
    }

    private function checkBasicData()
    {
        $this->info('📊 VERIFICACIÓN DE DATOS:');
        
        $groups = StudentGroup::active()->get();
        $teachers = Teacher::active()->get();
        $classrooms = Classroom::active()->get();

        $this->line("   - Grupos activos: {$groups->count()}");
        foreach ($groups as $group) {
            $this->line("     * {$group->name} - {$group->number_of_students} estudiantes");
        }

        $this->line("   - Profesores activos: {$teachers->count()}");
        $this->line("   - Salones activos: {$classrooms->count()}");
        foreach ($classrooms as $classroom) {
            $this->line("     * {$classroom->name} - Capacidad: {$classroom->capacity}");
        }

        // Verificar disponibilidades
        $teacherAvailabilities = TeacherAvailability::count();
        $classroomAvailabilities = ClassroomAvailability::count();
        $this->line("   - Disponibilidades profesores: {$teacherAvailabilities}");
        $this->line("   - Disponibilidades salones: {$classroomAvailabilities}");

        // Verificar horarios específicos
        $this->info('🕐 HORARIOS DE DISPONIBILIDAD:');
        $sampleTeacherAvailability = TeacherAvailability::first();
        $sampleClassroomAvailability = ClassroomAvailability::first();
        
        if ($sampleTeacherAvailability) {
            $this->line("   - Profesor ejemplo: {$sampleTeacherAvailability->day} {$sampleTeacherAvailability->start_time} - {$sampleTeacherAvailability->end_time}");
        } else {
            $this->error("   - ❌ No hay disponibilidades de profesores");
        }
        if ($sampleClassroomAvailability) {
            $this->line("   - Salón ejemplo: {$sampleClassroomAvailability->day} {$sampleClassroomAvailability->start_time} - {$sampleClassroomAvailability->end_time}");
        } else {
            $this->error("   - ❌ No hay disponibilidades de salones");
        }
    }

    private function checkSpecificMatches()
    {
        $this->info('🎯 VERIFICANDO COINCIDENCIAS:');

        $groups = StudentGroup::active()->get();
        $classrooms = Classroom::active()->get();

        if ($groups->count() === 0) {
            $this->error("   ❌ No hay grupos activos");
            return;
        }

        foreach ($groups as $group) {
            $this->line("   - Grupo: {$group->name} ({$group->number_of_students} estudiantes)");
            
            // Encontrar salones que puedan acomodar este grupo
            $suitableClassrooms = $classrooms->filter(function($classroom) use ($group) {
                return $classroom->capacity >= $group->number_of_students;
            });

            $this->line("     * Salones adecuados: {$suitableClassrooms->count()}");

            if ($suitableClassrooms->count() > 0) {
                foreach ($suitableClassrooms as $classroom) {
                    $this->line("       ✓ {$classroom->name} (Capacidad: {$classroom->capacity})");
                }
            } else {
                $this->error("       ❌ Ningún salón tiene capacidad para {$group->number_of_students} estudiantes");
                $this->line("       💡 Salones disponibles: " . $classrooms->pluck('name', 'capacity')->map(function($name, $capacity) {
                    return "{$name}($capacity)";
                })->implode(', '));
            }
        }
    }

    private function testAlgorithmWithDetails()
    {
        $this->info('🔄 PROBANDO ALGORITMO CON DETALLES:');

        $algorithm = new AssignmentAlgorithm();
        
        // Probar con umbral más bajo temporalmente
        $this->line("   - Probando con umbral reducido al 30%...");
        
        $assignments = $algorithm->generateAssignments(0.3); // 30% de umbral

        if (count($assignments) > 0) {
            $this->info("   ✅ Asignaciones generadas: " . count($assignments));
            foreach ($assignments as $assignment) {
                $this->line("     * {$assignment['group_name']} → {$assignment['classroom_name']} ({$assignment['teacher_name']})");
                $this->line("       Score: " . round($assignment['score'] * 100, 2) . "%");
            }
        } else {
            $this->error("   ❌ Cero asignaciones incluso con umbral bajo");
            $this->line("   🎯 Probando con umbral del 10%...");
            
            $assignments = $algorithm->generateAssignments(0.1); // 10% de umbral
            if (count($assignments) > 0) {
                $this->info("   ✅ Asignaciones con umbral 10%: " . count($assignments));
            } else {
                $this->error("   ❌ Cero asignaciones incluso con umbral del 10%");
                $this->suggestSolutions();
            }
        }
    }

    private function suggestSolutions()
    {
        $this->info('💡 SUGERENCIAS:');
        $this->line("   1. Verificar que los horarios de profesores y salones coincidan en el mismo día");
        $this->line("   2. Revisar que no haya conflictos de capacidad (grupos más grandes que salones)");
        $this->line("   3. Probar con datos de prueba más simples");
        $this->line("   4. Revisar el método calculateScore() en AssignmentAlgorithm");
        
        // Verificar datos específicos
        $groups = StudentGroup::active()->get();
        $maxGroupSize = $groups->count() > 0 ? $groups->max('number_of_students') : 0;
        $maxClassroomCapacity = Classroom::active()->max('capacity');
        
        $this->line("   - Grupo más grande: {$maxGroupSize} estudiantes");
        $this->line("   - Salón más grande: {$maxClassroomCapacity} capacidad");
        
        if ($maxGroupSize > $maxClassroomCapacity) {
            $this->error("   ❌ PROBLEMA: Hay grupos más grandes que la capacidad de cualquier salón");
        }
    }
}