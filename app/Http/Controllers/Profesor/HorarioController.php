<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class HorarioController extends Controller
{
    private function currentTeacher()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        return $user->teacher_id
            ? Teacher::find($user->teacher_id)
            : Teacher::where('user_id', $user->id)->first();
    }

    public function index()
    {
        $teacher = $this->currentTeacher();
        
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        // Obtener todas las asignaciones del profesor organizadas por día y hora
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'classroom'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        // Organizar por día de la semana (siempre presentes)
        $schedule = [
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
        ];

        foreach ($assignments as $assignment) {
            if (isset($schedule[$assignment->day])) {
                $schedule[$assignment->day][] = $assignment;
            }
        }

        // Totales por día y totales semanales
        $dayTotals = [];
        $weeklyTotals = ['classes' => 0, 'hours' => 0];
        foreach ($schedule as $day => $list) {
            $classes = count($list);
            $hours = 0;
            foreach ($list as $a) {
                if ($a->start_time && $a->end_time) {
                    $hours += Carbon::parse($a->start_time)->diffInMinutes(Carbon::parse($a->end_time)) / 60;
                }
            }
            $dayTotals[$day] = [
                'classes' => $classes,
                'hours' => round($hours, 1),
            ];
            $weeklyTotals['classes'] += $classes;
            $weeklyTotals['hours'] += $hours;
        }
        $weeklyTotals['hours'] = round($weeklyTotals['hours'], 1);

        return view('profesor.horario.index', [
            'teacher' => $teacher,
            'schedule' => $schedule,
            'assignments' => $assignments,
            'dayTotals' => $dayTotals,
            'weeklyTotals' => $weeklyTotals,
        ]);
    }

    public function exportPdf()
    {
        $teacher = $this->currentTeacher();
        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor.');
        }

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'classroom'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $schedule = [
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
        ];

        foreach ($assignments as $assignment) {
            if (isset($schedule[$assignment->day])) {
                $schedule[$assignment->day][] = $assignment;
            }
        }

        $dayTotals = [];
        $weeklyTotals = ['classes' => 0, 'hours' => 0];
        foreach ($schedule as $day => $list) {
            $classes = count($list);
            $hours = 0;
            foreach ($list as $a) {
                if ($a->start_time && $a->end_time) {
                    $hours += Carbon::parse($a->start_time)->diffInMinutes(Carbon::parse($a->end_time)) / 60;
                }
            }
            $dayTotals[$day] = [
                'classes' => $classes,
                'hours' => round($hours, 1),
            ];
            $weeklyTotals['classes'] += $classes;
            $weeklyTotals['hours'] += $hours;
        }
        $weeklyTotals['hours'] = round($weeklyTotals['hours'], 1);

        $pdf = Pdf::loadView('profesor.horario.pdf', [
            'teacher' => $teacher,
            'schedule' => $schedule,
            'assignments' => $assignments,
            'generatedAt' => now(),
            'dayTotals' => $dayTotals,
            'weeklyTotals' => $weeklyTotals,
        ])->setPaper('a4', 'landscape');

        $filename = 'Horario_' . str_replace(' ', '_', $teacher->full_name ?? 'Profesor') . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->stream($filename);
    }
}
