<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Device>
 */
class ReadingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nickname' => fake()->companyPrefix.Str::random(5),
            'timestamp' => fake()->dateTimeBetween('-1 years', 'now'),
            'readings' => [
                'temperature' => fake()->randomFloat(2, -30, 50),
                'humidity' => fake()->randomNumber(2, 0),
                'pressure' => fake()->randomNumber(3, 900),
                'voltage' => fake()->randomFloat(2, 2.00, 6.00),
                'moisture_a' => fake()->randomNumber(2, 0),
                'moisture_b' => fake()->randomNumber(2, 0),
                'moisture_c' => fake()->randomNumber(2, 0),
            ],
        ];
    }
}
