<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestriccionAsignacion;
use App\Models\Salon;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestriccionAsignacionController extends Controller
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
        $restricciones = RestriccionAsignacion::paginate(10);  // Paginación simple
        return view('admin.restricciones_asignacion.index', compact('restricciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salones = Salon::activo()->get();  // Salones activos for select
        $profesores = Profesor::activo()->get();  // Profesores activos for select
        return view('admin.restricciones_asignacion.create', compact('salones', 'profesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Map table name for validation (salon → salones, profesor → profesores)
        $table = $request->recurso_type === 'salon' ? 'salones' : 'profesores';

        // Parse 'valor' from JSON string to array
        $request['valor'] = json_decode($request->input('valor'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['valor' => 'Valor JSON inválido.']);
        }

        $validated = $request->validate([
            'recurso_type' => ['required', Rule::in(['salon', 'profesor'])],
            'recurso_id' => ['required', 'string', 'exists:' . $table . ',id'],
            'tipo_restriccion' => ['required', Rule::in(['horario', 'capacidad', 'especial'])],
            'valor' => ['required', 'array'],
            'activo' => ['boolean'],
        ]);

        RestriccionAsignacion::create($validated);

        return redirect()->route('admin.restricciones_asignacion.index')->with('success', 'Restricción creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RestriccionAsignacion $restriccionAsignacion)
    {
        return view('admin.restricciones_asignacion.show', compact('restriccionAsignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RestriccionAsignacion $restriccionAsignacion)
    {
        $salones = Salon::activo()->get();  // Salones activos for select
        $profesores = Profesor::activo()->get();  // Profesores activos for select
        return view('admin.restricciones_asignacion.edit', compact('restriccionAsignacion', 'salones', 'profesores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestriccionAsignacion $restriccionAsignacion)
    {
        // Map table name for validation (salon → salones, profesor → profesores)
        $table = $request->recurso_type === 'salon' ? 'salones' : 'profesores';

        // Parse 'valor' from JSON string to array
        $request['valor'] = json_decode($request->input('valor'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['valor' => 'Valor JSON inválido.']);
        }

        $validated = $request->validate([
            'recurso_type' => ['required', Rule::in(['salon', 'profesor'])],
            'recurso_id' => ['required', 'string', 'exists:' . $table . ',id'],
            'tipo_restriccion' => ['required', Rule::in(['horario', 'capacidad', 'especial'])],
            'valor' => ['required', 'array'],
            'activo' => ['boolean'],
        ]);

        $restriccionAsignacion->update($validated);

        return redirect()->route('admin.restricciones_asignacion.index')->with('success', 'Restricción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(RestriccionAsignacion $restriccionAsignacion)
    {
        $restriccionAsignacion->delete();

        return redirect()->route('admin.restricciones_asignacion.index')->with('success', 'Restricción eliminada exitosamente.');
    }
}
