<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Grupo;
use App\Models\Salon;
use App\Models\Profesor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsignacionController extends Controller
{
    public function __construct()
    {
        // Auth y CheckRole:admin aplicado en routes/web.php
    }

    /**
     * Display a listing of the resource. (GRILLA DRAG & DROP)
     */
    public function index(Request $request)
    {
        $salones = Salon::where('activo', 1)->orderBy('codigo')->get();

        // Días de la semana (lunes a sábado)
        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

        // Horas del día
        $horas = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

        // Todas las asignaciones activas del semestre (fijas)
        $asignaciones = Asignacion::where('activo', 1)
            ->with(['grupo', 'salon', 'profesor.user'])
            ->get();

        // Todos los grupos (para arrastrar)
        $grupos = Grupo::where('activo', 1)->orderBy('nombre')->get();

        // 👉 PROFESORES (ESTO FALTABA)
        $profesores = Profesor::where('activo', 1)
            ->with('user')
            ->orderBy('id')
            ->get();

        return view('admin.asignaciones.index', compact(
            'salones',
            'grupos',
            'dias',
            'horas',
            'asignaciones',
            'profesores' // 👈 Enviarlo a la vista
        ));
    }

    /**
     * Normaliza hora a formato H:i:s (extract time from datetime if needed)
     */
    private function normalizarHora($hora)
    {
        if (empty($hora)) return '00:00:00';

        // If it's full datetime (Y-m-d H:i:s), extract time
        if (Carbon::hasFormat($hora, 'Y-m-d H:i:s')) {
            $hora = Carbon::parse($hora)->format('H:i:s');
        }

        // If it's H:i, pad to H:i:s
        if (strlen($hora) == 5) {
            $hora .= ':00';
        }

        return $hora;
    }

    /**
     * Calcula hora fin if not provided (+1 hour default)
     */
    private function calcularHoraFin($horaInicio)
    {
        $dt = Carbon::createFromFormat('H:i:s', $horaInicio);
        $dt->addHour();
        return $dt->format('H:i:s');
    }

    // === Resto de métodos (sin cambios) ===
    public function create()
    {
        $grupos = Grupo::where('activo', 1)->get();
        $salones = Salon::where('activo', 1)->get();
        $profesores = Profesor::where('activo', 1)->get();
        return view('admin.asignaciones.create', compact('grupos', 'salones', 'profesores'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Asignación STORE recibido', $request->all());

            $request->validate([
                'grupo_id'    => 'nullable|exists:grupos,id',
                'profesor_id' => 'nullable|exists:profesores,id',
                'salon_id'    => 'required|exists:salones,id',
                'dia_semana'  => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            ]);

            if (!$request->filled('grupo_id') && !$request->filled('profesor_id')) {
                return response()->json(['success' => false, 'message' => 'Falta grupo o profesor'], 422);
            }

            // Chequeo de choque
            $choque = Asignacion::where('salon_id', $request->salon_id)
                ->where('dia_semana', $request->dia_semana)
                ->where('activo', 1)
                ->whereRaw('? < hora_fin AND ? > hora_inicio', [$request->hora_inicio.':00', $request->hora_inicio.':00'])
                ->orWhereRaw('? < hora_fin AND ? > hora_inicio', [$request->hora_fin.':00', $request->hora_fin.':00'])
                ->exists();

            if ($choque) {
                return response()->json(['success' => false, 'message' => 'Salón ocupado'], 422);
            }

            $asignacion = Asignacion::create([
                'grupo_id'    => $request->grupo_id,
                'profesor_id' => $request->profesor_id,
                'salon_id'    => $request->salon_id,
                'dia_semana'  => $request->dia_semana,
                'hora_inicio' => $request->hora_inicio . ':00',
                'hora_fin'    => $request->hora_fin . ':00',
                'estado'      => 'confirmada',
                'activo'      => 1,
            ]);

            $titulo = '';
            if ($request->grupo_id) {
                $grupo = Grupo::find($request->grupo_id);
                $titulo = $grupo?->nombre ?? 'Grupo eliminado';
                if ($request->profesor_id) {
                    $prof = Profesor::find($request->profesor_id);
                    $titulo .= ' - ' . ($prof?->user?->name ?? 'Profesor eliminado');
                }
            } else {
                $prof = Profesor::find($request->profesor_id);
                $titulo = 'Reserva - ' . ($prof?->user?->name ?? 'Profesor eliminado');
            }

            return response()->json([
                'success' => true,
                'asignacion' => [
                    'id' => $asignacion->id,
                    'titulo' => $titulo
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en AsignacionController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function update(Request $request, Asignacion $asignacion)
    {
        // Ya no se permite mover
        return response()->json(['success' => false, 'message' => 'No se permite mover asignaciones.'], 403);
    }

    public function show(Asignacion $asignacion)
    {
        return view('admin.asignaciones.show', compact('asignacion'));
    }

    public function edit(Asignacion $asignacion)
    {
        $grupos = Grupo::where('activo', 1)->get();
        $salones = Salon::where('activo', 1)->get();
        $profesores = Profesor::where('activo', 1)->get();
        return view('admin.asignaciones.edit', compact('asignacion', 'grupos', 'salones', 'profesores'));
    }

    public function destroy(Request $request, Asignacion $asignacion)
    {
        // Soft delete: set activo a false
        $asignacion->update(['activo' => false]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asignación eliminada.'
            ]);
        }

        return redirect()->route('admin.asignaciones.index')->with('success', 'Asignación eliminada.');
    }

    /**
     * Visualización calendario de asignaciones (HU13).
     */
    public function visualizacion(Request $request)
    {
        try {
            // === Normalizar fecha del filtro ===
            $fecha = $request->get('fecha');

            if ($fecha) {
                try {
                    $fecha = Carbon::parse($fecha)->format('Y-m-d');
                } catch (\Exception $e) {
                    $fecha = null;
                }
            }

            // === Filtros ===
            $salon_id    = $request->get('salon_id');
            $grupo_id    = $request->get('grupo_id');
            $profesor_id = $request->get('profesor_id');

            // === Consulta principal ===
            $asignaciones = Asignacion::with(['grupo', 'salon', 'profesor.user'])
                ->where('activo', true)
                ->when($salon_id, fn($q) => $q->where('salon_id', $salon_id))
                ->when($grupo_id, fn($q) => $q->where('grupo_id', $grupo_id))
                ->when($profesor_id, fn($q) => $q->where('profesor_id', $profesor_id))
                ->when($fecha, fn($q) => $q->whereDate('fecha', $fecha))
                ->get();

            // === Convertir a eventos FullCalendar ===
            $events = $asignaciones->map(function ($a) {

                $fecha = $a->fecha ? Carbon::parse($a->fecha)->format('Y-m-d') : null;
                if (!$fecha) return null;

                $inicio = $a->hora ? $a->hora : '08:00:00';
                $fin    = $a->hora_fin ? $a->hora_fin : '09:00:00';

                return [
                    'title' => ($a->grupo->nombre ?? 'Sin grupo') . ' - ' . ($a->salon->codigo ?? 'Sin salón'),
                    'start' => $fecha . 'T' . $inicio,
                    'end'   => $fecha . 'T' . $fin,

                    'extendedProps' => [
                        'grupo'    => $a->grupo->nombre ?? 'Sin grupo',
                        'salon'    => $a->salon->codigo ?? 'Sin salón',
                        'profesor' => optional(optional($a->profesor)->user)->name ?? 'Sin profesor',
                        'estado'   => $a->estado ?? 'desconocido',
                    ],

                    'backgroundColor' => $a->estado === 'confirmada'
                        ? '#10b981'
                        : '#f59e0b',
                ];
            })
            ->filter()
            ->values();

            $salones    = Salon::where('activo', 1)->get();
            $grupos     = Grupo::where('activo', 1)->get();
            $profesores = Profesor::with('user')->where('activo', 1)->get();

            // === Generar recursos para FullCalendar ===
            $resources = $this->buildResources($salones, $grupos, $profesores);

            return view('admin.asignaciones.visualizacion', [
                'events'     => $events,
                'resources'  => $resources,
                'salones'    => $salones,
                'grupos'     => $grupos,
                'profesores' => $profesores
            ]);

        } catch (\Exception $e) {
            \Log::error('ERROR FINAL en visualizacion(): ' . $e->getMessage());
            return response('Error 500: ' . $e->getMessage(), 500);
        }
    }

    private function buildResources($salones, $grupos, $profesores)
    {
        $resources = [];

        foreach ($salones as $salon) {
            $resources[] = [
                'id'   => 'salon-' . $salon->id,
                'title'=> $salon->codigo,
                'type'=> 'salon'
            ];
        }

        // Recurso especial cuando no tiene salón
        $resources[] = [
            'id' => 'sin-salon',
            'title' => 'Sin salón',
            'type' => 'salon'
        ];

        return $resources;
    }

}
