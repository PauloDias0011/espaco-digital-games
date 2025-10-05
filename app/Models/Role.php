<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasPermissions;

/**
 * @property int $id
 * @property string|null $team_id
 * @property string $name
 * @property string $guard_name
 * @property string|null $tenant_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Tenant|null $tenant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 */
class Role extends Model implements RoleContract
{
    use HasFactory, HasPermissions;

    protected $fillable = ['name', 'guard_name', 'team_id', 'tenant_id'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\RoleFactory::new();
    }

    /**
     * Get the tenant that owns the role.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            User::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.role_pivot_key', 'role_id'),
            config('permission.column_names.model_morph_key', 'model_id')
        );
    }

    /**
     * Find a role by its name and guard name.
     */
    public static function findByName(string $name, ?string $guardName = null, ?string $tenantId = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('name', $name)
            ->where('guard_name', $guardName);

        if ($tenantId) {
            $role->where('tenant_id', $tenantId);
        }

        $role = $role->first();

        if (!$role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    /**
     * Find a role by its id and guard name.
     */
    public static function findById(string|int $id, ?string $guardName = null, ?string $tenantId = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('id', $id)
            ->where('guard_name', $guardName);

        if ($tenantId) {
            $role->where('tenant_id', $tenantId);
        }

        $role = $role->first();

        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     */
    public static function findOrCreate(string $name, ?string $guardName = null, ?string $tenantId = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('name', $name)
            ->where('guard_name', $guardName);

        if ($tenantId) {
            $role->where('tenant_id', $tenantId);
        }

        $role = $role->first();

        if (!$role) {
            return static::create([
                'name' => $name,
                'guard_name' => $guardName,
                'tenant_id' => $tenantId,
            ]);
        }

        return $role;
    }

    /**
     * Scope a query to only include roles of a specific tenant.
     */
    public function scopeOfTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include global roles (without tenant).
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('tenant_id');
    }
}
