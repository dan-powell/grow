<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Enum\Icons;
use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAssignment;
use App\Models\Subject;
use App\Models\SubjectAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class DeviceAndFigureTest extends TestCase
{
    use RefreshDatabase;

    public function test_figure_last_reading_is_the_most_recent_datum(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        Datum::factory()->create(['figure_id' => $figure->id, 'value' => 1, 'created_at' => now()->subHour()]);
        $latest = Datum::factory()->create(['figure_id' => $figure->id, 'value' => 2, 'created_at' => now()]);

        $this->assertSame($latest->id, $figure->last_reading->id);
    }

    public function test_device_last_reading_comes_from_its_figures(): void
    {
        $device = Device::factory()->create();
        $this->assertNull($device->last_reading);

        $figure = Figure::factory()->for($device)->create();
        $datum = Datum::factory()->create(['figure_id' => $figure->id]);

        $this->assertSame($datum->id, $device->fresh()->last_reading->id);
    }

    public function test_device_alerted_requires_enabled_and_activated(): void
    {
        $device = Device::factory()->create(['alert_enabled' => true]);
        $this->assertFalse($device->alerted);

        $device->alert_activated = now();
        $this->assertTrue($device->alerted);

        $device->alert_enabled = false;
        $this->assertFalse($device->alerted);
    }

    public function test_device_image_src(): void
    {
        $this->assertNull(Device::factory()->make()->image_src);
        $this->assertSame(asset('storage/devices/a.jpg'), Device::factory()->make(['image' => 'devices/a.jpg'])->image_src);
    }

    public function test_figure_icon_without_a_subject_icon(): void
    {
        $this->assertSame(asset('storage/custom.png'), Figure::factory()->make(['icon_custom' => 'custom.png'])->icon_src);
        $this->assertSame(asset(Icons::TOMATO->value), Figure::factory()->make(['icon_custom' => null, 'icon' => 'TOMATO'])->icon_src);
        $this->assertSame(asset(Icons::SPROUT->value), Figure::factory()->make(['icon_custom' => null, 'icon' => null])->icon_src);
        $this->assertNull(Figure::factory()->make(['icon' => 'TOMATO'])->icon_small_src);

        $figure = Figure::factory()->for(Device::factory())->create(['icon' => 'MOISTURE']);
        FigureAssignment::factory()->for($figure)->for(Subject::factory()->state(['icon' => null]))->create();
        $this->assertSame(asset(Icons::MOISTURE->value), $figure->icon_src);
        $this->assertNull($figure->icon_small_src);
    }

    public function test_subject_icon_is_main_with_figure_icon_as_badge(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create(['icon' => 'MOISTURE', 'icon_custom' => null]);
        FigureAssignment::factory()->for($figure)->for(Subject::factory()->state(['icon' => 'BASIL']))->create();

        $this->assertSame(asset(Icons::BASIL->value), $figure->icon_src);
        $this->assertSame(asset(Icons::MOISTURE->value), $figure->icon_small_src);

        $figure->update(['icon' => null]);
        $this->assertSame(asset(Icons::BASIL->value), $figure->icon_src);
        $this->assertNull($figure->icon_small_src);
    }

    public function test_dashboard_scopes(): void
    {
        $device = Device::factory()->create();
        Device::factory()->has(Figure::factory()->state(['dashboard' => false]), 'figures')->create();
        $figure = Figure::factory()->for($device)->create(['dashboard' => true]);

        $this->assertSame([$figure->id], Figure::dashboard()->pluck('id')->all());
        $this->assertSame([$device->id], Device::dashboard()->pluck('id')->all());
    }

    public function test_figure_active_alerts_are_the_current_subjects_activated_alerts(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        $this->assertSame([], $figure->active_alerts->all());

        $previous = Subject::factory()->create();
        FigureAssignment::factory()->for($figure)->for($previous)->ended()->create();
        SubjectAlert::create(['subject_id' => $previous->id, 'enabled' => true, 'value' => 1])->forceFill(['activated' => now()])->save();
        $current = FigureAssignment::factory()->for($figure)->create(['started_at' => now()->subDay()])->subject;
        SubjectAlert::create(['subject_id' => $current->id, 'enabled' => true, 'value' => 1]);
        $active = SubjectAlert::create(['subject_id' => $current->id, 'enabled' => true, 'value' => 2]);
        $active->forceFill(['activated' => now()])->save();

        $this->assertSame([$active->id], $figure->fresh()->active_alerts->pluck('id')->all());
    }

    public function test_figure_display_name_is_the_current_subject_falling_back_to_its_own_name(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create(['name' => 'Moisture probe 2']);
        $this->assertSame('Moisture probe 2', $figure->display_name);

        $figure->assign(Subject::factory()->create(['name' => 'Basil']));

        $this->assertSame('Basil', $figure->display_name);
    }

    public function test_assign_ends_the_figures_and_the_subjects_open_assignments(): void
    {
        $this->freezeSecond();
        $figure = Figure::factory()->for(Device::factory())->create();
        $otherFigure = Figure::factory()->for(Device::factory())->create();
        $subject = Subject::factory()->create();
        $previous = FigureAssignment::factory()->for($figure)->create();
        $subjectsPrevious = FigureAssignment::factory()->for($otherFigure)->for($subject)->create();

        $assignment = $figure->assign($subject);

        $this->assertTrue($previous->fresh()->ended_at->equalTo(now()));
        $this->assertTrue($subjectsPrevious->fresh()->ended_at->equalTo(now()));
        $this->assertTrue($assignment->started_at->equalTo(now()));
        $this->assertNull($assignment->ended_at);
        $this->assertSame($subject->id, $figure->currentSubject->id);
        $this->assertNull($otherFigure->currentSubject);
    }

    public function test_assign_rejects_a_start_before_the_assignment_it_would_end(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        $previous = FigureAssignment::factory()->for($figure)->create(['started_at' => now()->subDay()]);

        try {
            $figure->assign(Subject::factory()->create(), now()->subWeek());
            $this->fail('Expected the assignment to be rejected.');
        } catch (InvalidArgumentException) {
            $this->assertNull($previous->fresh()->ended_at);
            $this->assertSame(1, $figure->assignments()->count());
        }
    }

    public function test_end_assignment_leaves_the_figure_unassigned(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        $assignment = FigureAssignment::factory()->for($figure)->create();

        $figure->endAssignment();

        $this->assertNotNull($assignment->fresh()->ended_at);
        $this->assertNull($figure->currentSubject);
    }
}
