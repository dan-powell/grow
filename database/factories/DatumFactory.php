<?php

namespace Database\Factories;

use App\Models\Datum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DatumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Datum::class;

    public function definition()
    {
        return [
            'value' => fake()->randomFloat(2, 1, 10000),
            'timestamp' => fake()->dateTime(),
        ];
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
