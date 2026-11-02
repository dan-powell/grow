<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class HandleInertiaRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_shares_the_newest_reading_id(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        Datum::factory()->create(['figure_id' => $figure->id]);
        $newest = Datum::factory()->create(['figure_id' => $figure->id]);

        $response = $this->get(route('dashboard.show'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('latest_reading_id', $newest->id));
    }

    public function test_shares_null_reading_id_when_there_are_no_readings(): void
    {
        $response = $this->get(route('dashboard.show'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('latest_reading_id', null));
    }

    public function test_partial_reload_of_reading_id_omits_page_props(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create(['dashboard' => true]);
        $reading = Datum::factory()->create(['figure_id' => $figure->id]);

        $response = $this->get(route('dashboard.show'));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->reloadOnly('latest_reading_id', fn (Assert $reload) => $reload
                    ->where('latest_reading_id', $reading->id)
                    ->missingAll(['devices', 'figures', 'breadcrumbs'])
                )
            );
    }
}
