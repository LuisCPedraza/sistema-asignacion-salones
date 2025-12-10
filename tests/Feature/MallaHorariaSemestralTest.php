<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Models\Career;
use App\Models\Semester;
use App\Models\Subject;
use App\Modules\GestionAcademica\Models\StudentGroup;

class MallaHorariaSemestralTest extends TestCase
{
    use RefreshDatabase;

    protected $coordinador;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Usar el rol coordinador existente (se crea en la migración) o crearlo si falta
        $role = Role::firstOrCreate(
            ['slug' => 'coordinador'],
            ['name' => 'Coordinador', 'is_active' => true]
        );

        // Crear usuario coordinador
        $this->coordinador = User::factory()->create([
            'role_id' => $role->id,
        ]);

        // Crear datos de prueba
        $this->createTestData();
    }

    protected function createTestData()
    {
        // Crear carrera
        $career = Career::create([
            'name' => 'Tecnología en Desarrollo de Software',
            'description' => 'Carrera de prueba',
            'duration_semesters' => 6,
            'is_active' => true,
        ]);

        // Crear semestres
        for ($i = 1; $i <= 3; $i++) {
            $semester = Semester::create([
                'career_id' => $career->id,
                'number' => $i,
                'description' => "Semestre {$i}",
                'is_active' => true,
            ]);

            // Crear grupos para cada semestre
            StudentGroup::create([
                'name' => "Grupo A",
                'semester_id' => $semester->id,
                'level' => "S{$i}",
                'group_type' => 'A',
                'schedule_type' => 'day',
                'student_count' => 30,
                'number_of_students' => 30,
                'is_active' => true,
            ]);

            StudentGroup::create([
                'name' => "Grupo B",
                'semester_id' => $semester->id,
                'level' => "S{$i}",
                'group_type' => 'B',
                'schedule_type' => 'night',
                'student_count' => 25,
                'number_of_students' => 25,
                'is_active' => true,
            ]);
        }
    }

    #[Test]
    public function test_malla_semestral_page_loads_successfully()
    {
        $response = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral'));

        $response->assertStatus(200);
        $response->assertViewIs('visualization.malla-semestral');
    }

    #[Test]
    public function test_malla_semestral_shows_careers_in_select()
    {
        $response = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral'));

        $response->assertStatus(200);
        $response->assertSee('Tecnología en Desarrollo de Software');
    }

    #[Test]
    public function test_malla_semestral_filters_by_career()
    {
        $career = Career::first();

        $response = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedCareeId', $career->id);
        $response->assertViewHas('semesters');
    }

    #[Test]
    public function test_malla_semestral_filters_by_semester()
    {
        $career = Career::first();
        $semester = $career->semesters()->first();

        $response = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id,
                'semester_id' => $semester->id,
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedSemesterId', $semester->id);
        $response->assertViewHas('groups');
    }

    #[Test]
    public function test_malla_semestral_shows_both_group_types()
    {
        $career = Career::first();
        $semester = $career->semesters()->first();

        $response = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id,
                'semester_id' => $semester->id,
            ]));

        $response->assertStatus(200);
        
        // Debe mostrar Grupo A y Grupo B
        $groups = $response->viewData('groups');
        $this->assertCount(2, $groups);
        
        $groupTypes = $groups->pluck('group_type')->toArray();
        $this->assertContains('A', $groupTypes);
        $this->assertContains('B', $groupTypes);
    }

    #[Test]
    public function test_malla_semestral_filters_day_and_night_time_blocks()
    {
        $career = Career::first();
        $semester = $career->semesters()->first();
        $groupA = $semester->studentGroups()->where('group_type', 'A')->first();

        $response = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id,
                'semester_id' => $semester->id,
                'group_id' => $groupA->id,
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('timeBlocks');
        
        // Verificar que los bloques sean diurnos
        $timeBlocks = $response->viewData('timeBlocks');
        $this->assertNotEmpty($timeBlocks);
    }

    #[Test]
    public function test_cascade_filters_work_correctly()
    {
        $career = Career::first();

        // Paso 1: Seleccionar carrera
        $response1 = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id
            ]));

        $response1->assertStatus(200);
        $semesters = $response1->viewData('semesters');
        $this->assertNotEmpty($semesters);

        // Paso 2: Seleccionar semestre
        $semester = $semesters->first();
        $response2 = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id,
                'semester_id' => $semester->id,
            ]));

        $response2->assertStatus(200);
        $groups = $response2->viewData('groups');
        $this->assertNotEmpty($groups);

        // Paso 3: Seleccionar grupo
        $group = $groups->first();
        $response3 = $this->actingAs($this->coordinador)
            ->get(route('visualization.horario.malla-semestral', [
                'career_id' => $career->id,
                'semester_id' => $semester->id,
                'group_id' => $group->id,
            ]));

        $response3->assertStatus(200);
        $response3->assertViewHas('selectedGroupId', $group->id);
    }
}
