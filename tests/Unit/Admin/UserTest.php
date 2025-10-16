<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]  // Cambiado de /** @test */
    public function index_returns_view_with_users()
    {
        // Arrange: Crea user autenticado (admin) y 2 users de prueba
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        User::factory()->create(['role' => 'profesor']);
        User::factory()->create(['role' => 'admin']);

        // Act: Simula request a index
        $response = $this->get('/admin/users');

        // Assert: Respuesta OK y vista con 3 users (incluye autenticado)
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users', function ($users) {
            return $users->count() === 3;
        });
    }

    #[Test]  // Cambiado de /** @test */
    public function store_creates_user_with_role()
    {
        // Arrange: Crea user autenticado (admin)
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        // Datos de user con rol 'coordinador'
        $userData = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'coordinador'
        ];

        // Act: Simula POST a store
        $response = $this->post('/admin/users', $userData);

        // Assert: Redirige con success y user creado con rol
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'role' => 'coordinador'
        ]);
    }
}
