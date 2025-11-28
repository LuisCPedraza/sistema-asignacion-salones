<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Auth\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        $adminRole = Role::where('slug', Role::ADMINISTRADOR)->first();
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@universidad.edu'],
                [
                    'name' => 'Administrador Principal',
                    'password' => Hash::make('password123'),
                    'role_id' => $adminRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info('✅ Usuario administrador creado: admin@universidad.edu / password123');
        }

        // Crear usuario coordinador
        $coordinatorRole = Role::where('slug', Role::COORDINADOR)->first();
        if ($coordinatorRole) {
            User::firstOrCreate(
                ['email' => 'coordinador@universidad.edu'],
                [
                    'name' => 'Coordinador Académico',
                    'password' => Hash::make('password123'),
                    'role_id' => $coordinatorRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info('✅ Usuario coordinador creado: coordinador@universidad.edu / password123');
        }

        // Crear usuario profesor de prueba
        $professorRole = Role::where('slug', Role::PROFESOR)->first();
        if ($professorRole) {
            User::firstOrCreate(
                ['email' => 'profesor@universidad.edu'],
                [
                    'name' => 'Profesor Ejemplo',
                    'password' => Hash::make('password123'),
                    'role_id' => $professorRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info('✅ Usuario profesor creado: profesor@universidad.edu / password123');
        }

        // Usuario coordinador de infraestructura
        if (!User::where('email', 'infraestructura@universidad.edu')->exists()) {
            $infraRole = Role::where('slug', 'coordinador_infraestructura')->first();
            User::create([
                'name' => 'Coordinador Infraestructura',
                'email' => 'infraestructura@universidad.edu',
                'password' => bcrypt('password123'),
                'role_id' => $infraRole->id,
                'is_active' => true,
            ]);
            $this->command->info('✅ Usuario coordinador de infraestructura creado: infraestructura@universidad.edu / password123');
        }        
    }
}