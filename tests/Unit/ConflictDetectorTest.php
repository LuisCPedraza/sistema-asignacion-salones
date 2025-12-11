<?php

namespace Tests\Unit;

use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Asignacion\Services\ConflictDetector;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\Infraestructura\Models\Classroom;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ConflictDetectorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_instantiates_successfully(): void
    {
        $detector = new ConflictDetector();
        $this->assertInstanceOf(ConflictDetector::class, $detector);
    }

    #[Test]
    public function detect_all_conflicts_returns_array_when_empty(): void
    {
        $detector = new ConflictDetector();
        $conflicts = $detector->detectAllConflicts();
        $this->assertIsArray($conflicts);
    }

    #[Test]
    public function it_returns_conflict_report_with_required_keys(): void
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

    #[Test]
    public function detect_conflicts_for_single_assignment(): void
    {
        $teacher = Teacher::factory()->create();
        $group = StudentGroup::factory()->create(['number_of_students' => 30]);
        $classroom = Classroom::factory()->create(['capacity' => 40]);

        $assignment = Assignment::factory()->create([
            'teacher_id' => $teacher->id,
            'student_group_id' => $group->id,
            'classroom_id' => $classroom->id,
            'day' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
        ]);

        $detector = new ConflictDetector();
        $conflicts = $detector->detectConflictsForAssignment($assignment);

        $this->assertIsArray($conflicts);
    }

    #[Test]
    public function detect_all_conflicts_with_multiple_assignments(): void
    {
        $teacher = Teacher::factory()->create();
        $group = StudentGroup::factory()->create(['number_of_students' => 30]);
        $classroom = Classroom::factory()->create(['capacity' => 40]);

        Assignment::factory()->count(3)->create([
            'teacher_id' => $teacher->id,
            'student_group_id' => $group->id,
            'classroom_id' => $classroom->id,
            'day' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
        ]);

        $detector = new ConflictDetector();
        $allConflicts = $detector->detectAllConflicts();

        $this->assertIsArray($allConflicts);
    }
}

