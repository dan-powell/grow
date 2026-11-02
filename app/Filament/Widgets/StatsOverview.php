<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\SubjectAlert;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    #[\Override]
    protected static ?int $sort = 1;

    #[\Override]
    protected ?string $pollingInterval = '60s';

    /**
     * @return array<Stat>
     */
    #[\Override]
    protected function getStats(): array
    {
        $readingsPerHour = $this->readingsPerHour();
        $lateDevices = Device::where('alert_enabled', true)->whereNotNull('alert_activated')->count();
        $activeAlerts = SubjectAlert::where('enabled', true)->whereNotNull('activated')->count();
        $unassignedFigures = Figure::whereDoesntHave('currentAssignment')->count();

        return [
            Stat::make('Readings (24h)', number_format(array_sum($readingsPerHour)))
                ->description(number_format(end($readingsPerHour)).' in the last hour')
                ->chart($readingsPerHour)
                ->color('primary'),
            Stat::make('Devices', Device::enabled()->count().' / '.Device::count())
                ->description($lateDevices ? $lateDevices.' late' : 'All reporting on time')
                ->descriptionIcon($lateDevices ? Heroicon::ExclamationTriangle : Heroicon::CheckCircle)
                ->color($lateDevices ? 'danger' : 'success'),
            Stat::make('Active alerts', (string) $activeAlerts)
                ->description($activeAlerts ? 'Readings outside their alert values' : 'No subject alerts raised')
                ->descriptionIcon($activeAlerts ? Heroicon::BellAlert : Heroicon::CheckCircle)
                ->color($activeAlerts ? 'danger' : 'success'),
            Stat::make('Unassigned figures', (string) $unassignedFigures)
                ->description($unassignedFigures ? 'Readings not attributed to a subject' : 'Every figure has a subject')
                ->color($unassignedFigures ? 'warning' : 'success'),
        ];
    }

    /**
     * Readings received in each of the last 24 hours, oldest first.
     *
     * @return array<int, int>
     */
    private function readingsPerHour(): array
    {
        $start = now()->subHours(23)->startOfHour();
        $counts = array_fill(0, 24, 0);

        Datum::where('created_at', '>=', $start)
            ->pluck('created_at')
            ->each(function ($createdAt) use ($start, &$counts): void {
                $counts[min(23, (int) $start->diffInHours($createdAt))]++;
            });

        return $counts;
    }
}
