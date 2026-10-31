<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enum\Severity;
use App\Jobs\ProcessReading;
use App\Jobs\ProcessReadingDatum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAlert;
use App\Models\Log;
use App\Models\User;
use App\Notifications\DeviceAlertLateReadingResolved;
use App\Notifications\FigureAlertResolved;
use App\Notifications\FigureAlertTriggered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class ProcessReadingTest extends TestCase
{
    use RefreshDatabase;

    private Device $device;

    private Figure $figure;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        User::factory()->create(['receive_alerts' => true, 'email' => 'alerts@example.com']);
        User::factory()->create(['receive_alerts' => false]);

        $this->device = Device::factory()->create(['nickname' => 'greenhouse']);
        $this->figure = Figure::factory()->for($this->device)->create([
            'key' => 'temperature',
            'calibrate' => false,
            'calibrate_value' => null,
        ]);
    }

    public function test_reading_stores_data_for_known_keys_only(): void
    {
        ProcessReading::dispatch('greenhouse', ['temperature' => 21.5, 'unknown' => 3], '2024-06-01 12:00:00');

        $this->assertSame(1, $this->figure->data()->count());
        $this->assertEqualsWithDelta(21.5, $this->figure->data()->first()->value, 0.0001);
        $this->assertDatabaseHas('log', ['name' => 'Reading received', 'severity' => Severity::INFO->value, 'device_id' => $this->device->id]);
        Notification::assertNothingSent();
    }

    public function test_reading_resolves_late_reading_alert(): void
    {
        $this->device->forceFill(['alert_activated' => now()->subHour()])->save();

        ProcessReading::dispatch('greenhouse', ['temperature' => 20], '2024-06-01 12:00:00');

        $this->assertNull($this->device->fresh()->alert_activated);
        $this->assertDatabaseHas('log', ['name' => 'Late Reading Resolved', 'severity' => Severity::SUCCESS->value]);
        Notification::assertSentOnDemandTimes(DeviceAlertLateReadingResolved::class, 1);
        Notification::assertSentOnDemand(
            DeviceAlertLateReadingResolved::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === ['alerts@example.com']
        );
    }

    public function test_upper_alert_triggers_then_resolves(): void
    {
        $alert = FigureAlert::create(['figure_id' => $this->figure->id, 'enabled' => true, 'value' => 30, 'lower' => false, 'email' => true]);

        ProcessReadingDatum::dispatch($this->figure, 35.0, '2024-06-01 12:00:00');

        $this->assertNotNull($alert->fresh()->activated);
        $this->assertSame(1, Log::where('name', 'Alert Triggered')->where('severity', Severity::WARNING->value)->count());
        Notification::assertSentOnDemandTimes(FigureAlertTriggered::class, 1);

        // A second high reading must not re-trigger the alert
        ProcessReadingDatum::dispatch($this->figure, 36.0, '2024-06-01 12:05:00');
        Notification::assertSentOnDemandTimes(FigureAlertTriggered::class, 1);

        ProcessReadingDatum::dispatch($this->figure, 25.0, '2024-06-01 12:10:00');

        $this->assertNull($alert->fresh()->activated);
        $this->assertDatabaseHas('log', ['name' => 'Alert Resolved', 'severity' => Severity::SUCCESS->value]);
        Notification::assertSentOnDemandTimes(FigureAlertResolved::class, 1);
    }

    public function test_lower_alert_triggers_without_email(): void
    {
        $alert = FigureAlert::create(['figure_id' => $this->figure->id, 'enabled' => true, 'value' => 10, 'lower' => true, 'email' => false]);

        ProcessReadingDatum::dispatch($this->figure, 5.0, '2024-06-01 12:00:00');

        $this->assertNotNull($alert->fresh()->activated);
        Notification::assertNothingSent();
    }

    public function test_disabled_alert_is_ignored(): void
    {
        $alert = FigureAlert::create(['figure_id' => $this->figure->id, 'enabled' => false, 'value' => 10, 'lower' => false, 'email' => true]);

        ProcessReadingDatum::dispatch($this->figure, 50.0, '2024-06-01 12:00:00');

        $this->assertNull($alert->fresh()->activated);
        Notification::assertNothingSent();
    }
}
