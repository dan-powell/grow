<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ReadingData;
use App\Models\DeviceConfig;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceConfigFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeviceConfig::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'key' => fake()->slug()
        ];
    }

}
