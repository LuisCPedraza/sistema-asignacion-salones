<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class SystemConfigTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_system_config()
    {
        $adminRole = Role::where('slug', 'administrador')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin);

        $response = $this->get(route('admin.config.index'));

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_edit_system_config()
    {
        $adminRole = Role::where('slug', 'administrador')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin);

        $response = $this->put(route('admin.config.update'), [
            'institution_name' => 'Universidad de Prueba',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}