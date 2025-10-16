<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LoginRoleTest extends TestCase
{
    use RefreshDatabase;  // Limpia DB entre tests

    #[Test]
    public function login_redirects_to_correct_dashboard_by_role()
    {
        // Arrange: Crea users con diferentes roles y passwords hashed
        $admin = User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);
        $profesor = User::factory()->create(['role' => 'profesor', 'password' => bcrypt('password')]);
        $coordinador = User::factory()->create(['role' => 'coordinador', 'password' => bcrypt('password')]);

        // Act & Assert para admin
        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);
        $this->assertStringContainsString('/admin/dashboard', $response->headers->get('Location', ''));

        // Logout antes del siguiente login
        Auth::logout();

        // Act & Assert para profesor
        $response = $this->post('/login', [
            'email' => $profesor->email,
            'password' => 'password',
        ]);
        $this->assertStringContainsString('/profesor/perfil', $response->headers->get('Location', ''));

        // Logout antes del siguiente login
        Auth::logout();

        // Act & Assert para coordinador
        $response = $this->post('/login', [
            'email' => $coordinador->email,
            'password' => 'password',
        ]);
        $this->assertStringContainsString('/coordinador/asignaciones', $response->headers->get('Location', ''));
    }

    #[Test]
    public function middleware_blocks_access_for_wrong_role()
    {
        // Arrange: Crea user profesor
        $profesor = User::factory()->create(['role' => 'profesor']);

        // Act: Simula acceso a admin dashboard como profesor
        $this->actingAs($profesor);
        $response = $this->get('/admin/dashboard');

        // Assert: 403 acceso denegado
        $response->assertStatus(403);
    }
}
