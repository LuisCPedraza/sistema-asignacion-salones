<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TeacherAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function profesor_puede_ver_sus_disponibilidades()
    {
        // Crear usuario profesor con role_id = 7 (profesor)
        $user = User::factory()->create(['role_id' => 7]);

        // Crear profesor asociado
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@test.com',
            'phone' => '1234567890',
            'specialty' => 'Matemáticas',
        ]);

        // Crear disponibilidad
        TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'is_available' => true,
            'notes' => 'Disponible en la mañana',
        ]);

        // Autenticar y acceder
        $response = $this->actingAs($user)
            ->get(route('gestion-academica.teachers.availabilities.my'));

        // Aserciones
        $response->assertStatus(200);
        $response->assertViewHas('availabilities');
        $response->assertSee('08:00-12:00');  // Verificar formato
        $response->assertSee('Lunes');         // Verificar nombre del día
        $response->assertDontSee('Call to a member function'); // Sin errores
    }

    #[Test]
    public function formatted_start_time_retorna_string_valido()
    {
        // Crear usuario y profesor manualmente
        $user = User::factory()->create(['role_id' => 7]);
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'first_name' => 'María',
            'last_name' => 'González',
            'email' => 'maria.gonzalez@test.com',
            'phone' => '9876543210',
            'specialty' => 'Física',
        ]);

        $availability = TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'tuesday',
            'start_time' => '14:30:00',
            'end_time' => '18:00:00',
            'is_available' => true,
        ]);

        // Verificar que el método retorna un string
        $this->assertIsString($availability->formatted_start_time);
        $this->assertEquals($availability->formatted_start_time, '14:30');
        $this->assertEquals($availability->formatted_end_time, '18:00');
    }

    #[Test]
    public function formatted_time_maneja_diferentes_formatos_correctamente()
    {
        // Crear usuario y profesor manualmente
        $user = User::factory()->create(['role_id' => 7]);
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'first_name' => 'Carlos',
            'last_name' => 'Ramírez',
            'email' => 'carlos.ramirez@test.com',
            'phone' => '5555555555',
            'specialty' => 'Química',
        ]);

        // Probar con horario nocturno (hasta medianoche)
        $availability = TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'wednesday',
            'start_time' => '19:00:00',
            'end_time' => '23:59:00',
            'is_available' => true,
        ]);

        // Debe formatear correctamente sin importar si es formato de 12 o 24 horas
        $this->assertEquals('19:00', $availability->formatted_start_time);
        $this->assertEquals('23:59', $availability->formatted_end_time);
    }
}