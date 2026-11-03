<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAssignment;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class FigureAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public static function momentProvider(): array
    {
        return [
            'before start' => ['2024-05-31 23:59:59', null],
            'at start' => ['2024-06-01 00:00:00', 'first'],
            'at hand-over' => ['2024-06-10 00:00:00', 'second'],
            'while open' => ['2025-01-01 00:00:00', 'second'],
        ];
    }

    #[DataProvider('momentProvider')]
    public function test_assignment_at_finds_the_assignment_in_effect(string $moment, ?string $expected): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        $assignments = [
            'first' => FigureAssignment::factory()->for($figure)->create(['started_at' => '2024-06-01 00:00:00', 'ended_at' => '2024-06-10 00:00:00']),
            'second' => FigureAssignment::factory()->for($figure)->create(['started_at' => '2024-06-10 00:00:00']),
        ];

        $this->assertSame($expected ? $assignments[$expected]->id : null, $figure->assignmentAt($moment)?->id);
    }

    public function test_assignment_at_compares_other_timezones_in_the_application_timezone(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        $assignment = FigureAssignment::factory()->for($figure)->create(['started_at' => '2024-06-01 12:00:00']);

        // 12:30 in London during BST is 11:30 UTC, before the assignment started
        $this->assertNull($figure->assignmentAt(now()->parse('2024-06-01 12:30:00', 'Europe/London')));
        $this->assertSame($assignment->id, $figure->assignmentAt(now()->parse('2024-06-01 13:30:00', 'Europe/London'))?->id);
    }

    public static function overlapProvider(): array
    {
        return [
            'inside the existing period' => ['2024-06-05 00:00:00', '2024-06-06 00:00:00', true],
            'open-ended after its start' => ['2024-06-09 00:00:00', null, true],
            'ending as the existing one starts' => ['2024-05-01 00:00:00', '2024-06-01 00:00:00', false],
            'starting as the existing one ends' => ['2024-06-10 00:00:00', null, false],
        ];
    }

    #[DataProvider('overlapProvider')]
    public function test_overlaps_existing_detects_overlapping_periods(string $start, ?string $end, bool $overlaps): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        FigureAssignment::factory()->for($figure)->create(['started_at' => '2024-06-01 00:00:00', 'ended_at' => '2024-06-10 00:00:00']);

        $candidate = FigureAssignment::factory()->for($figure)->make(['started_at' => $start, 'ended_at' => $end]);

        $this->assertSame($overlaps, $candidate->overlapsExisting());
    }

    public function test_overlaps_existing_checks_the_subject_across_figures_and_ignores_itself(): void
    {
        $subject = Subject::factory()->create();
        $existing = FigureAssignment::factory()->for($subject)->create(['started_at' => '2024-06-01 00:00:00']);

        $this->assertTrue(FigureAssignment::factory()->for($subject)->make(['started_at' => '2024-07-01 00:00:00'])->overlapsExisting());
        $this->assertFalse($existing->overlapsExisting());
    }
}
