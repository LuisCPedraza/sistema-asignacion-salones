<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use App\Modules\Asignacion\Models\AssignmentRule;

class AssignmentAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos de prueba directamente en el test
        $this->createTestData();
    }

    private function createTestData()
    {
        // Crear reglas de asignación con 'name' incluido
        AssignmentRule::create([
            'name' => 'Capacidad del Salón',
            'parameter' => 'capacity',
            'weight' => 30,
            'is_active' => true,
            'description' => 'Capacidad del salón'
        ]);
        AssignmentRule::create([
            'name' => 'Disponibilidad del Profesor',
            'parameter' => 'teacher_availability',
            'weight' => 25,
            'is_active' => true,
            'description' => 'Disponibilidad del profesor'
        ]);
        AssignmentRule::create([
            'name' => 'Disponibilidad del Salón', 
            'parameter' => 'classroom_availability',
            'weight' => 25,
            'is_active' => true,
            'description' => 'Disponibilidad del salón'
        ]);
        AssignmentRule::create([
            'name' => 'Recursos Requeridos',
            'parameter' => 'resources',
            'weight' => 10,
            'is_active' => true,
            'description' => 'Recursos requeridos'
        ]);
        AssignmentRule::create([
            'name' => 'Proximidad',
            'parameter' => 'proximity',
            'weight' => 10,
            'is_active' => true,
            'description' => 'Proximidad'
        ]);

        // Crear profesor
        $teacher = Teacher::create([
            'first_name' => 'Profesor',
            'last_name' => 'Prueba',
            'email' => 'profesor@test.edu',
            'specialty' => 'General',
            'is_active' => true,
        ]);

        // Crear salón
        $classroom = Classroom::create([
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        // Crear grupo
        $group = StudentGroup::create([
            'name' => 'Grupo Prueba',
            'level' => 'intermedio',
            'student_count' => 25,
            'special_features' => 'Ninguna',
            'number_of_students' => 25,
            'special_requirements' => 'Ninguno',
            'is_active' => true,
        ]);

        // Crear disponibilidades que COINCIDAN exactamente
        TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'is_available' => true,
        ]);

        ClassroomAvailability::create([
            'classroom_id' => $classroom->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'is_available' => true,
            'availability_type' => 'regular',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_algorithm_generates_assignments_with_matching_data()
    {
        $algorithm = new AssignmentAlgorithm();
        
        $assignments = $algorithm->generateAssignments(0.1); // Umbral bajo del 10%
        
        $this->assertGreaterThan(0, count($assignments), 
            "El algoritmo debería generar asignaciones con datos que coinciden perfectamente");
        
        // Verificar detalles de la asignación
        $this->assertEquals(1, count($assignments));
        $this->assertEquals('Grupo Prueba', $assignments[0]['group_name']);
        $this->assertEquals('Aula 101', $assignments[0]['classroom_name']);
        $this->assertEquals('monday', $assignments[0]['day']);
        $this->assertEquals('08:00:00', $assignments[0]['start_time']);
        $this->assertEquals('10:00:00', $assignments[0]['end_time']);
        $this->assertGreaterThan(0.8, $assignments[0]['score']); // Score debería ser alto
    }
}