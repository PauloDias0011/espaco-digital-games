<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property string|null $tenant_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Tenant|null $tenant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Role> $roles
 */
class Permission extends Model implements PermissionContract
{
    use HasFactory, HasRoles;

    protected $fillable = ['name', 'guard_name', 'tenant_id'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\PermissionFactory::new();
    }

    /**
     * Get the tenant that owns the permission.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Find a permission by its name and guard name.
     */
    public static function findByName(string $name, ?string $guardName = null, ?string $tenantId = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::where('name', $name)
            ->where('guard_name', $guardName);

        if ($tenantId) {
            $permission->where('tenant_id', $tenantId);
        }

        $permission = $permission->first();

        if (!$permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }

        return $permission;
    }

    /**
     * Find a permission by its id and guard name.
     */
    public static function findById(string|int $id, ?string $guardName = null, ?string $tenantId = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::where('id', $id)
            ->where('guard_name', $guardName);

        if ($tenantId) {
            $permission->where('tenant_id', $tenantId);
        }

        $permission = $permission->first();

        if (!$permission) {
            throw PermissionDoesNotExist::withId($id);
        }

        return $permission;
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     */
    public static function findOrCreate(string $name, ?string $guardName = null, ?string $tenantId = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::where('name', $name)
            ->where('guard_name', $guardName);

        if ($tenantId) {
            $permission->where('tenant_id', $tenantId);
        }

        $permission = $permission->first();

        if (!$permission) {
            return static::create([
                'name' => $name,
                'guard_name' => $guardName,
                'tenant_id' => $tenantId,
            ]);
        }

        return $permission;
    }

    /**
     * Scope a query to only include permissions of a specific tenant.
     */
    public function scopeOfTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include global permissions (without tenant).
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('tenant_id');
    }
}
