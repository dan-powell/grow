<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\User;
use App\Notifications\DeviceAlertLateReading;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Send test notifications')]
#[Signature('test:notifications')]
class TestNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $devices = Device::with('latestDatum')->get();
        $user = User::first();

        foreach ($devices as $device) {
            if ($device->last_reading) {
                $this->info('Sending alarm notification to '.$user->email);
                $user->notify(new DeviceAlertLateReading($device, $device->last_reading));
            }
        }
    }
}
