<?php

namespace App\Modules\Asignacion\Services;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Models\Teacher;
use App\Models\TimeSlot;
use App\Modules\Asignacion\Models\AssignmentRule;
use Illuminate\Support\Facades\DB;
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
        // Limpiamos asignaciones anteriores (opcional, comenta si no quieres)
        Assignment::truncate();

        $groups = StudentGroup::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->inRandomOrder()->get();
        $classrooms = Classroom::where('is_active', true)->inRandomOrder()->get();
        $timeSlots = TimeSlot::all();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $assignments = [];

        foreach ($groups as $index => $group) {
            // Rotamos profesores y salones para que no se repitan tanto
            $teacher = $teachers->get($index % $teachers->count());
            $classroom = $classrooms->get($index % $classrooms->count());
            $timeSlot = $timeSlots->random();

            // Diferente día según el grupo
            $day = $days[$index %  5]; // 0 a 5 → lunes a sábado

            $assignment = Assignment::create([
                'student_group_id' => $group->id,
                'teacher_id' => $teacher->id,
                'classroom_id' => $classroom->id,
                'time_slot_id' => $timeSlot->id,
                'day' => $day,
                'start_time' => $timeSlot->start_time,
                'end_time' => $timeSlot->end_time,
                'score' => mt_rand(70, 100) / 100, // Score aleatorio entre 0.7 y 1.0
                'assigned_by_algorithm' => true,
                'is_confirmed' => true,
                'notes' => 'Asignado automáticamente - Release 2'
            ]);

            $assignments[] = $assignment;
        }

        return $assignments;
    }

    protected function calcularScore($grupo, $salon, $franja)
    {
        $scoreTotal = 0;

        foreach ($this->reglas as $regla) {
            $metodo = 'regla_' . $regla->slug;
            if (method_exists($this, $metodo)) {
                $puntaje = $this->$metodo($grupo, $salon, $franja);
                $scoreTotal += $puntaje * $regla->weight;
            }
        }

        return $scoreTotal;
    }

    // REGLAS REALES (todas las que están en tu seeder)
    protected function regla_capacidad_optima($grupo, $salon, $franja) {
        $diferencia = $salon->capacity - $grupo->number_of_students;
        if ($diferencia < 0) return -1000;
        if ($diferencia <= 5) return 100;
        if ($diferencia <= 10) return 50;
        return 10;
    }

    protected function regla_equipamiento_necesario($grupo, $salon, $franja) {
        if (!$grupo->special_requirements) return 50;
        $reqs = json_decode($grupo->special_requirements, true) ?? [];
        $tiene = 0;
        foreach ($reqs as $req) {
            if (str_contains(strtolower($salon->resources), $req)) $tiene++;
        }
        return $tiene == count($reqs) ? 200 : -500;
    }

    protected function regla_minimizar_cambios_salon($grupo, $salon, $franja) {
        return 30; // Se implementará mejor con histórico
    }

    protected function regla_preferencias_horarias($grupo, $salon, $franja) {
        $hora = (int) substr($franja->start_time, 0, 2);
        if ($hora >= 17) return 40; // Bonifica nocturna si aplica
        return 20;
    }

    protected function salonDisponible($salon, $franja)
    {
        return $salon->availabilities->contains(function ($avail) use ($franja) {
            $franjaInicio = substr($franja->start_time, 0, 8);
            $franjaFin = substr($franja->end_time, 0, 8);
            $availInicio = $avail->start_time->format('H:i:s');
            $availFin = $avail->end_time->format('H:i:s');

            return $avail->day === $franja->day &&
                $availInicio <= $franjaInicio &&
                $availFin >= $franjaFin;
        });
    }
}