# Seeder Inicial - Dados Demo

Este documento descreve o seeder inicial `DemoDataSeeder` que cria dados de demonstração completos para o sistema.

## Dados Criados

### ✅ Unidade "Espaço Digital Demo"

**Informações:**
- **Nome**: Espaço Digital Demo
- **ID**: espaco-digital-demo
- **Domínio**: demo.espacodigital.local
- **Status**: Ativo
- **Configurações**:
  - Tema: getskills
  - Idioma: pt-BR
  - Timezone: America/Sao_Paulo

### ✅ Usuário Super Admin

**Credenciais:**
- **Nome**: Admin Sistema
- **Email**: admin@espacodigital.demo
- **Senha**: password
- **Role**: superadmin
- **Status**: Ativo

### ✅ Séries Padrão (12 séries)

**Ensino Fundamental I:**
- 1º Ano EF
- 2º Ano EF
- 3º Ano EF
- 4º Ano EF
- 5º Ano EF

**Ensino Fundamental II:**
- 6º Ano EF
- 7º Ano EF
- 8º Ano EF
- 9º Ano EF

**Ensino Médio:**
- 1º Ano EM
- 2º Ano EM
- 3º Ano EM

### ✅ Disciplinas Padrão (6 disciplinas)

1. **Português** - Língua Portuguesa e Literatura (#3498db)
2. **Matemática** - Matemática e Lógica (#e74c3c)
3. **Ciências** - Ciências da Natureza (#2ecc71)
4. **História** - História e Cultura (#f39c12)
5. **Geografia** - Geografia e Espaço (#9b59b6)
6. **Inglês** - Língua Inglesa (#1abc9c)

### ✅ Turmas de Exemplo (8 turmas)

**Ensino Fundamental I:**
- 1º Ano EF - Turma A (2024)
- 5º Ano EF - Turma A (2024)

**Ensino Fundamental II:**
- 6º Ano EF - Turma A (2024)
- 6º Ano EF - Turma B (2024)
- 9º Ano EF - Turma A (2024)

**Ensino Médio:**
- 1º Ano EM - Turma A (2024)
- 1º Ano EM - Turma B (2024)
- 3º Ano EM - Turma A (2024)

### ✅ Alunos de Teste (2 alunos)

**Aluno 1 - Ensino Fundamental:**
- **Nome**: João Silva Santos
- **Nascimento**: 15/03/2015
- **Série**: 1º Ano EF
- **Turma**: Turma A
- **Role**: aluno
- **Status**: Ativo

**Aluno 2 - Ensino Médio:**
- **Nome**: Maria Oliveira Costa
- **Nascimento**: 22/08/2007
- **Série**: 3º Ano EM
- **Turma**: Turma A
- **Role**: aluno
- **Status**: Ativo

## Estrutura de Arquivos

### Seeders
- `database/seeders/DemoDataSeeder.php` - Seeder principal com todos os dados
- `database/seeders/DatabaseSeeder.php` - Seeder principal atualizado

### Models
- `app/Models/Subject.php` - Model para disciplinas

### Migrations
- `database/migrations/2025_10_05_030958_create_subjects_table.php` - Tabela de disciplinas

## Como Executar

### Executar Todos os Seeders
```bash
php artisan db:seed
```

### Executar Apenas o Demo Seeder
```bash
php artisan db:seed --class=DemoDataSeeder
```

### Executar com Fresh Migration
```bash
php artisan migrate:fresh --seed
```

## Dados de Acesso

### Super Admin
```
Email: admin@espacodigital.demo
Senha: password
Role: superadmin
```

### Alunos de Teste

**Aluno EF (João):**
```
Nome: João
Sobrenome: Silva Santos
Data de Nascimento: 15/03/2015
```

**Aluno EM (Maria):**
```
Nome: Maria
Sobrenome: Oliveira Costa
Data de Nascimento: 22/08/2007
```

## Permissões Criadas

### Permissões Globais (Super Admin)
- users.*
- tenants.*
- roles.*
- permissions.*

### Permissões por Tenant
- users.*
- classes.*
- series.*
- subjects.*
- trails.*
- games.*
- reports.read

### Roles por Tenant
- **admin**: Todas as permissões
- **tutor**: Permissões de conteúdo e relatórios
- **aluno**: Permissões de visualização e jogos

## Model Subject

### Campos
- `id` (ulid)
- `tenant_id` (foreign key)
- `name` (string)
- `description` (text, nullable)
- `color` (string, hex color)
- `active` (boolean)
- `created_at`, `updated_at`

### Relações
- `belongsTo Tenant`

### Scopes
- `active()` - Disciplinas ativas
- `ofTenant($tenantId)` - Por tenant
- `ordered()` - Ordenadas por nome

## Funcionalidades do Seeder

### Criação Inteligente
- **Verifica Duplicidade**: Usa `firstOrCreate` para evitar duplicatas
- **Relacionamentos**: Cria automaticamente as relações entre entidades
- **Roles e Permissões**: Atribui automaticamente roles e permissões

### Feedback Detalhado
- Progresso em tempo real
- Contagem de registros criados
- Resumo final com informações de acesso
- Emojis para melhor visualização

### Configuração Completa
- Tenant com domínio configurado
- Usuários com senhas definidas
- Relacionamentos entre séries e turmas
- Permissões específicas por tenant

## Exemplos de Uso

### Verificar Dados Criados
```php
// Verificar tenant
$tenant = \Modules\Tenancy\App\Models\Tenant::find('espaco-digital-demo');

// Verificar usuários
$users = \Modules\Identity\App\Models\User::ofTenant($tenant->id)->get();

// Verificar séries
$series = \Modules\Identity\App\Models\Series::ofTenant($tenant->id)->get();

// Verificar disciplinas
$subjects = \App\Models\Subject::ofTenant($tenant->id)->get();
```

### Acessar Sistema
1. **Configurar Host**: Adicionar `demo.espacodigital.local` ao `/etc/hosts`
2. **Acessar**: http://demo.espacodigital.local
3. **Login Admin**: admin@espacodigital.demo / password
4. **Login Aluno**: Use dados dos alunos de teste

## Troubleshooting

### Erro: "Tenant não encontrado"
- Verificar se o seeder foi executado
- Confirmar que o tenant foi criado com ID correto

### Erro: "Usuário não encontrado"
- Verificar se as credenciais estão corretas
- Confirmar que o usuário pertence ao tenant

### Erro: "Domínio não configurado"
- Adicionar domínio ao arquivo hosts
- Verificar configuração do servidor web

### Erro: "Permissões insuficientes"
- Verificar se roles foram atribuídos
- Confirmar que permissões foram criadas

## Próximos Passos

1. **Configurar Domínio**: Adicionar ao hosts local
2. **Testar Login**: Verificar acesso de admin e alunos
3. **Explorar Sistema**: Navegar pelas funcionalidades
4. **Personalizar**: Adaptar dados conforme necessário

## Personalização

### Adicionar Mais Séries
```php
// No método createSeries()
['name' => '4º Ano EM', 'description' => 'Quarto Ano do Ensino Médio', 'order' => 13],
```

### Adicionar Mais Disciplinas
```php
// No método createSubjects()
['name' => 'Educação Física', 'description' => 'Educação Física e Esportes', 'color' => '#e67e22'],
```

### Adicionar Mais Alunos
```php
// No método createDemoStudents()
[
    'first_name' => 'Pedro',
    'last_name' => 'Ferreira Lima',
    'birthdate' => '2009-12-10',
    'series_id' => $series->where('name', '7º Ano EF')->first()->id,
    'class_id' => $classrooms->where('name', 'Turma A')->where('series_id', $series->where('name', '7º Ano EF')->first()->id)->first()->id,
],
```
