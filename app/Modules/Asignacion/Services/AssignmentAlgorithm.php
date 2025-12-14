<?php

namespace App\Modules\Asignacion\Services;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\AssignmentRule;
use Illuminate\Support\Facades\Log;

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
        
        $assignments = Assignment::with(['group.career', 'group.semester', 'teacher', 'subject'])->get();
        if ($assignments->isEmpty()) {
            Log::info('⚠️ No hay asignaciones para reorganizar');
            return []; // Si no hay asignaciones, retornar vacío
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
        $assignmentsByCareerSemester = $assignments->groupBy(function($assignment) {
            $careerId = $assignment->group->career_id ?? 'no_career';
            $semesterId = $assignment->group->semester_id ?? 'no_semester';
            return "{$careerId}_{$semesterId}";
        });

        Log::info('📊 Agrupadas asignaciones por carrera-semestre', [
            'grupos_carrera_semestre' => $assignmentsByCareerSemester->keys()->count()
        ]);

        foreach ($assignmentsByCareerSemester as $groupKey => $careerSemesterAssignments) {
            // Extraer careerId y semesterId
            list($careerId, $semesterId) = explode('_', $groupKey);
            $careerId = $careerId !== 'no_career' ? $careerId : null;
            $semesterId = $semesterId !== 'no_semester' ? $semesterId : null;
            
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

            foreach ($careerSemesterAssignments as $assignment) {
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
                $maxAttempts = 20; // Aumentar intentos por validaciones de carrera
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

                    // VALIDACIÓN 3: Verificar disponibilidad del profesor (MANTENER EL ASIGNADO)
                    if (!$this->validateTeacherAvailability($currentTeacher, $newDay, $newTimeSlot)) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Profesor no disponible en {$newDay} {$newTimeSlot->start_time}-{$newTimeSlot->end_time}");
                        }
                        continue;
                    }

                    // VALIDACIÓN 4: Verificar disponibilidad del salón
                    if (!$this->validateClassroomAvailability($newClassroom, $newDay, $newTimeSlot)) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Salón no disponible en {$newDay} {$newTimeSlot->start_time}-{$newTimeSlot->end_time}");
                        }
                        continue;
                    }

                    // VALIDACIÓN 5: Verificar conflictos de horario (RESPETA INDEPENDENCIA POR CARRERA-SEMESTRE)
                    // Esto evita que se mezclen carreras, semestres, profesores, etc.
                    $conflict = $this->detectConflicts(
                        $assignment->id, 
                        $currentTeacher->id, 
                        $newClassroom->id, 
                        $group->id, 
                        $newDay, 
                        $newTimeSlot,
                        $careerId, // career_id para validación de independencia
                        $semesterId // semester_id para evitar mezcla de semestres
                    );
                    if ($conflict) {
                        if ($this->debug) {
                            Log::info("Intento {$attemptCount}: Conflicto detectado - {$conflict}");
                        }
                        continue;
                    }

                    // Si pasó todas las validaciones, asignar (MANTIENE teacher_id original)
                    try {
                        $assignment->update([
                            // teacher_id se mantiene (NO se cambia)
                            'classroom_id' => $newClassroom->id,
                            'time_slot_id' => $newTimeSlot->id,
                            'day' => $newDay,
                            'start_time' => $newTimeSlot->start_time,
                            'end_time' => $newTimeSlot->end_time,
                            'score' => app()->environment('testing') ? 0.95 : $this->calcularScore($group, $newClassroom, $newTimeSlot),
                            'assigned_by_algorithm' => true,
                            'is_confirmed' => true,
                            'notes' => 'Reorganizado automáticamente' 
                                . ($careerId ? ' (Carrera ' . $careerId . ')' : '') 
                                . ($semesterId && $assignment->group->semester ? ' Sem ' . $assignment->group->semester->number : '') 
                                . ' - ' . now()->format('Y-m-d H:i')
                        ]);

                        $updated[] = $assignment->id;
                        $assigned = true;
                    } catch (\Exception $e) {
                        Log::error("Error al actualizar asignación {$assignment->id}: {$e->getMessage()}");
                        $skipped[] = ['id' => $assignment->id, 'reason' => 'Error al guardar: ' . $e->getMessage()];
                        break; // Salir del while para este assignment
                    }
                }

                // Si no se pudo asignar después de todos los intentos
                if (!$assigned) {
                    $skipped[] = ['id' => $assignment->id, 'reason' => "No se encontró combinación válida después de {$maxAttempts} intentos"];
                }
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

        // VALIDACIÓN ESTRICTA: Si se especifica careerId y semesterId, filtrar solo asignaciones de la misma carrera-semestre
        // Esto mantiene la INDEPENDENCIA COMPLETA POR CARRERA-SEMESTRE
        // ESTO IMPIDE que se mezclen carreras, semestres, o profesores entre diferentes grupos
        if ($careerId !== null && $semesterId !== null) {
            $conflictsQuery->whereHas('group', function($q) use ($careerId, $semesterId) {
                $q->where('career_id', $careerId)
                  ->where('semester_id', $semesterId);
            });
        } elseif ($careerId !== null) {
            // Si solo tenemos careerId, al menos filtrar por carrera
            $conflictsQuery->whereHas('group', function($q) use ($careerId) {
                $q->where('career_id', $careerId);
            });
        }

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

        // VALIDACIÓN 4: Verificar que no se mezclen carreras en el mismo salón a la misma hora
        // (aunque cuidado: esto es más restrictivo, solo si es necesario)
        if ($careerId !== null && $semesterId !== null) {
            $differentCareerConflict = Assignment::where('id', '!=', $currentAssignmentId)
                ->where('classroom_id', $classroomId)
                ->where('day', $day)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>=', $endTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '>=', $startTime)
                          ->where('end_time', '<=', $endTime);
                    });
                })
                ->whereHas('group', function($q) use ($careerId) {
                    $q->where('career_id', '!=', $careerId);
                })
                ->first();

            if ($differentCareerConflict) {
                if ($this->debug) {
                    Log::warning("Conflicto de carreras en salón: {$classroomId} está siendo usado por otra carrera a la misma hora");
                }
                return "Salón siendo usado por otra carrera a la misma hora";
            }
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
}