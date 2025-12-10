<?php

namespace App\Modules\Infraestructura\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Maintenance;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('coordinador_infraestructura')) {
                abort(403, 'Acceso denegado.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $activeClassroomsCount = Classroom::where('is_active', true)->count();
        $maintenanceInProgressCount = Maintenance::where('status', 'en_progreso')->count();
        $maintenancePendingCount = Maintenance::where('status', 'pendiente')->count();
        $totalCapacity = Classroom::where('is_active', true)->sum('capacity') ?? 0;

        return view('infraestructura.dashboard', [
            'activeClassroomsCount' => $activeClassroomsCount,
            'maintenanceInProgressCount' => $maintenanceInProgressCount,
            'maintenancePendingCount' => $maintenancePendingCount,
            'totalCapacity' => $totalCapacity,
        ]);
    }
}
