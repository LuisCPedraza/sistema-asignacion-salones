<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Auth\Models\Role;

class DebugAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_debug_teacher_availability_creation()
    {
        $coordinatorRole = Role::where('slug', 'coordinador')->first();
        $user = User::factory()->create(['role_id' => $coordinatorRole->id]);
        $this->actingAs($user);

        $teacher = Teacher::create([
            'first_name' => 'Debug',
            'last_name' => 'Teacher',
            'email' => 'debug@test.edu',
            'specialty' => 'Debug',
            'is_active' => true,
        ]);

        // Datos que enviaremos
        $data = [
            'day' => 'monday',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'is_available' => true,
            'notes' => 'Debug test'
        ];

        \Log::info('Enviando datos para teacher availability:', $data);

        $response = $this->post(route('gestion-academica.teachers.availabilities.store', $teacher), $data);

        \Log::info('Respuesta status: ' . $response->status());
        \Log::info('Respuesta headers: ', $response->headers->all());
        
        if ($response->getContent()) {
            \Log::info('Respuesta content: ' . $response->getContent());
        }

        // Verificar en la base de datos
        \Log::info('Teacher availabilities en BD:', \App\Modules\GestionAcademica\Models\TeacherAvailability::all()->toArray());

        $this->assertTrue(true); // Solo para que el test pase mientras debugueamos
    }

    public function test_debug_classroom_availability_creation()
    {
        $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
        $user = User::factory()->create(['role_id' => $infraRole->id]);
        $this->actingAs($user);

        $classroom = Classroom::create([
            'name' => 'Aula Debug',
            'code' => 'DEBUG',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        // Datos que enviaremos
        $data = [
            'day' => 'monday',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'is_available' => true,
            'availability_type' => 'regular',
            'notes' => 'Debug test'
        ];

        \Log::info('Enviando datos para classroom availability:', $data);

        $response = $this->post(route('infraestructura.classrooms.availabilities.store', $classroom), $data);

        \Log::info('Respuesta status: ' . $response->status());
        
        if ($response->getContent()) {
            \Log::info('Respuesta content: ' . $response->getContent());
        }

        // Verificar en la base de datos
        \Log::info('Classroom availabilities en BD:', \App\Modules\Infraestructura\Models\ClassroomAvailability::all()->toArray());

        $this->assertTrue(true); // Solo para que el test pase mientras debugueamos
    }
}
