<?php

namespace Database\Factories;

use App\Enum\Icons;
use App\Models\Device;
use App\Models\Figure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<Device>
 */
class FigureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = Figure::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'key' => fake()->slug(),
            'summary' => fake()->name(),
            'dashboard' => fake()->boolean(),
            'icon' => fake()->randomElement([null, ...Icons::names()]),
            'icon_small' => fake()->randomElement([null, ...Icons::names()]),
            'prefix' => fake()->randomElement([null, '£', '^']),
            'suffix' => fake()->randomElement([null, '℃', '%']),
            'range_min' => fake()->randomFloat(2, 1, 2500),
            'range_max' => fake()->randomFloat(2, 750, 10000),
            'range_min_color' => random_int(0, 1) ? fake()->rgbCssColor() : null,
            'range_max_color' => random_int(0, 1) ? fake()->rgbCssColor() : null,
        ];
    }

    public function name($name)
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
            'key' => Str::slug($name),
        ]);
    }
}
