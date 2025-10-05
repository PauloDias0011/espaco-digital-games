# Painel Administrativo - Gestão de Usuários

Este documento descreve o painel administrativo para gestão de usuários implementado no módulo Identity.

## Funcionalidades Implementadas

### ✅ Listagem de Usuários

**Campos Exibidos:**
- Nome completo + Data de nascimento
- Código único do aluno
- Série atual
- Turma atual
- Pontuação total (stub do módulo Trail)
- Status (Ativo/Suspenso)
- Última atividade (stub do módulo Trail)

**Funcionalidades:**
- Paginação (15 usuários por página)
- Ordenação por diferentes campos
- Busca por nome, sobrenome ou código

### ✅ Filtros Avançados

**Filtros Disponíveis:**
- **Busca**: Nome, sobrenome ou código do aluno
- **Série**: Filtro por série específica
- **Turma**: Filtro por turma específica (dinâmico baseado na série)
- **Status**: Ativo ou Suspenso
- **Ordenação**: Data de cadastro, nome, sobrenome, código

### ✅ Ações Individuais

**Botões de Ação:**
- **Editar**: Abre modal para edição completa
- **Suspender/Ativar**: Alterna status do aluno
- **Resetar Progresso**: Stub para funcionalidade do módulo Trail
- **Excluir**: Remove o aluno (com confirmação)

### ✅ Ações em Lote

**Operações em Massa:**
- **Selecionar Todos**: Seleciona todos os usuários da página
- **Ativar Selecionados**: Ativa múltiplos alunos
- **Suspender Selecionados**: Suspende múltiplos alunos
- **Excluir Selecionados**: Remove múltiplos alunos (com confirmação)

### ✅ Cadastro de Alunos

**Modal de Cadastro:**
- Nome e sobrenome obrigatórios
- Data de nascimento com validação
- Seleção de série (obrigatória)
- Seleção de turma (opcional, filtrada por série)
- Geração automática de código único
- Validação de duplicidade

### ✅ Edição de Alunos

**Modal de Edição:**
- Edição de todos os campos básicos
- Alteração de série e turma
- Controle de status (ativo/suspenso)
- Exibição de informações do aluno (código, data de cadastro, etc.)
- Validação de duplicidade (excluindo o próprio usuário)

## Estrutura de Arquivos

### Controllers
- `app/Http/Controllers/Admin/UserController.php`

### Form Requests
- `app/Http/Requests/Admin/StoreUserRequest.php`
- `app/Http/Requests/Admin/UpdateUserRequest.php`

### Middleware
- `app/Http/Middleware/EnsureUserCanManageStudents.php`

