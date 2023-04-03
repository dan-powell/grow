<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Datum, Device, Figure, User};
use Illuminate\Database\Seeder;

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

        Device::factory()
            ->count(fake()->numberBetween(2, 6))
            ->has(Figure::factory()
                    ->count(fake()->numberBetween(6, 10))
                    ->has(Datum::factory()
                            ->count(fake()->numberBetween(1000, 2000)), 'data'), 'figures')
            ->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'admin' => true,
        ]);
    }
}
