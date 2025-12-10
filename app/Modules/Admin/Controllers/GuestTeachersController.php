<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;

class GuestTeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado. Se requiere rol de administrador.');
            }
            return $next($request);
        });
    }

    /**
     * Mostrar dashboard de profesores invitados
     */
    public function index(Request $request)
    {
        $query = Teacher::where('is_guest', true)
            ->with('user')
            ->orderBy('access_expires_at', 'asc');

        // Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('access_expires_at')
                      ->orWhere('access_expires_at', '>', now());
                });
            } elseif ($request->status === 'expired') {
                $query->where('access_expires_at', '<=', now());
            } elseif ($request->status === 'expiring_soon') {
                $query->whereBetween('access_expires_at', [
                    now(),
                    now()->addDays(7),
                ]);
            }
        }

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhere('email', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        }

        $guestTeachers = $query->paginate(10)->withQueryString();

        // Calcular estadísticas
        $stats = [
            'total' => Teacher::where('is_guest', true)->count(),
            'active' => Teacher::where('is_guest', true)->withValidAccess()->count(),
            'expired' => Teacher::where('is_guest', true)->expiredGuest()->count(),
            'expiring_soon' => Teacher::where('is_guest', true)
                ->whereBetween('access_expires_at', [now(), now()->addDays(7)])
                ->count(),
        ];

        return view('admin.guest-teachers.index', compact('guestTeachers', 'stats'));
    }

    /**
     * Mostrar detalles de un profesor invitado
     */
    public function show(Teacher $teacher)
    {
        if (!$teacher->is_guest) {
            abort(404, 'Profesor no es invitado');
        }

        $teacher->load('user', 'availabilities');

        return view('admin.guest-teachers.show', compact('teacher'));
    }
}
