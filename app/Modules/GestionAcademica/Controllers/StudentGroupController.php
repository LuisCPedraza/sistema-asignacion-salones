<?php

namespace App\Modules\GestionAcademica\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GestionAcademica\Models\StudentGroup;
use Illuminate\Http\Request;

class StudentGroupController extends Controller
{
    public function __construct()
    {
        // Verificación manual del rol - forma correcta
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('coordinador')) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = StudentGroup::with(['semester.career']);

        // Filtro por carrera (a través del semestre)
        if ($careerId = $request->get('career_id')) {
            $query->whereHas('semester', function($q) use ($careerId) {
                $q->where('career_id', $careerId);
            });
        }

        // Filtro por tipo de grupo (A o B)
        if ($groupType = $request->get('group_type')) {
            $query->where('name', 'like', "%Grupo {$groupType}%");
        }

        // Filtro de estado
        if ($request->get('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->get('status') === 'inactive') {
            $query->where('is_active', false);
        }

        // Filtro de jornada (schedule_type)
        if ($scheduleType = $request->get('schedule_type')) {
            $query->where('schedule_type', $scheduleType);
        }

        // Ordenamiento
        switch ($request->get('sort')) {
            case 'level':
                $query->orderBy('level');
                break;
            case 'students':
                $query->orderByDesc('student_count');
                break;
            case 'recent':
                $query->latest();
                break;
            default:
                $query->orderBy('name');
        }

        $groups = $query->paginate(10)->appends($request->query());

        // Estadísticas globales para tarjetas
        $stats = [
            'total' => StudentGroup::count(),
            'active' => StudentGroup::where('is_active', true)->count(),
            'students' => StudentGroup::sum('student_count'),
            'avg' => round(StudentGroup::avg('student_count'), 1),
        ];

        // Obtener lista de carreras para el filtro
        $careers = \App\Models\Career::orderBy('name')->get(['id', 'code', 'name']);

        return view('gestion-academica.student-groups.index', compact('groups', 'stats', 'careers'));
    }

    public function create()
    {
        // Solución temporal - cargar períodos si el modelo existe, sino array vacío
        try {
            $periods = \App\Models\AcademicPeriod::pluck('name', 'id');
        } catch (\Exception $e) {
            $periods = [];
        }
        
        return view('gestion-academica.student-groups.create', compact('periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'student_count' => 'required|integer|min:1|max:500',
            'special_features' => 'nullable|string',
            'academic_period_id' => 'nullable|exists:academic_periods,id',
        ]);

        StudentGroup::create($validated);

        return redirect()->route('gestion-academica.student-groups.index')
            ->with('success', 'Grupo creado exitosamente (HU3).');
    }

    public function show(StudentGroup $studentGroup)
    {
        return view('gestion-academica.student-groups.show', compact('studentGroup'));
    }

    public function edit(StudentGroup $studentGroup)
    {
        // Solución temporal - cargar períodos si el modelo existe, sino array vacío
        try {
            $periods = \App\Models\AcademicPeriod::pluck('name', 'id');
        } catch (\Exception $e) {
            $periods = [];
        }
        
        return view('gestion-academica.student-groups.edit', compact('studentGroup', 'periods'));
    }

    public function update(Request $request, StudentGroup $studentGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'student_count' => 'required|integer|min:1|max:500',
            'special_features' => 'nullable|string',
            'academic_period_id' => 'nullable|exists:academic_periods,id',
            'is_active' => 'boolean',
        ]);

        $studentGroup->update($validated);

        return redirect()->route('gestion-academica.student-groups.index')
            ->with('success', 'Grupo actualizado (HU4).');
    }

    public function destroy(StudentGroup $studentGroup)
    {
        $studentGroup->update(['is_active' => false]);
        return redirect()->route('gestion-academica.student-groups.index')
            ->with('success', 'Grupo desactivado (HU4).');
    }
}