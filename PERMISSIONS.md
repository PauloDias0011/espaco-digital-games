# Sistema de Permissões com Escopo por Tenant

Este projeto utiliza o `spatie/laravel-permission` com modificações para suportar escopo por tenant.

## Configuração

### Models Customizados

- **App\Models\Role**: Model Role customizado com suporte a tenant_id
- **App\Models\Permission**: Model Permission customizado com suporte a tenant_id
- **App\Traits\HasTenantRoles**: Trait customizado para gerenciar roles e permissões por tenant

### Migrations

As tabelas de permissões foram modificadas para incluir o campo `tenant_id`:

- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

## Uso

### No Model User

O model `User` do módulo Identity já possui o trait `HasTenantRoles` configurado:

```php
use App\Traits\HasTenantRoles;

class User extends Authenticatable
{
    use HasTenantRoles;
    // ...
}
```

### Métodos Disponíveis

#### Roles por Tenant

```php
// Verificar se usuário tem role no tenant atual
$user->hasTenantRole('admin');

// Atribuir role no tenant atual
$user->assignTenantRole('admin');

// Remover role no tenant atual
$user->removeTenantRole('admin');

// Obter roles do tenant atual
$user->tenantRoles;
```

#### Permissões por Tenant

```php
// Verificar se usuário tem permissão no tenant atual
$user->hasTenantPermission('users.create');

// Dar permissão no tenant atual
$user->giveTenantPermission('users.create');

// Revogar permissão no tenant atual
$user->revokeTenantPermission('users.create');

// Obter permissões do tenant atual
$user->tenantPermissions;
```

### Criação de Roles e Permissões

```php
use App\Models\Role;
use App\Models\Permission;

// Criar role global (sem tenant)
$role = Role::create([
    'name' => 'superadmin',
    'guard_name' => 'web',
    'tenant_id' => null,
]);

// Criar role por tenant
$role = Role::create([
    'name' => 'admin',
    'guard_name' => 'web',
    'tenant_id' => $tenant->id,
]);

// Criar permissão global
$permission = Permission::create([
    'name' => 'users.create',
    'guard_name' => 'web',
    'tenant_id' => null,
]);

// Criar permissão por tenant
$permission = Permission::create([
    'name' => 'users.create',
    'guard_name' => 'web',
    'tenant_id' => $tenant->id,
]);
```

### Scopes

```php
// Roles por tenant
Role::ofTenant($tenantId)->get();

// Roles globais
Role::global()->get();

// Permissões por tenant
Permission::ofTenant($tenantId)->get();

// Permissões globais
Permission::global()->get();
```

## Roles Padrão

O seeder `RoleSeeder` cria os seguintes roles:

### Roles Globais

- **superadmin**: Acesso total ao sistema (sem escopo de tenant)

### Roles por Tenant

- **admin**: Administrador do tenant
- **tutor**: Tutor/Professor
- **aluno**: Estudante

## Permissões Padrão

### Permissões de Usuários
- `users.create`
- `users.read`
- `users.update`
- `users.delete`

### Permissões de Roles
- `roles.create`
- `roles.read`
- `roles.update`
- `roles.delete`

### Permissões de Permissões
- `permissions.create`
- `permissions.read`
- `permissions.update`
- `permissions.delete`

### Permissões de Classes
- `classes.create`
- `classes.read`
- `classes.update`
- `classes.delete`

### Permissões de Séries
- `series.create`
- `series.read`
- `series.update`
- `series.delete`

### Permissões de Trilhas
- `trails.create`
- `trails.read`
- `trails.update`
- `trails.delete`

### Permissões de Jogos
- `games.create`
- `games.read`
- `games.update`
- `games.delete`
- `games.play`

### Permissões de Relatórios
- `reports.read`

## Middleware

Para proteger rotas com permissões por tenant, você pode usar:

```php
// Middleware padrão do spatie
Route::middleware(['auth', 'permission:users.create'])->group(function () {
    // rotas protegidas
});

// Middleware customizado para tenant (a ser implementado)
Route::middleware(['auth', 'tenant.permission:users.create'])->group(function () {
    // rotas protegidas por tenant
});
```

## Cache

O sistema de permissões utiliza cache para melhorar a performance. O cache é automaticamente limpo quando roles ou permissões são modificados.

## Observações Importantes

1. **Escopo por Tenant**: Todas as operações de roles e permissões consideram automaticamente o `tenant_id` do usuário
2. **Roles Globais**: Superadmin tem acesso global (sem escopo de tenant)
3. **Compatibilidade**: Mantém compatibilidade com a API padrão do spatie/laravel-permission
4. **Performance**: Utilize cache e índices adequados para consultas eficientes

## Executar Seeder

Para criar os roles padrão:

```bash
php artisan db:seed --class=RoleSeeder
```

Ou executar todos os seeders:

```bash
php artisan db:seed
```
