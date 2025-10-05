# Setup do Sistema Espaço Digital

Este guia mostra como configurar e executar o sistema com dados demo.

## 🚀 Setup Rápido

### 1. Executar Comando de Setup
```bash
php artisan demo:setup
```

### 2. Configurar Host Local
Adicione ao arquivo `/etc/hosts` (Linux/Mac) ou `C:\Windows\System32\drivers\etc\hosts` (Windows):
```
127.0.0.1 demo.espacodigital.local
```

### 3. Configurar Servidor Web
Configure seu servidor web (Apache/Nginx) para apontar para este diretório.

### 4. Acessar o Sistema
- **Admin**: http://demo.espacodigital.local
- **Alunos**: http://demo.espacodigital.local/student/login

## 📋 Dados de Acesso

### Super Admin
- **Email**: admin@espacodigital.demo
- **Senha**: password
- **Acesso**: Total ao sistema

### Alunos de Teste

**Aluno Ensino Fundamental:**
- **Nome**: João
- **Sobrenome**: Silva Santos
- **Data Nascimento**: 15/03/2015

**Aluno Ensino Médio:**
- **Nome**: Maria
- **Sobrenome**: Oliveira Costa
- **Data Nascimento**: 22/08/2007

## 🛠️ Comandos Disponíveis

### Setup Completo (Recomendado)
```bash
php artisan demo:setup
```

### Setup com Fresh Database
```bash
php artisan demo:setup --fresh
```

### Executar Apenas Seeders
```bash
php artisan db:seed
```

### Executar Seeder Específico
```bash
php artisan db:seed --class=DemoDataSeeder
```

## 📊 Dados Criados

### Unidade
- **Nome**: Espaço Digital Demo
- **Domínio**: demo.espacodigital.local
- **Configurações**: Tema GetSkills, idioma PT-BR

### Séries (12)
- **EF I**: 1º ao 5º Ano
- **EF II**: 6º ao 9º Ano  
- **EM**: 1º ao 3º Ano

### Disciplinas (6)
- Português, Matemática, Ciências
- História, Geografia, Inglês

### Turmas (8)
- Distribuídas entre diferentes séries
- Ano letivo 2024

### Usuários
- 1 Super Admin
- 2 Alunos de teste

### Permissões
- Roles globais e por tenant
- Permissões específicas por módulo

## 🔧 Funcionalidades Disponíveis

### Para Super Admin
- ✅ Gestão completa de usuários
- ✅ Visualização de todas as unidades
- ✅ Configuração de sistema
- ✅ Acesso a todos os módulos

### Para Alunos
- ✅ Login por nome + data de nascimento
- ✅ Visualização do perfil
- ✅ Acesso à trilha (placeholder)
- ✅ Sistema de autenticação seguro

### Painel Administrativo
- ✅ Listagem de usuários com filtros
- ✅ Cadastro de alunos via modal
- ✅ Edição de dados dos alunos
- ✅ Controle de status (ativo/suspenso)
- ✅ Ações em lote
- ✅ Reset de progresso (stub)

## 🌐 URLs Importantes

### Públicas
- `/` - Dashboard principal
- `/student/register` - Cadastro de alunos
- `/student/login` - Login de alunos

### Administrativas
- `/admin/users` - Gestão de usuários
- `/admin/users/create` - Cadastro de usuários
- `/admin/users/{id}/edit` - Edição de usuários

### Protegidas (Alunos)
- `/student/profile` - Perfil do aluno
- `/trilha/atual` - Trilha de aprendizado

## 🐛 Troubleshooting

### Erro: "Site não encontrado"
- Verificar configuração do arquivo hosts
- Confirmar que o servidor web está rodando
- Verificar se o domínio está configurado corretamente

### Erro: "Tenant não encontrado"
- Executar o seeder: `php artisan db:seed --class=DemoDataSeeder`
- Verificar se o tenant foi criado no banco

### Erro: "Credenciais inválidas"
- Verificar se está usando as credenciais corretas
- Confirmar que o usuário foi criado pelo seeder
- Verificar se pertence ao tenant correto

### Erro: "Acesso negado"
- Verificar se o usuário tem as permissões necessárias
- Confirmar que está logado no tenant correto
- Verificar se a conta está ativa

## 🔄 Reset do Sistema

### Reset Completo
```bash
php artisan demo:setup --fresh
```

### Reset Apenas Dados
```bash
php artisan migrate:fresh --seed
```

### Reset Apenas Seeders
```bash
php artisan db:seed --class=DemoDataSeeder
```

## 📝 Personalização

### Adicionar Mais Usuários
Edite o arquivo `database/seeders/DemoDataSeeder.php` no método `createDemoStudents()`.

### Adicionar Mais Séries
Edite o método `createSeries()` no mesmo arquivo.

### Adicionar Mais Disciplinas
Edite o método `createSubjects()` no mesmo arquivo.

### Configurar Novos Tenants
Edite o método `createDemoTenant()` ou crie novos tenants via interface.

## 🎯 Próximos Passos

1. **Explorar Sistema**: Navegue pelas funcionalidades disponíveis
2. **Testar Login**: Use as credenciais fornecidas
3. **Gerenciar Usuários**: Acesse o painel administrativo
4. **Personalizar**: Adapte os dados conforme necessário
5. **Desenvolver**: Implemente novas funcionalidades

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique a documentação técnica
2. Consulte os logs do Laravel
3. Verifique a configuração do banco de dados
4. Confirme se todas as migrations foram executadas

## 🎉 Pronto!

O sistema está configurado e pronto para uso. Aproveite para explorar todas as funcionalidades implementadas!
