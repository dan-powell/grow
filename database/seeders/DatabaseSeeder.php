<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\DeviceConfig;
use App\Models\Reading;
use App\Models\ReadingData;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Create a test device using fixed values
        $devices = Device::factory()
            ->name('Test 1')
            ->has(DeviceConfig::factory()->name('Temperature'), 'configs')
            ->has(DeviceConfig::factory()->name('Pressure'), 'configs')
            ->create();


        $devices = Device::factory()
            ->count(rand(1,3))
            ->has(DeviceConfig::factory()->count(rand(1,6)), 'configs')
            ->create();

        foreach($devices as $device) {

            $readings = Reading::factory()
                ->count(rand(10,100))
                ->make();

            $readings = $device->readings()->saveMany($readings);

            foreach($readings as $reading) {
                foreach($device->configs as $config) {
                    $reading->data()->saveMany(
                        ReadingData::factory()
                            ->key($config->key)
                            ->config($config->id)
                            ->count(1)
                            ->make()
                        );
                }
            }
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }


}
