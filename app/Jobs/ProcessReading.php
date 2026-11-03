<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Facades\LogHelper;
use App\Models\Device;
use App\Models\User;
use App\Notifications\DeviceAlertLateReadingResolved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class ProcessReading implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param  array<string, float|int|string|null>  $readings  Values keyed by figure key; null when a sensor has nothing to report
     */
    public function __construct(
        private string $nickname,
        private array $readings,
        private string $timestamp,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $device = Device::where('nickname', $this->nickname)->with(['figures'])->firstOrFail();

        // Handle device timeout alerts
        if ($device->alert_activated) {
            // Alert timeout was activated, but we just received a reading so we should resolve it
            Notification::send(User::receivesAlerts()->get(), new DeviceAlertLateReadingResolved($device));
            LogHelper::success()->name('Late Reading Resolved')->summary('Device delayed reading resolved.')->device($device)->save();
            $device->alert_activated = null;
            $device->save();
        }

        // Handle readings
        foreach ($this->readings as $key => $value) {
            $figure = $device->figures->firstWhere('key', $key);
            if ($figure && $value !== null) {
                ProcessReadingDatum::dispatch($figure, (float) $value, $this->timestamp);
            }
        }

        LogHelper::info()->name('Reading received')->device($device)->save();
    }
}
