<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SimpleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar los seeders necesarios
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
    }

    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Inicia Sesión', false);
    }

    public function test_admin_user_can_login_with_correct_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'admin@universidad.edu',
            'password' => 'password123',
        ]);

        // Primero verificar que no hay errores de autenticación
        $response->assertSessionHasNoErrors();
        
        // Luego verificar la redirección
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_users_cannot_login_with_incorrect_password()
    {
        $response = $this->post('/login', [
            'email' => 'admin@universidad.edu',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        // Primero hacer login
        $this->post('/login', [
            'email' => 'admin@universidad.edu',
            'password' => 'password123',
        ]);

        // Luego hacer logout
        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}