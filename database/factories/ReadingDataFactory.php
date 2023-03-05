<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ReadingData;
use App\Models\DeviceConfig;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class ReadingDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReadingData::class;

    public function definition()
    {
        return [
            'key' => DeviceConfig::inRandomOrder()->first()->key,
            'value' => fake()->randomFloat()
        ];
    }

    public function key($key)
    {
        return $this->state(function (array $attributes) use ($key) {
            return [
                'key' => $key,
            ];
        });
    }

    public function keys($keys)
    {
        return $this->state(function (array $attributes) use ($keys) {
            return [
                'key' => fake()->randomElement($keys),
            ];
        });
    }

    public function config($config_id)
    {
        return $this->state(function (array $attributes) use ($config_id) {
            return [
                'config_id' => $config_id,
            ];
        });
    }


}
