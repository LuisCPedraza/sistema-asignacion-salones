<?php

namespace App\Modules\GestionAcademica\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use Illuminate\Http\Request;

class TeacherAvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Coordinadores pueden gestionar todas las disponibilidades
            // Profesores solo pueden gestionar las suyas propias
            if (!auth()->check()) {
                abort(403, 'Acceso denegado.');
            }
            
            $user = auth()->user();
            if (!$user->hasRole('coordinador') && !$user->hasRole('profesor')) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador o profesor.');
            }
            
            return $next($request);
        });
    }

    // Mostrar disponibilidades de un profesor
    public function index(Teacher $teacher)
    {
        // Verificar permisos: profesor solo puede ver sus propias disponibilidades
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes ver tus propias disponibilidades.');
        }

        $availabilities = $teacher->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();
        
        return view('gestion-academica.availability.index', compact('teacher', 'availabilities'));
    }

    // Mostrar formulario para crear disponibilidad
    public function create(Teacher $teacher)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        return view('gestion-academica.availability.create', compact('teacher'));
    }

    // Almacenar nueva disponibilidad
    public function store(Request $request, Teacher $teacher)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        $validated = $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
            'notes' => 'nullable|string|max:255'
        ]);

        // Validar que no se solapen horarios
        $overlapping = $teacher->availabilities()
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['start_time' => 'El horario se solapa con una disponibilidad existente.']);
        }

        $teacher->availabilities()->create($validated);

        return redirect()->route('gestion-academica.teachers.availabilities.index', $teacher)
            ->with('success', 'Disponibilidad agregada exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit(Teacher $teacher, TeacherAvailability $availability)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        return view('gestion-academica.availability.edit', compact('teacher', 'availability'));
    }

    // Actualizar disponibilidad
    public function update(Request $request, Teacher $teacher, TeacherAvailability $availability)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        $validated = $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
            'notes' => 'nullable|string|max:255'
        ]);

        // Validar solapamientos (excluyendo el registro actual)
        $overlapping = $teacher->availabilities()
            ->where('id', '!=', $availability->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['start_time' => 'El horario se solapa con otra disponibilidad existente.']);
        }

        $availability->update($validated);

        return redirect()->route('gestion-academica.teachers.availabilities.index', $teacher)
            ->with('success', 'Disponibilidad actualizada exitosamente.');
    }

    // Eliminar disponibilidad
    public function destroy(Teacher $teacher, TeacherAvailability $availability)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        $availability->delete();

        return redirect()->route('gestion-academica.teachers.availabilities.index', $teacher)
            ->with('success', 'Disponibilidad eliminada exitosamente.');
    }
}