<?php

namespace App\Modules\GestionAcademica\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Models\Teacher;
use App\Modules\Asignacion\Models\Assignment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
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

    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Queries base
        $groupsQuery = StudentGroup::query();
        $teachersQuery = Teacher::query();
        $assignmentsQuery = Assignment::with(['teacher', 'group', 'classroom', 'subject']);

        // Aplicar filtros de fecha si existen
        if ($startDate) {
            $groupsQuery->whereDate('created_at', '>=', $startDate);
            $teachersQuery->whereDate('created_at', '>=', $startDate);
            $assignmentsQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $groupsQuery->whereDate('created_at', '<=', $endDate);
            $teachersQuery->whereDate('created_at', '<=', $endDate);
            $assignmentsQuery->whereDate('created_at', '<=', $endDate);
        }

        // Obtener datos recientes
        $recentGroups = (clone $groupsQuery)
            ->with('career')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentTeachers = (clone $teachersQuery)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Calcular métricas
        $metrics = [
            'total_groups' => (clone $groupsQuery)->count(),
            'active_groups' => (clone $groupsQuery)->where('is_active', true)->count(),
            'total_teachers' => (clone $teachersQuery)->count(),
            'active_teachers' => (clone $teachersQuery)->where('is_active', true)->count(),
            'total_assignments' => (clone $assignmentsQuery)->count(),
            'total_students' => (clone $groupsQuery)->sum('student_count'),
        ];

        // Horas de clase totales (asumiendo 2 horas por asignación en promedio)
        $metrics['total_class_hours'] = $metrics['total_assignments'] * 2;

        // Promedio de calidad de asignaciones
        $avgScore = (clone $assignmentsQuery)->avg('score');
        $metrics['avg_quality'] = $avgScore ? round($avgScore * 100, 1) : 0;

        return view('gestion-academica.reports.index', [
            'metrics' => $metrics,
            'recentGroups' => $recentGroups,
            'recentTeachers' => $recentTeachers,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Queries base
        $groupsQuery = StudentGroup::query();
        $teachersQuery = Teacher::query();
        $assignmentsQuery = Assignment::with(['teacher', 'group', 'classroom', 'subject']);

        // Aplicar filtros de fecha
        if ($startDate) {
            $groupsQuery->whereDate('created_at', '>=', $startDate);
            $teachersQuery->whereDate('created_at', '>=', $startDate);
            $assignmentsQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $groupsQuery->whereDate('created_at', '<=', $endDate);
            $teachersQuery->whereDate('created_at', '<=', $endDate);
            $assignmentsQuery->whereDate('created_at', '<=', $endDate);
        }

        // Obtener datos
        $groups = $groupsQuery->with('career')->get();
        $teachers = $teachersQuery->get();

        // Métricas
        $metrics = [
            'total_groups' => $groups->count(),
            'active_groups' => $groups->where('is_active', true)->count(),
            'total_teachers' => $teachers->count(),
            'active_teachers' => $teachers->where('is_active', true)->count(),
            'total_assignments' => $assignmentsQuery->count(),
            'total_students' => $groups->sum('student_count'),
            'total_class_hours' => $assignmentsQuery->count() * 2,
            'avg_quality' => round($assignmentsQuery->avg('score') * 100, 1),
        ];

        $pdf = Pdf::loadView('gestion-academica.reports.pdf', [
            'metrics' => $metrics,
            'groups' => $groups,
            'teachers' => $teachers,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10)
        ->setOption('margin-right', 10);

        $filename = 'reporte_academico_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
}
