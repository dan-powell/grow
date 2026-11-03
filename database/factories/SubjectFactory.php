<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\Icons;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'name' => fake()->randomElement(['Basil', 'Tomato', 'Chilli', 'Mint', 'Rosemary', 'Thyme', 'Coriander', 'Parsley']),
            'summary' => fake()->sentence(),
            'icon' => fake()->randomElement([null, ...Icons::names()]),
        ];
    }
}
