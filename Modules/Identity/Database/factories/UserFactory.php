<?php

declare(strict_types=1);

namespace Modules\Identity\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Identity\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birthdate' => $this->faker->dateTimeBetween('-30 years', '-10 years'),
            'role' => $this->faker->randomElement(['superadmin', 'admin', 'tutor', 'aluno']),
            'series_id' => null,
            'class_id' => null,
            'status' => $this->faker->randomElement(['active', 'suspended']),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ];
    }

    /**
     * Indicate that the user is a superadmin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'superadmin',
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a tutor.
     */
    public function tutor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'tutor',
        ]);
    }

    /**
     * Indicate that the user is a student.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'aluno',
        ]);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}
