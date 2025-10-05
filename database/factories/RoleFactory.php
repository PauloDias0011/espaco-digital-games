<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tenancy\App\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['admin', 'tutor', 'aluno', 'manager']),
            'guard_name' => 'web',
            'tenant_id' => null,
        ];
    }

    /**
     * Indicate that the role is for a specific tenant.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * Indicate that the role is global (no tenant).
     */
    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => null,
        ]);
    }

    /**
     * Create a superadmin role.
     */
    public function superadmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'superadmin',
        ]);
    }

    /**
     * Create an admin role.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
        ]);
    }

    /**
     * Create a tutor role.
     */
    public function tutor(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'tutor',
        ]);
    }

    /**
     * Create a student role.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'aluno',
        ]);
    }
}