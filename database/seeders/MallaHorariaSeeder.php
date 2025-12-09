<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\TimeSlot;

class MallaHorariaSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🎯 Creando Malla Horaria de Prueba...');

        // Limpiar asignaciones previas
        Assignment::query()->delete();

        // Crear carrera principal: Tecnología en Desarrollo de Software
        $carrera = StudentGroup::firstOrCreate(
            ['name' => 'Tecnología en Desarrollo de Software'],
            [
                'level' => 'profesional',
                'student_count' => 50,
                'special_features' => 'Programa intensivo',
                'is_active' => true,
            ]
        );

        // Crear profesores
        $profesores = [
            ['Giraldo', 'Salazar', 'Esteban Elías'],
            ['Medina', 'Murín', 'Alejandro'],
            ['Lino', 'Alexander', ''],
            ['Tamayo', 'Hernández', 'Wilson'],
            ['Osorio', 'Gustavo', 'Adolfo'],
            ['Galeano', 'Garibello', 'Juan Carlos'],
            ['Serna', 'Imbachi', 'Tatiana'],
            ['Caicedo', 'Balanta', 'Carlos Héctor'],
            ['Riaño', 'Barón', 'Yazmin Helena'],
            ['Villegas', 'Mondragón', 'Francisco Javier'],
        ];

        $profesoresObj = [];
        foreach ($profesores as $profesor) {
            $prof = Teacher::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $profesor[0] . $profesor[1])) . '@sena.edu'],
                [
                    'first_name' => $profesor[0],
                    'last_name' => $profesor[1],
                    'specialty' => 'Tecnología',
                    'is_active' => true,
                ]
            );
            $profesoresObj[] = $prof;
        }

        // Crear salones
        $salones = [
            ['2008-Sala 1', 'Bolívar', '2008', 30],
            ['1062', 'Bolívar', '1062', 30],
            ['3010', 'Balsas', '3010', 25],
            ['202', 'Balsas', '202', 25],
            ['201', 'Balsas', '201', 25],
            ['102', 'Balsas', '102', 25],
            ['2000', 'Bolívar', '2000', 35],
            ['1004', 'Bolívar', '1004', 30],
            ['1002', 'Balsas', '1002', 25],
            ['205', 'Balsas', '205', 25],
            ['314-Sala 3', 'Balsas', '314-3', 20],
            ['2010-Sala 3', 'Bolívar', '2010-3', 20],
            ['2006-Sala 1', 'Bolívar', '2006-1', 25],
            ['2008-Sala 2', 'Balsas', '2008-2', 25],
            ['213-Sala 2', 'Balsas', '213-2', 20],
        ];

        $salonesObj = [];
        foreach ($salones as $salon) {
            $sal = Classroom::firstOrCreate(
                ['name' => $salon[0]],
                [
                    'code' => $salon[2],
                    'location' => $salon[1],
                    'capacity' => $salon[3],
                    'type' => 'aula',
                    'floor' => 1,
                    'is_active' => true,
                ]
            );
            $salonesObj[] = $sal;
        }

        // Definir materias por semestre (como en la imagen)
        $materias = [
            1 => [ // Semestre 1
                [
                    'name' => 'Fundamentos de Programación Imperativa',
                    'code' => '750012C',
                    'credits' => 3,
                    'days' => ['wednesday', 'friday'],
                    'times' => [[1, 2], [1, 2]], // Bloques
                    'professors' => [0, 3],
                    'classrooms' => [0, 2],
                ],
                [
                    'name' => 'Matemática Básica',
                    'code' => '111023C',
                    'credits' => 3,
                    'days' => ['wednesday', 'thursday'],
                    'times' => [[2, 3], [1, 2]],
                    'professors' => [1, 0],
                    'classrooms' => [1, 3],
                ],
                [
                    'name' => 'Inserción a la Vida Universitaria',
                    'code' => '000000X',
                    'credits' => 0,
                    'days' => ['monday'],
                    'times' => [[1]],
                    'professors' => [2],
                    'classrooms' => [0],
                ],
            ],
            2 => [ // Semestre 2
                [
                    'name' => 'Desarrollo de Software II',
                    'code' => '750021C',
                    'credits' => 3,
                    'days' => ['friday', 'saturday'],
                    'times' => [[1, 2], [1]],
                    'professors' => [4, 1],
                    'classrooms' => [6, 3],
                ],
                [
                    'name' => 'Cálculo Monovariable',
                    'code' => '111021C',
                    'credits' => 3,
                    'days' => ['tuesday', 'thursday'],
                    'times' => [[2, 3], [2]],
                    'professors' => [0, 3],
                    'classrooms' => [7, 5],
                ],
                [
                    'name' => 'Álgebra Lineal',
                    'code' => '111038C',
                    'credits' => 3,
                    'days' => ['monday', 'wednesday'],
                    'times' => [[3], [2]],
                    'professors' => [2, 3],
                    'classrooms' => [4, 2],
                ],
            ],
            3 => [ // Semestre 3
                [
                    'name' => 'Matemáticas Discretas',
                    'code' => '750006C',
                    'credits' => 4,
                    'days' => ['monday', 'wednesday'],
                    'times' => [[3, 4], [3, 4]],
                    'professors' => [5, 6],
                    'classrooms' => [8, 9],
                ],
                [
                    'name' => 'Inglés con Fines Generales y Académicos I',
                    'code' => '204025C',
                    'credits' => 2,
                    'days' => ['tuesday', 'thursday'],
                    'times' => [[3, 4], [3, 4]],
                    'professors' => [7, 3],
                    'classrooms' => [10, 2],
                ],
                [
                    'name' => 'Sistemas Operativos',
                    'code' => '750001C',
                    'credits' => 4,
                    'days' => ['thursday', 'friday'],
                    'times' => [[2, 3], [1]],
                    'professors' => [1, 8],
                    'classrooms' => [11, 6],
                ],
                [
                    'name' => 'Bases de Datos',
                    'code' => '750006C',
                    'credits' => 4,
                    'days' => ['monday', 'thursday'],
                    'times' => [[2], [4]],
                    'professors' => [4, 9],
                    'classrooms' => [1, 8],
                ],
            ],
        ];

        // Bloques horarios para asignaciones
        $blockMap = [
            1 => ['start' => '08:00:00', 'end' => '10:00:00'],
            2 => ['start' => '10:00:00', 'end' => '12:00:00'],
            3 => ['start' => '14:00:00', 'end' => '16:00:00'],
            4 => ['start' => '16:00:00', 'end' => '18:00:00'],
            5 => ['start' => '18:00:00', 'end' => '20:00:00'],
            6 => ['start' => '20:00:00', 'end' => '22:00:00'],
        ];

        $dayOrder = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];

        // Crear asignaciones
        foreach ($materias as $semester => $materialList) {
            foreach ($materialList as $materia) {
                foreach ($materia['days'] as $idx => $day) {
                    $block = $materia['times'][$idx];
                    if (is_array($block)) {
                        $block = $block[0]; // Tomar el primer bloque si es un array
                    }

                    $startTime = $blockMap[$block]['start'];
                    $endTime = $blockMap[$block]['end'];

                    Assignment::create([
                        'student_group_id' => $carrera->id,
                        'teacher_id' => $profesoresObj[$materia['professors'][$idx]]->id,
                        'classroom_id' => $salonesObj[$materia['classrooms'][$idx]]->id,
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'score' => rand(70, 100) / 100,
                        'is_confirmed' => true,
                        'notes' => $materia['name'],
                    ]);
                }
            }
        }

        $this->command->info('✅ Malla horaria creada exitosamente!');
        $this->command->info('   - Carrera: Tecnología en Desarrollo de Software');
        $this->command->info('   - Asignaciones: ' . Assignment::count());
        $this->command->info('   - Profesores: ' . Teacher::count());
        $this->command->info('   - Salones: ' . Classroom::count());
    }
}
