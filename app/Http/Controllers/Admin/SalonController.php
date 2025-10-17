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
        // $this->middleware('auth');  // Protege todos los métodos
        // $this->middleware('role:admin');  // Pendiente para HU2 si no está
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
            'ubicacion' => ['nullable', 'string', 'max:160'],
            'activo' => ['boolean'],
        ]);

        Salon::create($validated);

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
            'ubicacion' => ['nullable', 'string', 'max:160'],
            'activo' => ['boolean'],
        ]);

        $salon->update($validated);

        return redirect()->route('admin.salones.index')->with('success', 'Salón actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salon $salon)
    {
        $salon->delete();

        return redirect()->route('admin.salones.index')->with('success', 'Salón eliminado exitosamente.');
    }
}