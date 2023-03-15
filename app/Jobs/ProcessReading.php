<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Datum, Device, User};
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeviceAlertLateReadingResolved;
use App\Jobs\ProcessReadingDatum;

class ProcessReading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $nickname;
    private array $readings;
    private string $timestamp;

    /**
     * Create a new job instance.
     */
    public function __construct(string $nickname, array $readings, string $timestamp)
    {
        $this->nickname = $nickname;
        $this->readings = $readings;
        $this->timestamp = $timestamp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $device = Device::where('nickname', $this->nickname)->with(['figures'])->firstOrFail();
        $users = User::where('receive_alerts', true)->get();

        // Handle device timeout alerts
        if($device->alert_activated) {
            // Alert timeout was activated, but we just recieved a reading so we should resolve it
            foreach ($users as $user) {
                Notification::route('mail', [$user->email])->notify(new DeviceAlertLateReadingResolved($device));
            }
            $device->alert_activated = null;
            $device->save();
        }

        // Handle readings
        foreach ($this->readings as $key => $value) {
            $figure = $device->figures->where('key', $key)->first();
            if ($figure) {
                ProcessReadingDatum::dispatch($figure, $value, $this->timestamp);
            }
        }
    }
}
