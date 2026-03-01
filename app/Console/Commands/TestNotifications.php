<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\User;
use App\Notifications\DeviceAlertLateReading;
use Database\Factories\ReadingFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    
        $devices = Device::all();
        $user = User::first();

        foreach ($devices as $device) {
            if($device->lastReading) {
                $this->info('Sending alarm notification to '.$user->email);
                Notification::route('mail', [$user->email])->notify(new DeviceAlertLateReading($device, $device->lastReading));
            }
        }
    }
}
