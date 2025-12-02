<?php

namespace App\Modules\Asignacion\Services;

use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Asignacion\Models\AssignmentRule;
use Illuminate\Support\Facades\Log;

class AssignmentAlgorithm
{
    private $rules;
    private $timeSlots;

    public function __construct()
    {
        $this->rules = AssignmentRule::active()->byWeight()->get();
        $this->generateTimeSlots();
        
        Log::info('🎯 ALGORITMO INICIALIZADO', [
            'rules_count' => $this->rules->count(),
            'time_slots_count' => count($this->timeSlots)
        ]);
    }

    /**
     * Generar asignaciones automáticas
     */
    public function generateAssignments($threshold = 0.6): array
    {
        $groups = StudentGroup::active()->get();
        $teachers = Teacher::with('availabilities')->get();
        $classrooms = Classroom::with('availabilities')->get();

        $assignments = [];
        $assignedGroups = [];

        Log::info('📊 DATOS PARA ASIGNACIÓN', [
            'groups' => $groups->count(),
            'teachers' => $teachers->count(),
            'classrooms' => $classrooms->count(),
            'threshold' => $threshold
        ]);

        foreach ($groups as $group) {
            Log::info("🔍 PROCESANDO GRUPO: {$group->name}", [
                'group_id' => $group->id,
                'students' => $group->number_of_students
            ]);

            if (in_array($group->id, $assignedGroups)) {
                continue;
            }

            $bestAssignment = $this->findBestAssignment($group, $teachers, $classrooms, $threshold);

            if ($bestAssignment) {
                $assignments[] = $bestAssignment;
                $assignedGroups[] = $group->id;
                
                Log::info("✅ ASIGNACIÓN CREADA PARA GRUPO: {$group->name}", [
                    'teacher' => $bestAssignment['teacher_name'],
                    'classroom' => $bestAssignment['classroom_name'],
                    'score' => $bestAssignment['score'],
                    'day' => $bestAssignment['day'],
                    'time' => $bestAssignment['start_time'] . ' - ' . $bestAssignment['end_time']
                ]);
            } else {
                Log::warning("❌ NO SE ENCONTRÓ ASIGNACIÓN PARA GRUPO: {$group->name}");
            }
        }

        Log::info('📈 RESULTADO FINAL ALGORITMO', [
            'total_assignments' => count($assignments),
            'assigned_groups' => $assignedGroups
        ]);

        return $assignments;
    }

    /**
     * Encontrar la mejor asignación para un grupo
     */
    private function findBestAssignment($group, $teachers, $classrooms, $threshold): ?array
    {
        $bestScore = 0;
        $bestAssignment = null;

        foreach ($teachers as $teacher) {
            foreach ($classrooms as $classroom) {
                foreach ($this->timeSlots as $timeSlot) {
                    Log::debug("🔄 PROBANDO COMBINACIÓN", [
                        'group' => $group->name,
                        'teacher' => $teacher->first_name,
                        'classroom' => $classroom->name,
                        'timeslot' => $timeSlot
                    ]);

                    $basicCheck = $this->checkBasicAvailability($group, $teacher, $classroom, $timeSlot);
                    
                    if (!$basicCheck['available']) {
                        Log::debug("❌ FALLA DISPONIBILIDAD BÁSICA", $basicCheck['reasons']);
                        continue;
                    }

                    $score = $this->calculateAssignmentScore($group, $teacher, $classroom, $timeSlot);

                    Log::debug("📈 SCORE CALCULADO", ['score' => $score]);

                    if ($score > $bestScore && $score >= $threshold) {
                        $bestScore = $score;
                        $bestAssignment = [
                            'student_group_id' => $group->id,
                            'teacher_id' => $teacher->id,
                            'classroom_id' => $classroom->id,
                            'day' => $timeSlot['day'],
                            'start_time' => $timeSlot['start'],
                            'end_time' => $timeSlot['end'],
                            'score' => $score,
                            'group_name' => $group->name,
                            'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
                            'classroom_name' => $classroom->name,
                            'notes' => "Asignación automática - Score: " . round($score * 100, 2) . "%"
                        ];
                        
                        Log::info("🎯 NUEVA MEJOR ASIGNACIÓN ENCONTRADA", $bestAssignment);
                    }
                }
            }
        }

        return $bestAssignment;
    }

