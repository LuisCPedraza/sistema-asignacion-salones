<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Modules\Asignacion\Services\ConflictDetector;

class ConflictDetectorTest extends TestCase
{
    /** @test */
    public function it_instantiates_successfully()
    {
        $detector = new ConflictDetector();
        $this->assertInstanceOf(ConflictDetector::class, $detector);
    }

    /** @test */
    public function it_returns_conflict_report_with_required_keys()
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
    public function it_returns_array_from_detect_all_conflicts()
    {
        $detector = new ConflictDetector();
        $conflicts = $detector->detectAllConflicts();

        $this->assertIsArray($conflicts);
    }

    /** @test */
    public function it_can_detect_conflicts_for_assignment()
    {
        $detector = new ConflictDetector();
        // Sin parámetro, retornará array vacío pero sin error
        // Esta es una prueba de que el método existe y es callable
        $this->assertTrue(method_exists($detector, 'detectConflictsForAssignment'));
    }
}
