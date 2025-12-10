<?php

namespace Tests\Unit;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\Semester;
use App\Models\Teacher;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Admin\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportServiceTest extends TestCase
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
     * Test getGeneralStatistics with data
     */
    public function test_get_general_statistics_with_data(): void
    {
        // Create test data
        $semester = Semester::factory()->create();
        
        $group = StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'student_count' => 30
        ]);

        $classroom = Classroom::factory()->create(['capacity' => 40, 'is_active' => true]);
        $teacher = Teacher::factory()->create(['is_active' => true]);

        Assignment::factory(3)->create([
            'student_group_id' => $group->id,
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'score' => 0.9
        ]);

        $stats = $this->reportService->getGeneralStatistics();

        $this->assertEquals(1, $stats['total_groups']);
        $this->assertEquals(1, $stats['total_classrooms']);
        $this->assertEquals(1, $stats['total_teachers']);
        $this->assertEquals(3, $stats['total_assignments']);
        $this->assertGreaterThanOrEqual(0.8, $stats['avg_quality_score']);
    }

    /**
     * Test getClassroomUtilization returns array collection
     */
    public function test_get_classroom_utilization_returns_array(): void
    {
        $utilization = $this->reportService->getClassroomUtilization();

        $this->assertTrue(is_array($utilization) || is_object($utilization));
    }

    /**
     * Test getClassroomUtilization with test data
     */
    public function test_get_classroom_utilization_with_data(): void
    {
        $career = Career::factory()->create();
        $semester = Semester::factory()->create(['career_id' => $career->id]);

        $group = StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'student_count' => 25
        ]);

        $classroom1 = Classroom::factory()->create(['name' => 'Sala A', 'capacity' => 50]);
        $classroom2 = Classroom::factory()->create(['name' => 'Sala B', 'capacity' => 40]);
        $teacher = Teacher::factory()->create();

        // Create assignments
        Assignment::factory(2)->create([
            'student_group_id' => $group->id,
            'classroom_id' => $classroom1->id,
            'teacher_id' => $teacher->id,
            'score' => 0.95
        ]);

        Assignment::factory(1)->create([
            'student_group_id' => $group->id,
            'classroom_id' => $classroom2->id,
            'teacher_id' => $teacher->id,
            'score' => 0.80
        ]);

        $utilization = $this->reportService->getClassroomUtilization($career->id, $semester->id);

        $this->assertTrue(is_array($utilization) || is_object($utilization));
    }

    /**
     * Test getTeacherUtilization returns array
     */
    public function test_get_teacher_utilization_returns_array(): void
    {
        $utilization = $this->reportService->getTeacherUtilization();

        $this->assertTrue(is_array($utilization) || is_object($utilization));
    }

    /**
     * Test getTeacherUtilization with data
     */
    public function test_get_teacher_utilization_with_data(): void
    {
        $career = Career::factory()->create();
        $semester = Semester::factory()->create(['career_id' => $career->id]);

        $group = StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'student_count' => 30
        ]);

        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();

        // Create assignments for teachers
        Assignment::factory(3)->create([
            'student_group_id' => $group->id,
            'teacher_id' => $teacher1->id,
            'classroom_id' => $classroom->id,
            'score' => 0.90
        ]);

        Assignment::factory(2)->create([
            'student_group_id' => $group->id,
            'teacher_id' => $teacher2->id,
            'classroom_id' => $classroom->id,
            'score' => 0.85
        ]);

        $utilization = $this->reportService->getTeacherUtilization($career->id, $semester->id);

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
     * Test getQualityDistribution with assignments
     */
    public function test_get_quality_distribution_with_assignments(): void
    {
        $career = Career::factory()->create();
        $semester = Semester::factory()->create(['career_id' => $career->id]);

        $group = StudentGroup::factory()->create([
            'semester_id' => $semester->id,
            'student_count' => 30
        ]);

        $classroom = Classroom::factory()->create();
        $teacher = Teacher::factory()->create();

        // Create assignments with different quality scores
        Assignment::factory(2)->create([
            'student_group_id' => $group->id,
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'score' => 0.95 // Excellent
        ]);

        Assignment::factory(2)->create([
            'student_group_id' => $group->id,
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'score' => 0.85 // Good
        ]);

        $distribution = $this->reportService->getQualityDistribution($career->id, $semester->id);

        $this->assertIsArray($distribution);
        $this->assertGreaterThan(0, $distribution['excellent']);
        $this->assertGreaterThan(0, $distribution['good']);
    }

    /**
     * Test getMonthlyTrends returns array with months
     */
    public function test_get_monthly_trends_returns_months(): void
    {
        $trends = $this->reportService->getMonthlyTrends(6);

        $this->assertIsArray($trends);
        $this->assertCount(6, $trends);
        
        foreach ($trends as $trend) {
            $this->assertArrayHasKey('month', $trend);
            $this->assertArrayHasKey('assignments', $trend);
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