    /**
     * Calcular score de asignación
     */
    private function calculateAssignmentScore($group, $teacher, $classroom, $timeSlot): float
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($this->rules as $rule) {
            $ruleScore = $this->applyRule($rule, $group, $teacher, $classroom, $timeSlot);
            $totalScore += $ruleScore * $rule->weight;
            $totalWeight += $rule->weight;
            
            Log::debug("📊 REGLA APLICADA", [
                'rule' => $rule->parameter,
                'score' => $ruleScore,
                'weight' => $rule->weight
            ]);
        }

        $finalScore = $totalWeight > 0 ? $totalScore / $totalWeight : 0;
        Log::debug("🎯 SCORE FINAL CALCULADO", ['score' => $finalScore]);
        
        return $finalScore;
    }

    /**
     * Aplicar regla específica
     */
    private function applyRule($rule, $group, $teacher, $classroom, $timeSlot): float
    {
        switch ($rule->parameter) {
            case 'capacity':
                return $this->checkCapacity($group, $classroom);
            case 'teacher_availability':
                return $this->checkTeacherAvailability($teacher, $timeSlot);
            case 'classroom_availability':
                return $this->checkClassroomAvailability($classroom, $timeSlot);
            case 'proximity':
                return $this->checkProximity($group, $classroom);
            case 'resources':
                return $this->checkResources($group, $classroom);
            default:
                return 0;
        }
    }

    /**
     * Verificar capacidad del salón
     */
    private function checkCapacity($group, $classroom): float
    {
        $requiredCapacity = $group->number_of_students ?? $group->student_count ?? 0;
        $classroomCapacity = $classroom->capacity;

        Log::debug("📏 VERIFICANDO CAPACIDAD", [
            'group' => $group->name,
            'required' => $requiredCapacity,
            'classroom' => $classroom->name,
            'available' => $classroomCapacity
        ]);

        if ($classroomCapacity >= $requiredCapacity) {
            $utilization = $requiredCapacity / $classroomCapacity;
            $score = $utilization >= 0.7 ? 1.0 : $utilization;
            Log::debug("✅ CAPACIDAD ADECUADA", ['score' => $score]);
            return $score;
        }

        Log::debug("❌ CAPACIDAD INSUFICIENTE");
        return 0;
    }

    /**
     * Verificar disponibilidad del profesor
     */
    private function checkTeacherAvailability($teacher, $timeSlot): float
    {
        foreach ($teacher->availabilities as $availability) {
            $availabilityStart = $this->normalizeTime($availability->start_time);
            $availabilityEnd = $this->normalizeTime($availability->end_time);
            $requiredStart = $this->normalizeTime($timeSlot['start']);
            $requiredEnd = $this->normalizeTime($timeSlot['end']);
            
            Log::debug("👨‍🏫 VERIFICANDO DISPONIBILIDAD PROFESOR", [
                'teacher' => $teacher->first_name,
                'required_day' => $timeSlot['day'],
                'required_time' => $requiredStart . ' - ' . $requiredEnd,
                'available_day' => $availability->day,
                'available_time' => $availabilityStart . ' - ' . $availabilityEnd
            ]);

            if ($availability->day === $timeSlot['day'] &&
                $availabilityStart <= $requiredStart &&
                $availabilityEnd >= $requiredEnd) {
                Log::debug("✅ PROFESOR DISPONIBLE");
                return 1.0;
            }
        }

        Log::debug("❌ PROFESOR NO DISPONIBLE");
        return 0;
    }

    /**
     * Verificar disponibilidad del salón
     */
    private function checkClassroomAvailability($classroom, $timeSlot): float
    {
        foreach ($classroom->availabilities as $availability) {
            $availabilityStart = $this->normalizeTime($availability->start_time);
            $availabilityEnd = $this->normalizeTime($availability->end_time);
            $requiredStart = $this->normalizeTime($timeSlot['start']);
            $requiredEnd = $this->normalizeTime($timeSlot['end']);
            
            Log::debug("🏫 VERIFICANDO DISPONIBILIDAD AULA", [
                'classroom' => $classroom->name,
                'required_day' => $timeSlot['day'],
                'required_time' => $requiredStart . ' - ' . $requiredEnd,
                'available_day' => $availability->day,
                'available_time' => $availabilityStart . ' - ' . $availabilityEnd
            ]);

            if ($availability->day === $timeSlot['day'] &&
                $availabilityStart <= $requiredStart &&
                $availabilityEnd >= $requiredEnd) {
                Log::debug("✅ AULA DISPONIBLE");
                return 1.0;
            }
        }

        Log::debug("❌ AULA NO DISPONIBLE");
        return 0;
    }

    /**
     * Normalizar formato de tiempo
     */
    private function normalizeTime($time): string
    {
        if ($time instanceof \DateTime) {
            return $time->format('H:i:s');
        }
        
        $time = (string) $time;
        if (strlen($time) === 5) {
            return $time . ':00';
        }
        
        return $time;
    }

    /**
     * Verificar proximidad
     */
    private function checkProximity($group, $classroom): float
    {
        return 0.8; // Valor por defecto para pruebas
    }

    /**
     * Verificar recursos requeridos
     */
    private function checkResources($group, $classroom): float
    {
        if (!trim((string) $group->special_features)) {
            return 1.0;
        }

        $requiredResources = array_filter(
            array_map('trim', explode(',', $group->special_features))
        );

        $availableResources = $classroom->resources_array ?? [];

        $matched = 0;
        foreach ($requiredResources as $resource) {
            if (in_array($resource, $availableResources)) {
                $matched++;
            }
        }

        $score = count($requiredResources) > 0
            ? $matched / count($requiredResources)
            : 1.0;

        Log::debug("🔧 VERIFICANDO RECURSOS", [
            'required'  => $requiredResources,
            'available' => $availableResources,
            'score'     => $score
        ]);

        return $score;
    }

    /**
     * Verificar disponibilidad básica
     */
    private function checkBasicAvailability($group, $teacher, $classroom, $timeSlot): array
    {
        $teacherAvailable = $this->checkTeacherAvailability($teacher, $timeSlot) > 0;
        $classroomAvailable = $this->checkClassroomAvailability($classroom, $timeSlot) > 0;
        $capacityOk = $this->checkCapacity($group, $classroom) > 0;

        $result = [
            'available' => $teacherAvailable && $classroomAvailable && $capacityOk,
            'reasons' => [
                'teacher' => $teacherAvailable ? '✅' : '❌',
                'classroom' => $classroomAvailable ? '✅' : '❌', 
                'capacity' => $capacityOk ? '✅' : '❌'
            ]
        ];

        Log::debug("🔍 DISPONIBILIDAD BÁSICA", $result);
        return $result;
    }

    /**
     * Generar slots de tiempo
     */
    private function generateTimeSlots(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $this->timeSlots = [];

        foreach ($days as $day) {
            // Jornada diurna: 8:00 - 17:00 (bloques de 2 horas)
            for ($hour = 8; $hour <= 16; $hour += 2) {
                $this->timeSlots[] = [
                    'day' => $day,
                    'start' => sprintf('%02d:00:00', $hour),
                    'end' => sprintf('%02d:00:00', $hour + 2)
                ];
            }

            // Jornada nocturna: 17:00 - 21:00 (bloques de 2 horas)
            for ($hour = 17; $hour <= 20; $hour += 2) {
                $this->timeSlots[] = [
                    'day' => $day,
                    'start' => sprintf('%02d:00:00', $hour),
                    'end' => sprintf('%02d:00:00', $hour + 2)
                ];
            }
        }

        Log::debug("🕐 TIME SLOTS GENERADOS", [
            'total_slots' => count($this->timeSlots),
            'sample_slots' => array_slice($this->timeSlots, 0, 3)
        ]);
    }

    /**
     * Obtener reglas activas (nuevo método)
     */
    public function getActiveRules()
    {
        return AssignmentRule::active()->orderBy('weight', 'desc')->get();
    }
}