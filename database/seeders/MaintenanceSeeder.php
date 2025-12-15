<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Maintenance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        // If there are no classrooms, skip to avoid errors
        $classrooms = Classroom::where('is_active', true)->get();
        if ($classrooms->isEmpty()) {
            $classrooms = Classroom::limit(5)->get();
        }
        if ($classrooms->isEmpty()) {
            // No classrooms available; create minimal fake classrooms
            $classrooms = collect();
            for ($i = 1; $i <= 3; $i++) {
                $classrooms->push(Classroom::create([
                    'name' => 'Aula Demo ' . $i,
                    'code' => 'DEM-' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                    'capacity' => 40,
                    'floor' => 1,
                    'type' => 'aula',
                    'is_active' => true,
                ]));
            }
        }

        // Clean previous demo data (optional)
        // DB::table('maintenances')->truncate();

        $titles = [
            'Mantenimiento de proyector',
            'Revisión de cableado eléctrico',
            'Limpieza profunda del aula',
            'Cambio de luminarias',
            'Reparación de aire acondicionado',
            'Pintura de paredes',
            'Ajuste de mobiliario',
            'Revisión de red y puntos LAN',
        ];

        $responsibles = ['Equipo Infraestructura', 'Proveedor Externo', 'Mantenimiento Operaciones'];

        // Create a balanced set of maintenance tasks
        foreach ($classrooms as $classroom) {
            // Pendientes (2)
            Maintenance::factory()
                ->count(2)
                ->pending()
                ->state(function () use ($classroom, $titles, $responsibles) {
                    return [
                        'classroom_id' => $classroom->id,
                        'title' => $titles[array_rand($titles)],
                        'responsible' => $responsibles[array_rand($responsibles)],
                        'type' => ['preventivo', 'correctivo'][array_rand(['preventivo', 'correctivo'])],
                    ];
                })
                ->create();

            // En progreso (2)
            Maintenance::factory()
                ->count(2)
                ->inProgress()
                ->state(function () use ($classroom, $titles, $responsibles) {
                    return [
                        'classroom_id' => $classroom->id,
                        'title' => $titles[array_rand($titles)],
                        'responsible' => $responsibles[array_rand($responsibles)],
                        'type' => ['preventivo', 'correctivo'][array_rand(['preventivo', 'correctivo'])],
                    ];
                })
                ->create();

            // Completados (2)
            Maintenance::factory()
                ->count(2)
                ->completed()
                ->state(function () use ($classroom, $titles, $responsibles) {
                    return [
                        'classroom_id' => $classroom->id,
                        'title' => $titles[array_rand($titles)],
                        'responsible' => $responsibles[array_rand($responsibles)],
                        'type' => ['preventivo', 'correctivo'][array_rand(['preventivo', 'correctivo'])],
                    ];
                })
                ->create();

            // Cancelados (1)
            Maintenance::factory()
                ->count(1)
                ->cancelled()
                ->state(function () use ($classroom, $titles, $responsibles) {
                    return [
                        'classroom_id' => $classroom->id,
                        'title' => $titles[array_rand($titles)],
                        'responsible' => $responsibles[array_rand($responsibles)],
                        'type' => ['preventivo', 'correctivo'][array_rand(['preventivo', 'correctivo'])],
                    ];
                })
                ->create();
        }
    }
}
