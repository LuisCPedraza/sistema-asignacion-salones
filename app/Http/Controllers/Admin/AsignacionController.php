<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Grupo;
use App\Models\Salon;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsignacionController extends Controller
{
    public function __construct()
    {
        // Auth y CheckRole:admin aplicado in routes/web.php
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaciones = Asignacion::paginate(10);  // Paginación simple
        return view('admin.asignaciones.index', compact('asignaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupos = Grupo::activo()->get();  // Grupos activos for select
        $salones = Salon::activo()->get();  // Salones activos for select
        $profesores = Profesor::activo()->get();  // Profesores activos for select
        return view('admin.asignaciones.create', compact('grupos', 'salones', 'profesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Parse 'conflictos' from JSON string to array
        $request['conflictos'] = json_decode($request->input('conflictos'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['conflictos' => 'Conflictos JSON inválido.']);
        }

        $validated = $request->validate([
            'grupo_id' => ['required', 'string', 'exists:grupos,id'],
            'salon_id' => ['required', 'string', 'exists:salones,id'],
            'profesor_id' => ['required', 'string', 'exists:profesores,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'estado' => ['required', Rule::in(['propuesta', 'confirmada', 'cancelada'])],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'conflictos' => ['nullable', 'array'],
            'activo' => ['boolean'],
        ]);

        Asignacion::create($validated);

        return redirect()->route('admin.asignaciones.index')->with('success', 'Asignación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asignacion $asignacion)
    {
        return view('admin.asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignacion $asignacion)
    {
        $grupos = Grupo::activo()->get();  // Grupos activos for select
        $salones = Salon::activo()->get();  // Salones activos for select
        $profesores = Profesor::activo()->get();  // Profesores activos for select
        return view('admin.asignaciones.edit', compact('asignacion', 'grupos', 'salones', 'profesores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignacion $asignacion)
    {
        // Parse 'conflictos' from JSON string to array
        $request['conflictos'] = json_decode($request->input('conflictos'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['conflictos' => 'Conflictos JSON inválido.']);
        }

        $validated = $request->validate([
            'grupo_id' => ['required', 'string', 'exists:grupos,id'],
            'salon_id' => ['required', 'string', 'exists:salones,id'],
            'profesor_id' => ['required', 'string', 'exists:profesores,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'estado' => ['required', Rule::in(['propuesta', 'confirmada', 'cancelada'])],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'conflictos' => ['nullable', 'array'],
            'activo' => ['boolean'],
        ]);

        $asignacion->update($validated);

        return redirect()->route('admin.asignaciones.index')->with('success', 'Asignación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();

        return redirect()->route('admin.asignaciones.index')->with('success', 'Asignación eliminada exitosamente.');
    }
}
