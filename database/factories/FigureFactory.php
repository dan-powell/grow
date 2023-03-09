<?php

namespace Database\Factories;

use App\Models\Figure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;
use App\Enum\Icons;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class FigureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
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
            'range_min_color' => rand(0, 1) ? fake()->rgbCssColor() : null,
            'range_max_color' => rand(0, 1) ? fake()->rgbCssColor() : null,
        ];
    }

    public function name($name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name,
                'key' => Str::slug($name),
            ];
        });
    }
}
