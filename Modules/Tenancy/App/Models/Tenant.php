<?php

declare(strict_types=1);

namespace Modules\Tenancy\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Tenancy\Database\factories\TenantFactory;
use Stancl\Tenancy\Contracts\Tenant as TenantContract;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property array|null $settings
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Tenant extends Model implements TenantContract
{
    use HasFactory, HasUlids;

    /**
     * The table associated with the model.
     */
    protected $table = 'tenants';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'data',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['name', 'slug', 'status'];

    /**
     * Get the domains for the tenant.
     */
    public function domains(): HasMany
    {
        return $this->hasMany(TenantDomain::class);
    }

    /**
     * Get tenant name from data.
     */
    public function getName(): string
    {
        return $this->data['name'] ?? '';
    }

    /**
     * Get tenant slug from data.
     */
    public function getSlug(): string
    {
        return $this->data['slug'] ?? '';
    }

    /**
     * Get tenant status from data.
     */
    public function getStatus(): string
    {
        return $this->data['status'] ?? 'active';
    }

    /**
     * Accessor for name attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->getName();
    }

    /**
     * Accessor for slug attribute.
     */
    public function getSlugAttribute(): string
    {
        return $this->getSlug();
    }

    /**
     * Accessor for status attribute.
     */
    public function getStatusAttribute(): string
    {
        return $this->getStatus();
    }

    /**
     * Accessor for settings attribute.
     */
    public function getSettingsAttribute(): ?array
    {
        return $this->data['settings'] ?? null;
    }

    /**
     * Check if tenant is active.
     */
    public function isActive(): bool
    {
        return $this->getStatus() === 'active';
    }

    /**
     * Check if tenant is inactive.
     */
    public function isInactive(): bool
    {
        return $this->getStatus() === 'inactive';
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): TenantFactory
    {
        return TenantFactory::new();
    }

    /**
     * Get the tenant key name for the tenant.
     */
    public function getTenantKeyName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Get the tenant key value.
     */
    public function getTenantKey(): string
    {
        return $this->getKey();
    }

    /**
     * Get internal tenant data.
     */
    public function getInternal(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->data;
        }
        
        return $this->data[$key] ?? null;
    }

    /**
     * Set internal tenant data.
     */
    public function setInternal(string $key, mixed $value): static
    {
        $data = $this->data ?? [];
        $data[$key] = $value;
        $this->data = $data;
        
        return $this;
    }

    /**
     * Run a callback within the tenant context.
     */
    public function run(callable $callback): mixed
    {
        return tenancy()->initialize($this, $callback);
    }
}
