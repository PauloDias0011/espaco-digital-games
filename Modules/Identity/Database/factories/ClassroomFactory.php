<?php

declare(strict_types=1);

namespace Modules\Identity\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Identity\App\Models\Classroom;
use Modules\Identity\App\Models\Series;
use Modules\Tenancy\App\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Identity\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->words(2, true) . ' - ' . $this->faker->randomLetter() . $this->faker->randomDigit(),
            'year' => $this->faker->numberBetween(2020, 2030),
            'series_id' => Series::factory(),
        ];
    }

    /**
     * Indicate that the classroom is for a specific year.
     */
    public function forYear(int $year): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => $year,
        ]);
    }

    /**
     * Indicate that the classroom is for a specific series.
     */
    public function forSeries(Series $series): static
    {
        return $this->state(fn (array $attributes) => [
            'series_id' => $series->id,
        ]);
    }

    /**
     * Indicate that the classroom is for a specific tenant.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenant->id,
        ]);
    }
}
