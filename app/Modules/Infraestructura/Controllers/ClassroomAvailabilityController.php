<?php

namespace App\Modules\Infraestructura\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\ClassroomAvailability;
use Illuminate\Http\Request;

class ClassroomAvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('coordinador_infraestructura')) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador de infraestructura.');
            }
            return $next($request);
        });
    }

    public function index(Classroom $classroom)
    {
        $availabilities = $classroom->availabilities()->orderBy('day')->orderBy('start_time')->get();
        return view('infraestructura.availability.index', compact('classroom', 'availabilities'));
    }

    public function create(Classroom $classroom)
    {
        return view('infraestructura.availability.create', compact('classroom'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'required|boolean',
            'availability_type' => 'required|string|in:regular,maintenance,special',
            'notes' => 'nullable|string|max:500',
        ]);

        // Convertir formato de tiempo para consistencia
        $validated['start_time'] = $validated['start_time'] . ':00';
        $validated['end_time'] = $validated['end_time'] . ':00';

        // Debug: verificar datos antes de crear
        \Log::info('Creando classroom availability con datos:', $validated);

        try {
            $availability = $classroom->availabilities()->create($validated);
            \Log::info('Classroom availability creada exitosamente:', $availability->toArray());
        } catch (\Exception $e) {
            \Log::error('Error creando classroom availability: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creando la disponibilidad: ' . $e->getMessage());
        }

        return redirect()
            ->route('infraestructura.classrooms.availabilities.index', $classroom)
            ->with('success', 'Disponibilidad creada exitosamente.');
    }

    public function edit(Classroom $classroom, ClassroomAvailability $availability)
    {
        return view('infraestructura.availability.edit', compact('classroom', 'availability'));
    }

    public function update(Request $request, Classroom $classroom, ClassroomAvailability $availability)
    {
        $validated = $request->validate([
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
            'availability_type' => 'required|in:regular,maintenance,reserved,special_event',
            'notes' => 'nullable|string|max:255'
        ]);

        // Validar solapamientos (excluyendo el registro actual)
        $overlapping = $classroom->availabilities()
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

        return redirect()->route('infraestructura.classrooms.availabilities.index', $classroom)
            ->with('success', 'Disponibilidad actualizada exitosamente.');
    }

    public function destroy(Classroom $classroom, ClassroomAvailability $availability)
    {
        $availability->delete();

        return redirect()->route('infraestructura.classrooms.availabilities.index', $classroom)
            ->with('success', 'Disponibilidad eliminada exitosamente.');
    }
}