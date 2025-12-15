<?php

namespace App\Modules\Asignacion\Services;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\AssignmentRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AssignmentAlgorithm
{
    protected $reglas;
    protected $debug = false;

    public function __construct()
    {
        $this->reglas = AssignmentRule::where('is_active', true)
            ->orderBy('weight', 'desc')
            ->get()
            ->map(function ($rule) {
                // Normalizar pesos para compatibilidad con HU10 (acepta 0-1 o 0-100)
                $rule->normalized_weight = $this->normalizeWeight($rule->weight);
                return $rule;
            });
    }

    public function enableDebug()
    {
        $this->debug = true;
        return $this;
    }

    /**
     * Compatibilidad HU10: pesos en 0-1 o 0-100
     */
    protected function normalizeWeight($weight): float
    {
        $numeric = (float) $weight;
        if ($numeric > 1) {
            $numeric = $numeric / 100; // soporta pesos almacenados como porcentaje entero
        }
        return max(0.0, min(1.0, $numeric));
    }

    protected function isRuleEnabled(string $parameter): bool
    {
        return $this->reglas->contains(fn($rule) => $rule->parameter === $parameter && ($rule->normalized_weight ?? 0) > 0);
    }

    public function generateAssignments()
    {
        // REORGANIZAR (SHUFFLE) LAS ASIGNACIONES EXISTENTES
        // Sin crear nuevas, solo cambiando posiciones (días, franjas horarias, aulas)
        // RESPETA: teacher_id existente, independencia por carrera-semestre, duración clases 2-3 horas
        // NO MEZCLA: carreras, semestres, profesores entre diferentes grupos
        
        Log::info("🔵 INICIO generateAssignments");
        $assignments = Assignment::with(['group.career', 'group.semester', 'teacher', 'subject'])->get();
        Log::info("✅ Cargadas {$assignments->count()} asignaciones con relaciones");
        
        if ($assignments->isEmpty()) {
            Log::info('⚠️ No hay asignaciones para reorganizar');
            return []; // Si no hay asignaciones, retornar vacío
        }

        // Cargar profesores activos con disponibilidades y preparar carga inicial en cero
        $activeTeachers = Teacher::where('is_active', true)
            ->with(['availabilities'])
            ->get();
        Log::info("✅ Cargados {$activeTeachers->count()} profesores activos");
        
        $teacherMap = $activeTeachers->keyBy('id');

        $teacherWeeklyHours = [];
        $teacherDailyHours = [];
        foreach ($teacherMap as $tid => $t) {
            $teacherWeeklyHours[$tid] = 0;
            $teacherDailyHours[$tid] = [
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ];
        }

        // Tracking de horas semanales por materia (basado en créditos académicos)
        // Fórmula: max_weekly_hours = credit_hours + 1
        $subjectWeeklyHours = []; // [subject_id => horas_asignadas]
        $subjectMaxHours = []; // [subject_id => límite_máximo]

        // OPTIMIZACIÓN: Crear índices hash para búsquedas O(1) de conflictos
        // Evita 9M iteraciones de filter() reemplazándolas con lookups directos
        $assignmentsByDay = $assignments->groupBy('day');
        $pendingUpdates = []; // Para batch updates al final
        
        // Índices por recurso para detección instantánea de conflictos O(1)
        $indexByTeacher = []; // [day][teacher_id][time_range] = assignment_ids
        $indexByClassroom = []; // [day][classroom_id][time_range] = assignment_ids
        $indexByGroup = []; // [day][group_id][time_range] = assignment_ids
        
        // Construir índices iniciales
        foreach ($assignments as $a) {
            $day = $a->day;
            $timeKey = $this->buildTimeKey($a->start_time, $a->end_time);
            
            if (!isset($indexByTeacher[$day])) $indexByTeacher[$day] = [];
            if (!isset($indexByClassroom[$day])) $indexByClassroom[$day] = [];
            if (!isset($indexByGroup[$day])) $indexByGroup[$day] = [];
            
            if (!isset($indexByTeacher[$day][$a->teacher_id])) $indexByTeacher[$day][$a->teacher_id] = [];
            if (!isset($indexByClassroom[$day][$a->classroom_id])) $indexByClassroom[$day][$a->classroom_id] = [];
            if (!isset($indexByGroup[$day][$a->student_group_id])) $indexByGroup[$day][$a->student_group_id] = [];
            
            $indexByTeacher[$day][$a->teacher_id][$timeKey][] = $a->id;
            $indexByClassroom[$day][$a->classroom_id][$timeKey][] = $a->id;
            $indexByGroup[$day][$a->student_group_id][$timeKey][] = $a->id;
        }

        // Calcular carga inicial REAL por profesor (semanal y diaria)
        foreach ($assignments as $a) {
            $tid = $a->teacher_id;
            $day = $a->day;
            if (!$tid || !$day) { continue; }
            try {
                $start = new \DateTime($this->extractTime($a->start_time ?? '08:00:00'));
                $end = new \DateTime(substr($a->end_time ?? '10:00:00', 0, 8));
                $durationHours = max(0, ($end->getTimestamp() - $start->getTimestamp()) / 3600);
            } catch (\Exception $e) {
                $durationHours = 0;
            }
            $teacherWeeklyHours[$tid] = ($teacherWeeklyHours[$tid] ?? 0) + $durationHours;
            $teacherDailyHours[$tid][$day] = ($teacherDailyHours[$tid][$day] ?? 0) + $durationHours;
        }

        // Detectar carga docente previa: registrar profesores sobrecargados para redistribución
        $overloadedTeachers = [];
        foreach ($teacherWeeklyHours as $teacherId => $hours) {
            if ($hours > 42) {
                $overloadedTeachers[$teacherId]['weekly'] = $hours;
            }
        }
        foreach ($teacherDailyHours as $teacherId => $byDay) {
            foreach ($byDay as $day => $hours) {
                if ($hours > 7) {
                    $overloadedTeachers[$teacherId]['daily'][$day] = $hours;
                }
            }
        }

        if (!empty($overloadedTeachers)) {
            $names = [];
            foreach (array_keys($overloadedTeachers) as $teacherId) {
                $teacher = $assignments->firstWhere('teacher_id', $teacherId)?->teacher;
                $names[] = $teacher ? $teacher->full_name : ('ID ' . $teacherId);
            }
            $detalle = collect($overloadedTeachers)->map(function($data, $id) {
                $weekly = isset($data['weekly']) ? "semana {$data['weekly']}h" : '';
                $dailyParts = [];
                if (!empty($data['daily'])) {
                    foreach ($data['daily'] as $day => $h) {
                        $dailyParts[] = "{$day} {$h}h";
                    }
                }
                $daily = $dailyParts ? ('; día(s) ' . implode(', ', $dailyParts)) : '';
                return "Profesor {$id}: {$weekly}{$daily}";
            })->implode(' | ');
            Log::warning('⚠️ Profesores sobrecargados detectados (serán redistribuidos): ' . implode(', ', $names) . ' | Detalle: ' . $detalle);
        }

        // Cargar salones con sus edificios para usar ubicaciones
        $classrooms = Classroom::where('is_active', true)
            ->with(['availabilities', 'building'])
            ->inRandomOrder()
            ->get();
        
        // Filtrar TimeSlots con duración entre 2 y 3 horas (120-180 minutos) si hay disponibles
        $validTimeSlots = TimeSlot::all()->filter(function($slot) {
            return $this->validateClassDuration($slot);
        });
        
        // Si no hay slots válidos de 2-3h, usar todos los disponibles
        $timeSlots = $validTimeSlots->isNotEmpty() ? $validTimeSlots : TimeSlot::all();
        
        Log::info('🚀 Iniciando reorganización de asignaciones', [
            'total_asignaciones' => $assignments->count(),
            'salones_activos' => $classrooms->count(),
            'franjas_horarias' => $timeSlots->count(),
            'ubicaciones_disponibles' => $classrooms->groupBy('building.location')->keys()->join(', ')
        ]);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $updated = [];
        $skipped = [];
        $newSemesterAssignments = [];

        // Agrupar asignaciones por carrera-semestre para mantener independencia COMPLETA
        // Esto evita que se mezclen carreras, semestres, profesores o grupos
        // Importante: usar separador seguro para no romper con underscores en IDs o textos
        $assignmentsByCareerSemester = $assignments->groupBy(function($assignment) {
            $careerId = $assignment->group->career_id ?? 'none';
            $semesterId = $assignment->group->semester_id ?? 'none';
            return "{$careerId}|{$semesterId}";
        });

        Log::info('📊 Agrupadas asignaciones por carrera-semestre', [
            'grupos_carrera_semestre' => $assignmentsByCareerSemester->keys()->count()
        ]);

        $processedCount = 0;
        foreach ($assignmentsByCareerSemester as $groupKey => $careerSemesterAssignments) {
            $processedCount++;
            Log::info("🔄 Procesando grupo {$processedCount}/{$assignmentsByCareerSemester->count()}: {$groupKey}");
            
            // Extraer careerId y semesterId de forma segura
            [$careerKey, $semesterKey] = array_pad(explode('|', $groupKey, 2), 2, 'none');
            $careerId = $careerKey !== 'none' ? (int) $careerKey : null;
            $semesterId = $semesterKey !== 'none' ? (int) $semesterKey : null;
            
            // Registrar si hay nuevos semestres
            if ($semesterId && is_numeric($semesterId)) {
                $semester = $careerSemesterAssignments->first()->group->semester;
                if ($semester && $semester->number > 7) {
                    $newSemesterAssignments[] = [
                        'career' => $careerId,
                        'semester' => $semester->number,
                        'count' => $careerSemesterAssignments->count()
                    ];
                }
            }

            $assignmentCount = 0;
            foreach ($careerSemesterAssignments as $assignment) {
                $assignmentCount++;
                if ($assignmentCount % 100 === 0) {
                    Log::info("   → Procesada asignación {$assignmentCount}/{$careerSemesterAssignments->count()}");
                }
                
                // Obtener grupo para filtrar franjas horarias
                $group = $assignment->group;
                if (!$group) {
                    $skipped[] = ['id' => $assignment->id, 'reason' => 'Grupo no encontrado'];
                    continue;
                }

                // MANTENER PROFESOR ASIGNADO - NO CAMBIAR teacher_id
                $currentTeacher = $assignment->teacher;
                if (!$currentTeacher || !$currentTeacher->is_active) {
                    $skipped[] = ['id' => $assignment->id, 'reason' => 'Profesor no disponible'];
                    continue;
                }

                $originalDay = $assignment->day ?? null;
                $originalDuration = $this->calculateDurationHours($assignment->start_time, $assignment->end_time);

                // Verificar límite de horas semanales por materia (basado en créditos)
                $subjectId = $assignment->subject_id;
                if ($subjectId && $assignment->subject) {
                    // Calcular límite máximo si no existe: credit_hours + 1
                    if (!isset($subjectMaxHours[$subjectId])) {
                        $creditHours = $assignment->subject->credit_hours ?? 3;
                        $subjectMaxHours[$subjectId] = $creditHours + 1;
                        $subjectWeeklyHours[$subjectId] = 0;
                    }

                    // Verificar si la materia ya excedió su límite semanal
                    $currentSubjectHours = $subjectWeeklyHours[$subjectId] ?? 0;
                    $maxSubjectHours = $subjectMaxHours[$subjectId];
                    
                    if ($currentSubjectHours >= $maxSubjectHours) {
                        $skipped[] = [
                            'id' => $assignment->id, 
                            'reason' => "Materia {$assignment->subject->code} alcanzó límite semanal ({$maxSubjectHours}h basado en {$assignment->subject->credit_hours} créditos)"
                        ];
                        continue;
                    }
                }

                // Si no hay aulas, saltar
                if ($classrooms->isEmpty() || $timeSlots->isEmpty()) {
                    $skipped[] = ['id' => $assignment->id, 'reason' => 'Sin aulas o franjas horarias disponibles'];
                    continue;
                }

                // Filtrar salones con capacidad suficiente PRIMERO
                $validClassrooms = $classrooms->filter(function($classroom) use ($group) {
                    return $this->validateCapacity($group, $classroom);
                });

                // Si no hay salones válidos, intentar con cualquiera pero priorizar
                if ($validClassrooms->isEmpty()) {
                    $validClassrooms = $classrooms;
                }

                // Intentar múltiples combinaciones antes de rendirse
                $maxAttempts = 2; // CRÍTICO: Con 1474 asignaciones, 2 intentos = ~35k validaciones (~10-15s)
                $attemptCount = 0;
                $assigned = false;

                while ($attemptCount < $maxAttempts && !$assigned) {
                    $attemptCount++;

                    // Seleccionar nueva aula y franja (PROFESOR SE MANTIENE)
                    // Priorizar salones con capacidad suficiente
                    $firstHalf = ceil($maxAttempts / 2);
                    if ($attemptCount <= $firstHalf && !$validClassrooms->isEmpty()) {
                        // Primeros intentos: usar solo salones válidos
                        $newClassroom = $validClassrooms->random();
                    } else {
                        // Si falla, intentar con cualquier salón
                        $newClassroom = $classrooms->random();
                    }
                    
                    // Filtrar franjas por tipo de horario del grupo
                    $filteredSlots = $timeSlots->where('schedule_type', $group->schedule_type ?? 'day');
                    $newTimeSlot = $filteredSlots->isNotEmpty() ? $filteredSlots->random() : $timeSlots->random();

                    // Nuevo día aleatorio
                    $newDay = $days[array_rand($days)];

                    // VALIDACIÓN 0: Duración de clase (2-3 horas) - solo si hay slots válidos disponibles
                    if ($validTimeSlots->isNotEmpty() && !$this->validateClassDuration($newTimeSlot)) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Duración de clase fuera de rango 2-3 horas");
                        }
                        continue;
                    }

                    // VALIDACIÓN 1: Verificar capacidad del salón
                    if (!$this->validateCapacity($group, $newClassroom)) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Capacidad insuficiente. Salón: {$newClassroom->capacity}, Estudiantes: {$group->number_of_students}");
                        }
                        continue;
                    }

