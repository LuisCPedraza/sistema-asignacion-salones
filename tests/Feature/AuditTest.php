<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_index_page_loads()
    {
        // Crear usuario admin
        $admin = User::factory()->create(['role_id' => 1]);

        // Actuar como admin para poder registrar logs
        $this->actingAs($admin);

        // Crear algunos logs de prueba
        AuditLog::log(
            User::class,
            $admin->id,
            'create',
            null,
            ['name' => 'Test User', 'email' => 'test@example.com'],
            'Test: Usuario de prueba creado'
        );

        AuditLog::log(
            User::class,
            $admin->id,
            'update',
            ['name' => 'Old Name'],
            ['name' => 'New Name'],
            'Test: Nombre actualizado'
        );

        // Acceder a la página como admin
        $response = $this->get(route('admin.audit.index'));

        $response->assertStatus(200);
        $response->assertViewHas('logs');
        $response->assertViewHas('filters');
    }

    public function test_audit_show_page()
    {
        $admin = User::factory()->create(['role_id' => 1]);

        $this->actingAs($admin);

        $log = AuditLog::log(
            User::class,
            $admin->id,
            'update',
            ['email' => 'old@example.com'],
            ['email' => 'new@example.com'],
            'Test: Email actualizado'
        );

        $response = $this->get(route('admin.audit.show', $log));

        $response->assertStatus(200);
        $response->assertViewHas('auditLog');
        $response->assertSee('Detalle de Auditoría');
    }

    public function test_audit_logs_user_creation()
    {
        $admin = User::factory()->create(['role_id' => 1]);

        $this->actingAs($admin);

        // Crear un usuario
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com'
        ]);

        // Verificar que se creó un log
        $log = AuditLog::where('model', 'User')
            ->where('model_id', $user->id)
            ->where('action', 'create')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('Original Name', $log->new_values['name']);
    }

    public function test_audit_filters_work()
    {
        $admin = User::factory()->create(['role_id' => 1]);

        $this->actingAs($admin);

        AuditLog::log(User::class, $admin->id, 'create', null, ['name' => 'User 1'], 'Test 1');
        AuditLog::log(User::class, $admin->id, 'update', null, ['name' => 'User 2'], 'Test 2');
        AuditLog::log(User::class, $admin->id, 'delete', null, ['name' => 'User 3'], 'Test 3');

        // Filtrar por acción
        $response = $this->get(route('admin.audit.index', ['action' => 'update']));

        $response->assertStatus(200);
        $logs = $response->viewData('logs');
        // Debe tener al menos un log con action = update
        $this->assertTrue($logs->count() > 0);
    }

    public function test_audit_formatted_changes()
    {
        $admin = User::factory()->create(['role_id' => 1]);

        $this->actingAs($admin);

        $log = AuditLog::log(
            User::class,
            $admin->id,
            'update',
            ['email' => 'old@example.com', 'name' => 'Old Name'],
            ['email' => 'new@example.com', 'name' => 'New Name'],
            'Test: Cambios múltiples'
        );

        $formatted = $log->getFormattedChanges();
        
        $this->assertCount(2, $formatted);
        $this->assertEquals('Old Name', $formatted['name']['old']);
        $this->assertEquals('New Name', $formatted['name']['new']);
    }

    public function test_unauthorized_cannot_access_audit()
    {
        $user = User::factory()->create(['role_id' => 2]); // No administrador

        $this->actingAs($user);

        $response = $this->get(route('admin.audit.index'));

        $response->assertStatus(403);
    }
}
