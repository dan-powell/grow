<?php

namespace App\Console\Commands;

use App\Models\{Device, Reading, User};
use App\Notifications\DeviceAlertLateReading;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLastReading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reading:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks last readings for each device';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ok = true;
        $devices = Device::all();
        foreach ($devices as $device) {
            $this->info('Checking: ' . $device->name);
            // Alert enabled & has a timeout setting?
            if ($device->reading_alert && $device->reading_alert_time) {
                // Get the latest reading
                $latest_reading = Reading::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
                $this->info('Last Reading: ' . $latest_reading->created_at->toDateTimeString());
                // Check if the last reading older than timeout config
                if (isset($latest_reading) && $latest_reading->created_at->lessThan(Carbon::now()->subMinutes($device->reading_alert_time))) {
                    $this->info($device->name . ' (' . ($device->location ?? '') . ') has not had a reading for ' . $latest_reading->created_at->diffInHours(now()) . ' hours');

                    // Check if last alert is set. If it is, then don't send a notification
                    if (!$device->reading_alert_last) {
                        $this->info('Alert notification triggered');
                        // Send notifications to all subscribed users
                        $users = User::where('receive_alerts', true)->get();
                        foreach ($users as $user) {
                            $this->info('Sending alarm notification to ' . $user->email);
                            Notification::route('mail', [$user->email])->notify(new DeviceAlertLateReading($device, $latest_reading));
                        }
                        $device->reading_alert_last = now();
                        $device->save();
                    }
                    $ok = false;
                }
            } else {
                $this->info('Alerts disabled');
            }
        }
        if ($ok) {
            $this->info('All devices OK');
        }
    }
}
