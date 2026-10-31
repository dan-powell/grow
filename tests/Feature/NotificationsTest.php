<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAlert;
use App\Notifications\DeviceAlertLateReading;
use App\Notifications\DeviceAlertLateReadingResolved;
use App\Notifications\FigureAlertResolved;
use App\Notifications\FigureAlertTriggered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    private Device $device;

    private Figure $figure;

    private Datum $datum;

    protected function setUp(): void
    {
        parent::setUp();

        $this->device = Device::factory()->create(['name' => 'Greenhouse', 'location' => 'Garden']);
        $this->figure = Figure::factory()->for($this->device)->create([
            'name' => 'Temperature',
            'prefix' => null,
            'suffix' => 'C',
            'calibrate' => false,
        ]);
        $this->datum = Datum::factory()->create(['figure_id' => $this->figure->id, 'value' => 35])->fresh();
    }

    public function test_device_late_reading_mail(): void
    {
        $mail = new DeviceAlertLateReading($this->device, $this->datum)->toMail(new AnonymousNotifiable);

        $this->assertSame('🪫 Device alert - Greenhouse late reading', $mail->subject);
        $this->assertStringStartsWith('Greenhouse has not sent any readings since', $mail->introLines[0]);
        $this->assertContains('Garden', $mail->introLines);
    }

    public function test_device_late_reading_resolved_mail(): void
    {
        $mail = new DeviceAlertLateReadingResolved($this->device)->toMail(new AnonymousNotifiable);

        $this->assertSame('🔋 Device alert resolved - Greenhouse late reading', $mail->subject);
    }

    public function test_figure_alert_triggered_mail(): void
    {
        $alert = FigureAlert::create(['figure_id' => $this->figure->id, 'enabled' => true, 'value' => 30, 'lower' => false]);

        $mail = new FigureAlertTriggered($this->figure, $alert, $this->datum)->toMail(new AnonymousNotifiable);

        $this->assertSame('Alert Triggered - Temperature (Greenhouse)', $mail->subject);
        $this->assertContains('The reading value "35" has exceeded the alert by 5', $mail->introLines);
        $this->assertContains('Reading Value: 35C', $mail->introLines);
    }

    public function test_figure_alert_resolved_mail(): void
    {
        $alert = FigureAlert::create(['figure_id' => $this->figure->id, 'enabled' => true, 'value' => 40, 'lower' => false]);

        $mail = new FigureAlertResolved($this->figure, $alert, $this->datum)->toMail(new AnonymousNotifiable);

        $this->assertSame('Alert resolved - Temperature (Greenhouse)', $mail->subject);
        $this->assertContains('Alert Trigger Value: 40', $mail->introLines);
    }
}
