<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Infraestructura\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        // Datos reales proporcionados: sedes Bolivar y Balsas
        $buildings = [
            [
                'name' => 'Bolivar',
                'code' => 'BOL',
                'location' => 'Bolivar',
                'floors' => 3,
                'description' => 'Sede Bolivar con aulas, laboratorios de cómputo, auditorio para eventos y sótanos',
                'facilities' => ['aulas', 'laboratorios', 'auditorio', 'sotano'],
                'is_active' => true,
            ],
            [
                'name' => 'Balsas',
                'code' => 'BAL',
                'location' => 'Balsas',
                'floors' => 3,
                'description' => 'Sede Balsas con aulas, laboratorios de cómputo y zona deportiva',
                'facilities' => ['aulas', 'laboratorios', 'zona_deportiva'],
                'is_active' => true,
            ],
        ];

        foreach ($buildings as $building) {
            Building::updateOrCreate(
                ['code' => $building['code']],
                $building
            );
        }
    }
}