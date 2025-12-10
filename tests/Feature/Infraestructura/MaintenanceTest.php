<?php

namespace Tests\Feature\Infraestructura;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Maintenance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MaintenanceTest extends TestCase
{
    use RefreshDatabase;

    protected $coordinatorInfra;
    protected $classroom;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear rol coordinador de infraestructura
        $role = Role::firstOrCreate(
            ['slug' => 'coordinador_infraestructura'],
            ['name' => 'Coordinador de Infraestructura']
        );

        // Crear usuario coordinador de infraestructura
        $this->coordinatorInfra = User::factory()->create(['role_id' => $role->id, 'is_active' => true]);

        // Crear un salón
        $this->classroom = Classroom::factory()->create(['is_active' => true]);
    }

    #[Test]
    public function coordinator_infra_can_view_maintenance_index()
    {
        $this->actingAs($this->coordinatorInfra)
            ->get(route('infraestructura.maintenance.index'))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.maintenance.index');
    }

    #[Test]
    public function coordinator_infra_can_create_maintenance()
    {
        $this->actingAs($this->coordinatorInfra)
            ->get(route('infraestructura.maintenance.create'))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.maintenance.create');
    }

    #[Test]
    public function coordinator_infra_can_store_maintenance()
    {
        $data = [
            'classroom_id' => $this->classroom->id,
            'type' => 'preventivo',
            'title' => 'Limpieza y revisión',
            'description' => 'Mantenimiento preventivo',
            'status' => 'pendiente',
            'scheduled_date' => now()->addDays(7)->format('Y-m-d'),
            'responsible' => 'Juan Pérez',
            'cost' => 150.50,
        ];

        $this->actingAs($this->coordinatorInfra)
            ->post(route('infraestructura.maintenance.store'), $data)
            ->assertRedirect(route('infraestructura.maintenance.index'));

        $this->assertDatabaseHas('maintenances', [
            'classroom_id' => $this->classroom->id,
            'title' => 'Limpieza y revisión',
            'type' => 'preventivo',
            'status' => 'pendiente',
        ]);
    }

    #[Test]
    public function coordinator_infra_can_view_maintenance_details()
    {
        $maintenance = Maintenance::factory()->create([
            'classroom_id' => $this->classroom->id,
        ]);

        $this->actingAs($this->coordinatorInfra)
            ->get(route('infraestructura.maintenance.show', $maintenance))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.maintenance.show')
            ->assertViewHas('maintenance', $maintenance);
    }

    #[Test]
    public function coordinator_infra_can_edit_maintenance()
    {
        $maintenance = Maintenance::factory()->create([
            'classroom_id' => $this->classroom->id,
        ]);

        $this->actingAs($this->coordinatorInfra)
            ->get(route('infraestructura.maintenance.edit', $maintenance))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.maintenance.edit')
            ->assertViewHas('maintenance', $maintenance);
    }

    #[Test]
    public function coordinator_infra_can_update_maintenance()
    {
        $maintenance = Maintenance::factory()->create([
            'classroom_id' => $this->classroom->id,
            'status' => 'pendiente',
        ]);

        $updated = [
            'classroom_id' => $this->classroom->id,
            'type' => 'correctivo',
            'title' => 'Reparación urgente',
            'description' => 'Reparación de proyector',
            'status' => 'en_progreso',
            'responsible' => 'María López',
            'cost' => 500.00,
        ];

        $this->actingAs($this->coordinatorInfra)
            ->put(route('infraestructura.maintenance.update', $maintenance), $updated)
            ->assertRedirect(route('infraestructura.maintenance.show', $maintenance));

        $maintenance->refresh();
        $this->assertEquals('correctivo', $maintenance->type);
        $this->assertEquals('Reparación urgente', $maintenance->title);
        $this->assertEquals('en_progreso', $maintenance->status);
    }

    #[Test]
    public function coordinator_infra_can_delete_maintenance()
    {
        $maintenance = Maintenance::factory()->create([
            'classroom_id' => $this->classroom->id,
        ]);

        $this->actingAs($this->coordinatorInfra)
            ->delete(route('infraestructura.maintenance.destroy', $maintenance))
            ->assertRedirect(route('infraestructura.maintenance.index'));

        $this->assertDatabaseMissing('maintenances', ['id' => $maintenance->id]);
    }

    #[Test]
    public function coordinator_infra_can_mark_maintenance_as_in_progress()
    {
        $maintenance = Maintenance::factory()->create([
            'classroom_id' => $this->classroom->id,
            'status' => 'pendiente',
        ]);

        $this->actingAs($this->coordinatorInfra)
            ->post(route('infraestructura.maintenance.mark-in-progress', $maintenance))
            ->assertRedirect();

        $maintenance->refresh();
        $this->assertEquals('en_progreso', $maintenance->status);
        $this->assertNotNull($maintenance->start_date);
    }

    #[Test]
    public function coordinator_infra_can_mark_maintenance_as_completed()
    {
        $maintenance = Maintenance::factory()->create([
            'classroom_id' => $this->classroom->id,
            'status' => 'en_progreso',
        ]);

        $this->actingAs($this->coordinatorInfra)
            ->post(route('infraestructura.maintenance.mark-completed', $maintenance))
            ->assertRedirect();

        $maintenance->refresh();
        $this->assertEquals('completado', $maintenance->status);
        $this->assertNotNull($maintenance->end_date);
    }

    #[Test]
    public function maintenance_requires_classroom_id()
    {
        $data = [
            'type' => 'preventivo',
            'title' => 'Mantenimiento',
            'status' => 'pendiente',
        ];

        $this->actingAs($this->coordinatorInfra)
            ->post(route('infraestructura.maintenance.store'), $data)
            ->assertSessionHasErrors('classroom_id');
    }

    #[Test]
    public function maintenance_requires_valid_type()
    {
        $data = [
            'classroom_id' => $this->classroom->id,
            'type' => 'invalido',
            'title' => 'Mantenimiento',
            'status' => 'pendiente',
        ];

        $this->actingAs($this->coordinatorInfra)
            ->post(route('infraestructura.maintenance.store'), $data)
            ->assertSessionHasErrors('type');
    }

    #[Test]
    public function non_infraestructura_coordinator_cannot_access_maintenance()
    {
        $otherRole = Role::firstOrCreate(
            ['slug' => 'profesor'],
            ['name' => 'Profesor']
        );

        $otherUser = User::factory()->create([
            'role_id' => $otherRole->id,
            'is_active' => true,
        ]);

        $this->actingAs($otherUser)
            ->get(route('infraestructura.maintenance.index'))
            ->assertStatus(403);
    }
}
