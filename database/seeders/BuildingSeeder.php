<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Infraestructura\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        $buildings = [
            [
                'name' => 'Edificio de Ingeniería',
                'code' => 'EI',
                'location' => 'Campus Central',
                'floors' => 5,
                'description' => 'Edificio principal de la facultad de ingeniería',
                'facilities' => ['laboratorios', 'aulas', 'oficinas'],
                'is_active' => true,
            ],
            [
                'name' => 'Edificio de Ciencias',
                'code' => 'EC',
                'location' => 'Campus Norte',
                'floors' => 4,
                'description' => 'Edificio para facultad de ciencias',
                'facilities' => ['laboratorios', 'aulas', 'biblioteca'],
                'is_active' => true,
            ],
            [
                'name' => 'Edificio de Administración',
                'code' => 'EA',
                'location' => 'Campus Central',
                'floors' => 3,
                'description' => 'Edificio administrativo y de dirección',
                'facilities' => ['oficinas', 'salas de reuniones'],
                'is_active' => true,
            ],
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}