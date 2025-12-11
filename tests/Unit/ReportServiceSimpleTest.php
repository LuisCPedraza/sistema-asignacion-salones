<?php

namespace Tests\Unit;

use App\Modules\Admin\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportServiceSimpleTest extends TestCase
{
    use RefreshDatabase;

    protected ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportService = app(ReportService::class);
    }

    /**
     * Test instantiation of ReportService
     */
    public function test_report_service_instantiates_successfully(): void
    {
        $this->assertInstanceOf(ReportService::class, $this->reportService);
    }

    /**
     * Test getGeneralStatistics returns required keys
     */
    public function test_get_general_statistics_returns_required_keys(): void
    {
        $stats = $this->reportService->getGeneralStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_groups', $stats);
        $this->assertArrayHasKey('total_classrooms', $stats);
        $this->assertArrayHasKey('total_teachers', $stats);
        $this->assertArrayHasKey('total_assignments', $stats);
        $this->assertArrayHasKey('avg_quality_score', $stats);
    }

    /**
     * Test getClassroomUtilization returns collection/array
     */
    public function test_get_classroom_utilization_returns_data(): void
    {
        $utilization = $this->reportService->getClassroomUtilization();

        $this->assertTrue(is_array($utilization) || is_object($utilization));
    }

    /**
     * Test getTeacherUtilization returns collection/array
     */
    public function test_get_teacher_utilization_returns_data(): void
    {
        $utilization = $this->reportService->getTeacherUtilization();

        $this->assertTrue(is_array($utilization) || is_object($utilization));
    }

    /**
     * Test getGroupStatistics returns required keys
     */
    public function test_get_group_statistics_returns_required_keys(): void
    {
        $stats = $this->reportService->getGroupStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_groups', $stats);
        $this->assertArrayHasKey('total_students', $stats);
        $this->assertArrayHasKey('avg_group_size', $stats);
        $this->assertArrayHasKey('groups_with_assignments', $stats);
    }

    /**
     * Test getQualityDistribution returns percentages
     */
    public function test_get_quality_distribution_returns_percentages(): void
    {
        $distribution = $this->reportService->getQualityDistribution();

        $this->assertIsArray($distribution);
        $this->assertArrayHasKey('excellent', $distribution);
        $this->assertArrayHasKey('good', $distribution);
        $this->assertArrayHasKey('fair', $distribution);
        $this->assertArrayHasKey('poor', $distribution);

        // Percentages should sum to 100 or less (if no data)
        $total = array_sum($distribution);
        $this->assertLessThanOrEqual(101, $total);
    }

    /**
     * Test getMonthlyTrends returns array with months
     */
    public function test_get_monthly_trends_returns_array_with_months(): void
    {
        $trends = $this->reportService->getMonthlyTrends(6);

        $this->assertIsArray($trends);
        $this->assertCount(6, $trends);
        
        foreach ($trends as $trend) {
            $this->assertArrayHasKey('month', $trend);
            $this->assertArrayHasKey('assignments', $trend);
            $this->assertIsString($trend['month']);
            $this->assertIsInt($trend['assignments']);
        }
    }

    /**
     * Test getConflictStatistics returns required keys
     */
    public function test_get_conflict_statistics_returns_required_keys(): void
    {
        $stats = $this->reportService->getConflictStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_conflicts', $stats);
        $this->assertArrayHasKey('conflict_percentage', $stats);
    }

    /**
     * Test getComprehensiveReport combines all reports
     */
    public function test_get_comprehensive_report_returns_all_sections(): void
    {
        $report = $this->reportService->getComprehensiveReport();

        $this->assertIsArray($report);
        $this->assertArrayHasKey('general_statistics', $report);
        $this->assertArrayHasKey('classroom_utilization', $report);
        $this->assertArrayHasKey('teacher_utilization', $report);
        $this->assertArrayHasKey('group_statistics', $report);
        $this->assertArrayHasKey('quality_distribution', $report);
        $this->assertArrayHasKey('monthly_trends', $report);
        $this->assertArrayHasKey('conflict_statistics', $report);
    }
}
