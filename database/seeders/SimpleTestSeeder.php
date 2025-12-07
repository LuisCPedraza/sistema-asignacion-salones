<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use App\Modules\Auth\Models\Role;
use App\Models\User;

class SimpleTestSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🎯 Creando datos de prueba SIMPLES para algoritmo...');

        // Primero seed roles, usuario admin y reglas
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            AssignmentRuleSeeder::class, // ¡NUEVO!
        ]);

        // Limpiar datos existentes
        TeacherAvailability::query()->delete();
        ClassroomAvailability::query()->delete();
        StudentGroup::query()->delete();
        Teacher::query()->delete();
        Classroom::query()->delete();

        // Crear datos mínimos para prueba
        $teacher = Teacher::create([
            'first_name' => 'Profesor',
            'last_name' => 'Prueba',
            'email' => 'profesor@test.edu',
            'specialty' => 'General',
            'is_active' => true,
        ]);

        $classroom = Classroom::create([
            'name' => 'Aula 101',
            'code' => 'A101',
            'capacity' => 30,
            'type' => 'aula',
            'floor' => 1,
            'is_active' => true,
        ]);

        // Crear grupo usando las columnas EXISTENTES en la base de datos
        $group = StudentGroup::create([
            'name' => 'Grupo Prueba',
            'level' => 'intermedio',
            'student_count' => 25, // COLUMNA EXISTENTE Y OBLIGATORIA
            'special_features' => 'Ninguna', // COLUMNA EXISTENTE Y OBLIGATORIA
            'number_of_students' => 25, // Columna nueva que agregamos
            'special_requirements' => 'Ninguno', // Columna nueva que agregamos
            'is_active' => true,
        ]);

        // Crear disponibilidades que COINCIDAN exactamente con los time slots del algoritmo
        TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'monday',
            'start_time' => '08:00:00', // Coincide con el primer time slot
            'end_time' => '10:00:00',   // Coincide con el primer time slot
            'is_available' => true,
        ]);

        ClassroomAvailability::create([
            'classroom_id' => $classroom->id,
            'day' => 'monday',
            'start_time' => '08:00:00', // Coincide con el primer time slot
            'end_time' => '10:00:00',   // Coincide con el primer time slot
            'is_available' => true,
            'availability_type' => 'regular',
        ]);

        $this->command->info('✅ Datos de prueba simples creados:');
        $this->command->info("   - 1 profesor");
        $this->command->info("   - 1 salón (capacidad: 30)");
        $this->command->info("   - 1 grupo (25 estudiantes)");
        $this->command->info("   - Disponibilidades coincidentes: Lunes 08:00-10:00");
        $this->command->info("   - Reglas de asignación configuradas");

        // Verificar que los datos se crearon
        $this->command->info('📊 VERIFICACIÓN DE DATOS CREADOS:');
        $this->command->info("   - Grupos: " . StudentGroup::count());
        $this->command->info("   - Profesores: " . Teacher::count());
        $this->command->info("   - Salones: " . Classroom::count());
        $this->command->info("   - Disponibilidades profesores: " . TeacherAvailability::count());
        $this->command->info("   - Disponibilidades salones: " . ClassroomAvailability::count());
    }
}