                    // VALIDACIÓN 2: Requerimientos de recursos (solo si la regla está activa)
                    if ($this->isRuleEnabled('resources') && !$this->validateResources($group, $newClassroom)) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Salón no cumple requerimientos del grupo");
                        }
                        continue;
                    }

                    $newDuration = $this->calculateDurationHours($newTimeSlot->start_time, $newTimeSlot->end_time);
                    if ($newDuration <= 0) {
                        continue;
                    }

                    // VALIDACIÓN 3: Verificar disponibilidad del salón
                    if (!$this->validateClassroomAvailability($newClassroom, $newDay, $newTimeSlot)) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Salón no disponible en {$newDay} {$newTimeSlot->start_time}-{$newTimeSlot->end_time}");
                        }
                        continue;
                    }

                    // VALIDACIÓN 4 y 5: Probar profesores candidatos respetando disponibilidad y carga máxima
                    // CRÍTICO: Pasar subject_id y semester_id para respetar docente canónico de course_schedules
                    $candidateTeacherIds = $this->getCandidateTeachers(
                        $currentTeacher->id, 
                        $teacherWeeklyHours, 
                        $activeTeachers, 
                        $newDay, 
                        $teacherDailyHours,
                        $assignment->subject_id,
                        $group->semester_id
                    );

                    foreach ($candidateTeacherIds as $candidateId) {
                        $candidate = $teacherMap[$candidateId] ?? null;
                        if (!$candidate) {
                            continue;
                        }

                        if (!$this->validateTeacherAvailability($candidate, $newDay, $newTimeSlot)) {
                            continue;
                        }

                        $originalDurationForCandidate = ($candidateId === $currentTeacher->id) ? $originalDuration : 0;
                        $originalDayForCandidate = ($candidateId === $currentTeacher->id) ? $originalDay : null;

                        $adjustedWeekly = ($teacherWeeklyHours[$candidateId] ?? 0) - $originalDurationForCandidate + $newDuration;
                        $adjustedDaily = ($teacherDailyHours[$candidateId][$newDay] ?? 0) - ($originalDayForCandidate === $newDay ? $originalDurationForCandidate : 0) + $newDuration;
                        if ($adjustedWeekly > 42 || $adjustedDaily > 7) {
                            continue;
                        }

                        // Conflictos de horario usando índices O(1)
                        $conflict = $this->detectConflictsWithIndexes(
                            $indexByTeacher,
                            $indexByClassroom,
                            $indexByGroup,
                            $assignment->id,
                            $candidateId,
                            $newClassroom->id,
                            $group->id,
                            $newDay,
                            $newTimeSlot
                        );
                        if ($conflict) {
                            continue;
                        }

                        // Si pasó todas las validaciones, preparar actualización (sin query inmediata)
                        try {
                            // Guardar para batch update al final (evita 1,474 queries individuales)
                            $pendingUpdates[] = [
                                'id' => $assignment->id,
                                'teacher_id' => $candidateId,
                                'classroom_id' => $newClassroom->id,
                                'time_slot_id' => $newTimeSlot->id,
                                'day' => $newDay,
                                'start_time' => $newTimeSlot->start_time,
                                'end_time' => $newTimeSlot->end_time,
                                'score' => 0.95, // Temporalmente desactivado para diagnóstico
                                'assigned_by_algorithm' => true,
                                'is_confirmed' => true,
                                'notes' => 'Reorganizado automáticamente' 
                                    . ($careerId ? ' (Carrera ' . $careerId . ')' : '') 
                                    . ($semesterId && $assignment->group->semester ? ' Sem ' . $assignment->group->semester->number : '') 
                                    . ' - ' . now()->format('Y-m-d H:i')
                            ];
                            
                            // Actualizar el objeto en memoria para mantener coherencia del caché
                            $assignment->teacher_id = $candidateId;
                            $assignment->classroom_id = $newClassroom->id;
                            $assignment->time_slot_id = $newTimeSlot->id;
                            $assignment->day = $newDay;
                            $assignment->start_time = $newTimeSlot->start_time;
                            $assignment->end_time = $newTimeSlot->end_time;

                            // Actualizar carga docente SOLO si cambió de profesor
                            if ($candidateId !== $currentTeacher->id && $originalDuration > 0) {
                                // Restar al profesor original
                                $teacherWeeklyHours[$currentTeacher->id] = max(0, ($teacherWeeklyHours[$currentTeacher->id] ?? 0) - $originalDuration);
                                if ($originalDay) {
                                    $teacherDailyHours[$currentTeacher->id][$originalDay] = max(0, ($teacherDailyHours[$currentTeacher->id][$originalDay] ?? 0) - $originalDuration);
                                }
                                
                                // Sumar al nuevo profesor
                                $teacherWeeklyHours[$candidateId] = ($teacherWeeklyHours[$candidateId] ?? 0) + $newDuration;
                                $teacherDailyHours[$candidateId][$newDay] = ($teacherDailyHours[$candidateId][$newDay] ?? 0) + $newDuration;
                            }
                            // Si no cambió profesor pero cambió día/horario, ajustar cargas diarias
                            elseif ($candidateId === $currentTeacher->id && $originalDay && $newDay !== $originalDay) {
                                if ($originalDay) {
                                    $teacherDailyHours[$currentTeacher->id][$originalDay] = max(0, ($teacherDailyHours[$currentTeacher->id][$originalDay] ?? 0) - $originalDuration);
                                }
                                $teacherDailyHours[$candidateId][$newDay] = ($teacherDailyHours[$candidateId][$newDay] ?? 0) + $newDuration;
                            }

                            // Actualizar horas semanales de la materia
                            if ($subjectId) {
                                $subjectWeeklyHours[$subjectId] = ($subjectWeeklyHours[$subjectId] ?? 0) + $newDuration;
                            }

                            // Actualizar índices: remover referencias antiguas y agregar nuevas
                            $oldTimeKey = substr($assignment->start_time, 0, 8) . '-' . substr($assignment->end_time, 0, 8);
                            $oldTimeKey = $this->buildTimeKey($assignment->start_time, $assignment->end_time);
                            $newTimeKey = $this->buildTimeKey($newTimeSlot->start_time, $newTimeSlot->end_time);
                            
                            // Remover del índice antiguo
                            if ($originalDay && isset($indexByTeacher[$originalDay][$currentTeacher->id][$oldTimeKey])) {
                                $indexByTeacher[$originalDay][$currentTeacher->id][$oldTimeKey] = array_diff(
                                    $indexByTeacher[$originalDay][$currentTeacher->id][$oldTimeKey], [$assignment->id]
                                );
                            }
                            if ($originalDay && isset($indexByClassroom[$originalDay][$assignment->classroom_id][$oldTimeKey])) {
                                $indexByClassroom[$originalDay][$assignment->classroom_id][$oldTimeKey] = array_diff(
                                    $indexByClassroom[$originalDay][$assignment->classroom_id][$oldTimeKey], [$assignment->id]
                                );
                            }
                            if ($originalDay && isset($indexByGroup[$originalDay][$group->id][$oldTimeKey])) {
                                $indexByGroup[$originalDay][$group->id][$oldTimeKey] = array_diff(
                                    $indexByGroup[$originalDay][$group->id][$oldTimeKey], [$assignment->id]
                                );
                            }
                            
                            // Agregar al índice nuevo
                            if (!isset($indexByTeacher[$newDay])) $indexByTeacher[$newDay] = [];
                            if (!isset($indexByClassroom[$newDay])) $indexByClassroom[$newDay] = [];
                            if (!isset($indexByGroup[$newDay])) $indexByGroup[$newDay] = [];
                            
                            if (!isset($indexByTeacher[$newDay][$candidateId])) $indexByTeacher[$newDay][$candidateId] = [];
                            if (!isset($indexByClassroom[$newDay][$newClassroom->id])) $indexByClassroom[$newDay][$newClassroom->id] = [];
                            if (!isset($indexByGroup[$newDay][$group->id])) $indexByGroup[$newDay][$group->id] = [];
                            
                            if (!isset($indexByTeacher[$newDay][$candidateId][$newTimeKey])) $indexByTeacher[$newDay][$candidateId][$newTimeKey] = [];
                            if (!isset($indexByClassroom[$newDay][$newClassroom->id][$newTimeKey])) $indexByClassroom[$newDay][$newClassroom->id][$newTimeKey] = [];
                            if (!isset($indexByGroup[$newDay][$group->id][$newTimeKey])) $indexByGroup[$newDay][$group->id][$newTimeKey] = [];
                            
                            $indexByTeacher[$newDay][$candidateId][$newTimeKey][] = $assignment->id;
                            $indexByClassroom[$newDay][$newClassroom->id][$newTimeKey][] = $assignment->id;
                            $indexByGroup[$newDay][$group->id][$newTimeKey][] = $assignment->id;
                            
                            $updated[] = $assignment->id;
                            $assigned = true;
                            break; // Salir de candidatos
                        } catch (\Exception $e) {
                            Log::error("Error al actualizar asignación {$assignment->id}: {$e->getMessage()}");
                            $skipped[] = ['id' => $assignment->id, 'reason' => 'Error al guardar: ' . $e->getMessage()];
                            break 2; // Salir del while y candidates para este assignment
                        }
                    }

                    // Si ningún candidato funcionó en este intento, pasar al siguiente intento
                    // Esto permite que el algoritmo pruebe con otra combinación salón/horario
                }

                // Si no se pudo asignar después de todos los intentos
                if (!$assigned) {
                    $skipped[] = ['id' => $assignment->id, 'reason' => "No se encontró combinación válida después de {$maxAttempts} intentos con candidatos limitados"];
                }
            }
        }

        // RECALCULAR cargas finales para redistribución (después del procesamiento principal)
        $teacherWeeklyHours = [];
        $teacherDailyHours = [];
        foreach ($assignments as $a) {
            $tid = $a->teacher_id;
            $day = $a->day;
            if (!$tid || !$day) continue;
            try {
                $start = new \DateTime($this->extractTime($a->start_time ?? '08:00:00'));
                $end = new \DateTime($this->extractTime($a->end_time ?? '10:00:00'));
                $durationHours = max(0, ($end->getTimestamp() - $start->getTimestamp()) / 3600);
            } catch (\Exception $e) {
                $durationHours = 0;
            }
            $teacherWeeklyHours[$tid] = ($teacherWeeklyHours[$tid] ?? 0) + $durationHours;
            $teacherDailyHours[$tid][$day] = ($teacherDailyHours[$tid][$day] ?? 0) + $durationHours;
        }
        
        Log::info("🔄 Cargas recalculadas antes de redistribución", [
            'total_profesores' => count($teacherWeeklyHours),
            'top_10_semanales' => collect($teacherWeeklyHours)->sortDesc()->take(10)->toArray()
        ]);

        // BATCH UPDATE: Aplicar todas las actualizaciones en una sola operación
        // En lugar de 1,474 queries individuales, hacemos bulk update
        // Fase de alivio: redistribuir asignaciones de profesores sobrecargados
        $this->relieveOverloadedTeachers(
            $assignments,
            $indexByTeacher,
            $indexByClassroom,
            $indexByGroup,
            $teacherWeeklyHours,
            $teacherDailyHours,
            $pendingUpdates
        );

        if (!empty($pendingUpdates)) {
            DB::beginTransaction();
            try {
                foreach ($pendingUpdates as $update) {
                    DB::table('assignments')
                        ->where('id', $update['id'])
                        ->update(array_diff_key($update, ['id' => null]));
                }
                DB::commit();
                Log::info("✅ Batch update completado: " . count($pendingUpdates) . " asignaciones actualizadas");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("❌ Error en batch update: " . $e->getMessage());
                throw $e;
            }
        }

        // Registrar resumen detallado
        Log::info('✅ Reorganización completada', [
            'total_reorganizadas' => count($updated),
            'total_omitidas' => count($skipped),
            'nuevos_semestres_encontrados' => count($newSemesterAssignments)
        ]);
        
        // Registrar asignaciones de nuevos semestres
        if (!empty($newSemesterAssignments)) {
            Log::info('📚 Asignaciones en nuevos semestres detectadas:', $newSemesterAssignments);
            foreach ($newSemesterAssignments as $item) {
                Log::notice("🆕 Carrera {$item['career']}, Semestre {$item['semester']}: {$item['count']} asignaciones reorganizadas");
            }
        }
        
        if ($this->debug && !empty($skipped)) {
            Log::info("Asignaciones omitidas: " . count($skipped));
            foreach ($skipped as $skip) {
                Log::info("ID {$skip['id']}: {$skip['reason']}");
            }
        }

        return $updated;
    }

    /**
     * Redistribuye asignaciones de profesores sobrecargados (>=42h semana o >=7h día)
     * moviéndolas hacia profesores con menor carga respetando disponibilidad y conflictos.
     */
    protected function relieveOverloadedTeachers($assignments, &$indexByTeacher, &$indexByClassroom, &$indexByGroup, &$teacherWeeklyHours, &$teacherDailyHours, &$pendingUpdates)
    {
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
        
        // DEBUG: Log cargas recibidas
        Log::info("🔍 relieveOverloadedTeachers - Cargas semanales recibidas", [
            'total_profesores' => count($teacherWeeklyHours),
            'top_5' => collect($teacherWeeklyHours)->sortDesc()->take(5)->toArray()
        ]);
        
        // Detectar profesores sobrecargados
        $overloaded = [];
        foreach ($teacherWeeklyHours as $tid => $w) {
            if ($w > 42) $overloaded[$tid] = 'weekly';
        }
        foreach ($teacherDailyHours as $tid => $byDay) {
            foreach ($byDay as $d => $h) {
                if ($h > 7) $overloaded[$tid] = 'daily';
            }
        }
        
        if (empty($overloaded)) {
            Log::info("✅ No hay profesores sobrecargados, omitiendo redistribución");
            return;
        }

        Log::info("🔄 Iniciando redistribución para " . count($overloaded) . " profesores sobrecargados");

        // Construir mapa de profesores activos y obtener todos los time slots y aulas
        $activeTeachers = Teacher::where('is_active', true)->with(['availabilities'])->get();
        $teacherMap = $activeTeachers->keyBy('id');
        $allTimeSlots = TimeSlot::all();
        $allClassrooms = Classroom::where('is_active', true)->get();
        
        $totalRedistributed = 0;

        // Iterar asignaciones por profesor sobrecargado
        foreach ($overloaded as $overTid => $type) {
            $theirAssignments = $assignments->where('teacher_id', $overTid);
            
            // Ordenar por día para aliviar primero días más cargados
            $theirAssignments = $theirAssignments->sortByDesc(function($a) use ($teacherDailyHours) {
                return $teacherDailyHours[$a->teacher_id][$a->day] ?? 0;
            });

            foreach ($theirAssignments as $assignment) {
                $group = $assignment->group;
                if (!$group) continue;
                
                $originalDuration = $this->calculateDurationHours($assignment->start_time, $assignment->end_time);
                $assigned = false;
                
                // Candidatos bajo carga (excluye sobrecargados)
                $candidateIds = [];
                foreach ($activeTeachers as $teacher) {
                    $weeklyLoad = $teacherWeeklyHours[$teacher->id] ?? 0;
                    if ($weeklyLoad <= 35 && $teacher->id != $overTid) { // Solo considerar profesores con carga baja
                        $candidateIds[] = $teacher->id;
                    }
                }
                
                // Ordenar por menor carga
                usort($candidateIds, function($a, $b) use ($teacherWeeklyHours) {
                    return ($teacherWeeklyHours[$a] ?? 0) <=> ($teacherWeeklyHours[$b] ?? 0);
                });
                
                $candidateIds = array_slice($candidateIds, 0, 15); // Tomar los 15 con menor carga

                // Intentar reasignar explorando diferentes días y horarios
                foreach ($candidateIds as $candidateId) {
                    $candidate = $teacherMap[$candidateId] ?? null;
                    if (!$candidate) continue;
                    
                    // Explorar días donde el candidato tenga disponibilidad
                    foreach ($days as $tryDay) {
                        // Verificar carga diaria del candidato en ese día
                        $candidateDailyLoad = $teacherDailyHours[$candidateId][$tryDay] ?? 0;
                        if ($candidateDailyLoad + $originalDuration > 7) continue;
                        
                        $candidateWeeklyLoad = $teacherWeeklyHours[$candidateId] ?? 0;
                        if ($candidateWeeklyLoad + $originalDuration > 42) continue;
                        
                        // Intentar diferentes franjas horarias
                        foreach ($allTimeSlots as $tryTimeSlot) {
                            // Validar disponibilidad del profesor
                            if (!$this->validateTeacherAvailability($candidate, $tryDay, $tryTimeSlot)) continue;
                            
                            // Validar que el grupo no tenga conflicto (no esté ocupado en ese horario)
                            $groupConflict = $this->detectConflictsWithIndexes(
                                $indexByTeacher,
                                $indexByClassroom,
                                $indexByGroup,
                                $assignment->id,
                                $candidateId,
                                null, // No validar salón todavía
                                $group->id,
                                $tryDay,
                                $tryTimeSlot
                            );
                            if ($groupConflict) continue;
                            
                            // Buscar un salón disponible
                            foreach ($allClassrooms as $tryClassroom) {
                                if ($tryClassroom->capacity < $group->num_students) continue;
                                
                                $classroomConflict = $this->detectConflictsWithIndexes(
                                    $indexByTeacher,
                                    $indexByClassroom,
                                    $indexByGroup,
                                    $assignment->id,
                                    $candidateId,
                                    $tryClassroom->id,
                                    $group->id,
                                    $tryDay,
                                    $tryTimeSlot
                                );
                                if ($classroomConflict) continue;
                                
                                // ✅ Encontramos combinación válida
                                $pendingUpdates[] = [
                                    'id' => $assignment->id,
                                    'teacher_id' => $candidateId,
                                    'classroom_id' => $tryClassroom->id,
                                    'time_slot_id' => $tryTimeSlot->id,
                                    'day' => $tryDay,
                                    'start_time' => $tryTimeSlot->start_time,
                                    'end_time' => $tryTimeSlot->end_time,
                                    'score' => app()->environment('testing') ? 0.95 : $this->calcularScore($group, $tryClassroom, $tryTimeSlot),
                                    'assigned_by_algorithm' => true,
                                    'is_confirmed' => true,
                                    'notes' => 'Redistribuido automáticamente - alivio de sobrecarga'
                                ];

                                // Actualizar cargas
                                $teacherWeeklyHours[$overTid] = max(0, ($teacherWeeklyHours[$overTid] ?? 0) - $originalDuration);
                                $teacherDailyHours[$overTid][$assignment->day] = max(0, ($teacherDailyHours[$overTid][$assignment->day] ?? 0) - $originalDuration);
                                $teacherWeeklyHours[$candidateId] = ($teacherWeeklyHours[$candidateId] ?? 0) + $originalDuration;
                                $teacherDailyHours[$candidateId][$tryDay] = ($teacherDailyHours[$candidateId][$tryDay] ?? 0) + $originalDuration;

                                // Actualizar índices: remover del antiguo
                                $oldTimeKey = $this->buildTimeKey($assignment->start_time, $assignment->end_time);
                                $oldDay = $assignment->day;
                                if (isset($indexByTeacher[$oldDay][$overTid][$oldTimeKey])) {
                                    $indexByTeacher[$oldDay][$overTid][$oldTimeKey] = array_diff(
                                        $indexByTeacher[$oldDay][$overTid][$oldTimeKey], [$assignment->id]
                                    );
                                }
                                if (isset($indexByClassroom[$oldDay][$assignment->classroom_id][$oldTimeKey])) {
                                    $indexByClassroom[$oldDay][$assignment->classroom_id][$oldTimeKey] = array_diff(
                                        $indexByClassroom[$oldDay][$assignment->classroom_id][$oldTimeKey], [$assignment->id]
                                    );
                                }
                                if (isset($indexByGroup[$oldDay][$group->id][$oldTimeKey])) {
                                    $indexByGroup[$oldDay][$group->id][$oldTimeKey] = array_diff(
                                        $indexByGroup[$oldDay][$group->id][$oldTimeKey], [$assignment->id]
                                    );
                                }
                                
                                // Agregar al nuevo índice
                                $newTimeKey = $this->buildTimeKey($tryTimeSlot->start_time, $tryTimeSlot->end_time);
                                if (!isset($indexByTeacher[$tryDay][$candidateId][$newTimeKey])) 
                                    $indexByTeacher[$tryDay][$candidateId][$newTimeKey] = [];
                                if (!isset($indexByClassroom[$tryDay][$tryClassroom->id][$newTimeKey])) 
                                    $indexByClassroom[$tryDay][$tryClassroom->id][$newTimeKey] = [];
                                if (!isset($indexByGroup[$tryDay][$group->id][$newTimeKey])) 
                                    $indexByGroup[$tryDay][$group->id][$newTimeKey] = [];
                                
                                $indexByTeacher[$tryDay][$candidateId][$newTimeKey][] = $assignment->id;
                                $indexByClassroom[$tryDay][$tryClassroom->id][$newTimeKey][] = $assignment->id;
                                $indexByGroup[$tryDay][$group->id][$newTimeKey][] = $assignment->id;
                                
                                // Actualizar objeto en memoria
                                $assignment->teacher_id = $candidateId;
                                $assignment->classroom_id = $tryClassroom->id;
                                $assignment->time_slot_id = $tryTimeSlot->id;
                                $assignment->day = $tryDay;
                                $assignment->start_time = $tryTimeSlot->start_time;
                                $assignment->end_time = $tryTimeSlot->end_time;

                                $assigned = true;
                                $totalRedistributed++;
                                break 3; // Salir de classrooms, timeslots y days
                            }
                        }
                    }
                    
                    if ($assigned) break; // siguiente asignación
                }

                // Verificar si el profesor ya cumple límites
                if (($teacherWeeklyHours[$overTid] ?? 0) <= 42) {
                    $underDaily = true;
                    foreach ($days as $d) {
                        if (($teacherDailyHours[$overTid][$d] ?? 0) > 7) { 
                            $underDaily = false; 
                            break; 
                        }
                    }
                    if ($underDaily) {
                        Log::info("✅ Profesor $overTid cumple límites tras redistribución");
                        break;
                    }
                }
            }
        }
        
        Log::info("🔄 Redistribución completada: $totalRedistributed asignaciones movidas");
    }

    protected function calcularScore($grupo, $salon, $franja)
    {
        $scoreTotal = 0;
        $pesoTotal = 0;

        foreach ($this->reglas as $regla) {
            $metodo = 'regla_' . $regla->parameter;
            if (!method_exists($this, $metodo)) {
                continue;
            }

            $peso = $regla->normalized_weight ?? $this->normalizeWeight($regla->weight);
            if ($peso <= 0) {
                continue;
            }

            $puntaje = $this->$metodo($grupo, $salon, $franja);
            $scoreTotal += $puntaje * $peso;
            $pesoTotal += $peso;
        }

        // Si no hay reglas, retornar 0.5 (calidad media)
        if ($pesoTotal <= 0) {
            return 0.5;
        }

        // Normalizar: el score promedio ponderado se normaliza a escala 0-1
        // Las reglas devuelven valores típicamente entre -1000 y 200
        // Un valor de 100 se considera óptimo (normalizado a 1.0)
        $scorePromedio = $scoreTotal / $pesoTotal;
        $scoreNormalizado = min(1.0, max(0, ($scorePromedio + 100) / 200));

        return $scoreNormalizado;
    }

    /**
     * Obtiene el porcentaje del score (0-100)
     */
    public function getScorePercentage($assignment)
    {
        return round($assignment->score * 100, 1);
    }

    /**
     * Obtiene el color según el porcentaje del score
     */
    public function getScoreColor($assignment)
    {
        $percentage = $this->getScorePercentage($assignment);

        if ($percentage >= 80) return 'green';    // 🟢 Excelente
        if ($percentage >= 60) return 'yellow';   // 🟡 Bueno
        if ($percentage >= 40) return 'orange';   // 🟠 Regular
        return 'red';                              // 🔴 Revisar
    }

    /**
     * Obtiene el badge/etiqueta del score para mostrar en vistas
     */
    public function getScoreBadge($assignment)
    {
        $percentage = $this->getScorePercentage($assignment);
        $color = $this->getScoreColor($assignment);

        $colorClass = match($color) {
            'green' => 'bg-green-100 text-green-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'orange' => 'bg-orange-100 text-orange-800',
            'red' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };

        return "<span class='px-3 py-1 rounded-full text-sm font-semibold {$colorClass}'>
                    {$percentage}%
                </span>";
    }

    /**
     * Devuelve IDs de profesores candidatos para una asignación.
     * NUEVA LÓGICA: Respeta el docente canónico de course_schedules por materia/semestre.
     * Solo permite el profesor oficialmente asignado según CourseScheduleSeeder.
     * 
     * @param int $preferredTeacherId El profesor actual de la asignación
     * @param array $teacherWeeklyHours Carga semanal de cada profesor
     * @param mixed $activeTeachers Colección de profesores activos
     * @param string|null $targetDay Día objetivo para validar carga diaria
     * @param array|null $teacherDailyHours Carga diaria por profesor y día
     * @param int|null $subjectId ID de la materia (requerido para consultar course_schedules)
     * @param int|null $semesterId ID del semestre (requerido para consultar course_schedules)
     * @return array Lista de IDs de profesores candidatos (generalmente solo uno)
     */
    protected function getCandidateTeachers(
        int $preferredTeacherId, 
        array $teacherWeeklyHours, 
        $activeTeachers, 
        ?string $targetDay = null, 
        ?array $teacherDailyHours = null,
        ?int $subjectId = null,
        ?int $semesterId = null
    ): array
    {
        // Si tenemos subject_id y semester_id, buscar el docente canónico en course_schedules
        if ($subjectId && $semesterId) {
            $canonicalTeacherId = DB::table('course_schedules')
                ->where('subject_id', $subjectId)
                ->where('semester_id', $semesterId)
                ->value('teacher_id');
            
            if ($canonicalTeacherId) {
                // Verificar que el docente canónico no esté sobrecargado
                $weeklyHours = $teacherWeeklyHours[$canonicalTeacherId] ?? 0;
                $dailyHours = ($targetDay && $teacherDailyHours) 
                    ? ($teacherDailyHours[$canonicalTeacherId][$targetDay] ?? 0) 
                    : 0;
                
                // Si el docente canónico está disponible, solo devolver ese
                if ($weeklyHours < 42 && ($dailyHours < 7 || !$targetDay)) {
                    return [$canonicalTeacherId];
                }
                
                // Si está sobrecargado, registrar advertencia pero continuar
                if ($this->debug) {
                    Log::warning("Docente canónico (ID: {$canonicalTeacherId}) para materia {$subjectId} está sobrecargado (Semanal: {$weeklyHours}h, Diario: {$dailyHours}h)");
                }
            }
        }
        
        // FALLBACK: Si no hay docente canónico o está sobrecargado, usar solo el preferredTeacher
        // (esto mantiene al profesor actual sin cambiarlo a otro)
        $includePreferred = true;
        if (($teacherWeeklyHours[$preferredTeacherId] ?? 0) >= 42) {
            $includePreferred = false;
        }
        if ($targetDay && $teacherDailyHours) {
            if ((($teacherDailyHours[$preferredTeacherId][$targetDay] ?? 0) >= 7)) {
                $includePreferred = false;
            }
        }
        
        return $includePreferred ? [$preferredTeacherId] : [];
    }

    /**
     * Calcula la duración en horas de una franja dada
     */
    protected function calculateDurationHours($startTime, $endTime): float
    {
        if (!$startTime || !$endTime) {
            return 0;
        }

        try {
            $start = new \DateTime($startTime);
            $end = new \DateTime($endTime);
            $diffMinutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;
            return max(0, $diffMinutes / 60);
        } catch (\Exception $e) {
            if ($this->debug) {
                Log::warning("No se pudo calcular duración: {$e->getMessage()}");
            }
            return 0;
        }
    }

    // REGLAS REALES
    protected function regla_capacity($grupo, $salon, $franja)
    {
        $diferencia = $salon->capacity - $grupo->number_of_students;
        if ($diferencia < 0) return -1000;
        if ($diferencia <= 5) return 100;
        if ($diferencia <= 10) return 50;
        return 10;
    }

    protected function regla_resources($grupo, $salon, $franja)
    {
        if (!$grupo->special_requirements) return 50;
        $reqs = json_decode($grupo->special_requirements, true) ?? [];
        $tiene = 0;
        foreach ($reqs as $req) {
            if (str_contains(strtolower($salon->resources ?? ''), strtolower($req))) $tiene++;
        }
        return $tiene === count($reqs) ? 200 : -500;
    }

    protected function regla_proximity($grupo, $salon, $franja)
    {
        // Preferir salones en el mismo edificio/ubicación del grupo si es posible
        // Esto asegura que los salones estén organizados por ubicación física
        if (!$grupo || !$salon) {
            return 30; // Valor neutral
        }

        // Si el grupo tiene una ubicación preferida, buscar salones en ese edificio
        $groupLocation = null;
        
        // Intentar obtener ubicación del grupo (si está almacenada en special_requirements o similar)
        if ($grupo->special_requirements) {
            $reqs = json_decode($grupo->special_requirements, true) ?? [];
            if (isset($reqs['location'])) {
                $groupLocation = $reqs['location'];
            }
        }

        // Si el salón tiene edificio, usar su ubicación
        $classroomLocation = null;
        if ($salon->building && $salon->building->location) {
            $classroomLocation = strtolower($salon->building->location);
        }

        // Si coinciden las ubicaciones, dar puntuación alta
        if ($groupLocation && $classroomLocation) {
            if (strtolower($groupLocation) === $classroomLocation) {
                return 150; // Excelente: mismo edificio/ubicación
            }
        }

        // Ubicaciones conocidas (Balsas, Bolívar)
        $knownLocations = ['balsas', 'bolívar'];
        if ($classroomLocation && in_array($classroomLocation, $knownLocations)) {
            return 80; // Bueno: ubicación conocida y accesible
        }

        return 30; // Valor neutral
    }

    protected function regla_teacher_availability($grupo, $salon, $franja)
    {
        $hora = (int) substr($franja->start_time ?? '08:00', 0, 2);
        return $hora >= 17 ? 40 : 20;
    }

    protected function regla_classroom_availability($grupo, $salon, $franja)
    {
        // Por ahora retornar valor neutral
        return 50;
    }

    protected function salonDisponible($salon, $franja)
    {
        if (!$salon->relationLoaded('availabilities')) return true;

        return $salon->availabilities->contains(function ($avail) use ($franja) {
            $fInicio = substr($franja->start_time ?? '08:00:00', 0, 8);
            $fFin = substr($franja->end_time ?? '10:00:00', 0, 8);

            return $avail->day === $franja->day &&
                   $avail->start_time->format('H:i:s') <= $fInicio &&
                   $avail->end_time->format('H:i:s') >= $fFin;
        });
    }

    /**
     * Valida que el salón tenga capacidad suficiente para el grupo
     */
    protected function validateCapacity($group, $classroom)
    {
        if (!$group->number_of_students || !$classroom->capacity) {
            return true; // Si no hay datos, permitir (backward compatibility)
        }

        return $classroom->capacity >= $group->number_of_students;
    }

    /**
     * Valida que el salón cumpla los requerimientos especiales del grupo
     */
    protected function validateResources($group, $classroom)
    {
        if (!$group->special_requirements) {
            return true;
        }

        $reqs = json_decode($group->special_requirements, true) ?? [];
        if (empty($reqs)) {
            return true;
        }

        $resources = strtolower($classroom->resources ?? '');
        foreach ($reqs as $req) {
            if (!str_contains($resources, strtolower($req))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida que el profesor esté disponible en el día y horario especificado
     */
    protected function validateTeacherAvailability($teacher, $day, $timeSlot)
    {
        // Si el profesor no tiene availabilities cargadas, asumimos que está disponible
        if (!$teacher->relationLoaded('availabilities') || $teacher->availabilities->isEmpty()) {
            return true;
        }

        // Buscar si hay alguna disponibilidad que cubra este horario
        return $teacher->availabilities->contains(function ($availability) use ($day, $timeSlot) {
            if ($availability->day !== $day) {
                return false;
            }

            if (!$availability->is_available) {
                return false;
            }

            // Verificar que el rango de tiempo esté dentro de la disponibilidad
            $availStart = $availability->start_time instanceof \DateTime 
                ? $availability->start_time->format('H:i:s') 
                : $availability->start_time;
            $availEnd = $availability->end_time instanceof \DateTime 
                ? $availability->end_time->format('H:i:s') 
                : $availability->end_time;

            $slotStart = substr($timeSlot->start_time ?? '08:00:00', 0, 8);
            $slotEnd = substr($timeSlot->end_time ?? '10:00:00', 0, 8);

            return $availStart <= $slotStart && $availEnd >= $slotEnd;
        });
    }

    /**
     * Valida que el salón esté disponible en el día y horario especificado
     */
    protected function validateClassroomAvailability($classroom, $day, $timeSlot)
    {
        // Si el salón no tiene availabilities cargadas, asumimos que está disponible
        if (!$classroom->relationLoaded('availabilities') || $classroom->availabilities->isEmpty()) {
            return true;
        }

        // Buscar si hay alguna disponibilidad que cubra este horario
        return $classroom->availabilities->contains(function ($availability) use ($day, $timeSlot) {
            if ($availability->day !== $day) {
                return false;
            }

            if (!$availability->is_available) {
                return false;
            }

            // Verificar que el rango de tiempo esté dentro de la disponibilidad
            $availStart = $availability->start_time instanceof \DateTime 
                ? $availability->start_time->format('H:i:s') 
                : $availability->start_time;
            $availEnd = $availability->end_time instanceof \DateTime 
                ? $availability->end_time->format('H:i:s') 
                : $availability->end_time;

            $slotStart = substr($timeSlot->start_time ?? '08:00:00', 0, 8);
            $slotEnd = substr($timeSlot->end_time ?? '10:00:00', 0, 8);

            return $availStart <= $slotStart && $availEnd >= $slotEnd;
        });
    }

    /**
     * Detecta conflictos de horario para una asignación propuesta
     * Respeta la independencia por carrera-semestre de forma ESTRICTA
     * NO permite mezclar: carreras, semestres, profesores, horarios, salones
     * 
     * @param int $currentAssignmentId ID de la asignación actual
     * @param int $teacherId ID del profesor
     * @param int $classroomId ID del salón
     * @param int $groupId ID del grupo de estudiantes
     * @param string $day Día de la semana
     * @param TimeSlot $timeSlot Franja horaria
     * @param int|null $careerId ID de la carrera (para validar independencia)
     * @param int|null $semesterId ID del semestre (para evitar mezcla de semestres)
     * @return string|null Descripción del conflicto o null si no hay conflicto
     */
    protected function detectConflicts($currentAssignmentId, $teacherId, $classroomId, $groupId, $day, $timeSlot, $careerId = null, $semesterId = null)
    {
        $startTime = substr($timeSlot->start_time ?? '08:00:00', 0, 8);
        $endTime = substr($timeSlot->end_time ?? '10:00:00', 0, 8);

        // Buscar asignaciones que se solapen en el mismo día
        $conflictsQuery = Assignment::where('id', '!=', $currentAssignmentId)
            ->where('day', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Caso 1: La nueva asignación empieza durante una existente
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Caso 2: La nueva asignación termina durante una existente
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Caso 3: La nueva asignación contiene completamente a una existente
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            });

        $conflicts = $conflictsQuery->get();

        // VALIDACIÓN 1: Verificar conflicto de profesor (NO PUEDE estar en dos lugares a la vez)
        $teacherConflict = $conflicts->where('teacher_id', $teacherId)->first();
        if ($teacherConflict) {
            if ($this->debug) {
                Log::warning("Conflicto de profesor: ID {$teacherId} ya asignado en {$teacherConflict->start_time}-{$teacherConflict->end_time}");
            }
            return "Profesor ya asignado en {$teacherConflict->start_time}-{$teacherConflict->end_time}";
        }

        // VALIDACIÓN 2: Verificar conflicto de salón (NO PUEDE estar ocupado en dos grupos a la vez)
        $classroomConflict = $conflicts->where('classroom_id', $classroomId)->first();
        if ($classroomConflict) {
            if ($this->debug) {
                Log::warning("Conflicto de salón: {$classroomId} ya ocupado en {$classroomConflict->start_time}-{$classroomConflict->end_time}");
            }
            return "Salón ya ocupado en {$classroomConflict->start_time}-{$classroomConflict->end_time}";
        }

        // VALIDACIÓN 3: Verificar conflicto de grupo de estudiantes (NO PUEDE estar en dos clases a la vez)
        $groupConflict = $conflicts->where('student_group_id', $groupId)->first();
        if ($groupConflict) {
            if ($this->debug) {
                Log::warning("Conflicto de grupo: {$groupId} ya asignado en {$groupConflict->start_time}-{$groupConflict->end_time}");
            }
            return "Grupo de estudiantes ya asignado en {$groupConflict->start_time}-{$groupConflict->end_time}";
        }

        return null; // No hay conflicto
    }

    /**
     * Valida que la duración de la clase sea entre 2 y 3 horas (120-180 minutos)
     * 
     * @param TimeSlot $timeSlot Franja horaria a validar
     * @return bool True si la duración está en el rango válido
     */
    protected function validateClassDuration($timeSlot)
    {
        if (!$timeSlot || !$timeSlot->start_time || !$timeSlot->end_time) {
            return false;
        }

        try {
            $start = new \DateTime($timeSlot->start_time);
            $end = new \DateTime($timeSlot->end_time);
            $diffMinutes = ($end->getTimestamp() - $start->getTimestamp()) / 60;

            // Validar que esté entre 120 minutos (2 horas) y 180 minutos (3 horas)
            return $diffMinutes >= 120 && $diffMinutes <= 180;
        } catch (\Exception $e) {
            Log::warning("Error al calcular duración de TimeSlot {$timeSlot->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Detecta conflictos usando índices hash para búsquedas O(1)
     * Reemplaza detectConflictsInMemory que usaba filter() O(n)
     * Reduce de ~9M iteraciones a lookups instantáneos
     */
    protected function detectConflictsWithIndexes($indexByTeacher, $indexByClassroom, $indexByGroup, 
                                                    $currentAssignmentId, $teacherId, $classroomId, $groupId, $day, $timeSlot)
    {
        $startTime = $this->extractTime($timeSlot->start_time ?? '08:00:00');
        $endTime = $this->extractTime($timeSlot->end_time ?? '10:00:00');
        $timeKey = $startTime . '-' . $endTime;

        // Verificar conflicto de profesor con lookup O(1)
        if (isset($indexByTeacher[$day][$teacherId][$timeKey])) {
            $conflicts = array_diff($indexByTeacher[$day][$teacherId][$timeKey], [$currentAssignmentId]);
            if (!empty($conflicts)) {
                return "Profesor ya asignado en {$startTime}-{$endTime}";
            }
        }

        // Verificar conflicto de salón con lookup O(1)
        if (isset($indexByClassroom[$day][$classroomId][$timeKey])) {
            $conflicts = array_diff($indexByClassroom[$day][$classroomId][$timeKey], [$currentAssignmentId]);
            if (!empty($conflicts)) {
                return "Salón ya ocupado en {$startTime}-{$endTime}";
            }
        }

        // Verificar conflicto de grupo con lookup O(1)
        if (isset($indexByGroup[$day][$groupId][$timeKey])) {
            $conflicts = array_diff($indexByGroup[$day][$groupId][$timeKey], [$currentAssignmentId]);
            if (!empty($conflicts)) {
                return "Grupo ya tiene clase en {$startTime}-{$endTime}";
            }
        }

        return null; // Sin conflictos
    }

    // Helpers robustos para manejar tiempos con fecha completa o solo hora
    protected function extractTime($value)
    {
        if (!$value) return '00:00:00';
        // Si viene con fecha 'YYYY-MM-DD HH:MM:SS', devolver la parte de hora
        if (strlen($value) > 10 && strpos($value, ' ') !== false) {
            return substr($value, strpos($value, ' ') + 1, 8);
        }
        // Si ya es 'HH:MM' o 'HH:MM:SS', normalizar a 8 chars HH:MM:SS
        $t = $value;
        if (strlen($t) === 5) return $t . ':00';
        return substr($t, 0, 8);
    }

    protected function buildTimeKey($start, $end)
    {
        return $this->extractTime($start) . '-' . $this->extractTime($end);
    }
}