### Views
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/partials/create-modal.blade.php`
- `resources/views/admin/users/partials/edit-modal.blade.php`

## Rotas Implementadas

### Rotas de Recursos
```php
GET    /admin/users              - Listagem de usuários
GET    /admin/users/create       - Modal de cadastro
POST   /admin/users              - Criar usuário
GET    /admin/users/{user}       - Visualizar usuário
GET    /admin/users/{user}/edit  - Modal de edição
PUT    /admin/users/{user}       - Atualizar usuário
DELETE /admin/users/{user}       - Excluir usuário
```

### Rotas Adicionais
```php
POST   /admin/users/{user}/toggle-status  - Alternar status
POST   /admin/users/{user}/reset-progress - Resetar progresso
GET    /admin/users/classrooms            - Buscar turmas por série
POST   /admin/users/bulk-update           - Ações em lote
```

## Middleware de Segurança

**EnsureUserCanManageStudents:**
- Verifica autenticação do usuário
- Confirma permissões de admin/tutor
- Valida pertencimento ao tenant
- Verifica status ativo do usuário

## Validações Implementadas

### Cadastro
- Nome e sobrenome obrigatórios
- Data de nascimento válida e anterior a hoje
- Série deve existir e pertencer ao tenant
- Turma deve existir e pertencer ao tenant (se informada)
- Não pode haver duplicidade de dados

### Edição
- Mesmas validações do cadastro
- Duplicidade excluindo o próprio usuário
- Status deve ser 'active' ou 'suspended'

## Componentes GetSkills Utilizados

### Interface
- Cards responsivos com headers padronizados
- Tabelas com hover effects
- Botões com ícones Font Awesome
- Modais Bootstrap para formulários
- Alertas para feedback de ações

### Formulários
- Inputs com validação visual
- Selects com filtros dinâmicos
- Feedback de erro inline
- Botões de ação padronizados

### Navegação
- Breadcrumbs padronizados
- Paginação com query string
- Filtros persistentes na URL

## Funcionalidades Técnicas

### JavaScript Interativo
- **Modais Dinâmicos**: Carregamento via AJAX
- **Filtros Dinâmicos**: Turmas filtradas por série
- **Ações em Lote**: Seleção múltipla com controle de estado
- **Validação Client-side**: Feedback imediato
- **Confirmações**: Modais de confirmação para ações destrutivas

### Backend
- **Paginação Eficiente**: Com query string para filtros
- **Validação Robusta**: Form Requests com validação customizada
- **Operações em Lote**: Processamento eficiente de múltiplos registros
- **Relacionamentos**: Eager loading para performance

## Stubs para Módulo Trail

### Pontuação Total
```php
public function getTotalScoreAttribute(): int
{
    // TODO: Implementar quando o módulo Trail estiver pronto
    return rand(0, 1000);
}
```

### Progresso
```php
public function getProgressPercentageAttribute(): float
{
    // TODO: Implementar quando o módulo Trail estiver pronto
    return rand(0, 100);
}
```

### Resetar Progresso
```php
public function resetProgress(User $user): JsonResponse
{
    // TODO: Implementar quando o módulo Trail estiver pronto
    return response()->json([
        'success' => true,
        'message' => 'Progresso resetado com sucesso! (Funcionalidade será implementada no módulo Trail)'
    ]);
}
```

## Permissões e Segurança

### Níveis de Acesso
- **Super Admin**: Acesso total ao sistema
- **Admin**: Gestão completa de usuários do tenant
- **Tutor**: Gestão de usuários do tenant (futuro)

### Validações de Segurança
- Verificação de pertencimento ao tenant
- Validação de permissões por role
- Proteção CSRF em todos os formulários
- Sanitização de dados de entrada

## Exemplos de Uso

### Acessar Painel
```php
// URL: /admin/users
// Middleware: web, tenant, auth, admin.manage.students
```

### Filtrar por Série
```php
// URL: /admin/users?series_id=123&status=active
```

### Buscar Aluno
```php
// URL: /admin/users?search=João Silva
```

### Ação em Lote
```javascript
// Selecionar usuários e ativar
selectedUsers = ['user1', 'user2', 'user3'];
bulkAction('activate');
```

## Próximos Passos

1. **Integração com Módulo Trail**
   - Implementar pontuação real
   - Sistema de progresso
   - Resetar progresso funcional

2. **Relatórios Avançados**
   - Exportação para CSV/Excel
   - Gráficos de progresso
   - Estatísticas por série/turma

3. **Notificações**
   - Alertas em tempo real
   - Histórico de atividades
   - Logs de auditoria

4. **Funcionalidades Extras**
   - Importação em lote
   - Backup/restore de dados
   - Integração com sistemas externos

## Troubleshooting

### Erro: "Acesso negado"
- Verificar se usuário tem role de admin/tutor
- Confirmar que pertence ao tenant atual
- Verificar se conta está ativa

### Erro: "Turma não encontrada"
- Verificar se turma pertence ao tenant
- Confirmar relacionamento série-turma
- Verificar dados no banco

### Erro: "Duplicidade de dados"
- Verificar se já existe aluno com mesmos dados
- Confirmar validação no Form Request
- Verificar dados no banco
