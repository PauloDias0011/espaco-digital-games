<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupDemoData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'demo:setup {--fresh : Run fresh migrations before seeding}';

    /**
     * The console command description.
     */
    protected $description = 'Setup demo data for Espaço Digital system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Configurando dados demo do Espaço Digital...');
        $this->newLine();

        // Verificar se deve executar fresh migrations
        if ($this->option('fresh')) {
            $this->warn('⚠️  Executando fresh migrations (todos os dados serão perdidos)...');
            
            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('❌ Operação cancelada.');
                return 1;
            }

            $this->info('🔄 Executando migrations...');
            Artisan::call('migrate:fresh');
            $this->info('✅ Migrations executadas com sucesso!');
        } else {
            $this->info('🔄 Executando migrations...');
            Artisan::call('migrate');
            $this->info('✅ Migrations executadas com sucesso!');
        }

        $this->newLine();

        // Executar seeders
        $this->info('🌱 Executando seeders...');
        
        try {
            Artisan::call('db:seed');
            $this->info('✅ Seeders executados com sucesso!');
        } catch (\Exception $e) {
            $this->error('❌ Erro ao executar seeders: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('🎉 Dados demo configurados com sucesso!');
        $this->newLine();

        // Exibir informações de acesso
        $this->displayAccessInfo();

        return 0;
    }

    /**
     * Display access information.
     */
    private function displayAccessInfo(): void
    {
        $this->info('📋 Informações de Acesso:');
        $this->newLine();

        // Super Admin
        $this->line('👑 <fg=cyan>Super Admin:</fg=cyan>');
        $this->line('   Email: <fg=yellow>admin@espacodigital.demo</fg=yellow>');
        $this->line('   Senha: <fg=yellow>password</fg=yellow>');
        $this->line('   URL: <fg=green>http://demo.espacodigital.local</fg=green>');
        $this->newLine();

        // Alunos
        $this->line('🎓 <fg=cyan>Alunos de Teste:</fg=cyan>');
        $this->line('   <fg=yellow>Aluno EF:</fg=yellow> João Silva Santos (15/03/2015)');
        $this->line('   <fg=yellow>Aluno EM:</fg=yellow> Maria Oliveira Costa (22/08/2007)');
        $this->line('   URL: <fg=green>http://demo.espacodigital.local/student/login</fg=green>');
        $this->newLine();

        // Configuração
        $this->line('⚙️  <fg=cyan>Configuração:</fg=cyan>');
        $this->line('   1. Adicione ao arquivo hosts: <fg=yellow>127.0.0.1 demo.espacodigital.local</fg=yellow>');
        $this->line('   2. Configure seu servidor web para apontar para este diretório');
        $this->line('   3. Acesse as URLs acima para testar o sistema');
        $this->newLine();

        // Painel Admin
        $this->line('🛠️  <fg=cyan>Painel Administrativo:</fg=cyan>');
        $this->line('   URL: <fg=green>http://demo.espacodigital.local/admin/users</fg=green>');
        $this->line('   Funcionalidades: Gestão de usuários, séries, turmas e disciplinas');
        $this->newLine();

        // Dados criados
        $this->line('📊 <fg=cyan>Dados Criados:</fg=cyan>');
        $this->line('   • 1 Unidade (Espaço Digital Demo)');
        $this->line('   • 1 Super Admin');
        $this->line('   • 12 Séries (EF I, EF II, EM)');
        $this->line('   • 6 Disciplinas');
        $this->line('   • 8 Turmas de exemplo');
        $this->line('   • 2 Alunos de teste');
        $this->line('   • Roles e permissões configurados');
        $this->newLine();

        $this->info('✨ Sistema pronto para uso!');
    }
}