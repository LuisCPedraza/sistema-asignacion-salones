<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;  // Limpia DB entre tests

    #[Test]  // Mantén attribute para compatibilidad
    public function index_returns_view_with_users()
    {
        // Arrange: Crea user autenticado (admin) y 2 users de prueba
        $this->actingAs(User::factory()->create(['rol' => 'admin']));
        User::factory()->create(['rol' => 'profesor']);
        User::factory()->create(['rol' => 'admin']);

        // Act: Simula request a index
        $response = $this->get('/admin/users');

        // Assert: Respuesta OK y vista con 3 users (incluye autenticado)
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users', function ($users) {
            return $users->count() === 3;
        });
    }

    #[Test]  // Mantén attribute para compatibilidad
    public function store_creates_user_with_role()
    {
        // Arrange: Crea user autenticado (admin)
        $this->actingAs(User::factory()->create(['rol' => 'admin']));

        // Datos de user con rol 'coordinador'
        $userData = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => 'coordinador'
        ];

        // Act: Simula POST a store
        $response = $this->post('/admin/users', $userData);

        // Assert: Redirige con success y user creado con rol
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'rol' => 'coordinador'
        ]);
    }

    #[Test]  // Nuevo test para rol ampliado 'superadmin'
    public function store_creates_superadmin_user()
    {
        // Arrange: Crea user autenticado (admin)
        $this->actingAs(User::factory()->create(['rol' => 'admin']));

        // Datos de user con rol 'superadmin'
        $userData = [
            'name' => 'Super Admin Test',
            'email' => 'super@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => 'superadmin'
        ];

        // Act: Simula POST a store
        $response = $this->post('/admin/users', $userData);

        // Assert: Redirige con success y user creado con rol
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'super@test.com',
            'rol' => 'superadmin'
        ]);
    }
}
