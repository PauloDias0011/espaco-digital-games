<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tenancy\App\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $permissions = [
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'roles.create',
            'roles.read',
            'roles.update',
            'roles.delete',
            'permissions.create',
            'permissions.read',
            'permissions.update',
            'permissions.delete',
            'classes.create',
            'classes.read',
            'classes.update',
            'classes.delete',
            'series.create',
            'series.read',
            'series.update',
            'series.delete',
            'trails.create',
            'trails.read',
            'trails.update',
            'trails.delete',
            'games.create',
            'games.read',
            'games.update',
            'games.delete',
            'games.play',
            'reports.read',
        ];

        return [
            'name' => $this->faker->randomElement($permissions),
            'guard_name' => 'web',
            'tenant_id' => null,
        ];
    }

    /**
     * Indicate that the permission is for a specific tenant.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenant->id,
        ]);
    }

    /**
     * Indicate that the permission is global (no tenant).
     */
    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => null,
        ]);
    }

    /**
     * Create a specific permission.
     */
    public function permission(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }
}