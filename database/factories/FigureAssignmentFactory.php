<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAssignment;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FigureAssignment>
 */
class FigureAssignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    #[\Override]
    protected $model = FigureAssignment::class;

    public function definition()
    {
        return [
            'figure_id' => Figure::factory()->for(Device::factory()),
            'subject_id' => Subject::factory(),
            'started_at' => now()->subMonth(),
            'ended_at' => null,
        ];
    }

    public function ended($at = null)
    {
        return $this->state(fn (array $attributes) => [
            'ended_at' => $at ?? now()->subDay(),
        ]);
    }
}
