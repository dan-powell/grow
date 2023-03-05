<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\DeviceDataconfig;
use App\Models\Reading;
use App\Models\ReadingData;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $devices = Device::factory()
            ->count(rand(1,3))
            ->has(
                DeviceDataconfig::factory()
                    ->count(rand(1,6)),
                'readings'
            )
            ->create();

        foreach($devices as $device) {

            $readings = Reading::factory()
                ->count(rand(100,1000))
                ->make();

            $readings = $device->readings()->saveMany($readings);

            foreach($readings as $reading) {
                foreach($device->dataconfigs as $dataconfig) {
                    $reading->data()->saveMany(
                        ReadingData::factory()
                            ->key($dataconfig->key)
                            ->count(1)
                            ->make()
                        );
                }
            }


        }


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }


}
