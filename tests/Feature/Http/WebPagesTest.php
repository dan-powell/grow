<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Enum\Severity;
use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\Log;
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
        Figure::factory()->for($device)->create(['dashboard' => true]);
        Figure::factory()->for($device)->create(['dashboard' => false]);

        $this->get(route('dashboard.show'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->has('devices', 1)
                ->where('devices.0.name', 'Greenhouse')
                ->has('figures', 1)
                ->has('figures.0.device')
                ->has('breadcrumbs', 1)
            );
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
