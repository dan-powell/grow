<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Enum\Icons;
use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_figure_icon_sources(): void
    {
        $this->assertSame(asset('storage/custom.png'), Figure::factory()->make(['icon_custom' => 'custom.png'])->icon_src);
        $this->assertSame(asset(Icons::TOMATO->value), Figure::factory()->make(['icon_custom' => null, 'icon' => 'TOMATO'])->icon_src);
        $this->assertSame(asset(Icons::SPROUT->value), Figure::factory()->make(['icon_custom' => null, 'icon' => null])->icon_src);
        $this->assertNull(Figure::factory()->make(['icon_small' => null])->icon_small_src);
        $this->assertSame(asset(Icons::BATTERY->value), Figure::factory()->make(['icon_small' => 'BATTERY'])->icon_small_src);
    }

    public function test_dashboard_scopes(): void
    {
        $device = Device::factory()->create();
        Device::factory()->has(Figure::factory()->state(['dashboard' => false]), 'figures')->create();
        $figure = Figure::factory()->for($device)->create(['dashboard' => true]);

        $this->assertSame([$figure->id], Figure::dashboard()->pluck('id')->all());
        $this->assertSame([$device->id], Device::dashboard()->pluck('id')->all());
    }

    public function test_figure_active_alerts_only_include_activated(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        FigureAlert::create(['figure_id' => $figure->id, 'enabled' => true, 'value' => 1]);
        $active = FigureAlert::create(['figure_id' => $figure->id, 'enabled' => true, 'value' => 2]);
        $active->activated = now();
        $active->save();

        $this->assertSame([$active->id], $figure->active_alerts->pluck('id')->all());
    }
}
