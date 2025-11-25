<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Solicitar Acceso');
        $response->assertSee('Proceso de Aprobación');
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Verificar que el usuario fue creado con los campos correctos
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_active' => false,
        ]);

        // Verificar que role_id es NULL
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNull($user->role_id);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
    }

    public function test_newly_registered_users_cannot_login_until_approved()
    {
        // Registrar un usuario
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Intentar iniciar sesión
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Debería fallar porque la cuenta no está activa
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_name_email_password()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_registration_requires_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_registration_requires_unique_email()
    {
        // Crear un usuario existente usando el estado withRole para evitar el problema de role_id
        $role = Role::factory()->administrador()->create();
        User::factory()->create([
            'email' => 'test@example.com',
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_pending_users_have_no_role_and_are_inactive()
    {
        // Crear un usuario pendiente usando el estado pending
        $user = User::factory()->pending()->create();

        $this->assertTrue($user->isPendingApproval());
        $this->assertFalse($user->canAccessSystem());
        $this->assertNull($user->role);
    }

    public function test_approved_users_can_access_system()
    {
        // Crear un rol
        $role = Role::factory()->profesor()->create();
        
        // Crear un usuario aprobado
        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $this->assertFalse($user->isPendingApproval());
        $this->assertTrue($user->canAccessSystem());
        $this->assertNotNull($user->role);
    }
}