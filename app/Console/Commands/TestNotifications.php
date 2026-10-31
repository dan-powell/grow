<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\User;
use App\Notifications\DeviceAlertLateReading;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

#[Description('Send test notifications')]
#[Signature('test:notifications')]
class TestNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $devices = Device::all();
        $user = User::first();

        foreach ($devices as $device) {
            if ($device->lastReading) {
                $this->info('Sending alarm notification to '.$user->email);
                Notification::route('mail', [$user->email])->notify(new DeviceAlertLateReading($device, $device->lastReading));
            }
        }
    }
}
