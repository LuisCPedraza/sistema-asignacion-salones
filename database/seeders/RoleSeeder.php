<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Auth\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Administrador',
                'slug' => Role::ADMINISTRADOR,
                'description' => 'Acceso completo al sistema'
            ],
            [
                'name' => 'Secretaria Administrativa',
                'slug' => Role::SECRETARIA_ADMINISTRATIVA,
                'description' => 'Gestión administrativa y reportes'
            ],
            [
                'name' => 'Coordinador',
                'slug' => Role::COORDINADOR,
                'description' => 'Gestión académica y asignaciones'
            ],
            [
                'name' => 'Secretaria de Coordinación',
                'slug' => Role::SECRETARIA_COORDINACION,
                'description' => 'Apoyo en gestión académica'
            ],
            [
                'name' => 'Coordinador de Infraestructura',
                'slug' => Role::COORDINADOR_INFRAESTRUCTURA,
                'description' => 'Gestión de salones y recursos'
            ],
            [
                'name' => 'Secretaria de Infraestructura',
                'slug' => Role::SECRETARIA_INFRAESTRUCTURA,
                'description' => 'Apoyo en gestión de infraestructura'
            ],
            [
                'name' => 'Profesor',
                'slug' => Role::PROFESOR,
                'description' => 'Acceso a horarios personales'
            ],
            [
                'name' => 'Profesor Invitado',
                'slug' => Role::PROFESOR_INVITADO,
                'description' => 'Acceso temporal al sistema'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
