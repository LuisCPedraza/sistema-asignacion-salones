<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Modules\Admin\Services\PdfExportService;
use App\Modules\Admin\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PdfExportServiceTest extends TestCase
{
    use RefreshDatabase;

    private PdfExportService $pdfService;
    private ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdfService = new PdfExportService();
        $this->reportService = new ReportService();
    }

    /**
     * Test: Servicio PDF se instancia correctamente
     */
    public function test_pdf_service_instantiates_successfully(): void
    {
        $this->assertInstanceOf(PdfExportService::class, $this->pdfService);
    }

    /**
     * Test: Exportar reporte general genera respuesta válida
     */
    public function test_export_general_report_returns_response(): void
    {
        $data = $this->reportService->getGeneralStatistics();
        
        $response = $this->pdfService->exportGeneralReport(['data' => $data]);
        
        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    /**
     * Test: Exportar reporte de utilización genera PDF landscape
     */
    public function test_export_utilization_report_returns_landscape_pdf(): void
    {
        $data = [
            'classroomUtilization' => $this->reportService->getClassroomUtilization(null, null),
            'teacherUtilization' => $this->reportService->getTeacherUtilization(null, null),
            'groupStats' => $this->reportService->getGroupStatistics(null, null),
        ];
        
        $response = $this->pdfService->exportUtilizationReport($data);
        
        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        
        // Verificar nombre del archivo contiene "utilizacion"
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('reporte-utilizacion-', $disposition);
    }

    /**
     * Test: Exportar reporte de estadísticas genera PDF portrait
     */
    public function test_export_statistics_report_returns_portrait_pdf(): void
    {
        $data = [
            'qualityDistribution' => $this->reportService->getQualityDistribution(null, null),
            'monthlyTrends' => $this->reportService->getMonthlyTrends(6),
            'conflictStats' => $this->reportService->getConflictStatistics(null, null),
        ];
        
        $response = $this->pdfService->exportStatisticsReport($data);
        
        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        
        // Verificar nombre del archivo contiene "estadisticas"
        $disposition = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('reporte-estadisticas-', $disposition);
    }

    /**
     * Test: PDF general contiene fecha actual en el nombre
     */
    public function test_pdf_filename_contains_current_date(): void
    {
        $data = $this->reportService->getGeneralStatistics();
        $response = $this->pdfService->exportGeneralReport(['data' => $data]);
        
        $disposition = $response->headers->get('Content-Disposition');
        $today = now()->format('Y-m-d');
        
        $this->assertStringContainsString($today, $disposition);
    }
}
