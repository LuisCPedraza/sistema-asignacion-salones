<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalonController extends Controller
{
    public function __construct()
    {
        // Auth y CheckRole:admin aplicado en routes/web.php
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salones = Salon::paginate(10);  // Paginación simple
        return view('admin.salones.index', compact('salones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.salones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:60', 'unique:salones,codigo'],
            'capacidad' => ['required', 'integer', 'min:1'],
            'ubicacion' => ['required', 'string', 'max:160'],
            'activo' => 'nullable|boolean',  // Cambiado: nullable|boolean for unchecked checkbox ('' → null → false)
        ]);

        // Set default activo true if not set
        $validated['activo'] = $validated['activo'] ?? true;

        // Handle horarios from checkboxes (maps to JSON in 'recursos')
        $validated['horarios'] = $request->horarios ?? [];

        $salon = Salon::create($validated);  // Uses mutator to save horarios in 'recursos' JSON

        return redirect()->route('admin.salones.index')->with('success', 'Salón creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salon $salon)
    {
        return view('admin.salones.show', compact('salon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salon $salon)
    {
        return view('admin.salones.edit', compact('salon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salon $salon)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:60', Rule::unique('salones', 'codigo')->ignore($salon->id)],
            'capacidad' => ['required', 'integer', 'min:1'],
            'ubicacion' => ['required', 'string', 'max:160'],
            'activo' => ['nullable', 'boolean'],  // Cambiado: nullable|boolean for unchecked checkbox
        ]);

        // Preserve current activo if not sent (unchecked checkbox)
        $validated['activo'] = $validated['activo'] ?? $salon->activo;

        // Handle horarios from checkboxes (maps to JSON in 'recursos')
        $salon->horarios = $request->horarios ?? [];  // Uses mutator to update 'recursos' JSON

        $salon->update($validated);  // Updates other fields

        return redirect()->route('admin.salones.index')->with('success', 'Salón actualizado exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Salon $salon)
    {
        $salon->delete();

        return redirect()->route('admin.salones.index')->with('success', 'Salón eliminado exitosamente.');
    }
}