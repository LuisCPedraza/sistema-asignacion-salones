<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Models\Subject;

class AssignmentAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos de prueba
        $this->createTestData();
    }

    protected function createTestData()
    {
        // Crear profesores
        Teacher::factory()->count(5)->create(['is_active' => true]);
        
        // Crear salones
        Classroom::factory()->count(3)->create(['is_active' => true]);
        
        // Crear franjas horarias
        TimeSlot::create([
            'name' => 'Bloque Diurno',
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'shift' => 'morning',
            'schedule_type' => 'day',
            'duration_minutes' => 120,
            'is_active' => true,
        ]);
        
        TimeSlot::create([
            'name' => 'Bloque Nocturno',
            'day' => 'monday',
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'shift' => 'night',
            'schedule_type' => 'night',
            'duration_minutes' => 120,
            'is_active' => true,
        ]);
        
        // Crear grupos de estudiantes
        StudentGroup::factory()->count(3)->create([
            'is_active' => true,
            'schedule_type' => 'day',
        ]);
    }

    /** @test */
    public function test_algorithm_reorganizes_existing_assignments_without_creating_new_ones()
    {
        // Crear asignaciones iniciales
        $group = StudentGroup::first();
        $teacher = Teacher::first();
        $classroom = Classroom::first();
        $timeSlot = TimeSlot::first();
        $subject = Subject::factory()->create();

        $initialAssignment = Assignment::create([
            'student_group_id' => $group->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'time_slot_id' => $timeSlot->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'score' => 0.8,
        ]);

        $countBefore = Assignment::count();

        // Ejecutar algoritmo
        $algorithm = new AssignmentAlgorithm();
        $result = $algorithm->generateAssignments();

        $countAfter = Assignment::count();

        // Aserciones
        $this->assertEquals($countBefore, $countAfter, 'El algoritmo no debe crear nuevas asignaciones');
        $this->assertCount(1, $result, 'Debe retornar la asignación reorganizada');
        
        // Verificar que la asignación existe pero puede tener valores diferentes
        $updated = Assignment::find($initialAssignment->id);
        $this->assertNotNull($updated);
    }

    /** @test */
    public function test_algorithm_respects_schedule_type_for_groups()
    {
        // Crear grupo NOCTURNO
        $nightGroup = StudentGroup::factory()->create([
            'schedule_type' => 'night',
            'is_active' => true,
        ]);

        $subject = Subject::factory()->create();
        $teacher = Teacher::first();
        $classroom = Classroom::first();
        $nightSlot = TimeSlot::where('schedule_type', 'night')->first();

        Assignment::create([
            'student_group_id' => $nightGroup->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'time_slot_id' => $nightSlot->id,
            'day' => 'monday',
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'score' => 0.8,
        ]);

        $algorithm = new AssignmentAlgorithm();
        $result = $algorithm->generateAssignments();

        // Verificar que el grupo nocturno sigue teniendo franjas nocturnas
        $assignment = Assignment::where('student_group_id', $nightGroup->id)->first();
        $this->assertNotNull($assignment);
        
        // La franja debe ser nocturna
        $timeSlot = TimeSlot::find($assignment->time_slot_id);
        if ($timeSlot) {
            $this->assertEquals('night', $timeSlot->schedule_type);
        }
    }

    /** @test */
    public function test_algorithm_handles_empty_assignments_gracefully()
    {
        // No hay asignaciones
        Assignment::truncate();

        $algorithm = new AssignmentAlgorithm();
        $result = $algorithm->generateAssignments();

        $this->assertIsArray($result);
        $this->assertEmpty($result, 'Debe retornar array vacío si no hay asignaciones');
    }

    /** @test */
    public function test_algorithm_updates_assignment_notes_with_timestamp()
    {
        $group = StudentGroup::first();
        $subject = Subject::factory()->create();
        $teacher = Teacher::first();
        $classroom = Classroom::first();
        $timeSlot = TimeSlot::first();

        $assignment = Assignment::create([
            'student_group_id' => $group->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'time_slot_id' => $timeSlot->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'score' => 0.8,
            'notes' => 'Original',
        ]);

        $algorithm = new AssignmentAlgorithm();
        $algorithm->generateAssignments();

        $updated = Assignment::find($assignment->id);
        $this->assertStringContainsString('Reorganizado automáticamente', $updated->notes);
    }

    /** @test */
    public function test_algorithm_marks_assignments_as_assigned_by_algorithm()
    {
        $group = StudentGroup::first();
        $subject = Subject::factory()->create();
        $teacher = Teacher::first();
        $classroom = Classroom::first();
        $timeSlot = TimeSlot::first();

        $assignment = Assignment::create([
            'student_group_id' => $group->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'time_slot_id' => $timeSlot->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'score' => 0.8,
            'assigned_by_algorithm' => false,
        ]);

        $algorithm = new AssignmentAlgorithm();
        $algorithm->generateAssignments();

        $updated = Assignment::find($assignment->id);
        $this->assertTrue($updated->assigned_by_algorithm);
        $this->assertTrue($updated->is_confirmed);
    }
}
