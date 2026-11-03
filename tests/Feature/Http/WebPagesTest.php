<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Enum\Severity;
use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAssignment;
use App\Models\Log;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class WebPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_lists_devices_and_dashboard_figures(): void
    {
        $device = Device::factory()->create(['name' => 'Greenhouse']);
        $figure = Figure::factory()->for($device)->create(['dashboard' => true]);
        Figure::factory()->for($device)->create(['dashboard' => false]);
        FigureAssignment::factory()->for($figure)->for(Subject::factory()->state(['name' => 'Basil']))->create();

        $this->get(route('dashboard.show'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->has('devices', 1)
                ->where('devices.0.name', 'Greenhouse')
                ->has('figures', 1)
                ->has('figures.0.device')
                ->where('figures.0.current_subject.name', 'Basil')
                ->has('breadcrumbs', 1)
            );
    }

    public function test_dashboard_hides_disabled_devices_and_their_figures(): void
    {
        $enabled = Device::factory()->create();
        Figure::factory()->for($enabled)->create(['dashboard' => true]);
        $disabled = Device::factory()->create(['enabled' => false]);
        Figure::factory()->for($disabled)->create(['dashboard' => true]);

        $this->get(route('dashboard.show'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('devices', 1)
                ->where('devices.0.id', $enabled->id)
                ->has('figures', 1)
                ->where('figures.0.device_id', $enabled->id)
            );
    }

    public function test_help_page_renders(): void
    {
        $this->get(route('help.show'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Help')
                ->has('breadcrumbs', 2)
            );
    }

    public function test_disabled_device_pages_return_404(): void
    {
        $device = Device::factory()->create(['enabled' => false]);
        $figure = Figure::factory()->for($device)->create();

        $this->get(route('device.show', $device))->assertNotFound();
        $this->get(route('device.figure.history', [$device, $figure]))->assertNotFound();
    }

    public function test_device_page_shows_device_with_figures(): void
    {
        $device = Device::factory()->has(Figure::factory()->count(2), 'figures')->create();

        $this->get(route('device.show', $device))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Devices/Show')
                ->where('device.id', $device->id)
                ->has('device.figures', 2)
                ->has('breadcrumbs', 2)
            );
    }

    public function test_device_page_includes_late_and_figure_alerts(): void
    {
        $device = Device::factory()->create(['alert_enabled' => true, 'alert_timeout' => 120, 'alert_activated' => now()]);
        $figure = Figure::factory()->for($device)->create();
        $subject = Subject::factory()->create();
        FigureAssignment::factory()->for($figure)->for($subject)->create();
        $subject->alerts()->create(['enabled' => true, 'value' => 30, 'lower' => true])->forceFill(['activated' => now()])->save();

        $this->get(route('device.show', $device))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('device.alerted', true)
                ->where('device.alert_timeout', 120)
                ->has('device.alert_activated')
                ->has('device.figures.0.active_alerts', 1)
                ->where('device.figures.0.active_alerts.0.value', 30)
                ->where('device.figures.0.active_alerts.0.lower', true)
            );
    }

    public function test_history_lists_assignments_in_the_window_and_names_the_page_after_the_subject(): void
    {
        $device = Device::factory()->create();
        $figure = Figure::factory()->for($device)->create();
        FigureAssignment::factory()->for($figure)->create(['started_at' => now()->subDays(90), 'ended_at' => now()->subDays(60)]);
        $previous = FigureAssignment::factory()->for($figure)->create(['started_at' => now()->subDays(60), 'ended_at' => now()->subDays(10)]);
        $current = FigureAssignment::factory()->for($figure)->for(Subject::factory()->state(['name' => 'Basil']))->create(['started_at' => now()->subDays(10)]);

        $this->get(route('device.figure.history', [$device, $figure]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('figure.current_subject.name', 'Basil')
                ->has('assignments', 2)
                ->where('assignments.0.id', $current->id)
                ->where('assignments.0.subject.name', 'Basil')
                ->where('assignments.1.id', $previous->id)
                ->where('breadcrumbs.2.title', 'Basil')
            );
    }

    public function test_unknown_device_returns_404(): void
    {
        $this->get('/devices/01HZZZZZZZZZZZZZZZZZZZZZZZ')->assertNotFound();
    }

    public function test_history_only_includes_last_30_days(): void
    {
        $device = Device::factory()->create();
        $figure = Figure::factory()->for($device)->create();
        $recent = Datum::factory()->create(['figure_id' => $figure->id, 'timestamp' => now()->subDays(2)]);
        Datum::factory()->create(['figure_id' => $figure->id, 'timestamp' => now()->subDays(60)]);

        $this->get(route('device.figure.history', [$device, $figure]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Devices/History')
                ->where('figure.id', $figure->id)
                ->has('readings', 1)
                ->where('readings.0.id', $recent->id)
                ->has('breadcrumbs', 3)
            );
    }

    public function test_log_index_is_paginated_and_filterable(): void
    {
        Log::create(['name' => 'Info entry'])->forceFill(['severity' => Severity::INFO->value])->save();
        Log::create(['name' => 'Warning entry'])->forceFill(['severity' => Severity::WARNING->value])->save();

        $this->get(route('log.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Log/Index')
                ->has('logs.data', 2)
                ->where('severities', array_values(Severity::options()))
            );

        $this->get(route('log.index', ['severity' => Severity::WARNING->value]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('logs.data', 1)
                ->where('logs.data.0.name', 'Warning entry')
            );
    }

    public function test_log_index_rejects_unknown_severity(): void
    {
        $this->get(route('log.index', ['severity' => 'bogus']))->assertForbidden();
    }
}
