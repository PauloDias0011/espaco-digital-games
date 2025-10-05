<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Modules\Tenancy\App\Models\Tenant;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar roles globais (sem tenant_id)
        $globalRoles = [
            'superadmin' => [
                'name' => 'Super Admin',
                'permissions' => [
                    'users.create',
                    'users.read',
                    'users.update',
                    'users.delete',
                    'tenants.create',
                    'tenants.read',
                    'tenants.update',
                    'tenants.delete',
                    'roles.create',
                    'roles.read',
                    'roles.update',
                    'roles.delete',
                    'permissions.create',
                    'permissions.read',
                    'permissions.update',
                    'permissions.delete',
                ],
            ],
        ];

        // Criar roles por tenant
        $tenantRoles = [
            'admin' => [
                'name' => 'Administrador',
                'permissions' => [
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
                    'reports.read',
                ],
            ],
            'tutor' => [
                'name' => 'Tutor',
                'permissions' => [
                    'users.read',
                    'classes.read',
                    'series.read',
                    'trails.create',
                    'trails.read',
                    'trails.update',
                    'trails.delete',
                    'games.create',
                    'games.read',
                    'games.update',
                    'games.delete',
                    'reports.read',
                ],
            ],
            'aluno' => [
                'name' => 'Aluno',
                'permissions' => [
                    'trails.read',
                    'games.read',
                    'games.play',
                ],
            ],
        ];

        // Criar roles globais
        foreach ($globalRoles as $roleKey => $roleData) {
            $role = Role::firstOrCreate(
                [
                    'name' => $roleKey,
                    'guard_name' => 'web',
                    'tenant_id' => null,
                ],
                [
                    'name' => $roleKey,
                    'guard_name' => 'web',
                    'tenant_id' => null,
                ]
            );

            // Criar e atribuir permissões globais
            foreach ($roleData['permissions'] as $permissionName) {
                $permission = Permission::firstOrCreate(
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web',
                        'tenant_id' => null,
                    ],
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web',
                        'tenant_id' => null,
                    ]
                );

                $role->givePermissionTo($permission);
            }

            $this->command->info("Role global '{$roleKey}' criado com sucesso!");
        }

        // Criar roles por tenant (se existirem tenants)
        $tenants = Tenant::all();
        
        if ($tenants->count() > 0) {
            foreach ($tenants as $tenant) {
                foreach ($tenantRoles as $roleKey => $roleData) {
                    $role = Role::firstOrCreate(
                        [
                            'name' => $roleKey,
                            'guard_name' => 'web',
                            'tenant_id' => $tenant->id,
                        ],
                        [
                            'name' => $roleKey,
                            'guard_name' => 'web',
                            'tenant_id' => $tenant->id,
                        ]
                    );

                    // Criar e atribuir permissões por tenant
                    foreach ($roleData['permissions'] as $permissionName) {
                        $permission = Permission::firstOrCreate(
                            [
                                'name' => $permissionName,
                                'guard_name' => 'web',
                                'tenant_id' => $tenant->id,
                            ],
                            [
                                'name' => $permissionName,
                                'guard_name' => 'web',
                                'tenant_id' => $tenant->id,
                            ]
                        );

                        $role->givePermissionTo($permission);
                    }

                    $this->command->info("Role '{$roleKey}' criado para tenant '{$tenant->name}'!");
                }
            }
        } else {
            $this->command->warn('Nenhum tenant encontrado. Roles por tenant não foram criados.');
        }

        $this->command->info('Seeder de roles executado com sucesso!');
    }
}