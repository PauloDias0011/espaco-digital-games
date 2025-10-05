# Fluxo de Autenticação de Alunos

Este documento descreve o sistema de cadastro e login de alunos implementado no projeto.

## Funcionalidades Implementadas

### ✅ Cadastro de Alunos

**Campos Obrigatórios:**
- Nome (first_name)
- Sobrenome (last_name) 
- Data de Nascimento (birthdate)
- Série (series_id)

**Funcionalidades Automáticas:**
- Geração de `user_code` único (8 caracteres alfanuméricos)
- Atribuição automática de `role = 'aluno'`
- Status inicial `active`
- Atribuição de senha padrão temporária
- Verificação de duplicidade por nome + sobrenome + data de nascimento

### ✅ Login de Alunos

**Credenciais:**
- Nome + Sobrenome + Data de Nascimento
- Verificação automática de pertencimento ao tenant atual
- Validação de status ativo

**Fluxo:**
1. Aluno preenche dados no formulário
2. Sistema valida credenciais no tenant atual
3. Se válido, faz login automático
4. Redireciona para `/trilha/atual`

### ✅ Middleware de Segurança

**EnsureStudentBelongsToTenant:**
- Verifica se usuário está autenticado
- Confirma que é um aluno (role = 'aluno')
- Valida pertencimento ao tenant atual
- Verifica status ativo
- Redireciona com mensagens de erro apropriadas

## Estrutura de Arquivos

### Controllers
- `app/Http/Controllers/StudentAuthController.php`

### Middleware
- `app/Http/Middleware/EnsureStudentBelongsToTenant.php`

### Form Requests
- `app/Http/Requests/StudentRegisterRequest.php`
- `app/Http/Requests/StudentLoginRequest.php`

### Views
- `resources/views/student/auth/register.blade.php`
- `resources/views/student/auth/login.blade.php`
- `resources/views/student/profile.blade.php`
- `resources/views/student/trail/current.blade.php`

### Models (Atualizados)
- `Modules/Identity/App/Models/User.php` - Adicionado user_code e métodos de autenticação

## Rotas Implementadas

### Rotas Públicas (dentro do tenant)
```php
GET  /student/register     - Formulário de cadastro
POST /student/register     - Processar cadastro
GET  /student/login        - Formulário de login  
POST /student/login        - Processar login
```

### Rotas Protegidas (autenticadas + middleware)
```php
GET  /student/profile      - Perfil do aluno
PUT  /student/profile      - Atualizar perfil
POST /student/logout       - Logout
GET  /trilha/atual         - Trilha atual (placeholder)
```

## Middleware Registrado

```php
'student.tenant' => \App\Http\Middleware\EnsureStudentBelongsToTenant::class
```

## Validações Implementadas

### Cadastro
- Nome e sobrenome obrigatórios
- Data de nascimento válida e anterior a hoje
- Série deve existir e pertencer ao tenant
- Não pode haver duplicidade de dados

### Login
- Nome, sobrenome e data obrigatórios
- Credenciais devem existir no tenant atual
- Aluno deve estar ativo

## Componentes GetSkills Utilizados

### Formulários
- `form-control` com classes de validação
- `form-label` para labels
- `form-select` para selects
- `btn btn-primary` para botões principais
- `alert` para feedback de erro/sucesso

### Layout
- Extensão do `layout.default`
- Breadcrumbs padronizados
- Cards responsivos
- Ícones Font Awesome

## Fluxo de Uso

### 1. Cadastro
1. Aluno acessa `/student/register`
2. Preenche dados (nome, sobrenome, data, série)
3. Sistema valida e cria conta
4. Exibe código único gerado
5. Redireciona para login

### 2. Login
1. Aluno acessa `/student/login`
2. Informa nome + sobrenome + data de nascimento
3. Sistema valida no tenant atual
4. Se válido, faz login automático
5. Redireciona para trilha atual

### 3. Acesso Protegido
1. Middleware verifica autenticação
2. Confirma que é aluno do tenant
3. Verifica status ativo
4. Permite acesso às rotas protegidas

## Segurança

### Validações
- CSRF protection em todos os formulários
- Validação server-side com Form Requests
- Sanitização de dados de entrada
- Verificação de pertencimento ao tenant

### Middleware
- Proteção de rotas sensíveis
- Verificação de autenticação
- Validação de contexto de tenant
- Redirecionamentos seguros

## Banco de Dados

### Tabela users (atualizada)
```sql
ALTER TABLE users ADD COLUMN user_code VARCHAR(8) UNIQUE;
CREATE INDEX users_user_code_tenant_index ON users(user_code, tenant_id);
```

### Campos relevantes
- `user_code`: Código único gerado automaticamente
- `tenant_id`: Vinculação ao tenant
- `role`: Sempre 'aluno' para estudantes
- `status`: 'active' ou 'suspended'

## Exemplos de Uso

### Verificar se aluno está logado
```php
if (Auth::check() && Auth::user()->isStudent()) {
    // Aluno logado
}
```

### Obter código do aluno
```php
$studentCode = Auth::user()->user_code;
```

### Verificar pertencimento ao tenant
```php
$belongsToTenant = Auth::user()->tenant_id === tenant()->id;
```

## Próximos Passos

1. **Implementar sistema de trilhas**
2. **Adicionar jogos educativos**
3. **Sistema de progresso e badges**
4. **Relatórios para tutores**
5. **Notificações em tempo real**

## Troubleshooting

### Erro: "Credenciais inválidas"
- Verificar se dados estão exatamente como cadastrados
- Confirmar que aluno pertence ao tenant atual
- Verificar se conta está ativa

### Erro: "Unidade não encontrada"
- Verificar configuração de tenancy
- Confirmar que domínio está configurado corretamente

### Erro: "Acesso negado"
- Verificar se usuário é aluno (role = 'aluno')
- Confirmar status ativo
- Verificar pertencimento ao tenant
