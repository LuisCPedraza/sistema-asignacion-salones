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
            'institution_code' => 'UNIV-TEST',
            'work_start_time' => '08:00',
            'work_end_time' => '17:00',
            'lunch_start_time' => '12:00',
            'lunch_end_time' => '13:00',
            'min_score_threshold' => '0.6',
            'max_attempts' => '15',
            'audit_retention_days' => '90',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}