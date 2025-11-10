<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialAsignacion;
use App\Models\Asignacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HistorialAsignacionController extends Controller
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
        $historial = HistorialAsignacion::paginate(10);  // Paginación simple
        return view('admin.historial_asignacion.index', compact('historial'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asignaciones = Asignacion::activo()->get();  // Asignaciones activas for select
        $usuarios = User::whereIn('rol', ['admin', 'coordinador'])->get();  // Usuarios admin/coordinador for select
        return view('admin.historial_asignacion.create', compact('asignaciones', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Parse 'cambios' from JSON string to array
        $request['cambios'] = json_decode($request->input('cambios'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['cambios' => 'Cambios JSON inválido.']);
        }

        $validated = $request->validate([
            'asignacion_id' => ['required', 'string', 'exists:asignaciones,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'accion' => ['required', Rule::in(['create', 'update', 'delete'])],
            'cambios' => ['required', 'array'],
            'fecha' => ['required', 'date_format:Y-m-d\TH:i'],  // Fix: date_format for datetime-local
            'activo' => ['boolean'],
        ]);

        HistorialAsignacion::create($validated);

        return redirect()->route('admin.historial_asignacion.index')->with('success', 'Historial registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HistorialAsignacion $historialAsignacion)
    {
        return view('admin.historial_asignacion.show', compact('historialAsignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistorialAsignacion $historialAsignacion)
    {
        $asignaciones = Asignacion::activo()->get();  // Asignaciones activas for select
        $usuarios = User::whereIn('rol', ['admin', 'coordinador'])->get();  // Usuarios admin/coordinador for select
        return view('admin.historial_asignacion.edit', compact('historialAsignacion', 'asignaciones', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistorialAsignacion $historialAsignacion)
    {
        // Parse 'cambios' from JSON string to array
        $request['cambios'] = json_decode($request->input('cambios'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['cambios' => 'Cambios JSON inválido.']);
        }

        $validated = $request->validate([
            'asignacion_id' => ['required', 'string', 'exists:asignaciones,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'accion' => ['required', Rule::in(['create', 'update', 'delete'])],
            'cambios' => ['required', 'array'],
            'fecha' => ['required', 'date_format:Y-m-d\TH:i'],  // Fix: date_format for datetime-local
            'activo' => ['boolean'],
        ]);

        $historialAsignacion->update($validated);

        return redirect()->route('admin.historial_asignacion.index')->with('success', 'Historial actualizado exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(HistorialAsignacion $historialAsignacion)
    {
        $historialAsignacion->delete();

        return redirect()->route('admin.historial_asignacion.index')->with('success', 'Historial eliminado exitosamente.');
    }
}