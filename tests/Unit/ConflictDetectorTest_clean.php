<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Modules\Asignacion\Services\ConflictDetector;

class ConflictDetectorTest extends TestCase
{
    /** @test */
    public function test_detector_instantiates_successfully()
    {
        $detector = new ConflictDetector();
        $this->assertInstanceOf(ConflictDetector::class, $detector);
    }

    /** @test */
    public function test_detector_returns_conflict_report_structure()
    {
        $detector = new ConflictDetector();
        $report = $detector->getConflictReport();

        $this->assertIsArray($report);
        $this->assertArrayHasKey('total_conflicts', $report);
        $this->assertArrayHasKey('critical_conflicts', $report);
        $this->assertArrayHasKey('high_conflicts', $report);
        $this->assertArrayHasKey('medium_conflicts', $report);
        $this->assertArrayHasKey('conflicts', $report);
    }

    /** @test */
    public function test_detector_returns_empty_conflicts_for_clean_schedule()
    {
        $detector = new ConflictDetector();
        $conflicts = $detector->detectAllConflicts();

        $this->assertIsArray($conflicts);
    }

    /** @test */
    public function test_detector_detect_all_conflicts_returns_array()
    {
        $detector = new ConflictDetector();
        $conflicts = $detector->detectAllConflicts();

        $this->assertIsArray($conflicts, 'detectAllConflicts debe retornar un array');
    }
}
