<?php

namespace App\Console\Commands;

use App\Models\{Device, Reading, User};
use App\Notifications\DeviceAlertLateReading;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Facades\LogHelper;

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
        $users = User::where('receive_alerts', true)->get();
        foreach ($devices as $device) {
            $this->info('Checking: ' . $device->name);
            // Alert enabled & has a timeout setting?
            if ($device->alert_enabled && $device->alert_timeout) {
                // Get the latest reading
                $reading = $device->last_reading;
                // Check if the last reading older than timeout config
                if (!isset($reading)) {
                    $this->info('No Readings Found');
                } else {
                    if ($reading->created_at->lessThan(Carbon::now()->subMinutes($device->alert_timeout))) {
                        $this->info($device->name . ' (' . ($device->location ?? '') . ') has not had a reading for ' . $reading->created_at->diffInHours(now()) . ' hours!');
                        // Check if alert is activated. If it is, then don't send a notification
                        if (!$device->alert_activated) {
                            $this->info('Alert notification triggered');
                            if ($device->alert_email) {
                                // Send notifications to all subscribed users (If emails are enabled)
                                foreach ($users as $user) {
                                    $this->info('Sending alarm notification to ' . $user->email);
                                    Notification::route('mail', [$user->email])->notify(new DeviceAlertLateReading($device, $reading));
                                }
                            }
                            LogHelper::warning()->name('Late Reading')->summary('Device reading delayed, check batteries.')->device($device)->save();
                            $device->alert_activated = now();
                            $device->save();
                        }
                        $ok = false;
                    }
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
