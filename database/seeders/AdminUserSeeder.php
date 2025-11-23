<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Verificar si ya existe el rol de administrador
        $adminRole = Role::where('slug', 'administrador')->first();
        
        if (!$adminRole) {
            $adminRole = Role::create([
                'name' => 'Administrador',
                'slug' => 'administrador',
                'description' => 'Acceso completo al sistema'
            ]);
        }

        // Verificar si ya existe el usuario admin
        $adminUser = User::where('email', 'admin@universidad.edu')->first();
        
        if (!$adminUser) {
            User::create([
                'name' => 'Administrador Principal',
                'email' => 'admin@universidad.edu',
                'password' => bcrypt('password123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Usuario administrador creado: admin@universidad.edu / password123');
        } else {
            $this->command->info('Usuario administrador ya existe');
        }
    }
}