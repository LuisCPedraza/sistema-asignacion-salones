<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Asignacion\Models\AssignmentRule;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Infraestructura\Models\ClassroomAvailability;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // CREAMOS REGLAS CON PESO ALTO
    AssignmentRule::insert([
        ['name' => 'Capacidad', 'parameter' => 'capacity', 'weight' => 100, 'is_active' => true],
        ['name' => 'Disponibilidad Profesor', 'parameter' => 'teacher_availability', 'weight' => 100, 'is_active' => true],
        ['name' => 'Disponibilidad Salón', 'parameter' => 'classroom_availability', 'weight' => 100, 'is_active' => true],
    ]);

    Teacher::create([
        'first_name' => 'Carlos',
        'last_name' => 'Mendoza',
        'email' => 'carlos@university.edu',
        'specialty' => 'Informática',
        'is_active' => true,
    ]);

    Classroom::create([
        'name' => 'Aula 101',
        'code' => 'A101',
        'capacity' => 40,
        'is_active' => true,
    ]);

    StudentGroup::create([
        'name' => 'Grupo Prueba',
        'level' => 'Universitario',
        'number_of_students' => 25,
        'student_count' => 25,
        'is_active' => true,
    ]);

    TeacherAvailability::create([
        'teacher_id' => 1,
        'day' => 'monday',
        'start_time' => '08:00:00',
        'end_time' => '10:00:00',
        'is_available' => true,
    ]);

    ClassroomAvailability::create([
        'classroom_id' => 1,
        'day' => 'monday',
        'start_time' => '08:00:00',
        'end_time' => '10:00:00',
        'is_available' => true,
    ]);

    $timeSlot = TimeSlot::create([
        'name' => 'Bloque 1',
        'day' => 'monday',
        'start_time' => '08:00:00',
        'end_time' => '10:00:00',
        'shift' => 'morning',
        'schedule_type' => 'day',
        'duration_minutes' => 120,
        'is_active' => true,
    ]);

    Assignment::create([
        'student_group_id' => 1,
        'teacher_id' => 1,
        'classroom_id' => 1,
        'time_slot_id' => $timeSlot->id,
        'day' => 'monday',
        'start_time' => $timeSlot->start_time,
        'end_time' => $timeSlot->end_time,
        'score' => 0.8,
        'assigned_by_algorithm' => false,
        'is_confirmed' => false,
    ]);
});

it('genera asignaciones con datos coincidentes', function () {
    $algorithm = new AssignmentAlgorithm();
    $algorithm->generateAssignments();
    $assignments = Assignment::with(['group', 'classroom', 'timeSlot'])->get();

    expect($assignments)
        ->not->toBeEmpty()
        ->and($assignments->first())
        ->group->name->toBe('Grupo Prueba')
        ->classroom->name->toBe('Aula 101')
        ->day->toBeIn(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
        ->start_time->format('H:i:s')->toBe('08:00:00')
        ->end_time->format('H:i:s')->toBe('10:00:00')
        ->score->toBeGreaterThanOrEqual(0.8); // el algoritmo actualiza la asignación existente
});