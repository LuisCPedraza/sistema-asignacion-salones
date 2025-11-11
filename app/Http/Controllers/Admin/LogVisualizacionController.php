<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogVisualizacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LogVisualizacionController extends Controller
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
        $logs = LogVisualizacion::paginate(10);  // Paginación simple
        return view('admin.logs_visualizacion.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereIn('rol', ['admin', 'coordinador'])->get();  // Users admin/coordinador for select
        return view('admin.logs_visualizacion.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Parse 'filtro' from JSON string to array
        $request['filtro'] = json_decode($request->input('filtro'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['filtro' => 'Filtros JSON inválido.']);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'string', 'exists:users,id'],
            'filtro' => ['required', 'array'],
            'fecha' => ['required', 'date'],
            'activo' => ['boolean'],
        ]);

        LogVisualizacion::create($validated);

        return redirect()->route('admin.logs_visualizacion.index')->with('success', 'Log creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogVisualizacion $logVisualizacion)
    {
        return view('admin.logs_visualizacion.show', compact('logVisualizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogVisualizacion $logVisualizacion)
    {
        $users = User::whereIn('rol', ['admin', 'coordinador'])->get();  // Users admin/coordinador for select
        return view('admin.logs_visualizacion.edit', compact('logVisualizacion', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogVisualizacion $logVisualizacion)
    {
        // Parse 'filtro' from JSON string to array
        $request['filtro'] = json_decode($request->input('filtro'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['filtro' => 'Filtros JSON inválido.']);
        }

        $validated = $request->validate([
            'filtro' => ['required', 'array'],
            'fecha' => ['required', 'date'],
            'activo' => ['boolean'],
        ]);

        $logVisualizacion->update($validated);

        return redirect()->route('admin.logs_visualizacion.index')->with('success', 'Log actualizado exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(LogVisualizacion $logVisualizacion)
    {
        $logVisualizacion->delete();

        return redirect()->route('admin.logs_visualizacion.index')->with('success', 'Log eliminado exitosamente.');
    }
}
