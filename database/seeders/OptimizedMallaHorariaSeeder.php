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

class OptimizedMallaHorariaSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🚀 Reiniciando Malla Horaria Optimizada...\n";

        // 1. LIMPIAR DATOS VIEJOS
        echo "🗑️  Limpiando datos antiguos...\n";
        Assignment::query()->delete();
        StudentGroup::query()->delete();
        Career::query()->delete();
        Semester::query()->delete();

        // 2. CREAR 2 CARRERAS
        echo "📚 Creando 2 carreras...\n";
        $careers = [
            Career::create([
                'name' => 'Tecnología en Desarrollo de Software',
                'description' => 'Formación en desarrollo de software y aplicaciones',
                'duration_semesters' => 6,
                'is_active' => true,
            ]),
            Career::create([
                'name' => 'Administración de Empresas',
                'description' => 'Formación en administración y gestión empresarial',
                'duration_semesters' => 6,
                'is_active' => true,
            ]),
        ];

        // 3. CREAR 6 SEMESTRES POR CARRERA
        echo "📖 Creando 6 semestres por carrera...\n";
        foreach ($careers as $career) {
            for ($i = 1; $i <= 6; $i++) {
                Semester::create([
                    'career_id' => $career->id,
                    'number' => $i,
                    'description' => "Semestre {$i}",
                    'is_active' => true,
                ]);
            }
        }

        // 4. OBTENER DATOS NECESARIOS
        echo "\n📊 Cargando datos...\n";
        $teachers = Teacher::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $timeSlots = TimeSlot::all();

        if ($teachers->count() < 5 || $classrooms->count() < 3 || $subjects->count() < 6) {
            echo "❌ No hay suficientes profesores, salones o asignaturas.\n";
            return;
        }

        echo "✓ {$teachers->count()} profesores\n";
        echo "✓ {$classrooms->count()} salones\n";
        echo "✓ {$subjects->count()} asignaturas\n";

        // 3B. VINCULAR ASIGNATURAS A SEMESTRES (DIFERENTES POR CARRERA)
        echo "\n🔗 Vinculando asignaturas a semestres...\n";
        $semesterCount = 0;
        
        // Para Tecnología en Desarrollo de Software (especialidades: Programación, Bases de Datos, Redes, etc)
        $subjects1 = $subjects->filter(function ($s) {
            return in_array($s->specialty, ['Programación', 'Bases de Datos', 'Redes', 'Ingeniería de Software']);
        });

        // Para Administración de Empresas (especialidades: Matemáticas, Seguridad, resto)
        $subjects2 = $subjects->filter(function ($s) {
            return in_array($s->specialty, ['Matemáticas', 'Seguridad']);
        });

        $allCareers = Career::all();
        $career1 = $allCareers->first(); // TDS
        $career2 = $allCareers->skip(1)->first(); // ADE

        // Carrera 1: 4 asignaturas por semestre
        foreach ($career1->semesters as $semester) {
            $subjectsToAssign = $subjects1->shuffle()->take(4);
            foreach ($subjectsToAssign as $subject) {
                \App\Models\CourseSchedule::firstOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,
                    ],
                    [
                        'position_in_semester' => rand(1, 3),
                        'required_teachers' => rand(2, 3),
                    ]
                );
                $semesterCount++;
            }
        }

        // Carrera 2: 4 asignaturas por semestre (diferentes a carrera 1)
        foreach ($career2->semesters as $semester) {
            $subjectsToAssign = $subjects2->shuffle()->take(4);
            foreach ($subjectsToAssign as $subject) {
                \App\Models\CourseSchedule::firstOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,
                    ],
                    [
                        'position_in_semester' => rand(1, 3),
                        'required_teachers' => rand(2, 3),
                    ]
                );
                $semesterCount++;
            }
        }
        echo "✓ {$semesterCount} asignaturas vinculadas\n";

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $totalAssignments = 0;

        // 5. GENERAR GRUPOS Y ASIGNACIONES
        foreach ($careers as $career) {
            echo "\n📚 {$career->name}\n";

            foreach ($career->semesters as $semester) {
                echo "   📖 Semestre {$semester->number}\n";

                // Obtener asignaturas para este semestre
                $semesterSubjects = $semester->courseSchedules()
                    ->with('subject')
                    ->get()
                    ->pluck('subject')
                    ->unique('id');

                if ($semesterSubjects->isEmpty()) {
                    echo "      ⚠️  Sin asignaturas\n";
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

                    // Filtrar bloques horarios del grupo
                    $groupTimeSlots = $timeSlots->where('schedule_type', $scheduleType);

                    echo "      👥 {$groupName} ({$groupTimeSlots->count()} bloques horarios)\n";

                    // PARA CADA ASIGNATURA
                    foreach ($semesterSubjects as $subject) {
                        // 2-3 profesores compatibles
                        $compatibleTeachers = $teachers->filter(function ($teacher) use ($subject) {
                            $specialties = json_decode($teacher->specialties ?? '[]', true);
                            return in_array($subject->specialty, $specialties);
                        })->shuffle();

                        if ($compatibleTeachers->isEmpty()) {
                            continue;
                        }

                        // Tomar 2-3 profesores
                        $selectedTeachers = $compatibleTeachers->take(rand(2, 3));

                        // PARA CADA PROFESOR CREAR 10-14 CLASES EN DIFERENTES DÍAS/HORAS (MÚLTIPLES POR DÍA)
                        foreach ($selectedTeachers as $teacher) {
                            $classesPerWeek = rand(10, 14); // Aumentado significativamente

                            for ($i = 0; $i < $classesPerWeek; $i++) {
                                // Permitir múltiples clases por día (no necesariamente todos diferentes)
                                $day = $days[array_rand($days)];
                                
                                // Seleccionar bloque aleatorio
                                $timeSlot = $groupTimeSlots->random();

                                // Salón aleatorio
                                $classroom = $classrooms->random();

                                // Crear asignación
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
        }

        echo "\n";
        echo "✅ Proceso completado:\n";
        echo "   • Carreras: 2\n";
        echo "   • Semestres: 12 (6 × 2 carreras)\n";
        echo "   • Grupos: 24 (2 grupos × 6 semestres × 2 carreras)\n";
        echo "   • Asignaciones: {$totalAssignments}\n";
        echo "\n";
    }
}
