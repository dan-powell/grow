<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->jobTitle(),
            'nickname' => fake()->slug(),
        ];
    }

    public function name($name)
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
            'nickname' => Str::slug($name),
        ]);
    }
}
