<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Figure;
use Str;

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
            'key' => fake()->slug()
        ];
    }

    public function name($name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name,
                'key' => Str::slug($name)
            ];
        });
    }

}
