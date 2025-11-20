<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Salon;
use App\Models\Grupo;
use App\Models\Profesor;
use Illuminate\Http\Request;

class AsignacionVisualizacionController extends Controller
{
    public function index(Request $request)
    {
        try {
            // ===========================
            // FILTROS
            // ===========================
            $query = Asignacion::where('activo', 1)
                ->with(['salon', 'grupo', 'profesor.user']);

            if ($request->filled('salon_id') && $request->salon_id !== 'todos') {
                $query->where('salon_id', $request->salon_id);
            }
            if ($request->filled('grupo_id') && $request->grupo_id !== 'todos') {
                $query->where('grupo_id', $request->grupo_id);
            }
            if ($request->filled('profesor_id') && $request->profesor_id !== 'todos') {
                $query->where('profesor_id', $request->profesor_id);
            }
            if ($request->filled('fecha')) {
                $query->whereDate('fecha', $request->fecha);
            }

            $asignaciones = $query->get();

            // ===========================
            // MAPEAR EVENTOS (con datos reales)
            // ===========================
            $events = $asignaciones->map(function ($a) {
                $grupo = $a->grupo ? $a->grupo->nombre : 'Sin grupo';
                $salon = $a->salon ? $a->salon->codigo : 'Sin salón';
                $profesor = $a->profesor && $a->profesor->user ? $a->profesor->user->name : 'Sin profesor';

                return [
                    'id' => $a->id,
                    'title' => "$grupo - $salon ($profesor)",
                    'start' => $a->fecha . 'T' . ($a->hora ?? '08:00:00'),
                    'end' => $a->fecha . 'T' . ($a->hora_fin ?? '09:00:00'),
                    'backgroundColor' => $a->estado === 'confirmada' ? '#10b981' : '#f59e0b',
                    'borderColor' => '#1f2937',
                    'textColor' => 'white',
                    'extendedProps' => [
                        'grupo' => $grupo,
                        'salon' => $salon,
                        'profesor' => $profesor,
                        'estado' => $a->estado ?? 'propuesta',
                    ],
                ];
            })->filter()->values();

            // ===========================
            // DATOS PARA FILTROS
            // ===========================
            return view('admin.asignaciones.visualizacion', [
                'events' => $events,
                'salones' => Salon::where('activo', 1)->get(),
                'grupos' => Grupo::where('activo', 1)->get(),
                'profesores' => Profesor::with('user')->where('activo', 1)->get(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en visualización: ' . $e->getMessage());
            return "ERROR: " . $e->getMessage();
        }
    }
}


