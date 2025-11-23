<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_users_can_logout()
    {
        $user = User::factory()->withRole('profesor')->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_redirect_to_correct_dashboard_based_on_role()
    {
        // Test admin redirect
        $admin = User::factory()->withRole('administrador')->create();
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        // Test coordinador redirect  
        $coordinador = User::factory()->withRole('coordinador')->create();
        $response = $this->actingAs($coordinador)->get('/coordinador/dashboard');
        $response->assertStatus(200);

        // Test profesor redirect
        $profesor = User::factory()->withRole('profesor')->create();
        $response = $this->actingAs($profesor)->get('/profesor/dashboard');
        $response->assertStatus(200);
    }
}