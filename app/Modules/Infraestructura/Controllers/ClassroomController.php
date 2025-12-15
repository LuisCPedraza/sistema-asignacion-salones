<?php

namespace App\Modules\Infraestructura\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Building;
use Illuminate\Http\Request;

class ClassroomController extends Controller
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

    public function index(Request $request)
    {
        $query = Classroom::with('building');

        // Filtros
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->integer('building_id'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }
        if ($request->filled('is_active')) {
            $active = $request->string('is_active') === '1';
            $query->where('is_active', $active);
        }
        if ($request->filled('capacity_min')) {
            $query->where('capacity', '>=', (int)$request->input('capacity_min'));
        }
        if ($request->filled('capacity_max')) {
            $query->where('capacity', '<=', (int)$request->input('capacity_max'));
        }
        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%");
            });
        }

        $classrooms = $query->latest()->paginate(10)->withQueryString();
        $buildings = Building::active()->get(['id','name']);
        
        return view('infraestructura.classrooms.index', compact('classrooms','buildings'));
    }

    public function create()
    {
        $buildings = Building::active()->get();
        return view('infraestructura.classrooms.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:classrooms,code',
            'capacity' => 'required|integer|min:1|max:500',
            'building_id' => 'nullable|exists:buildings,id',
            'location' => 'nullable|string|max:200',
            'floor' => 'required|integer|min:0|max:20',
            'wing' => 'nullable|string|max:50',
            'type' => 'required|in:aula,laboratorio,auditorio,sala_reuniones,taller',
            'resources' => 'nullable|array',
            'special_features' => 'nullable|string',
            'restrictions' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['resources'] = $request->resources ?: [];

        Classroom::create($validated);

        return redirect()->route('infraestructura.classrooms.index')
            ->with('success', 'Salón creado exitosamente (HU5).');
    }

    public function show(Classroom $classroom)
    {
        return view('infraestructura.classrooms.show', compact('classroom'));
    }

    public function edit(Classroom $classroom)
    {
        $buildings = Building::active()->get();
        return view('infraestructura.classrooms.edit', compact('classroom', 'buildings'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:classrooms,code,' . $classroom->id,
            'capacity' => 'required|integer|min:1|max:500',
            'building_id' => 'nullable|exists:buildings,id',
            'location' => 'nullable|string|max:200',
            'floor' => 'required|integer|min:0|max:20',
            'wing' => 'nullable|string|max:50',
            'type' => 'required|in:aula,laboratorio,auditorio,sala_reuniones,taller',
            'resources' => 'nullable|array',
            'special_features' => 'nullable|string',
            'restrictions' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['resources'] = $request->resources ?: [];

        $classroom->update($validated);

        return redirect()->route('infraestructura.classrooms.index')
            ->with('success', 'Salón actualizado (HU5).');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->update(['is_active' => false]);
        return redirect()->route('infraestructura.classrooms.index')
            ->with('success', 'Salón desactivado (HU5).');
    }
}