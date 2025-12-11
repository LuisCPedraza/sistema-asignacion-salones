<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Services\ReportService;
use App\Modules\Admin\Services\PdfExportService;
use App\Models\Career;
use App\Models\Semester;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;
    protected $pdfExportService;

    public function __construct(ReportService $reportService, PdfExportService $pdfExportService)
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado. Se requiere rol de administrador.');
            }
            return $next($request);
        });

        $this->reportService = $reportService;
        $this->pdfExportService = $pdfExportService;
    }

    public function index()
    {
        $stats = $this->reportService->getGeneralStatistics();
        return view('admin.reports.index', compact('stats'));
    }

    public function utilization(Request $request)
    {
        $careers = Career::where('is_active', true)->get();
        $semesters = Semester::where('is_active', true)->get();

        $careerId = $request->get('career_id');
        $semesterId = $request->get('semester_id');

        $classroomUtilization = $this->reportService->getClassroomUtilization($careerId, $semesterId);
        $teacherUtilization = $this->reportService->getTeacherUtilization($careerId, $semesterId);
        $groupStats = $this->reportService->getGroupStatistics($careerId, $semesterId);

        return view('admin.reports.utilization', compact(
            'classroomUtilization',
            'teacherUtilization',
            'groupStats',
            'careers',
            'semesters',
            'careerId',
            'semesterId'
        ));
    }

    public function statistics(Request $request)
    {
        $careers = Career::where('is_active', true)->get();
        $semesters = Semester::where('is_active', true)->get();

        $careerId = $request->get('career_id');
        $semesterId = $request->get('semester_id');

        $generalStats = $this->reportService->getGeneralStatistics();
        $qualityDistribution = $this->reportService->getQualityDistribution($careerId, $semesterId);
        $monthlyTrends = $this->reportService->getMonthlyTrends(6);
        $conflictStats = $this->reportService->getConflictStatistics($careerId, $semesterId);

        return view('admin.reports.statistics', compact(
            'generalStats',
            'qualityDistribution',
            'monthlyTrends',
            'conflictStats',
            'careers',
            'semesters',
            'careerId',
            'semesterId'
        ));
    }

    /**
     * Exportar reporte general a PDF
     */
    public function exportGeneralPdf()
    {
        $data = $this->reportService->getGeneralStatistics();
        return $this->pdfExportService->exportGeneralReport(['data' => $data]);
    }

    /**
     * Exportar reporte de utilización a PDF
     */
    public function exportUtilizationPdf(Request $request)
    {
        $careerId = $request->get('career_id');
        $semesterId = $request->get('semester_id');

        $data = [
            'classroomUtilization' => $this->reportService->getClassroomUtilization($careerId, $semesterId),
            'teacherUtilization' => $this->reportService->getTeacherUtilization($careerId, $semesterId),
            'groupStats' => $this->reportService->getGroupStatistics($careerId, $semesterId),
        ];

        // Agregar nombres de filtros para mostrar en el PDF
        if ($careerId) {
            $career = Career::find($careerId);
            $data['filters']['career_name'] = $career ? $career->name : null;
        }
        if ($semesterId) {
            $semester = Semester::find($semesterId);
            $data['filters']['semester_number'] = $semester ? $semester->number : null;
        }

        return $this->pdfExportService->exportUtilizationReport($data);
    }

    /**
     * Exportar reporte de estadísticas a PDF
     */
    public function exportStatisticsPdf(Request $request)
    {
        $careerId = $request->get('career_id');
        $semesterId = $request->get('semester_id');

        $data = [
            'qualityDistribution' => $this->reportService->getQualityDistribution($careerId, $semesterId),
            'monthlyTrends' => $this->reportService->getMonthlyTrends(6),
            'conflictStats' => $this->reportService->getConflictStatistics($careerId, $semesterId),
        ];

        return $this->pdfExportService->exportStatisticsReport($data);
    }
}