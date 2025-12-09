<?php

namespace App\Modules\Admin\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class PdfExportService
{
    /**
     * Exportar reporte general a PDF
     */
    public function exportGeneralReport(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('admin.reports.pdf.general', $data);
        return $pdf->download('reporte-general-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar reporte de utilización a PDF
     */
    public function exportUtilizationReport(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('admin.reports.pdf.utilization', $data)
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('reporte-utilizacion-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar reporte de estadísticas a PDF
     */
    public function exportStatisticsReport(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('admin.reports.pdf.statistics', $data)
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('reporte-estadisticas-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar asignaciones a PDF
     */
    public function exportAssignments(Collection $assignments, array $filters = []): \Illuminate\Http\Response
    {
        $data = [
            'assignments' => $assignments,
            'filters' => $filters,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.assignments', $data)
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('asignaciones-' . now()->format('Y-m-d') . '.pdf');
    }
}
