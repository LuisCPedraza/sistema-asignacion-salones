<?php

namespace App\Modules\GestionAcademica\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('coordinador')) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $teachers = Teacher::with('user')->latest()->paginate(10);
        return view('gestion-academica.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('gestion-academica.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:100',
            'specialties' => 'nullable|string', // CAMBIADO: de 'array' a 'string'
            'curriculum' => 'nullable|string',
            'years_experience' => 'required|integer|min:0|max:50',
            'academic_degree' => 'nullable|string|max:100',
            'availability_notes' => 'nullable|string',
            'weekly_availability' => 'nullable|array',
            'special_assignments' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // CONVERTIR specialties DE STRING A ARRAY
        if ($request->specialties) {
            $validated['specialties'] = array_map('trim', explode(',', $request->specialties));
        } else {
            $validated['specialties'] = [];
        }
        
        $validated['weekly_availability'] = $request->weekly_availability ?: [];

        Teacher::create($validated);

        return redirect()->route('gestion-academica.teachers.index')
            ->with('success', 'Profesor creado exitosamente (HU7).');
    }

    public function show(Teacher $teacher)
    {
        return view('gestion-academica.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('gestion-academica.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:100',
            'specialties' => 'nullable|string', // CAMBIADO: de 'array' a 'string'
            'curriculum' => 'nullable|string',
            'years_experience' => 'required|integer|min:0|max:50',
            'academic_degree' => 'nullable|string|max:100',
            'availability_notes' => 'nullable|string',
            'weekly_availability' => 'nullable|array',
            'special_assignments' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // CONVERTIR specialties DE STRING A ARRAY
        if ($request->specialties) {
            $validated['specialties'] = array_map('trim', explode(',', $request->specialties));
        } else {
            $validated['specialties'] = [];
        }
        
        $validated['weekly_availability'] = $request->weekly_availability ?: [];

        $teacher->update($validated);

        return redirect()->route('gestion-academica.teachers.index')
            ->with('success', 'Profesor actualizado (HU7).');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->update(['is_active' => false]);
        return redirect()->route('gestion-academica.teachers.index')
            ->with('success', 'Profesor desactivado (HU7).');
    }
}