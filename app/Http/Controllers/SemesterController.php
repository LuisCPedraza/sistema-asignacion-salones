<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Career;
use Illuminate\Http\Request;

class SemesterController extends Controller
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
        $semesters = Semester::with('career')
            ->orderBy('career_id')
            ->orderBy('number')
            ->paginate(15);
        return view('gestion-academica.semesters.index', compact('semesters'));
    }

    public function create()
    {
        $careers = Career::where('is_active', true)->orderBy('name')->get();
        return view('gestion-academica.semesters.create', compact('careers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'career_id' => 'required|exists:careers,id',
            'number' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Validar que no exista un semestre con el mismo número para la carrera
        $exists = Semester::where('career_id', $validated['career_id'])
            ->where('number', $validated['number'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe un semestre con este número para la carrera seleccionada');
        }

        Semester::create($validated);

        return redirect()->route('semesters.index')
            ->with('success', 'Semestre creado exitosamente');
    }

    public function edit(Semester $semester)
    {
        $careers = Career::where('is_active', true)->orderBy('name')->get();
        return view('gestion-academica.semesters.edit', compact('semester', 'careers'));
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'career_id' => 'required|exists:careers,id',
            'number' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Validar que no exista otro semestre con el mismo número para la carrera
        $exists = Semester::where('career_id', $validated['career_id'])
            ->where('number', $validated['number'])
            ->where('id', '!=', $semester->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe un semestre con este número para la carrera seleccionada');
        }

        $semester->update($validated);

        return redirect()->route('semesters.index')
            ->with('success', 'Semestre actualizado exitosamente');
    }

    public function destroy(Semester $semester)
    {
        // Validar que no tenga grupos asociados
        if ($semester->studentGroups()->count() > 0) {
            return redirect()->route('semesters.index')
                ->with('error', 'No se puede eliminar un semestre que tiene grupos de estudiantes asociados');
        }

        $semester->delete();

        return redirect()->route('semesters.index')
            ->with('success', 'Semestre eliminado exitosamente');
    }
}
