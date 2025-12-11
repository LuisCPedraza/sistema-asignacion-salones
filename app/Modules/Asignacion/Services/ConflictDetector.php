<?php

namespace App\Modules\Asignacion\Services;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;

class ConflictDetector
{
    /**
     * Detecta todos los conflictos en las asignaciones actuales
     */
    public function detectAllConflicts()
    {
        $assignments = Assignment::with(['group', 'teacher', 'classroom'])->get();
        $conflicts = [];

        foreach ($assignments as $assignment) {
            $assignmentConflicts = $this->detectConflictsForAssignment($assignment);
            
            if (!empty($assignmentConflicts)) {
                $conflicts[] = [
                    'assignment' => $assignment,
                    'conflicts' => $assignmentConflicts,
                    'severity' => $this->calculateSeverity($assignmentConflicts),
                    'message' => $this->generateMessage($assignment, $assignmentConflicts),
                ];
            }
        }

        return collect($conflicts)->sortByDesc('severity')->values()->all();
    }

    /**
     * Detecta conflictos para una asignación específica
     */
    public function detectConflictsForAssignment(Assignment $assignment)
    {
        $conflicts = [];

        // Conflictos de profesor
        $teacherConflicts = $this->findTeacherConflicts($assignment);
        if (!empty($teacherConflicts)) {
            $conflicts[] = [
                'type' => 'teacher',
                'severity' => 'high',
                'message' => $this->formatTeacherConflict($assignment, $teacherConflicts),
                'data' => $teacherConflicts,
            ];
        }

        // Conflictos de salón
        $classroomConflicts = $this->findClassroomConflicts($assignment);
        if (!empty($classroomConflicts)) {
            $conflicts[] = [
                'type' => 'classroom',
                'severity' => 'high',
                'message' => $this->formatClassroomConflict($assignment, $classroomConflicts),
                'data' => $classroomConflicts,
            ];
        }

        // Conflictos de grupo de estudiantes
        $groupConflicts = $this->findGroupConflicts($assignment);
        if (!empty($groupConflicts)) {
            $conflicts[] = [
                'type' => 'group',
                'severity' => 'critical',
                'message' => $this->formatGroupConflict($assignment, $groupConflicts),
                'data' => $groupConflicts,
            ];
        }

        // Validación de capacidad
        $capacityIssue = $this->checkCapacityIssue($assignment);
        if ($capacityIssue) {
            $conflicts[] = [
                'type' => 'capacity',
                'severity' => 'medium',
                'message' => $capacityIssue,
                'data' => [],
            ];
        }

        return $conflicts;
    }

    /**
     * Encuentra conflictos donde el profesor tiene otra clase al mismo tiempo
     */
    private function findTeacherConflicts(Assignment $assignment)
    {
        return Assignment::where('id', '!=', $assignment->id)
            ->where('teacher_id', $assignment->teacher_id)
            ->where('day', $assignment->day)
            ->where(function ($query) use ($assignment) {
                $query->where(function ($q) use ($assignment) {
                    // Caso 1: Nueva clase empieza durante una existente
                    $q->where('start_time', '<=', $assignment->start_time)
                      ->where('end_time', '>', $assignment->start_time);
                })->orWhere(function ($q) use ($assignment) {
                    // Caso 2: Nueva clase termina durante una existente
                    $q->where('start_time', '<', $assignment->end_time)
                      ->where('end_time', '>=', $assignment->end_time);
                })->orWhere(function ($q) use ($assignment) {
                    // Caso 3: Nueva clase contiene completamente a una existente
                    $q->where('start_time', '>=', $assignment->start_time)
                      ->where('end_time', '<=', $assignment->end_time);
                });
            })
            ->with(['group', 'classroom'])
            ->get()
            ->all();
    }

    /**
     * Encuentra conflictos donde el salón está ocupado por otro grupo
     */
    private function findClassroomConflicts(Assignment $assignment)
    {
        return Assignment::where('id', '!=', $assignment->id)
            ->where('classroom_id', $assignment->classroom_id)
            ->where('day', $assignment->day)
            ->where(function ($query) use ($assignment) {
                $query->where(function ($q) use ($assignment) {
                    $q->where('start_time', '<=', $assignment->start_time)
                      ->where('end_time', '>', $assignment->start_time);
                })->orWhere(function ($q) use ($assignment) {
                    $q->where('start_time', '<', $assignment->end_time)
                      ->where('end_time', '>=', $assignment->end_time);
                })->orWhere(function ($q) use ($assignment) {
                    $q->where('start_time', '>=', $assignment->start_time)
                      ->where('end_time', '<=', $assignment->end_time);
                });
            })
            ->with(['group', 'teacher'])
            ->get()
            ->all();
    }

    /**
     * Encuentra conflictos donde el grupo tiene otra clase al mismo tiempo
     */
    private function findGroupConflicts(Assignment $assignment)
    {
        return Assignment::where('id', '!=', $assignment->id)
            ->where('student_group_id', $assignment->student_group_id)
            ->where('day', $assignment->day)
            ->where(function ($query) use ($assignment) {
                $query->where(function ($q) use ($assignment) {
                    $q->where('start_time', '<=', $assignment->start_time)
                      ->where('end_time', '>', $assignment->start_time);
                })->orWhere(function ($q) use ($assignment) {
                    $q->where('start_time', '<', $assignment->end_time)
                      ->where('end_time', '>=', $assignment->end_time);
                })->orWhere(function ($q) use ($assignment) {
                    $q->where('start_time', '>=', $assignment->start_time)
                      ->where('end_time', '<=', $assignment->end_time);
                });
            })
            ->with(['classroom', 'teacher'])
            ->get()
            ->all();
    }

