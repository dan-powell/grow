<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enum\Severity;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\ActiveAlerts;
use App\Filament\Widgets\DeviceStatus;
use App\Filament\Widgets\LatestLog;
use App\Filament\Widgets\StatsOverview;
use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAssignment;
use App\Models\Log;
use App\Models\SubjectAlert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['admin' => true]));
    }

    public function test_dashboard_renders_the_widgets(): void
    {
        $this->get('/admin')
            ->assertOk()
            ->assertSeeLivewire(AccountWidget::class)
            ->assertSeeLivewire(StatsOverview::class)
            ->assertSeeLivewire(DeviceStatus::class)
            ->assertSeeLivewire(ActiveAlerts::class)
            ->assertSeeLivewire(LatestLog::class);
    }

    public function test_stats_count_recent_readings_late_devices_alerts_and_unassigned_figures(): void
    {
        $this->freezeSecond();
        Device::factory()->create(['alert_enabled' => true, 'alert_activated' => now()]);
        Device::factory()->create(['enabled' => false]);
        $figure = Figure::factory()->for(Device::factory())->create();
        Figure::factory()->for(Device::factory())->create();
        $assignment = FigureAssignment::factory()->for($figure)->create();
        SubjectAlert::create(['subject_id' => $assignment->subject_id, 'enabled' => true, 'value' => 10, 'lower' => true])->forceFill(['activated' => now()])->save();
        Datum::factory()->count(3)->create(['figure_id' => $figure->id]);
        Datum::factory()->create(['figure_id' => $figure->id])->forceFill(['created_at' => now()->subDays(2)])->save();

        Livewire::test(StatsOverview::class)
            ->assertSeeInOrder(['Readings (24h)', '3', '3 in the last hour'])
            ->assertSeeInOrder(['Devices', '3 / 4', '1 late'])
            ->assertSeeInOrder(['Active alerts', '1'])
            ->assertSeeInOrder(['Unassigned figures', '1']);
    }

    public function test_device_status_flags_late_devices(): void
    {
        $late = Device::factory()->create(['alert_enabled' => true, 'alert_activated' => now()]);
        $ok = Device::factory()->create(['alert_enabled' => true]);

        Livewire::test(DeviceStatus::class)
            ->assertCanSeeTableRecords([$late, $ok])
            ->assertTableColumnStateSet('status', 'Late', $late)
            ->assertTableColumnStateSet('status', 'OK', $ok);
    }

    public function test_active_alerts_lists_only_raised_alerts(): void
    {
        $subjectId = FigureAssignment::factory()->for(Figure::factory()->for(Device::factory()))->create()->subject_id;
        $raised = SubjectAlert::create(['subject_id' => $subjectId, 'enabled' => true, 'value' => 10, 'lower' => true]);
        $raised->forceFill(['activated' => now()])->save();
        $quiet = SubjectAlert::create(['subject_id' => $subjectId, 'enabled' => true, 'value' => 90, 'lower' => false]);

        Livewire::test(ActiveAlerts::class)
            ->assertCanSeeTableRecords([$raised])
            ->assertCanNotSeeTableRecords([$quiet])
            ->assertTableColumnStateSet('condition', 'Below 10', $raised);
    }

    public function test_latest_log_shows_recent_entries_with_their_device(): void
    {
        $device = Device::factory()->create(['name' => 'Greenhouse']);
        $log = Log::create(['name' => 'Late Reading', 'device_id' => $device->id]);
        $log->forceFill(['severity' => Severity::WARNING->value])->save();

        Livewire::test(LatestLog::class)
            ->assertCanSeeTableRecords([$log])
            ->assertTableColumnStateSet('device.name', 'Greenhouse', $log);
    }
}
