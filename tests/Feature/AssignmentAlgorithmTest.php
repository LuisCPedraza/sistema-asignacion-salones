<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\Asignacion\Services\AssignmentAlgorithm;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
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
});

it('genera asignaciones con datos coincidentes', function () {
    $algorithm = new AssignmentAlgorithm();
    $assignments = collect($algorithm->generateAssignments());

    expect($assignments)
        ->not->toBeEmpty()
        ->and($assignments->first())
        ->group->name->toBe('Grupo Prueba')
        ->classroom->name->toBe('Aula 101')
        ->day->toBe('monday')
        ->start_time->format('H:i:s')->toBe('08:00:00')
        ->end_time->format('H:i:s')->toBe('10:00:00')
        ->score->toBeGreaterThanOrEqual(0.9); // subimos a 0.9 por las reglas altas
});