<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;

trait HasTenantRoles
{
    use \Spatie\Permission\Traits\HasRoles;

    /**
     * Get roles for the current tenant.
     */
    public function tenantRoles(): MorphToMany
    {
        return $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key', 'model_id'),
            config('permission.column_names.role_pivot_key', 'role_id')
        )->where('tenant_id', $this->tenant_id);
    }

    /**
     * Get permissions for the current tenant.
     */
    public function tenantPermissions(): MorphToMany
    {
        return $this->morphToMany(
            Permission::class,
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key', 'model_id'),
            config('permission.column_names.permission_pivot_key', 'permission_id')
        )->where('tenant_id', $this->tenant_id);
    }

    /**
     * Check if user has a specific role in the current tenant.
     */
    public function hasTenantRole(string $role): bool
    {
        if (is_string($role)) {
            $role = Role::findByName($role, $this->getDefaultGuardName(), $this->tenant_id);
        }

        if (! $role instanceof Role) {
            throw new RoleDoesNotExist();
        }

        if ($this->getGuardName() !== $role->guard_name) {
            throw new GuardDoesNotMatch();
        }

        return $this->roles()->where('id', $role->id)->where('tenant_id', $this->tenant_id)->exists();
    }

    /**
     * Check if user has a specific permission in the current tenant.
     */
    public function hasTenantPermission(string $permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::findByName($permission, $this->getDefaultGuardName(), $this->tenant_id);
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist();
        }

        if ($this->getGuardName() !== $permission->guard_name) {
            throw new GuardDoesNotMatch();
        }

        return $this->permissions()->where('id', $permission->id)->where('tenant_id', $this->tenant_id)->exists();
    }

    /**
     * Assign a role to the user in the current tenant.
     */
    public function assignTenantRole(string $role): static
    {
        if (is_string($role)) {
            $role = Role::findOrCreate($role, $this->getDefaultGuardName(), $this->tenant_id);
        }

        if (! $role instanceof Role) {
            throw new RoleDoesNotExist();
        }

        if ($this->getGuardName() !== $role->guard_name) {
            throw new GuardDoesNotMatch();
        }

        $this->roles()->syncWithoutDetaching([
            $role->id => ['tenant_id' => $this->tenant_id]
        ]);

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Remove a role from the user in the current tenant.
     */
    public function removeTenantRole(string $role): static
    {
        if (is_string($role)) {
            $role = Role::findByName($role, $this->getDefaultGuardName(), $this->tenant_id);
        }

        if (! $role instanceof Role) {
            throw new RoleDoesNotExist();
        }

        if ($this->getGuardName() !== $role->guard_name) {
            throw new GuardDoesNotMatch();
        }

        $this->roles()->wherePivot('tenant_id', $this->tenant_id)->detach($role->id);

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Give permission to the user in the current tenant.
     */
    public function giveTenantPermission(string $permission): static
    {
        if (is_string($permission)) {
            $permission = Permission::findOrCreate($permission, $this->getDefaultGuardName(), $this->tenant_id);
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist();
        }

        if ($this->getGuardName() !== $permission->guard_name) {
            throw new GuardDoesNotMatch();
        }

        $this->permissions()->syncWithoutDetaching([
            $permission->id => ['tenant_id' => $this->tenant_id]
        ]);

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Revoke permission from the user in the current tenant.
     */
    public function revokeTenantPermission(string $permission): static
    {
        if (is_string($permission)) {
            $permission = Permission::findByName($permission, $this->getDefaultGuardName(), $this->tenant_id);
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist();
        }

        if ($this->getGuardName() !== $permission->guard_name) {
            throw new GuardDoesNotMatch();
        }

        $this->permissions()->wherePivot('tenant_id', $this->tenant_id)->detach($permission->id);

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Get default guard name.
     */
    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName(static::class);
    }
}
