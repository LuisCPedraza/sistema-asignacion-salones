<?php

namespace App\Modules\Infraestructura\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Infraestructura\Models\Maintenance;
use App\Modules\Infraestructura\Models\Classroom;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
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

    /**
     * Mostrar listado de mantenimientos
     */
    public function index(Request $request)
    {
        $query = Maintenance::with('classroom');

        // Filtros
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('responsible', 'like', "%$search%");
            });
        }

        $maintenances = $query->orderBy('created_at', 'desc')->paginate(15);
        $classrooms = Classroom::where('is_active', true)->get();

        return view('infraestructura.maintenance.index', [
            'maintenances' => $maintenances,
            'classrooms' => $classrooms,
            'filters' => $request->only(['classroom_id', 'type', 'status', 'search']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $classrooms = Classroom::where('is_active', true)->orderBy('name')->get();

        return view('infraestructura.maintenance.create', [
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Guardar nuevo mantenimiento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'type' => 'required|in:preventivo,correctivo',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en_progreso,completado,cancelado',
            'scheduled_date' => 'nullable|date_format:Y-m-d',
            'responsible' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        Maintenance::create($validated);

        return redirect()->route('infraestructura.maintenance.index')
            ->with('success', 'Mantenimiento registrado correctamente.');
    }

    /**
     * Mostrar un mantenimiento específico
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->load('classroom');

        return view('infraestructura.maintenance.show', [
            'maintenance' => $maintenance,
        ]);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Maintenance $maintenance)
    {
        $classrooms = Classroom::where('is_active', true)->orderBy('name')->get();

        return view('infraestructura.maintenance.edit', [
            'maintenance' => $maintenance,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Actualizar mantenimiento
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'type' => 'required|in:preventivo,correctivo',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,en_progreso,completado,cancelado',
            'scheduled_date' => 'nullable|date_format:Y-m-d',
            'start_date' => 'nullable|date_format:Y-m-d H:i',
            'end_date' => 'nullable|date_format:Y-m-d H:i',
            'responsible' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance->update($validated);

        return redirect()->route('infraestructura.maintenance.show', $maintenance)
            ->with('success', 'Mantenimiento actualizado correctamente.');
    }

    /**
     * Eliminar mantenimiento
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('infraestructura.maintenance.index')
            ->with('success', 'Mantenimiento eliminado correctamente.');
    }

    /**
     * Cambiar estado a en progreso
     */
    public function markInProgress(Maintenance $maintenance)
    {
        $maintenance->markAsInProgress();

        return redirect()->back()
            ->with('success', 'Mantenimiento marcado como en progreso.');
    }

    /**
     * Cambiar estado a completado
     */
    public function markCompleted(Maintenance $maintenance)
    {
        $maintenance->markAsCompleted();

        return redirect()->back()
            ->with('success', 'Mantenimiento marcado como completado.');
    }
}
