<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\User;  // Agregado: for $users in create/edit
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfesorController extends Controller
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
        $profesores = Profesor::paginate(10);  // Paginación simple
        return view('admin.profesores.index', compact('profesores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereIn('rol', ['profesor', 'superadmin'])->get();  // Usuarios for select (rol profesor/superadmin)
        return view('admin.profesores.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => ['required', 'integer', 'exists:users,id', 'unique:profesores,usuario_id'],
            'especialidades' => ['required', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);

        // Handle horarios from checkboxes (maps to JSON in 'recursos')
        $validated['horarios'] = $request->horarios ?? [];

        Profesor::create($validated);  // Uses mutator to save horarios in 'recursos' JSON

        return redirect()->route('admin.profesores.index')->with('success', 'Profesor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profesor $profesor)
    {
        return view('admin.profesores.show', compact('profesor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profesor $profesor)
    {
        $users = User::whereIn('rol', ['profesor', 'superadmin'])->get();  // Usuarios for select (rol profesor/superadmin)
        return view('admin.profesores.edit', compact('profesor', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profesor $profesor)
    {
        $validated = $request->validate([
            'especialidades' => ['required', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);

        // Handle horarios from checkboxes (maps to JSON in 'recursos')
        $profesor->horarios = $request->horarios ?? [];  // Uses mutator to update 'recursos' JSON

        $profesor->update($validated);  // Updates other fields

        return redirect()->route('admin.profesores.index')->with('success', 'Profesor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(Profesor $profesor)
    {
        $profesor->delete();

        return redirect()->route('admin.profesores.index')->with('success', 'Profesor eliminado exitosamente.');
    }
}
