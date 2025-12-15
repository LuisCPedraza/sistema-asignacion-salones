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
        $buildings = Building::all()->keyBy('code');

        $classrooms = [
            // Bolivar - Aulas regulares
            ['building_code' => 'BOL', 'name' => '3010', 'code' => 'BOL-3010', 'capacity' => 40, 'floor' => 3, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BOL', 'name' => '1062', 'code' => 'BOL-1062', 'capacity' => 45, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            ['building_code' => 'BOL', 'name' => '2000', 'code' => 'BOL-2000', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BOL', 'name' => 'Sotano 2', 'code' => 'BOL-SOT2', 'capacity' => 40, 'floor' => 0, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'iluminacion_artificial'])],
            ['building_code' => 'BOL', 'name' => 'Sotano 1', 'code' => 'BOL-SOT1', 'capacity' => 40, 'floor' => 0, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'iluminacion_artificial'])],
            ['building_code' => 'BOL', 'name' => '1064', 'code' => 'BOL-1064', 'capacity' => 45, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            ['building_code' => 'BOL', 'name' => '2001', 'code' => 'BOL-2001', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BOL', 'name' => '3011', 'code' => 'BOL-3011', 'capacity' => 40, 'floor' => 3, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BOL', 'name' => '3012', 'code' => 'BOL-3012', 'capacity' => 40, 'floor' => 3, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BOL', 'name' => '3000', 'code' => 'BOL-3000', 'capacity' => 40, 'floor' => 3, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            // Bolivar - Laboratorios de Cómputo (Salas)
            ['building_code' => 'BOL', 'name' => '2006-Sala 1', 'code' => 'BOL-2006-S1', 'capacity' => 35, 'floor' => 2, 'type' => 'laboratorio', 'resources' => json_encode(['computadoras', 'proyector', 'pizarra_digital', 'internet', 'software_especializado', 'aire_acondicionado']), 'special_features' => 'Laboratorio de cómputo con 35 estaciones de trabajo'],
            ['building_code' => 'BOL', 'name' => '2008-Sala 2', 'code' => 'BOL-2008-S2', 'capacity' => 35, 'floor' => 2, 'type' => 'laboratorio', 'resources' => json_encode(['computadoras', 'proyector', 'pizarra_digital', 'internet', 'software_especializado', 'aire_acondicionado']), 'special_features' => 'Laboratorio de cómputo con 35 estaciones de trabajo'],
            // Bolivar - Auditorio para eventos
            ['building_code' => 'BOL', 'name' => 'Auditorio', 'code' => 'BOL-AUD', 'capacity' => 400, 'floor' => 1, 'type' => 'auditorio', 'resources' => json_encode(['proyector', 'pantalla_gigante', 'sistema_audio_profesional', 'microfonos', 'iluminacion_escenica', 'aire_acondicionado', 'butacas_ergonomicas']), 'special_features' => 'Auditorio para eventos institucionales, conferencias y presentaciones masivas'],

            // Balsas - Aulas regulares
            ['building_code' => 'BAL', 'name' => '105', 'code' => 'BAL-105', 'capacity' => 40, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            ['building_code' => 'BAL', 'name' => '201', 'code' => 'BAL-201', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BAL', 'name' => '205', 'code' => 'BAL-205', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BAL', 'name' => '103', 'code' => 'BAL-103', 'capacity' => 40, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            ['building_code' => 'BAL', 'name' => '203', 'code' => 'BAL-203', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BAL', 'name' => '102', 'code' => 'BAL-102', 'capacity' => 40, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            ['building_code' => 'BAL', 'name' => '204', 'code' => 'BAL-204', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BAL', 'name' => '202', 'code' => 'BAL-202', 'capacity' => 40, 'floor' => 2, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'aire_acondicionado'])],
            ['building_code' => 'BAL', 'name' => '101', 'code' => 'BAL-101', 'capacity' => 40, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            ['building_code' => 'BAL', 'name' => '104', 'code' => 'BAL-104', 'capacity' => 40, 'floor' => 1, 'type' => 'aula', 'resources' => json_encode(['proyector', 'pizarra', 'ventiladores'])],
            // Balsas - Laboratorios de Cómputo (Salas)
            ['building_code' => 'BAL', 'name' => '213-sala2', 'code' => 'BAL-213-S2', 'capacity' => 45, 'floor' => 2, 'type' => 'laboratorio', 'resources' => json_encode(['computadoras', 'proyector', 'pizarra_digital', 'internet', 'software_especializado', 'aire_acondicionado']), 'special_features' => 'Laboratorio de cómputo con 45 estaciones de trabajo'],
            ['building_code' => 'BAL', 'name' => '214-sala2', 'code' => 'BAL-214-S2', 'capacity' => 45, 'floor' => 2, 'type' => 'laboratorio', 'resources' => json_encode(['computadoras', 'proyector', 'pizarra_digital', 'internet', 'software_especializado', 'aire_acondicionado']), 'special_features' => 'Laboratorio de cómputo con 45 estaciones de trabajo'],
            ['building_code' => 'BAL', 'name' => '313-Sala 4', 'code' => 'BAL-313-S4', 'capacity' => 45, 'floor' => 3, 'type' => 'laboratorio', 'resources' => json_encode(['computadoras', 'proyector', 'pizarra_digital', 'internet', 'software_especializado', 'aire_acondicionado']), 'special_features' => 'Laboratorio de cómputo con 45 estaciones de trabajo'],
            ['building_code' => 'BAL', 'name' => '314-Sala 3', 'code' => 'BAL-314-S3', 'capacity' => 45, 'floor' => 3, 'type' => 'laboratorio', 'resources' => json_encode(['computadoras', 'proyector', 'pizarra_digital', 'internet', 'software_especializado', 'aire_acondicionado']), 'special_features' => 'Laboratorio de cómputo con 45 estaciones de trabajo'],
            // Balsas - Zona Deportiva (canchas múltiples)
            ['building_code' => 'BAL', 'name' => 'Zona Deport', 'code' => 'BAL-ZONA-DEP', 'capacity' => 45, 'floor' => 1, 'type' => 'cancha_deportiva', 'resources' => json_encode(['cancha_futbol', 'cancha_basquetbol', 'cancha_voleibol', 'gradas', 'iluminacion_exterior']), 'special_features' => 'Zona deportiva con canchas múltiples para diferentes deportes'],
        ];

        foreach ($classrooms as $classroom) {
            $building = $buildings->get($classroom['building_code']);
            Classroom::firstOrCreate(
                ['code' => $classroom['code']],
                [
                    'name' => $classroom['name'],
                    'capacity' => $classroom['capacity'],
                    'type' => $classroom['type'] ?? 'aula',
                    'floor' => $classroom['floor'] ?? 1,
                    'building_id' => $building?->id,
                    'resources' => $classroom['resources'] ?? null,
                    'location' => $classroom['location'] ?? null,
                    'special_features' => $classroom['special_features'] ?? null,
                    'is_active' => true,
                ]
            );
        }
    }
}