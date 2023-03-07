<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Figure;
use App\Models\Datum;
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
        Device::factory()
            ->name('Test 1')
            ->has(Figure::factory()->name('Temperature'), 'figures')
            ->has(Figure::factory()->name('Pressure'), 'figures')
            ->create();


        $devices = Device::factory()
            ->count(rand(1,3))
            ->has(
                Figure::factory()
                    ->has(
                        Datum::factory()
                            ->count(rand(0,20))
                    , 'data')
                , 'figures')
            ->create();

        // foreach($devices as $device) {

        //     $readings = Reading::factory()
        //         ->count(rand(10,100))
        //         ->make();

        //     $readings = $device->readings()->saveMany($readings);

        //     foreach($readings as $reading) {
        //         foreach($device->configs as $config) {
        //             $reading->data()->saveMany(
        //                 ReadingData::factory()
        //                     ->key($config->key)
        //                     ->config($config->id)
        //                     ->count(1)
        //                     ->make()
        //                 );
        //         }
        //     }
        // }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }


}
