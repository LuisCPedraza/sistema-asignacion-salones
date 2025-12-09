<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;

class CreateTeacherForUser extends Command
{
    protected $signature = 'create:teacher-for-user {user_id=3}';

    protected $description = 'Crea un registro Teacher para un usuario profesor';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("Usuario ID {$userId} no encontrado");
            return;
        }
        
        if ($user->teacher) {
            $this->warn("El usuario ya tiene un profesor asociado (ID: {$user->teacher->id})");
            return;
        }
        
        if ($user->role->slug !== 'profesor' && $user->role->slug !== 'profesor_invitado') {
            $this->error("El usuario no tiene rol de profesor");
            return;
        }
        
        // Crear profesor
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'first_name' => explode(' ', $user->name)[0] ?? $user->name,
            'last_name' => explode(' ', $user->name)[1] ?? '',
            'email' => $user->email,
            'phone' => '',
            'specialty' => 'Especialidad Pendiente',
            'is_active' => true,
        ]);
        
        $this->info("✓ Profesor creado exitosamente");
        $this->info("  User ID: {$user->id}");
        $this->info("  Teacher ID: {$teacher->id}");
        $this->info("  Nombre: {$teacher->full_name}");
        $this->info("\nAhora el usuario puede acceder a /visualizacion/horario-personal y /gestion-academica/my-availabilities");
    }
}
