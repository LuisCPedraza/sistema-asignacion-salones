<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Career;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !($user->hasRole('coordinador') || $user->hasRole('secretaria_coordinacion'))) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador académico.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $subjects = Subject::with('career')
            ->orderBy('code')
            ->paginate(15);
        return view('gestion-academica.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $careers = Career::where('is_active', true)->orderBy('name')->get();
        return view('gestion-academica.subjects.create', compact('careers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:subjects,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specialty' => 'nullable|string|max:255',
            'career_id' => 'required|exists:careers,id',
            'credit_hours' => 'required|integer|min:1|max:20',
            'lecture_hours' => 'required|integer|min:0|max:40',
            'lab_hours' => 'required|integer|min:0|max:40',
            'semester_level' => 'required|integer|min:1|max:12',
            'is_active' => 'boolean',
        ]);

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Materia creada exitosamente');
    }

    public function edit(Subject $subject)
    {
        $careers = Career::where('is_active', true)->orderBy('name')->get();
        return view('gestion-academica.subjects.edit', compact('subject', 'careers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => "required|string|max:50|unique:subjects,code,{$subject->id}",
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specialty' => 'nullable|string|max:255',
            'career_id' => 'required|exists:careers,id',
            'credit_hours' => 'required|integer|min:1|max:20',
            'lecture_hours' => 'required|integer|min:0|max:40',
            'lab_hours' => 'required|integer|min:0|max:40',
            'semester_level' => 'required|integer|min:1|max:12',
            'is_active' => 'boolean',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Materia actualizada exitosamente');
    }

    public function destroy(Subject $subject)
    {
        // Validar que no tenga asignaciones asociadas
        if ($subject->courseSchedules()->count() > 0) {
            return redirect()->route('subjects.index')
                ->with('error', 'No se puede eliminar una materia que tiene asignaciones asociadas');
        }

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Materia eliminada exitosamente');
    }
}
