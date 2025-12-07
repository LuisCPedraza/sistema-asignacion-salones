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

        // Opcional: Asignar más relaciones si es necesario
        $teachers = Teacher::whereNull('user_id')->get();
        foreach ($teachers as $index => $teacher) {
            // Podrías crear usuarios adicionales o asignar a usuarios existentes
            $this->command->info("📝 Profesor sin usuario: {$teacher->first_name} {$teacher->last_name}");
        }
    }
}
