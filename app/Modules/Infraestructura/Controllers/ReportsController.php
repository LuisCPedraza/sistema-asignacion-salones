<?php

namespace App\Modules\Infraestructura\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Maintenance;
use App\Modules\Infraestructura\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('coordinador_infraestructura')) {
                abort(403, 'Acceso denegado. Se requiere rol de coordinador de infraestructura.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $reservationQuery = Reservation::with('classroom');
        $maintenanceQuery = Maintenance::with('classroom');

        if ($start) {
            $reservationQuery->whereDate('start_time', '>=', $start);
            $maintenanceQuery->whereDate('start_date', '>=', $start);
        }
        if ($end) {
            $reservationQuery->whereDate('end_time', '<=', $end);
            $maintenanceQuery->whereDate('end_date', '<=', $end);
        }

        $reservations = $reservationQuery->orderBy('start_time', 'desc')->take(10)->get();
        $maintenances = $maintenanceQuery->orderBy('start_date', 'desc')->take(10)->get();

        $metrics = [
            'active_classrooms' => Classroom::where('is_active', true)->count(),
            'reservations_total' => (clone $reservationQuery)->count(),
            'reservations_approved' => (clone $reservationQuery)->approved()->count(),
            'reservations_pending' => (clone $reservationQuery)->pending()->count(),
            'maintenance_in_progress' => (clone $maintenanceQuery)->where('status', 'en_progreso')->count(),
            'maintenance_completed' => (clone $maintenanceQuery)->where('status', 'completado')->count(),
        ];

        $hoursReserved = (clone $reservationQuery)
            ->get()
            ->sum(function ($r) {
                if ($r->start_time && $r->end_time) {
                    return $r->end_time->diffInMinutes($r->start_time) / 60;
                }
                return 0;
            });

        $metrics['hours_reserved'] = round($hoursReserved, 1);

        return view('infraestructura.reports.index', [
            'metrics' => $metrics,
            'reservations' => $reservations,
            'maintenances' => $maintenances,
            'filters' => [
                'start_date' => $start,
                'end_date' => $end,
            ],
        ]);
    }
}
