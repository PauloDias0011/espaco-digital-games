<?php

declare(strict_types=1);

namespace Modules\Tenancy\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tenancy\App\Models\Tenant;
use Modules\Tenancy\App\Models\TenantDomain;

class TenancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar unidade demo se não existir
        $demoTenant = Tenant::firstOrCreate(
            ['id' => 'demo-tenant-id'],
            [
                'data' => [
                    'name' => 'Espaço Digital Demo',
                    'slug' => 'espaco-digital-demo',
                    'status' => 'active',
                    'settings' => [
                        'theme' => 'light',
                        'language' => 'pt_BR',
                        'timezone' => 'America/Sao_Paulo',
                        'demo' => true,
                    ],
                ],
            ]
        );

        // Criar domínio demo se não existir
        TenantDomain::firstOrCreate(
            ['domain' => 'demo.localhost'],
            [
                'tenant_id' => $demoTenant->id,
            ]
        );

        // Criar algumas unidades adicionais para demonstração
        $tenants = [
            [
                'id' => 'escola-joao-silva-id',
                'name' => 'Escola Municipal João Silva',
                'slug' => 'escola-municipal-joao-silva',
                'status' => 'active',
                'domains' => ['escola-joao-silva.localhost'],
                'settings' => [
                    'theme' => 'light',
                    'language' => 'pt_BR',
                    'timezone' => 'America/Sao_Paulo',
                    'school_type' => 'municipal',
                ],
            ],
            [
                'id' => 'colegio-excelencia-id',
                'name' => 'Colégio Particular Excelência',
                'slug' => 'colegio-particular-excelencia',
                'status' => 'active',
                'domains' => ['excelencia.localhost', 'colegio-excelencia.localhost'],
                'settings' => [
                    'theme' => 'dark',
                    'language' => 'pt_BR',
                    'timezone' => 'America/Sao_Paulo',
                    'school_type' => 'private',
                ],
            ],
            [
                'id' => 'instituto-federal-id',
                'name' => 'Instituto Federal Tecnológico',
                'slug' => 'instituto-federal-tecnologico',
                'status' => 'inactive',
                'domains' => ['iftecnologico.localhost'],
                'settings' => [
                    'theme' => 'light',
                    'language' => 'pt_BR',
                    'timezone' => 'America/Sao_Paulo',
                    'school_type' => 'federal',
                ],
            ],
        ];

        foreach ($tenants as $tenantData) {
            $domains = $tenantData['domains'];
            $tenantId = $tenantData['id'];
            unset($tenantData['domains'], $tenantData['id']);

            $tenant = Tenant::firstOrCreate(
                ['id' => $tenantId],
                [
                    'data' => $tenantData,
                ]
            );

            foreach ($domains as $domain) {
                TenantDomain::firstOrCreate(
                    ['domain' => $domain],
                    ['tenant_id' => $tenant->id]
                );
            }
        }

        $this->command->info('Seeders de Tenancy executados com sucesso!');
        $this->command->info('Unidades criadas:');
        
        $createdTenants = Tenant::with('domains')->get();
        foreach ($createdTenants as $tenant) {
            $domains = $tenant->domains->pluck('domain')->join(', ');
            $this->command->line("- {$tenant->getName()} ({$tenant->getStatus()}) - Domínios: {$domains}");
        }
    }
}