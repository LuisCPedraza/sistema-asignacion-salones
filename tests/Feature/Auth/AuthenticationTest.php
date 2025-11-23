<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        // Crear un usuario con rol de administrador
        $user = User::factory()->withRole('administrador')->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->withRole('administrador')->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_users_cannot_login()
    {
        $user = User::factory()->withRole('profesor')->inactive()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_temporary_users_cannot_login_after_expiration()
    {
        $user = User::factory()->withRole('profesor_invitado')->create([
            'temporary_access_expires_at' => now()->subDays(1), // Expired yesterday
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}