<?php

namespace App\Modules\GestionAcademica\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
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
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes ver tus propias disponibilidades.');
        }

        $availabilities = $teacher->availabilities()->orderBy('day')->orderBy('start_time')->get();
        
        return view('gestion-academica.availability.index', compact('teacher', 'availabilities'));
    }

    // HU14: Mis disponibilidades (para profesores)
    public function myAvailabilities()
    {
        $user = Auth::user();
        
        // Buscar el profesor asociado por teacher_id o user_id
        $teacher = $user->teacher_id 
            ? Teacher::find($user->teacher_id) 
            : Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return redirect()->route('profesor.dashboard')
                ->with('error', 'No se encontró información del profesor asociado a tu usuario.');
        }

        $availabilities = TeacherAvailability::where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('gestion-academica.availability.my-availabilities', compact('teacher', 'availabilities'));
    }

    // Create (existente)
    public function create(Teacher $teacher)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        return view('gestion-academica.availability.create', compact('teacher'));
    }

    // Store (existente, fix: Usa 'day' en validated)
    public function store(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday', // Fix: 'day' no 'day_of_week'
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['start_time'] = $validated['start_time'] . ':00';
        $validated['end_time'] = $validated['end_time'] . ':00';

        \Log::info('Creando teacher availability con datos:', $validated);

        try {
            $availability = $teacher->availabilities()->create($validated);
            \Log::info('Teacher availability creada exitosamente:', $availability->toArray());
        } catch (\Exception $e) {
            \Log::error('Error creando teacher availability: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creando la disponibilidad: ' . $e->getMessage());
        }

        return redirect()
            ->route('gestion-academica.teachers.availabilities.index', $teacher)
            ->with('success', 'Disponibilidad creada exitosamente.');
    }

    // Edit (existente)
    public function edit(Teacher $teacher, TeacherAvailability $availability)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        return view('gestion-academica.availability.edit', compact('teacher', 'availability'));
    }

    // Update (existente)
    public function update(Request $request, Teacher $teacher, TeacherAvailability $availability)
    {
        if (auth()->user()->hasRole('profesor') && $teacher->user_id !== auth()->id()) {
            abort(403, 'Solo puedes gestionar tus propias disponibilidades.');
        }

        $validated = $request->validate([
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday', // Fix: 'day'
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
            'notes' => 'nullable|string|max:255'
        ]);

        $overlapping = $teacher->availabilities()
            ->where('id', '!=', $availability->id)
            ->where('day', $validated['day'])
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

    // Destroy (existente)
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