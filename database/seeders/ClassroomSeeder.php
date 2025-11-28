<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Building;

class ClassroomSeeder extends Seeder
{
    public function run()
    {
        $buildings = Building::all();

        $classrooms = [
            [
                'name' => 'Aula 101',
                'code' => 'A101',
                'capacity' => 30,
                'type' => 'aula',
                'floor' => 1,
                'resources' => json_encode(['proyector', 'pizarra_inteligente']),
                'special_features' => 'Aula climatizada con iluminación natural',
            ],
            [
                'name' => 'Laboratorio de Computación',
                'code' => 'LC1',
                'capacity' => 25,
                'type' => 'laboratorio',
                'floor' => 2,
                'resources' => json_encode(['computadoras', 'proyector', 'audio']),
                'special_features' => 'Equipos de última generación con software especializado',
            ],
            [
                'name' => 'Auditorio Principal',
                'code' => 'AP1',
                'capacity' => 200,
                'type' => 'auditorio',
                'floor' => 1,
                'resources' => json_encode(['proyector', 'audio', 'pizarra_inteligente']),
                'special_features' => 'Sistema de sonido surround y asientos ergonómicos',
            ],
            [
                'name' => 'Sala de Reuniones A',
                'code' => 'SRA',
                'capacity' => 15,
                'type' => 'sala_reuniones',
                'floor' => 3,
                'resources' => json_encode(['proyector', 'pizarra_inteligente']),
                'special_features' => 'Mesa de reuniones ejecutiva',
            ],
            [
                'name' => 'Taller de Mecánica',
                'code' => 'TM1',
                'capacity' => 20,
                'type' => 'taller',
                'floor' => 1,
                'resources' => json_encode(['herramientas', 'equipos especializados']),
                'special_features' => 'Equipos de seguridad y ventilación industrial',
            ],
        ];

        foreach ($classrooms as $index => $classroom) {
            $classroom['building_id'] = $buildings->random()->id;
            Classroom::create($classroom);
        }
    }
}