<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;

class CareerSpecificMallaHorariaSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🚀 Seeder Específico por Carrera - Malla Horaria\n";
        echo "=" . str_repeat("=", 50) . "\n";

        // 1. LIMPIAR DATOS VIEJOS
        echo "\n🗑️  Limpiando datos antiguos...\n";
        Assignment::query()->delete();
        StudentGroup::query()->delete();
        Subject::query()->delete();
        Career::query()->delete();
        Semester::query()->delete();

        // 2. OBTENER PROFESORES Y SALONES
        $teachers = Teacher::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->get();
        $timeSlots = TimeSlot::all();

        if ($teachers->count() < 10 || $classrooms->count() < 3) {
            echo "❌ No hay suficientes profesores o salones.\n";
            return;
        }

        echo "✓ {$teachers->count()} profesores\n";
        echo "✓ {$classrooms->count()} salones\n";
        echo "✓ {$timeSlots->count()} bloques horarios\n";

        // 3. CREAR CARRERA 1: TECNOLOGÍA EN DESARROLLO DE SOFTWARE
        echo "\n📚 CARRERA 1: Tecnología en Desarrollo de Software\n";
        $career1 = Career::create([
            'name' => 'Tecnología en Desarrollo de Software',
            'description' => 'Especialización en desarrollo de aplicaciones y software',
            'duration_semesters' => 6,
            'is_active' => true,
        ]);

        // Crear 6 semestres
        for ($i = 1; $i <= 6; $i++) {
            Semester::create([
                'career_id' => $career1->id,
                'number' => $i,
                'description' => "Semestre {$i}",
                'is_active' => true,
            ]);
        }

        // ASIGNATURAS EXCLUSIVAS PARA TDS
        $subjectsTDS = [
            ['name' => 'Introducción a la Programación', 'code' => 'PROG101', 'specialty' => 'Programación'],
            ['name' => 'Programación Orientada a Objetos', 'code' => 'PROG201', 'specialty' => 'Programación'],
            ['name' => 'Desarrollo Web Frontend', 'code' => 'WEB101', 'specialty' => 'Programación'],
            ['name' => 'Desarrollo Web Backend', 'code' => 'WEB201', 'specialty' => 'Programación'],
            ['name' => 'Fundamentos de Bases de Datos', 'code' => 'BD101', 'specialty' => 'Bases de Datos'],
            ['name' => 'Diseño de Bases de Datos', 'code' => 'BD201', 'specialty' => 'Bases de Datos'],
            ['name' => 'Redes de Computadores', 'code' => 'RED101', 'specialty' => 'Redes'],
            ['name' => 'Seguridad Informática', 'code' => 'SEC101', 'specialty' => 'Seguridad'],
        ];

        echo "\n   📖 Creando 8 asignaturas exclusivas para TDS...\n";
        $subjectsCollection1 = collect();
        foreach ($subjectsTDS as $subject) {
            $subjectsCollection1->push(Subject::create([
                'code' => $subject['code'],
                'name' => $subject['name'],
                'specialty' => $subject['specialty'],
                'credit_hours' => 3,
                'lecture_hours' => 2,
                'lab_hours' => 1,
                'semester_level' => 1,
                'is_active' => true,
            ]));
        }

        // Vincular asignaturas a semestres de TDS
        foreach ($career1->semesters as $semester) {
            $subjectsToAssign = $subjectsCollection1->shuffle()->take(4);
            foreach ($subjectsToAssign as $subject) {
                \App\Models\CourseSchedule::create([
                    'subject_id' => $subject->id,
                    'semester_id' => $semester->id,
                    'position_in_semester' => rand(1, 3),
                    'required_teachers' => rand(2, 3),
                ]);
            }
        }
        echo "   ✓ 24 asignaturas vinculadas (4 × 6 semestres)\n";

        // 4. CREAR CARRERA 2: ADMINISTRACIÓN DE EMPRESAS
        echo "\n📚 CARRERA 2: Administración de Empresas\n";
        $career2 = Career::create([
            'name' => 'Administración de Empresas',
            'description' => 'Especialización en gestión y administración empresarial',
            'duration_semesters' => 6,
            'is_active' => true,
        ]);

        // Crear 6 semestres
        for ($i = 1; $i <= 6; $i++) {
            Semester::create([
                'career_id' => $career2->id,
                'number' => $i,
                'description' => "Semestre {$i}",
                'is_active' => true,
            ]);
        }

        // ASIGNATURAS EXCLUSIVAS PARA ADMIN
        $subjectsADMIN = [
            ['name' => 'Contabilidad I', 'code' => 'CONT101', 'specialty' => 'Contabilidad'],
            ['name' => 'Contabilidad II', 'code' => 'CONT201', 'specialty' => 'Contabilidad'],
            ['name' => 'Finanzas Empresariales', 'code' => 'FIN101', 'specialty' => 'Finanzas'],
            ['name' => 'Gestión Financiera', 'code' => 'FIN201', 'specialty' => 'Finanzas'],
            ['name' => 'Marketing Estratégico', 'code' => 'MKT101', 'specialty' => 'Marketing'],
            ['name' => 'Gestión de Recursos Humanos', 'code' => 'RH101', 'specialty' => 'RRHH'],
            ['name' => 'Derecho Empresarial', 'code' => 'DER101', 'specialty' => 'Derecho'],
            ['name' => 'Estadística Aplicada', 'code' => 'EST101', 'specialty' => 'Estadística'],
        ];

        echo "\n   📖 Creando 8 asignaturas exclusivas para Admin...\n";
        $subjectsCollection2 = collect();
        foreach ($subjectsADMIN as $subject) {
            $subjectsCollection2->push(Subject::create([
                'code' => $subject['code'],
                'name' => $subject['name'],
                'specialty' => $subject['specialty'],
                'credit_hours' => 3,
                'lecture_hours' => 2,
                'lab_hours' => 1,
                'semester_level' => 1,
                'is_active' => true,
            ]));
        }

        // Vincular asignaturas a semestres de Admin
        foreach ($career2->semesters as $semester) {
            $subjectsToAssign = $subjectsCollection2->shuffle()->take(4);
            foreach ($subjectsToAssign as $subject) {
                \App\Models\CourseSchedule::create([
                    'subject_id' => $subject->id,
                    'semester_id' => $semester->id,
                    'position_in_semester' => rand(1, 3),
                    'required_teachers' => rand(2, 3),
                ]);
            }
        }
        echo "   ✓ 24 asignaturas vinculadas (4 × 6 semestres)\n";

        // 5. GENERAR ASIGNACIONES POR CARRERA
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $totalAssignments = 0;

        // CARRERA 1
        echo "\n📅 Generando asignaciones para TDS...\n";
        $totalAssignments += $this->generateAssignmentsForCareer(
            $career1,
            $subjectsCollection1,
            $teachers,
            $classrooms,
            $timeSlots,
            $days
        );

        // CARRERA 2
        echo "\n📅 Generando asignaciones para Admin...\n";
        $totalAssignments += $this->generateAssignmentsForCareer(
            $career2,
            $subjectsCollection2,
            $teachers,
            $classrooms,
            $timeSlots,
            $days
        );

        echo "\n";
        echo "✅ Proceso completado:\n";
        echo "   • Carreras: 2 (con asignaturas exclusivas)\n";
        echo "   • Semestres: 12\n";
        echo "   • Grupos: 24\n";
        echo "   • Asignaciones: {$totalAssignments}\n";
        echo "\n";
    }

    private function generateAssignmentsForCareer($career, $subjects, $teachers, $classrooms, $timeSlots, $days)
    {
        $totalAssignments = 0;

        foreach ($career->semesters as $semester) {
            echo "   📖 Semestre {$semester->number}\n";

            // Obtener asignaturas para este semestre
            $semesterSubjects = $semester->courseSchedules()
                ->with('subject')
                ->get()
                ->pluck('subject')
                ->unique('id');

            if ($semesterSubjects->isEmpty()) {
                continue;
            }

            // CREAR GRUPO A (DIURNO) Y GRUPO B (NOCTURNO)
            foreach (['A', 'B'] as $groupLetter) {
                $scheduleType = $groupLetter === 'A' ? 'day' : 'night';
                $groupName = "Grupo {$groupLetter}";

                $group = StudentGroup::create([
                    'name' => $groupName,
                    'level' => "S{$semester->number}",
                    'semester_id' => $semester->id,
                    'group_type' => $groupLetter,
                    'schedule_type' => $scheduleType,
                    'student_count' => rand(25, 35),
                    'number_of_students' => rand(25, 35),
                    'is_active' => true,
                ]);

                // Filtrar bloques horarios
                $groupTimeSlots = $timeSlots->where('schedule_type', $scheduleType);

                // PARA CADA ASIGNATURA
                foreach ($semesterSubjects as $subject) {
                    // Seleccionar profesores compatibles
                    $compatibleTeachers = $teachers->filter(function ($teacher) use ($subject) {
                        $specialties = json_decode($teacher->specialties ?? '[]', true);
                        return in_array($subject->specialty, $specialties);
                    })->shuffle();

                    if ($compatibleTeachers->isEmpty()) {
                        continue;
                    }

                    // Tomar 2-3 profesores
                    $selectedTeachers = $compatibleTeachers->take(rand(2, 3));

                    // PARA CADA PROFESOR: 12-16 CLASES POR SEMANA
                    foreach ($selectedTeachers as $teacher) {
                        $classesPerWeek = rand(12, 16); // Mucho más realista

                        for ($i = 0; $i < $classesPerWeek; $i++) {
                            $day = $days[array_rand($days)];
                            $timeSlot = $groupTimeSlots->random();
                            $classroom = $classrooms->random();

                            Assignment::create([
                                'student_group_id' => $group->id,
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher->id,
                                'classroom_id' => $classroom->id,
                                'day' => $day,
                                'start_time' => $timeSlot->start_time,
                                'end_time' => $timeSlot->end_time,
                                'score' => rand(75, 100) / 100,
                            ]);

                            $totalAssignments++;
                        }
                    }
                }
            }
        }

        return $totalAssignments;
    }
}
