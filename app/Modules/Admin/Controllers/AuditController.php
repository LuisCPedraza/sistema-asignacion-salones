<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado. Se requiere rol de administrador.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Filtrar por modelo
        if ($request->filled('model')) {
            $query->where('model', $request->input('model'));
        }

        // Filtrar por acción
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filtrar por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filtrar por fecha (desde)
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->input('from_date') . ' 00:00:00');
        }

        // Filtrar por fecha (hasta)
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->input('to_date') . ' 23:59:59');
        }

        // Buscar por descripción
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Ordenar por fecha más reciente primero
        $logs = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        // Obtener filtros disponibles
        $filters = AuditLog::getAvailableFilters();

        return view('admin.audit.index', compact('logs', 'filters'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('admin.audit.show', compact('auditLog'));
    }
}