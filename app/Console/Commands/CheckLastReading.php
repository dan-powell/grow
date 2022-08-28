<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Models\Reading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeviceAlertLateReading;

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
        foreach($devices as $device) {
            $latest_reading = Reading::where('device_id', $device->id)->orderBy('created_at', 'desc')->first();
            if(isset($latest_reading) && $latest_reading->created_at->lessThan(Carbon::now()->subHours(config('site.alert_device_late')))) {
                $this->info($device->name . ' (' . $device->location . ') has not had a reading for over ' . config('site.alert_device_late') . ' hours');
                if(!isset($device->alerted) || $device->alerted->lessThan(Carbon::now()->subHours(config('site.alert_timeout')))) {
                    $this->info('Sending alarm notification');
                    Notification::route('mail', [config('site.alert_email')])->notify(new DeviceAlertLateReading($device, $latest_reading));
                    $device->alerted = Carbon::now();
                    $device->save();
                }
                $ok = false;
            }
        }
        if ($ok) {
            $this->info('All devices OK');
        }
    }
}
