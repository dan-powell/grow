<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Jobs\ProcessReading;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class ReadingApiTest extends TestCase
{
    use RefreshDatabase;

    private function basicAuth(): array
    {
        $user = User::factory()->create(['email' => 'device@example.com']);

        return ['Authorization' => 'Basic '.base64_encode($user->email.':password')];
    }

    public function test_reading_requires_authentication(): void
    {
        Queue::fake();

        $this->postJson(route('reading.store'), [])->assertUnauthorized();

        Queue::assertNothingPushed();
    }

    public function test_reading_is_validated(): void
    {
        Queue::fake();

        $this->post(route('reading.store'), ['readings' => 'nope'], $this->basicAuth())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nickname', 'timestamp', 'readings']);

        Queue::assertNothingPushed();
    }

    public function test_reading_for_an_unknown_device_is_rejected(): void
    {
        Queue::fake();

        $this->post(route('reading.store'), [
            'nickname' => 'nobody',
            'timestamp' => '2024-06-01 12:00:00',
            'readings' => ['temperature' => 21.5],
        ], $this->basicAuth())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nickname']);

        Queue::assertNothingPushed();
    }

    public function test_non_numeric_reading_is_rejected(): void
    {
        Queue::fake();
        Device::factory()->create(['nickname' => 'greenhouse']);

        $this->post(route('reading.store'), [
            'nickname' => 'greenhouse',
            'timestamp' => '2024-06-01 12:00:00',
            'readings' => ['temperature' => 'hot', 'humidity' => 40],
        ], $this->basicAuth())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['readings.temperature']);

        Queue::assertNothingPushed();
    }

    public function test_valid_reading_is_queued(): void
    {
        Queue::fake();
        Device::factory()->create(['nickname' => 'greenhouse']);

        $this->post(route('reading.store'), [
            'nickname' => 'greenhouse',
            'timestamp' => '2024-06-01 12:00:00',
            'readings' => ['temperature' => 21.5, 'humidity' => null],
        ], $this->basicAuth())
            ->assertOk()
            ->assertExactJson(['status' => 'OK']);

        Queue::assertPushed(ProcessReading::class, 1);
    }
}
