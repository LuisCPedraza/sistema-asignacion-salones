<?php

namespace Tests\Feature\Infraestructura;

use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReservationTest extends TestCase
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
    public function coordinator_can_view_reservations_index()
    {
        $this->actingAs($this->coordinator)
            ->get(route('infraestructura.reservations.index'))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.reservations.index');
    }

    #[Test]
    public function coordinator_can_create_reservation()
    {
        $this->actingAs($this->coordinator)
            ->get(route('infraestructura.reservations.create'))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.reservations.create');
    }

    #[Test]
    public function coordinator_can_store_reservation()
    {
        $data = [
            'classroom_id' => $this->classroom->id,
            'title' => 'Reunión académica',
            'description' => 'Revisión de plan de estudios',
            'requester_name' => 'Juan Pérez',
            'requester_email' => 'juan@example.com',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'status' => 'pendiente',
            'notes' => 'Llevar proyector',
        ];

        $this->actingAs($this->coordinator)
            ->post(route('infraestructura.reservations.store'), $data)
            ->assertRedirect(route('infraestructura.reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'title' => 'Reunión académica',
            'status' => 'pendiente',
        ]);
    }

    #[Test]
    public function coordinator_can_view_and_edit_reservation()
    {
        $reservation = Reservation::factory()->create([
            'classroom_id' => $this->classroom->id,
        ]);

        $this->actingAs($this->coordinator)
            ->get(route('infraestructura.reservations.show', $reservation))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.reservations.show');

        $this->actingAs($this->coordinator)
            ->get(route('infraestructura.reservations.edit', $reservation))
            ->assertStatus(200)
            ->assertViewIs('infraestructura.reservations.edit');
    }

    #[Test]
    public function coordinator_can_update_reservation()
    {
        $reservation = Reservation::factory()->create([
            'classroom_id' => $this->classroom->id,
            'status' => 'pendiente',
        ]);

        $update = [
            'classroom_id' => $this->classroom->id,
            'title' => 'Reserva actualizada',
            'description' => 'Nueva descripción',
            'requester_name' => 'Maria Lopez',
            'requester_email' => 'maria@example.com',
            'start_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(2)->addHour()->format('Y-m-d H:i:s'),
            'status' => 'aprobada',
            'notes' => 'Notas nuevas',
        ];

        $this->actingAs($this->coordinator)
            ->put(route('infraestructura.reservations.update', $reservation), $update)
            ->assertRedirect(route('infraestructura.reservations.show', $reservation));

        $reservation->refresh();
        $this->assertEquals('Reserva actualizada', $reservation->title);
        $this->assertEquals('aprobada', $reservation->status);
    }

    #[Test]
    public function coordinator_can_delete_reservation()
    {
        $reservation = Reservation::factory()->create([
            'classroom_id' => $this->classroom->id,
        ]);

        $this->actingAs($this->coordinator)
            ->delete(route('infraestructura.reservations.destroy', $reservation))
            ->assertRedirect(route('infraestructura.reservations.index'));

        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    #[Test]
    public function coordinator_can_approve_reject_cancel()
    {
        $reservation = Reservation::factory()->create([
            'classroom_id' => $this->classroom->id,
            'status' => 'pendiente',
        ]);

        $this->actingAs($this->coordinator)
            ->post(route('infraestructura.reservations.approve', $reservation))
            ->assertRedirect();
        $this->assertEquals('aprobada', $reservation->fresh()->status);

        $this->actingAs($this->coordinator)
            ->post(route('infraestructura.reservations.reject', $reservation))
            ->assertRedirect();
        $this->assertEquals('rechazada', $reservation->fresh()->status);

        $this->actingAs($this->coordinator)
            ->post(route('infraestructura.reservations.cancel', $reservation))
            ->assertRedirect();
        $this->assertEquals('cancelada', $reservation->fresh()->status);
    }

    #[Test]
    public function validation_requires_classroom_and_valid_times()
    {
        $data = [
            'title' => 'Reserva sin salón',
            'requester_name' => 'Juan',
            'start_time' => now()->format('Y-m-d H:i:s'),
            'end_time' => now()->subHour()->format('Y-m-d H:i:s'),
            'status' => 'pendiente',
        ];

        $this->actingAs($this->coordinator)
            ->post(route('infraestructura.reservations.store'), $data)
            ->assertSessionHasErrors(['classroom_id', 'end_time']);
    }

    #[Test]
    public function non_coordinator_cannot_access_reservations()
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
            ->get(route('infraestructura.reservations.index'))
            ->assertStatus(403);
    }
}
