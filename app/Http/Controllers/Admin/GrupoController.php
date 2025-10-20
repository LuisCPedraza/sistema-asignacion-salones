<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');  // Protege todos los métodos
        // $this->middleware('role:admin');  // Pendiente para HU2 si no está
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = Grupo::paginate(10);  // Paginación simple
        return view('admin.grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.grupos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120', 'unique:grupos,nombre'],
            'nivel' => ['required', Rule::in(['basico', 'intermedio', 'avanzado'])],
            'num_estudiantes' => ['required', 'integer', 'min:1'],
            'activo' => ['boolean'],
        ]);

        Grupo::create($validated);

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        return view('admin.grupos.show', compact('grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        return view('admin.grupos.edit', compact('grupo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120', Rule::unique('grupos', 'nombre')->ignore($grupo->id)],
            'nivel' => ['required', Rule::in(['basico', 'intermedio', 'avanzado'])],
            'num_estudiantes' => ['required', 'integer', 'min:1'],
            'activo' => ['boolean'],
        ]);

        $grupo->update($validated);

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo eliminado exitosamente.');
    }
}
