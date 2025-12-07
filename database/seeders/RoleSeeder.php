<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\Auth\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Administrador',
                'slug' => Role::ADMINISTRADOR,
                'description' => 'Acceso completo al sistema',
                'is_active' => true
            ],
            [
                'name' => 'Secretaria Administrativa',
                'slug' => Role::SECRETARIA_ADMINISTRATIVA,
                'description' => 'Gestión administrativa y reportes',
                'is_active' => true
            ],
            [
                'name' => 'Coordinador',
                'slug' => Role::COORDINADOR,
                'description' => 'Gestión académica y asignaciones',
                'is_active' => true
            ],
            [
                'name' => 'Secretaria de Coordinación',
                'slug' => Role::SECRETARIA_COORDINACION,
                'description' => 'Apoyo en gestión académica',
                'is_active' => true
            ],
            [
                'name' => 'Coordinador de Infraestructura',
                'slug' => Role::COORDINADOR_INFRAESTRUCTURA,
                'description' => 'Gestión de salones y recursos',
                'is_active' => true
            ],
            [
                'name' => 'Secretaria de Infraestructura',
                'slug' => Role::SECRETARIA_INFRAESTRUCTURA,
                'description' => 'Apoyo en gestión de infraestructura',
                'is_active' => true
            ],
            [
                'name' => 'Profesor',
                'slug' => Role::PROFESOR,
                'description' => 'Acceso a horarios personales',
                'is_active' => true
            ],
            [
                'name' => 'Profesor Invitado',
                'slug' => Role::PROFESOR_INVITADO,
                'description' => 'Acceso temporal al sistema',
                'is_active' => true
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}