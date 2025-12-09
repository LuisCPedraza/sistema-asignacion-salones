<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\CourseSchedule;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImprovedAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds - Crea estructura completa con múltiples asignaciones
     */
    public function run(): void
    {
        $this->command->info('📚 Creando estructura mejorada de asignaciones...');

        // 1. Crear materias según especialidades
        $subjects = $this->createSubjects();
        $this->command->info("✓ {$subjects->count()} materias creadas");

        // 2. Obtener carreras creadas previamente
        $careers = Career::where('is_active', true)->get();
        $this->command->info("✓ {$careers->count()} carreras encontradas");

        // 3. Vincular materias a semestres de carreras
        $this->linkSubjectsToSemesters($subjects, $careers);
        $this->command->info("✓ Materias vinculadas a semestres");

        // 4. Generar múltiples asignaciones por grupo
        $totalAssignments = $this->generateAssignmentsForAllGroups($subjects, $careers);
        $this->command->info("✓ {$totalAssignments} asignaciones generadas");
    }

    private function createSubjects()
    {
        // Materias típicas de una carrera de software
        $subjectsData = [
            // Semestre 1
            ['code' => 'PROG101', 'name' => 'Introducción a la Programación', 'specialty' => 'Programación', 'semester' => 1],
            ['code' => 'MATH101', 'name' => 'Matemáticas Discretas', 'specialty' => 'Matemáticas', 'semester' => 1],
            
            // Semestre 2
            ['code' => 'PROG102', 'name' => 'Programación Orientada a Objetos', 'specialty' => 'Programación', 'semester' => 2],
            ['code' => 'DB101', 'name' => 'Fundamentos de Bases de Datos', 'specialty' => 'Bases de Datos', 'semester' => 2],
            
            // Semestre 3
            ['code' => 'WEB101', 'name' => 'Desarrollo Web Frontend', 'specialty' => 'Programación', 'semester' => 3],
            ['code' => 'DB102', 'name' => 'Diseño de Bases de Datos', 'specialty' => 'Bases de Datos', 'semester' => 3],
            
            // Semestre 4
            ['code' => 'WEB102', 'name' => 'Desarrollo Web Backend', 'specialty' => 'Programación', 'semester' => 4],
            ['code' => 'NET101', 'name' => 'Redes de Computadores', 'specialty' => 'Redes', 'semester' => 4],
            
            // Semestre 5
            ['code' => 'SE101', 'name' => 'Ingeniería de Software', 'specialty' => 'Ingeniería de Software', 'semester' => 5],
            ['code' => 'CLOUD101', 'name' => 'Cloud Computing', 'specialty' => 'Programación', 'semester' => 5],
            
            // Semestre 6
            ['code' => 'SE102', 'name' => 'Metodologías Ágiles', 'specialty' => 'Ingeniería de Software', 'semester' => 6],
            ['code' => 'SECURITY101', 'name' => 'Seguridad Informática', 'specialty' => 'Seguridad', 'semester' => 6],
        ];

        $subjects = collect();
        foreach ($subjectsData as $data) {
            $subject = Subject::firstOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'specialty' => $data['specialty'],
                    'credit_hours' => 3,
                    'lecture_hours' => 3,
                    'lab_hours' => 0,
                    'semester_level' => $data['semester'],
                    'is_active' => true,
                ]
            );
            $subjects->push($subject);
        }

        return $subjects;
    }

    private function linkSubjectsToSemesters($subjects, $careers)
    {
        foreach ($careers as $career) {
            for ($semNum = 1; $semNum <= 7; $semNum++) {
                $semester = $career->semesters()->where('number', $semNum)->first();
                if (!$semester) continue;

                // Vincular materias del semestre a esta carrera
                $semesterSubjects = $subjects->filter(fn($s) => $s->semester_level == $semNum)->take(2);
                
                foreach ($semesterSubjects as $index => $subject) {
                    CourseSchedule::firstOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'semester_id' => $semester->id,
                        ],
                        [
                            'position_in_semester' => $index + 1,
                            'required_teachers' => 1,
                        ]
                    );
                }
            }
        }
    }

    private function generateAssignmentsForAllGroups($subjects, $careers)
    {
        $totalAssignments = 0;
        $teachers = Teacher::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->get();

        if ($teachers->isEmpty() || $classrooms->isEmpty()) {
            $this->command->warn('⚠️  No hay profesores o salones activos');
            return 0;
        }

        // Días de la semana
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        // Bloques de tiempo (2 horas cada uno)
        $timeBlocks = [
            ['start' => '08:00:00', 'end' => '10:00:00'],
            ['start' => '10:00:00', 'end' => '12:00:00'],
            ['start' => '14:00:00', 'end' => '16:00:00'],
            ['start' => '16:00:00', 'end' => '18:00:00'],
        ];

        // Para cada carrera
        foreach ($careers as $career) {
            // Para cada semestre de la carrera
            for ($semNum = 1; $semNum <= 7; $semNum++) {
                $semester = $career->semesters()->where('number', $semNum)->first();
                if (!$semester) continue;

                // Obtener materias del semestre
                $courseSchedules = $semester->courseSchedules()->with('subject')->get();
                
                // Para cada grupo del semestre
                foreach ($semester->studentGroups as $group) {
                    // Para cada materia del semestre
                    foreach ($courseSchedules as $courseSchedule) {
                        $subject = $courseSchedule->subject;

                        // Seleccionar profesores con especialidad compatible
                        $compatibleTeachers = $teachers->filter(function ($teacher) use ($subject) {
                            $specialties = json_decode($teacher->specialties ?? '[]');
                            return in_array($subject->specialty, $specialties) || 
                                   $teacher->specialty === $subject->specialty;
                        })->take(2); // Máximo 2 profesores por materia

                        if ($compatibleTeachers->isEmpty()) {
                            // Si no hay profesores con especialidad, asignar aleatoriamente
                            $compatibleTeachers = collect([$teachers->random()]);
                        }

                        // Para cada profesor de la materia
                        foreach ($compatibleTeachers as $teacher) {
                            // Crear 2-3 asignaciones (clases por semana)
                            $numClasses = rand(2, 3);
                            $assignedDays = [];

                            for ($i = 0; $i < $numClasses; $i++) {
                                // Seleccionar un día diferente
                                $day = $days[array_rand($days)];
                                if (in_array($day, $assignedDays)) {
                                    $day = $days[array_rand($days)];
                                }
                                $assignedDays[] = $day;

                                // Crear asignación
                                $timeBlock = $timeBlocks[array_rand($timeBlocks)];

                                Assignment::create([
                                    'day' => $day,
                                    'start_time' => $timeBlock['start'],
                                    'end_time' => $timeBlock['end'],
                                    'student_group_id' => $group->id,
                                    'teacher_id' => $teacher->id,
                                    'classroom_id' => $classrooms->random()->id,
                                    'subject_id' => $subject->id,
                                ]);

                                $totalAssignments++;
                            }
                        }
                    }
                }
            }
        }

        return $totalAssignments;
    }
}
