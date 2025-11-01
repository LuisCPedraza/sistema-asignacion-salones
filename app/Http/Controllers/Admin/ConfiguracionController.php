<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConfiguracionController extends Controller
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
        $configuraciones = Configuracion::paginate(10);  // Paginación simple
        return view('admin.configuraciones.index', compact('configuraciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.configuraciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Parse 'value' from JSON string to array
        $request['value'] = json_decode($request->input('value'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['value' => 'Valor JSON inválido.']);
        }

        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', 'unique:configuraciones,key'],
            'value' => ['required', 'array'],
            'activo' => ['boolean'],
        ]);

        Configuracion::create($validated);

        return redirect()->route('admin.configuraciones.index')->with('success', 'Configuración creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracion)
    {
        return view('admin.configuraciones.show', compact('configuracion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracion)
    {
        return view('admin.configuraciones.edit', compact('configuracion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        // Parse 'value' from JSON string to array
        $request['value'] = json_decode($request->input('value'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['value' => 'Valor JSON inválido.']);
        }

        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', Rule::unique('configuraciones', 'key')->ignore($configuracion->id)],
            'value' => ['required', 'array'],
            'activo' => ['boolean'],
        ]);

        $configuracion->update($validated);

        return redirect()->route('admin.configuraciones.index')->with('success', 'Configuración actualizada exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Configuracion $configuracion)
    {
        $configuracion->delete();

        return redirect()->route('admin.configuraciones.index')->with('success', 'Configuración eliminada exitosamente.');
    }
}
