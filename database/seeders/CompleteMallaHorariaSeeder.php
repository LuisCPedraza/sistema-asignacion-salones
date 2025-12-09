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

class CompleteMallaHorariaSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚀 Iniciando generación completa de Malla Horaria...\n";

        // 1. Obtener o crear carreras
        $careers = Career::where('is_active', true)->get();
        if ($careers->isEmpty()) {
            echo "❌ No hay carreras activas. Ejecuta CareerSeeder primero.\n";
            return;
        }
        echo "✓ {$careers->count()} carreras encontradas\n";

        // 2. Obtener o crear profesores con especialidades
        $teachers = Teacher::where('is_active', true)->get();
        if ($teachers->count() < 5) {
            echo "❌ Se necesitan al menos 5 profesores activos.\n";
            return;
        }
        echo "✓ {$teachers->count()} profesores disponibles\n";

        // 3. Obtener o crear salones
        $classrooms = Classroom::where('is_active', true)->get();
        if ($classrooms->count() < 3) {
            echo "❌ Se necesitan al menos 3 salones activos.\n";
            return;
        }
        echo "✓ {$classrooms->count()} salones disponibles\n";

        // 4. Crear bloques horarios si no existen
        $timeSlots = TimeSlot::all();
        if ($timeSlots->count() < 6) {
            $this->call(TimeSlotSeeder::class);
            $timeSlots = TimeSlot::all();
        }
        echo "✓ {$timeSlots->count()} bloques horarios disponibles\n";

        // 5. Obtener asignaturas
        $subjects = Subject::where('is_active', true)->get();
        if ($subjects->isEmpty()) {
            echo "❌ Se necesitan asignaturas. Ejecuta SubjectSeeder primero.\n";
            return;
        }
        echo "✓ {$subjects->count()} asignaturas disponibles\n";

        // 6. Obtener salones de aula
        $classroomsArray = $classrooms->toArray();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $totalAssignments = 0;
        $careerCount = 0;

        // 7. Para cada carrera
        foreach ($careers as $career) {
            $careerCount++;
            echo "\n📚 Procesando Carrera: {$career->name}\n";

            // Para cada semestre de la carrera
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

                // CREAR 2 GRUPOS: A (Diurno) y B (Nocturno)
                foreach (['A', 'B'] as $groupLetter) {
                    $scheduleType = $groupLetter === 'A' ? 'day' : 'night';
                    $groupName = "Grupo {$groupLetter} - S{$semester->number}";

                    // Crear o actualizar grupo
                    $group = StudentGroup::updateOrCreate(
                        [
                            'name' => $groupName,
                            'semester_id' => $semester->id,
                            'group_type' => $groupLetter,
                        ],
                        [
                            'level' => "Semestre {$semester->number}",
                            'schedule_type' => $scheduleType,
                            'student_count' => rand(25, 35),
                            'number_of_students' => rand(25, 35),
                            'is_active' => true,
                        ]
                    );

                    // Filtrar bloques horarios según tipo de grupo
                    $groupTimeSlots = $timeSlots->where('schedule_type', $scheduleType);

                    echo "      👥 {$groupName} ({$groupTimeSlots->count()} bloques horarios)\n";

                    // Para cada asignatura del semestre
                    foreach ($semesterSubjects as $subject) {
                        // Seleccionar 2-3 profesores compatibles con la especialidad
                        $compatibleTeachers = $teachers->filter(function ($teacher) use ($subject) {
                            $specialties = json_decode($teacher->specialties ?? '[]', true);
                            return in_array($subject->specialty, $specialties);
                        })->shuffle();

                        if ($compatibleTeachers->isEmpty()) {
                            continue;
                        }

                        // Tomar máximo 3 profesores
                        $selectedTeachers = $compatibleTeachers->take(rand(2, 3));

                        // Para cada profesor crear 3-5 clases por semana (más realista)
                        foreach ($selectedTeachers as $teacher) {
                            $classesPerWeek = rand(3, 5);

                            for ($i = 0; $i < $classesPerWeek; $i++) {
                                // Seleccionar un día aleatorio
                                $day = $days[array_rand($days)];

                                // Seleccionar bloque horario aleatorio del grupo
                                $timeSlot = $groupTimeSlots->random();

                                // Seleccionar salón aleatorio
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
        echo "   • Carreras procesadas: {$careerCount}\n";
        echo "   • Asignaciones creadas: {$totalAssignments}\n";
        echo "   • Total de grupos: " . StudentGroup::count() . "\n";
        echo "\n";
    }
}
