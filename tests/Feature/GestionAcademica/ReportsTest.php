<?php

namespace Tests\Feature\GestionAcademica;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\Teacher;
use App\Models\Career;
use App\Models\Semester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    protected $coordinator;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(
            ['slug' => 'coordinador'],
            ['name' => 'Coordinador']
        );

        $this->coordinator = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function coordinator_can_view_reports_page()
    {
        $this->actingAs($this->coordinator)
            ->get(route('gestion-academica.reports.index'))
            ->assertStatus(200)
            ->assertViewIs('gestion-academica.reports.index')
            ->assertSee('Reportes Académicos');
    }

    #[Test]
    public function reports_show_metrics_with_data()
    {
        $career = Career::factory()->create();
        $semester = Semester::factory()->create(['career_id' => $career->id]);
        
        StudentGroup::factory()->count(3)->create([
            'semester_id' => $semester->id,
            'is_active' => true,
            'student_count' => 30,
        ]);

        Teacher::factory()->count(2)->create([
            'is_active' => true,
        ]);

        $this->actingAs($this->coordinator)
            ->get(route('gestion-academica.reports.index'))
            ->assertStatus(200)
            ->assertSee('Grupos Totales')
            ->assertSee('Profesores Totales')
            ->assertSee('Total Estudiantes');
    }

    #[Test]
    public function coordinator_can_export_reports_to_pdf()
    {
        $career = Career::factory()->create();
        $semester = Semester::factory()->create(['career_id' => $career->id]);
        
        StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->coordinator)
            ->get(route('gestion-academica.reports.export'));

        $response->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    }

    #[Test]
    public function non_coordinator_cannot_access_reports()
    {
        $otherRole = Role::firstOrCreate(
            ['slug' => 'profesor'],
            ['name' => 'Profesor']
        );

        $user = User::factory()->create([
            'role_id' => $otherRole->id,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('gestion-academica.reports.index'))
            ->assertStatus(403);
    }

    #[Test]
    public function reports_can_filter_by_date_range()
    {
        $career = Career::factory()->create();
        $semester = Semester::factory()->create(['career_id' => $career->id]);
        
        // Grupo antiguo
        StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'created_at' => now()->subMonths(6),
        ]);

        // Grupo reciente
        StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->actingAs($this->coordinator)
            ->get(route('gestion-academica.reports.index', [
                'start_date' => now()->subWeek()->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]));

        $response->assertStatus(200);
    }
}