    /**
     * Verifica si la capacidad del salón es suficiente para el grupo
     */
    private function checkCapacityIssue(Assignment $assignment)
    {
        if (!$assignment->group || !$assignment->classroom) {
            return null;
        }

        $requiredCapacity = $assignment->group->number_of_students ?? 0;
        $availableCapacity = $assignment->classroom->capacity ?? 0;

        if ($requiredCapacity > $availableCapacity) {
            return "⚠️ Capacidad insuficiente: El salón tiene {$availableCapacity} asientos pero el grupo necesita {$requiredCapacity}";
        }

        return null;
    }

    /**
     * Formatea mensaje para conflicto de profesor
     */
    private function formatTeacherConflict(Assignment $assignment, array $conflicts)
    {
        $conflictCount = count($conflicts);
        $teacher = $assignment->teacher;
        
        $message = "🧑‍🏫 El profesor {$teacher->full_name} tiene {$conflictCount} conflicto" . ($conflictCount > 1 ? 's' : '');
        $message .= " de horario en {$assignment->day}:\n";

        foreach ($conflicts as $conflict) {
            $message .= "   • {$conflict->group->name} en {$conflict->classroom->name} ({$conflict->start_time->format('H:i')}-{$conflict->end_time->format('H:i')})\n";
        }

        $message .= "\n💡 Sugerencia: Cambiar la hora o el día de una de las clases.";

        return $message;
    }

    /**
     * Formatea mensaje para conflicto de salón
     */
    private function formatClassroomConflict(Assignment $assignment, array $conflicts)
    {
        $conflictCount = count($conflicts);
        $classroom = $assignment->classroom;
        
        $message = "🏫 El salón {$classroom->name} está ocupado por {$conflictCount} conflicto" . ($conflictCount > 1 ? 's' : '');
        $message .= " en {$assignment->day}:\n";

        foreach ($conflicts as $conflict) {
            $message .= "   • {$conflict->group->name} ({$conflict->start_time->format('H:i')}-{$conflict->end_time->format('H:i')})\n";
        }

        $message .= "\n💡 Sugerencia: Asignar un salón diferente o cambiar el horario.";

        return $message;
    }

    /**
     * Formatea mensaje para conflicto de grupo
     */
    private function formatGroupConflict(Assignment $assignment, array $conflicts)
    {
        $conflictCount = count($conflicts);
        $group = $assignment->group;
        
        $message = "👥 El grupo {$group->name} tiene {$conflictCount} conflicto" . ($conflictCount > 1 ? 's' : '');
        $message .= " - 2 clases simultáneas en {$assignment->day}:\n";

        foreach ($conflicts as $conflict) {
            $message .= "   • {$conflict->teacher->full_name} en {$conflict->classroom->name} ({$conflict->start_time->format('H:i')}-{$conflict->end_time->format('H:i')})\n";
        }

        $message .= "\n💡 Sugerencia: Reprogramar una de las clases a otro horario o día.";

        return $message;
    }

    /**
     * Calcula la severidad general de los conflictos
     */
    private function calculateSeverity(array $conflicts)
    {
        $hasConflict = false;

        foreach ($conflicts as $conflict) {
            if ($conflict['severity'] === 'critical') {
                return 3; // Critical
            }
            if ($conflict['severity'] === 'high') {
                $hasConflict = true;
            }
        }

        return $hasConflict ? 2 : 1;
    }

    /**
     * Genera mensaje resumido para una asignación
     */
    private function generateMessage(Assignment $assignment, array $conflicts)
    {
        $conflictCount = count($conflicts);
        $assignment_info = "{$assignment->group->name} - {$assignment->day} ({$assignment->start_time->format('H:i')})";

        return "{$conflictCount} conflicto" . ($conflictCount > 1 ? 's' : '') . ": {$assignment_info}";
    }

    /**
     * Genera reporte de conflictos en formato estruturado para API
     */
    public function getConflictReport()
    {
        $conflicts = $this->detectAllConflicts();

        $summary = [
            'total_conflicts' => count($conflicts),
            'critical_conflicts' => 0,
            'high_conflicts' => 0,
            'medium_conflicts' => 0,
            'conflicts' => [],
        ];

        foreach ($conflicts as $conflict) {
            $summary['conflicts'][] = [
                'assignment_id' => $conflict['assignment']->id,
                'group' => $conflict['assignment']->group->name ?? 'Unknown',
                'day' => $conflict['assignment']->day,
                'time' => $conflict['assignment']->start_time->format('H:i') . '-' . $conflict['assignment']->end_time->format('H:i'),
                'severity' => $this->severityToLabel($conflict['severity']),
                'conflict_count' => count($conflict['conflicts']),
                'message' => $conflict['message'],
            ];

            if ($conflict['severity'] === 3) {
                $summary['critical_conflicts']++;
            } elseif ($conflict['severity'] === 2) {
                $summary['high_conflicts']++;
            } else {
                $summary['medium_conflicts']++;
            }
        }

        return $summary;
    }

    /**
     * Convierte número de severidad a etiqueta
     */
    private function severityToLabel($severity)
    {
        return match($severity) {
            3 => 'CRITICAL',
            2 => 'HIGH',
            1 => 'MEDIUM',
            default => 'UNKNOWN',
        };
    }
}
