<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Enum\Severity;
use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\User;
use App\Notifications\DeviceAlertLateReading;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class CommandsTest extends TestCase
{
    use RefreshDatabase;

    private function deviceWithReading(\DateTimeInterface $readingAt, array $attributes = []): Device
    {
        $device = Device::factory()->create(array_merge([
            'name' => 'Greenhouse',
            'alert_enabled' => true,
            'alert_timeout' => 60,
            'alert_email' => true,
        ], $attributes));
        $figure = Figure::factory()->for($device)->create();
        Datum::factory()->create(['figure_id' => $figure->id, 'created_at' => $readingAt]);

        return $device;
    }

    public function test_late_reading_raises_alert_once(): void
    {
        Notification::fake();
        $this->freezeSecond();
        User::factory()->create(['receive_alerts' => true]);
        $device = $this->deviceWithReading(now()->subMinutes(150));

        $this->artisan('reading:check')
            ->expectsOutput('Greenhouse () has not had a reading for 2 hours!')
            ->expectsOutput('Alert notification triggered')
            ->doesntExpectOutput('All devices OK')
            ->assertSuccessful();

        $this->assertNotNull($device->fresh()->alert_activated);
        $this->assertDatabaseHas('log', ['name' => 'Late Reading', 'severity' => Severity::WARNING->value, 'device_id' => $device->id]);
        Notification::assertSentOnDemandTimes(DeviceAlertLateReading::class, 1);

        // Already alerted: no further notifications
        $this->artisan('reading:check')->doesntExpectOutput('Alert notification triggered')->assertSuccessful();
        Notification::assertSentOnDemandTimes(DeviceAlertLateReading::class, 1);
    }

    public function test_recent_reading_is_ok(): void
    {
        Notification::fake();
        $device = $this->deviceWithReading(now()->subMinutes(5));

        $this->artisan('reading:check')->expectsOutput('All devices OK')->assertSuccessful();

        $this->assertNull($device->fresh()->alert_activated);
        Notification::assertNothingSent();
    }

    public function test_disabled_alerts_are_skipped(): void
    {
        Notification::fake();
        $this->deviceWithReading(now()->subDays(2), ['alert_enabled' => false]);

        $this->artisan('reading:check')
            ->expectsOutput('Alerts disabled')
            ->expectsOutput('All devices OK')
            ->assertSuccessful();

        Notification::assertNothingSent();
    }

    public function test_test_notifications_sends_for_devices_with_readings(): void
    {
        Notification::fake();
        User::factory()->create();
        $this->deviceWithReading(now()->subMinutes(5));
        Device::factory()->create();

        $this->artisan('test:notifications')->assertSuccessful();

        Notification::assertSentOnDemandTimes(DeviceAlertLateReading::class, 1);
    }

    public function test_user_add_creates_user(): void
    {
        $this->artisan('user:add', ['username' => 'new@example.com', 'password' => 'secret123'])
            ->expectsOutput('User "new@example.com" added')
            ->assertSuccessful();

        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }
}
