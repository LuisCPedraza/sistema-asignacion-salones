<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;

class AssignTeachersToUsersSeeder extends Seeder
{
    public function run()
    {
        // Asignar el usuario profesor existente al primer profesor
        $professorUser = User::where('email', 'profesor@universidad.edu')->first();
        $firstTeacher = Teacher::first();
        
        if ($professorUser && $firstTeacher) {
            $firstTeacher->update(['user_id' => $professorUser->id]);
            $this->command->info("✅ Usuario profesor asignado al profesor: {$firstTeacher->first_name}");
        }

        // Crear usuarios para los profesores sin user_id
        $profesorRole = \App\Modules\Auth\Models\Role::where('slug', 'profesor')->first();
        if (!$profesorRole) {
            $this->command->error('❌ Rol profesor no encontrado');
            return;
        }

        $teachers = Teacher::whereNull('user_id')->get();
        foreach ($teachers as $teacher) {
            // Crear usuario para cada profesor
            $user = User::create([
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'email' => $teacher->email,
                'password' => bcrypt('password'), // Contraseña por defecto
                'role_id' => $profesorRole->id,
            ]);

            // Vincular profesor con usuario
            $teacher->update(['user_id' => $user->id]);
            
            $this->command->info("✅ Usuario creado para: {$teacher->first_name} {$teacher->last_name} ({$teacher->email})");
        }
    }
}
