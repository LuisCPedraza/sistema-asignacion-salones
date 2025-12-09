<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckTeacher extends Command
{
    protected $signature = 'check:teacher {user_id=3}';

    protected $description = 'Verifica si un usuario tiene profesor asociado';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("Usuario ID {$userId} no encontrado");
            return;
        }
        
        $this->info("Usuario: {$user->name}");
        $this->info("Rol: " . ($user->role ? $user->role->slug : "SIN ROL"));
        
        if ($user->teacher) {
            $this->info("Profesor: SÍ (ID: {$user->teacher->id})");
        } else {
            $this->error("Profesor: NO - ESTA ES LA RAZÓN DEL 404!");
            $this->info("Es necesario crear un registro Teacher para este usuario.");
        }
    }
}
