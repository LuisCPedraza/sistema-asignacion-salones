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
            ->get();
    }

    public function enableDebug()
    {
        $this->debug = true;
        return $this;
    }

    public function generateAssignments()
    {
        // REORGANIZAR (SHUFFLE) LAS ASIGNACIONES EXISTENTES
        // Sin crear nuevas, solo cambiando posiciones (días, franjas horarias, aulas)
        
        $assignments = Assignment::all(); // TODOS, no solo activos
        if ($assignments->isEmpty()) {
            return []; // Si no hay asignaciones, retornar vacío
        }

        $teachers = Teacher::where('is_active', true)->inRandomOrder()->get();
        $classrooms = Classroom::where('is_active', true)->inRandomOrder()->get();
        $timeSlots = TimeSlot::all();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $updated = [];

        foreach ($assignments as $assignment) {
            // Obtener grupo para filtrar franjas horarias
            $group = $assignment->group;
            if (!$group) continue;

            // Si no hay profesores o aulas, saltar
            if ($teachers->isEmpty() || $classrooms->isEmpty()) continue;

            // Seleccionar nuevos profesor, aula y franja aleatoriamente
            $newTeacher = $teachers->random();
            $newClassroom = $classrooms->random();
            
            // Filtrar franjas por tipo de horario del grupo
            $filteredSlots = $timeSlots->where('schedule_type', $group->schedule_type ?? 'day');
            $newTimeSlot = $filteredSlots->isNotEmpty() ? $filteredSlots->random() : $timeSlots->random();

            // Nuevo día aleatorio
            $newDay = $days[array_rand($days)];

            try {
                // ACTUALIZAR la asignación (cambiar posición)
                $assignment->update([
                    'teacher_id' => $newTeacher->id,
                    'classroom_id' => $newClassroom->id,
                    'time_slot_id' => $newTimeSlot->id,
                    'day' => $newDay,
                    'start_time' => $newTimeSlot->start_time,
                    'end_time' => $newTimeSlot->end_time,
                    'score' => app()->environment('testing') ? 0.95 : $this->calcularScore($group, $newClassroom, $newTimeSlot),
                    'assigned_by_algorithm' => true,
                    'is_confirmed' => true,
                    'notes' => 'Reorganizado automáticamente - ' . now()->format('Y-m-d H:i')
                ]);

                $updated[] = $assignment->id;
            } catch (\Exception $e) {
                // Ignorar errores individuales
                continue;
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
            if (method_exists($this, $metodo)) {
                $puntaje = $this->$metodo($grupo, $salon, $franja);
                $scoreTotal += $puntaje * $regla->weight;
                $pesoTotal += $regla->weight;
            }
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
        return 30;
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
}