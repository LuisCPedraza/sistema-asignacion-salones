<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !($user->hasRole('coordinador') || $user->hasRole('secretaria_coordinacion'))) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador académico.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $careers = Career::orderBy('name')->paginate(15);
        return view('gestion-academica.careers.index', compact('careers'));
    }

    public function create()
    {
        return view('gestion-academica.careers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:careers,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_semesters' => 'required|integer|min:1|max:12',
            'is_active' => 'boolean',
        ]);

        Career::create($validated);

        return redirect()->route('careers.index')
            ->with('success', 'Carrera creada exitosamente');
    }

    public function edit(Career $career)
    {
        return view('gestion-academica.careers.edit', compact('career'));
    }

    public function update(Request $request, Career $career)
    {
        $validated = $request->validate([
            'code' => "required|string|max:50|unique:careers,code,{$career->id}",
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_semesters' => 'required|integer|min:1|max:12',
            'is_active' => 'boolean',
        ]);

        $career->update($validated);

        return redirect()->route('careers.index')
            ->with('success', 'Carrera actualizada exitosamente');
    }

    public function destroy(Career $career)
    {
        // Validar que no tenga semestres asociados
        if ($career->semesters()->count() > 0) {
            return redirect()->route('careers.index')
                ->with('error', 'No se puede eliminar una carrera que tiene semestres asociados');
        }

        $career->delete();

        return redirect()->route('careers.index')
            ->with('success', 'Carrera eliminada exitosamente');
    }
}
