<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiddlewareRoleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]  // Cambiado de /** @test */
    public function middleware_allows_access_for_correct_role()
    {
        // Arrange: Crea user admin
        $admin = User::factory()->create(['role' => 'admin']);

        // Act: Simula acceso a admin dashboard como admin
        $response = $this->actingAs($admin)->get('/admin/dashboard');

        // Assert: Respuesta OK (200, vista cargada)
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    #[Test]  // Cambiado de /** @test */
    public function middleware_blocks_access_for_wrong_role()
    {
        // Arrange: Crea user profesor
        $profesor = User::factory()->create(['role' => 'profesor']);

        // Act: Simula acceso a admin dashboard como profesor
        $response = $this->actingAs($profesor)->get('/admin/dashboard');

        // Assert: 403 acceso denegado
        $response->assertStatus(403);
    }

    #[Test]  // Cambiado de /** @test */
    public function middleware_blocks_unauthenticated_access()
    {
        // Act: Simula acceso sin autenticación
        $response = $this->get('/admin/dashboard');

        // Assert: Redirige a login (302, no 200)
        $response->assertRedirect(route('login'));
    }
}
