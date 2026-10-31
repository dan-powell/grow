<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Datum;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DatumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
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
        return $this->state(fn (array $attributes) => [
            'config_id' => $config_id,
        ]);
    }
}
