# Módulo Identity

Módulo responsável pela gestão de usuários, turmas e autenticação no sistema.

## Estrutura

### Models

- **User**: Representa os usuários do sistema com diferentes roles (superadmin, admin, tutor, aluno)
- **Classroom**: Representa as turmas/classes do sistema
- **Series**: Representa as séries do sistema (placeholder para implementação futura)

### Migrations

- `2024_01_15_000001_create_users_table.php`: Cria a tabela de usuários
- `2024_01_15_000002_create_classes_table.php`: Cria a tabela de turmas

### Relações

#### User
- `belongsTo Tenant`
- `belongsTo Series`
- `belongsTo Classroom`

#### Classroom
- `belongsTo Tenant`
- `belongsTo Series`
- `hasMany Users`

#### Series
- `hasMany Users`
- `hasMany Classrooms`

#### Tenant
- `hasMany Users`

## Campos

### Users
- `id` (ulid)
- `tenant_id` (foreignId -> tenants.id, cascade)
- `first_name` (string)
- `last_name` (string)
- `birthdate` (date)
- `role` (enum: superadmin, admin, tutor, aluno)
- `series_id` (foreignId nullable)
- `class_id` (foreignId nullable)
- `status` (enum: active, suspended)
- `email_verified_at` (timestamp nullable)
- `password` (string)
- `remember_token` (string nullable)
- `created_at`, `updated_at`

### Classes
- `id` (ulid)
- `tenant_id` (foreignId -> tenants.id)
- `name` (string)
- `year` (integer)
- `series_id` (foreignId nullable)
- `created_at`, `updated_at`

## Factories

O módulo inclui factories para todos os models, permitindo criação de dados de teste:

```php
// Criar usuário
User::factory()->create();

// Criar usuário com role específica
User::factory()->student()->create();

// Criar turma
Classroom::factory()->create();

// Criar série
Series::factory()->create();
```

## Scopes

### User
- `active()`: Usuários ativos
- `ofRole($role)`: Usuários com role específica
- `ofTenant($tenantId)`: Usuários de um tenant específico

### Classroom
- `ofTenant($tenantId)`: Turmas de um tenant específico
- `ofYear($year)`: Turmas de um ano específico
- `ofSeries($seriesId)`: Turmas de uma série específica

### Series
- `ofTenant($tenantId)`: Séries de um tenant específico
- `ordered()`: Séries ordenadas por campo order

## Métodos Helper

### User
- `getFullNameAttribute()`: Retorna nome completo
- `isSuperAdmin()`, `isAdmin()`, `isTutor()`, `isStudent()`: Verifica roles
- `isActive()`, `isSuspended()`: Verifica status

### Classroom
- `students()`: Relação para alunos da turma
- `tutors()`: Relação para tutores da turma

## Configuração

O módulo inclui arquivo de configuração em `config/identity.php` com opções padrão para usuários, turmas e séries.
