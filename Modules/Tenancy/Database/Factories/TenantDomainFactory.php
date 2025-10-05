<?php

declare(strict_types=1);

namespace Modules\Tenancy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tenancy\App\Models\TenantDomain;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Tenancy\App\Models\TenantDomain>
 */
class TenantDomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TenantDomain::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'domain' => $this->faker->domainName(),
        ];
    }

    /**
     * Create a domain with a specific tenant.
     */
    public function forTenant($tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => is_string($tenant) ? $tenant : $tenant->id,
        ]);
    }

    /**
     * Create a subdomain.
     */
    public function subdomain(string $subdomain = null): static
    {
        $subdomain = $subdomain ?? $this->faker->slug(1);
        
        return $this->state(fn (array $attributes) => [
            'domain' => $subdomain . '.example.com',
        ]);
    }
}

