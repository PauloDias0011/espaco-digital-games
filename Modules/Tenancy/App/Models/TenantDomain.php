<?php

declare(strict_types=1);

namespace Modules\Tenancy\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Tenancy\Database\factories\TenantDomainFactory;

/**
 * @property int $id
 * @property string $tenant_id
 * @property string $domain
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Modules\Tenancy\App\Models\Tenant $tenant
 */
class TenantDomain extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'domains';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'domain',
    ];

    /**
     * Get the tenant that owns the domain.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): TenantDomainFactory
    {
        return TenantDomainFactory::new();
    }
}
