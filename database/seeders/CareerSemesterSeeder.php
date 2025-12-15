<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareerSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear carreras con datos reales proporcionados
        $careers = [
            [
                'name' => 'Tecnología en desarrollo de Software',
                'code' => '2724',
                'description' => 'Carrera tecnológica enfocada en desarrollo de aplicaciones web, móviles y software empresarial',
                'duration_semesters' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'COMERCIO EXTERIOR',
                'code' => '3857',
                'description' => 'Carrera profesional en comercio internacional y gestión de importaciones y exportaciones',
                'duration_semesters' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'CONTADURÍA PÚBLICA',
                'code' => '3841',
                'description' => 'Carrera profesional en contabilidad, auditoría y finanzas corporativas',
                'duration_semesters' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'ADMINISTRACIÓN DE EMPRESAS',
                'code' => '3845',
                'description' => 'Carrera profesional en gestión empresarial, negocios y administración organizacional',
                'duration_semesters' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($careers as $careerData) {
            $career = Career::updateOrCreate(
                ['code' => $careerData['code']],
                $careerData
            );

            // Crear 7 semestres por carrera
            for ($semesterNum = 1; $semesterNum <= 7; $semesterNum++) {
                $semester = $career->semesters()->create([
                    'number' => $semesterNum,
                    'description' => "Semestre {$semesterNum}",
                    'is_active' => true,
                ]);

                // Crear grupos por semestre (A, B)
                $groupLetters = ['A', 'B'];
                foreach ($groupLetters as $letter) {
                    $groupName = "Grupo {$letter} - Semestre {$semesterNum}";
                    
                    $group = StudentGroup::create([
                        'name' => $groupName,
                        'semester_id' => $semester->id,
                        'level' => 'profesional',
                        'student_count' => rand(20, 40),
                        'special_features' => 'Grupo semestral',
                        'is_active' => true,
                    ]);

                    // Crear 5-6 asignaciones por grupo
                    $this->createAssignmentsForGroup($group, $semesterNum);
                }
            }
        }
    }

    private function createAssignmentsForGroup($group, $semesterNum): void
    {
        // Obtener profesores y salones disponibles
        $teachers = Teacher::all();
        $classrooms = Classroom::all();

        if ($teachers->isEmpty() || $classrooms->isEmpty()) {
            return;
        }

        // Días de la semana (lunes a sábado)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        // Bloques de tiempo
        $timeBlocks = [
            ['start' => '08:00:00', 'end' => '10:00:00'],
            ['start' => '10:00:00', 'end' => '12:00:00'],
            ['start' => '14:00:00', 'end' => '16:00:00'],
            ['start' => '16:00:00', 'end' => '18:00:00'],
        ];

        // Crear 5-6 asignaciones
        $numAssignments = rand(5, 6);
        for ($i = 0; $i < $numAssignments; $i++) {
            Assignment::create([
                'day' => $days[array_rand($days)],
                'start_time' => $timeBlocks[array_rand($timeBlocks)]['start'],
                'end_time' => $timeBlocks[array_rand($timeBlocks)]['end'],
                'student_group_id' => $group->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classrooms->random()->id,
            ]);
        }
    }
}
