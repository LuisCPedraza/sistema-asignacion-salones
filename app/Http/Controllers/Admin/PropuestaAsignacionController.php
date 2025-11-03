<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropuestaAsignacion;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PropuestaAsignacionController extends Controller
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
        $propuestas = PropuestaAsignacion::paginate(10);  // Paginación simple
        return view('admin.propuestas_asignacion.index', compact('propuestas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asignaciones = Asignacion::activo()->get();  // Asignaciones activas for select
        return view('admin.propuestas_asignacion.create', compact('asignaciones'));
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
            'asignacion_id' => ['required', 'string', 'exists:asignaciones,id', 'unique:propuestas_asignacion,asignacion_id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'conflictos' => ['nullable', 'array'],
            'orden' => ['required', 'integer', 'min:1', 'max:10'],
            'activo' => ['boolean'],
        ]);

        PropuestaAsignacion::create($validated);

        return redirect()->route('admin.propuestas_asignacion.index')->with('success', 'Propuesta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PropuestaAsignacion $propuestaAsignacion)
    {
        return view('admin.propuestas_asignacion.show', compact('propuestaAsignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PropuestaAsignacion $propuestaAsignacion)
    {
        $asignaciones = Asignacion::activo()->get();  // Asignaciones activas for select
        return view('admin.propuestas_asignacion.edit', compact('propuestaAsignacion', 'asignaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PropuestaAsignacion $propuestaAsignacion)
    {
        // Parse 'conflictos' from JSON string to array
        $request['conflictos'] = json_decode($request->input('conflictos'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['conflictos' => 'Conflictos JSON inválido.']);
        }

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'conflictos' => ['nullable', 'array'],
            'orden' => ['required', 'integer', 'min:1', 'max:10'],
            'activo' => ['boolean'],
        ]);

        $propuestaAsignacion->update($validated);

        return redirect()->route('admin.propuestas_asignacion.index')->with('success', 'Propuesta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(PropuestaAsignacion $propuestaAsignacion)
    {
        $propuestaAsignacion->delete();

        return redirect()->route('admin.propuestas_asignacion.index')->with('success', 'Propuesta eliminada exitosamente.');
    }
}
