<?php

namespace Tests\Feature\Infraestructura;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Reservation;
use App\Modules\Infraestructura\Models\Maintenance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    protected $coordinator;
    protected $classroom;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(
            ['slug' => 'coordinador_infraestructura'],
            ['name' => 'Coordinador de Infraestructura']
        );

        $this->coordinator = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $this->classroom = Classroom::factory()->create(['is_active' => true]);
    }

    #[Test]
    public function coordinator_can_view_reports_page()
    {
        $this->actingAs($this->coordinator)
            ->get(route('infraestructura.reports.index'))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.reports.index');
    }

    #[Test]
    public function reports_show_metrics_with_data()
    {
        Reservation::factory()->approved()->create([
            'classroom_id' => $this->classroom->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
        ]);
        Maintenance::factory()->inProgress()->create([
            'classroom_id' => $this->classroom->id,
            'title' => 'Prueba',
        ]);

        $this->actingAs($this->coordinator)
            ->get(route('infraestructura.reports.index'))
            ->assertStatus(200)
            ->assertSee('Reservas Totales')
            ->assertSee('Mtos en progreso')
            ->assertSee('Prueba');
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
            ->get(route('infraestructura.reports.index'))
            ->assertStatus(403);
    }
}
