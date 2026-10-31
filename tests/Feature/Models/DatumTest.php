<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class DatumTest extends TestCase
{
    use RefreshDatabase;

    private function makeDatum(float $value, array $figureAttributes = []): Datum
    {
        $figure = Figure::factory()
            ->for(Device::factory())
            ->create(array_merge([
                'prefix' => null,
                'suffix' => null,
                'calibrate' => false,
                'calibrate_value' => null,
                'calibrate_percentage' => false,
                'range_min' => 0,
                'range_max' => 100,
                'range_min_color' => null,
                'range_max_color' => null,
            ], $figureAttributes));

        return Datum::factory()->create([
            'figure_id' => $figure->id,
            'value' => $value,
            'timestamp' => '2024-06-01 12:00:00',
        ])->fresh();
    }

    public function test_value_is_unchanged_without_calibration(): void
    {
        $datum = $this->makeDatum(42.5);

        $this->assertEqualsWithDelta(42.5, $datum->value_calibrated, 0.0001);
    }

    public function test_value_is_calibrated_by_fixed_amount(): void
    {
        $datum = $this->makeDatum(20, ['calibrate' => true, 'calibrate_value' => 2.5]);

        $this->assertEqualsWithDelta(22.5, $datum->value_calibrated, 0.0001);
    }

    public function test_value_is_calibrated_by_percentage(): void
    {
        $datum = $this->makeDatum(200, ['calibrate' => true, 'calibrate_value' => 10, 'calibrate_percentage' => true]);

        $this->assertEqualsWithDelta(220, $datum->value_calibrated, 0.0001);
    }

    public function test_value_string_includes_prefix_and_suffix(): void
    {
        $datum = $this->makeDatum(21, ['prefix' => '~', 'suffix' => '℃']);

        $this->assertSame('~21℃', $datum->value_string);
    }

    public function test_range_percentage_is_clamped_and_scaled(): void
    {
        $this->assertEquals(0, $this->makeDatum(-5)->range_percentage);
        $this->assertEquals(100, $this->makeDatum(150)->range_percentage);
        $this->assertEqualsWithDelta(25, $this->makeDatum(25)->range_percentage, 0.0001);
    }

    public function test_range_percentage_is_null_without_a_range(): void
    {
        $datum = $this->makeDatum(25, ['range_min' => null, 'range_max' => null]);

        $this->assertNull($datum->range_percentage);
    }

    public function test_range_color_mixes_min_and_max_colours(): void
    {
        $datum = $this->makeDatum(50, ['range_min_color' => 'rgb(0, 0, 255)', 'range_max_color' => 'rgb(255, 0, 0)']);

        $this->assertSame('rgb(128,0,128)', $datum->range_color);
    }

    public function test_range_color_uses_default_without_colours(): void
    {
        $this->assertSame('rgb(144, 163, 90)', $this->makeDatum(50)->range_color);
    }

    public function test_timestamps_are_converted_to_london_time(): void
    {
        $datum = $this->makeDatum(1);

        $this->assertInstanceOf(Carbon::class, $datum->timestamp);
        $this->assertSame('Europe/London', $datum->timestamp->getTimezone()->getName());
        $this->assertSame('2024-06-01 13:00:00', $datum->timestamp_formatted);
        $this->assertSame('1st Jun 2024', $datum->timestamp_date);
        $this->assertSame('01:00pm', $datum->timestamp_time);
        $this->assertSame('Europe/London', $datum->created_at->getTimezone()->getName());
    }
}
