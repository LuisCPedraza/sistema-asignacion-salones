<?php

namespace App\Modules\Visualization\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\Teacher; // Este sí está en app/Models
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Visualization\Exports\HorarioExport;

class HorarioController extends Controller
{
    /**
     * HU13: Horario semestral completo (Coordinadores)
     */
    public function semestral(Request $request)
    {
        $assignments = Assignment::with(['group', 'teacher', 'classroom', 'timeSlot'])
            ->when($request->day, function ($query) use ($request) {
                $query->where('day', $request->day);
            })
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('visualization.horario-semestral', compact('assignments'));
    }

    /**
     * HU13: Export horario semestral
     */
    public function exportSemestral()
    {
        return Excel::download(new HorarioExport('semestral'), 'horario-semestral.xlsx');
    }

    /**
     * HU14: Horario personal (Profesores)
     */
    public function personal()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(404, 'No perfil de profesor asociado.');
        }

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['group', 'classroom', 'timeSlot'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('visualization.horario-personal', compact('assignments', 'teacher'));
    }

    /**
     * HU14: Export horario personal
     */
    public function exportPersonal()
    {
        $teacher = Auth::user()->teacher;
        return Excel::download(new HorarioExport('personal', $teacher->id), 'mi-horario.xlsx');
    }
}