<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Identity\App\Models\Classroom;
use Modules\Identity\App\Models\Series;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;
use Modules\Tenancy\App\Models\TenantDomain;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Criando dados demo...');

        // 1. Criar Unidade "Espaço Digital Demo"
        $tenant = $this->createDemoTenant();
        $this->command->info('✅ Unidade criada: ' . $tenant->name);

        // 2. Criar Usuário Super Admin
        $superAdmin = $this->createSuperAdmin($tenant);
        $this->command->info('✅ Super Admin criado: ' . $superAdmin->full_name);

        // 3. Criar Séries padrão
        $series = $this->createSeries($tenant);
        $this->command->info('✅ Séries criadas: ' . $series->count() . ' séries');

        // 4. Criar Disciplinas padrão
        $subjects = $this->createSubjects($tenant);
        $this->command->info('✅ Disciplinas criadas: ' . $subjects->count() . ' disciplinas');

        // 5. Criar Turmas de exemplo
        $classrooms = $this->createClassrooms($tenant, $series);
        $this->command->info('✅ Turmas criadas: ' . $classrooms->count() . ' turmas');

        // 6. Criar Alunos de teste
        $students = $this->createDemoStudents($tenant, $series, $classrooms);
        $this->command->info('✅ Alunos criados: ' . $students->count() . ' alunos');

        // 7. Criar Roles e Permissões para o tenant
        $this->createTenantRoles($tenant);

        $this->command->info('🎉 Dados demo criados com sucesso!');
        $this->command->info('📋 Resumo:');
        $this->command->info('   - Unidade: ' . $tenant->name);
        $this->command->info('   - Domínio: ' . $tenant->domains->first()->domain ?? 'N/A');
        $this->command->info('   - Super Admin: admin@espacodigital.demo (senha: password)');
        $this->command->info('   - Séries: ' . $series->count());
        $this->command->info('   - Disciplinas: ' . $subjects->count());
        $this->command->info('   - Turmas: ' . $classrooms->count());
        $this->command->info('   - Alunos: ' . $students->count());
    }

    /**
     * Criar unidade demo.
     */
    private function createDemoTenant(): Tenant
    {
        $tenant = Tenant::firstOrCreate(
            ['id' => 'espaco-digital-demo'],
            [
                'id' => 'espaco-digital-demo',
                'data' => [
                    'name' => 'Espaço Digital Demo',
                    'slug' => 'espaco-digital-demo',
                    'status' => 'active',
                    'settings' => [
                        'theme' => 'getskills',
                        'language' => 'pt-BR',
                        'timezone' => 'America/Sao_Paulo',
                    ]
                ]
            ]
        );

        // Criar domínio para o tenant
        TenantDomain::firstOrCreate(
            ['domain' => 'demo.espacodigital.local'],
            [
                'domain' => 'demo.espacodigital.local',
                'tenant_id' => $tenant->id,
            ]
        );

        return $tenant;
    }

    /**
     * Criar super admin.
     */
    private function createSuperAdmin(Tenant $tenant): User
    {
        $superAdmin = User::firstOrCreate(
            [
                'first_name' => 'Admin',
                'last_name' => 'Sistema',
                'tenant_id' => $tenant->id,
            ],
            [
                'tenant_id' => $tenant->id,
                'first_name' => 'Admin',
                'last_name' => 'Sistema',
                'birthdate' => '1980-01-01',
                'role' => 'superadmin',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Atribuir role de superadmin (global)
        $superAdmin->assignRole('superadmin');

        return $superAdmin;
    }

    /**
     * Criar séries padrão.
     */
    private function createSeries(Tenant $tenant)
    {
        $seriesData = [
            // Ensino Fundamental I
            ['name' => '1º Ano EF', 'description' => 'Primeiro Ano do Ensino Fundamental', 'order' => 1],
            ['name' => '2º Ano EF', 'description' => 'Segundo Ano do Ensino Fundamental', 'order' => 2],
            ['name' => '3º Ano EF', 'description' => 'Terceiro Ano do Ensino Fundamental', 'order' => 3],
            ['name' => '4º Ano EF', 'description' => 'Quarto Ano do Ensino Fundamental', 'order' => 4],
            ['name' => '5º Ano EF', 'description' => 'Quinto Ano do Ensino Fundamental', 'order' => 5],
            
            // Ensino Fundamental II
            ['name' => '6º Ano EF', 'description' => 'Sexto Ano do Ensino Fundamental', 'order' => 6],
            ['name' => '7º Ano EF', 'description' => 'Sétimo Ano do Ensino Fundamental', 'order' => 7],
            ['name' => '8º Ano EF', 'description' => 'Oitavo Ano do Ensino Fundamental', 'order' => 8],
            ['name' => '9º Ano EF', 'description' => 'Nono Ano do Ensino Fundamental', 'order' => 9],
            
            // Ensino Médio
            ['name' => '1º Ano EM', 'description' => 'Primeiro Ano do Ensino Médio', 'order' => 10],
            ['name' => '2º Ano EM', 'description' => 'Segundo Ano do Ensino Médio', 'order' => 11],
            ['name' => '3º Ano EM', 'description' => 'Terceiro Ano do Ensino Médio', 'order' => 12],
        ];

        $series = collect();
        foreach ($seriesData as $data) {
            $serie = Series::firstOrCreate(
                [
                    'name' => $data['name'],
                    'tenant_id' => $tenant->id,
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'order' => $data['order'],
                ]
            );
            $series->push($serie);
        }

        return $series;
    }

    /**
     * Criar disciplinas padrão.
     */
    private function createSubjects(Tenant $tenant)
    {
        $subjectsData = [
            ['name' => 'Português', 'description' => 'Língua Portuguesa e Literatura', 'color' => '#3498db'],
            ['name' => 'Matemática', 'description' => 'Matemática e Lógica', 'color' => '#e74c3c'],
            ['name' => 'Ciências', 'description' => 'Ciências da Natureza', 'color' => '#2ecc71'],
            ['name' => 'História', 'description' => 'História e Cultura', 'color' => '#f39c12'],
            ['name' => 'Geografia', 'description' => 'Geografia e Espaço', 'color' => '#9b59b6'],
            ['name' => 'Inglês', 'description' => 'Língua Inglesa', 'color' => '#1abc9c'],
        ];

        $subjects = collect();
        foreach ($subjectsData as $data) {
            $subject = \App\Models\Subject::firstOrCreate(
                [
                    'name' => $data['name'],
                    'tenant_id' => $tenant->id,
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'color' => $data['color'],
                    'active' => true,
                ]
            );
            $subjects->push($subject);
        }

        return $subjects;
    }

    /**
     * Criar turmas de exemplo.
     */
    private function createClassrooms(Tenant $tenant, $series)
    {
        $classroomsData = [
            // EF I
            ['name' => 'Turma A', 'series_id' => $series->where('name', '1º Ano EF')->first()->id, 'year' => 2024],
            ['name' => 'Turma A', 'series_id' => $series->where('name', '5º Ano EF')->first()->id, 'year' => 2024],
            
            // EF II
            ['name' => 'Turma A', 'series_id' => $series->where('name', '6º Ano EF')->first()->id, 'year' => 2024],
            ['name' => 'Turma B', 'series_id' => $series->where('name', '6º Ano EF')->first()->id, 'year' => 2024],
            ['name' => 'Turma A', 'series_id' => $series->where('name', '9º Ano EF')->first()->id, 'year' => 2024],
            
            // EM
            ['name' => 'Turma A', 'series_id' => $series->where('name', '1º Ano EM')->first()->id, 'year' => 2024],
            ['name' => 'Turma B', 'series_id' => $series->where('name', '1º Ano EM')->first()->id, 'year' => 2024],
            ['name' => 'Turma A', 'series_id' => $series->where('name', '3º Ano EM')->first()->id, 'year' => 2024],
        ];

        $classrooms = collect();
        foreach ($classroomsData as $data) {
            $classroom = Classroom::firstOrCreate(
                [
                    'name' => $data['name'],
                    'series_id' => $data['series_id'],
                    'tenant_id' => $tenant->id,
                    'year' => $data['year'],
                ],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $data['name'],
                    'series_id' => $data['series_id'],
                    'year' => $data['year'],
                ]
            );
            $classrooms->push($classroom);
        }

        return $classrooms;
    }

    /**
     * Criar alunos de teste.
     */
    private function createDemoStudents(Tenant $tenant, $series, $classrooms)
    {
        $studentsData = [
            // Aluno do EF
            [
                'first_name' => 'João',
                'last_name' => 'Silva Santos',
                'birthdate' => '2015-03-15',
                'series_id' => $series->where('name', '1º Ano EF')->first()->id,
                'class_id' => $classrooms->where('name', 'Turma A')->where('series_id', $series->where('name', '1º Ano EF')->first()->id)->first()->id,
            ],
            
            // Aluno do EM
            [
                'first_name' => 'Maria',
                'last_name' => 'Oliveira Costa',
                'birthdate' => '2007-08-22',
                'series_id' => $series->where('name', '3º Ano EM')->first()->id,
                'class_id' => $classrooms->where('name', 'Turma A')->where('series_id', $series->where('name', '3º Ano EM')->first()->id)->first()->id,
            ],
        ];

        $students = collect();
        foreach ($studentsData as $data) {
            $student = User::firstOrCreate(
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'birthdate' => $data['birthdate'],
                    'tenant_id' => $tenant->id,
                ],
                [
                    'tenant_id' => $tenant->id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'birthdate' => $data['birthdate'],
                    'role' => 'aluno',
                    'series_id' => $data['series_id'],
                    'class_id' => $data['class_id'],
                    'status' => 'active',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Atribuir role de aluno
            $student->assignTenantRole('aluno');
            $students->push($student);
        }

        return $students;
    }

    /**
     * Criar roles e permissões para o tenant.
     */
    private function createTenantRoles(Tenant $tenant): void
    {
        // Criar permissões específicas do tenant
        $permissions = [
            'users.create', 'users.read', 'users.update', 'users.delete',
            'classes.create', 'classes.read', 'classes.update', 'classes.delete',
            'series.create', 'series.read', 'series.update', 'series.delete',
            'subjects.create', 'subjects.read', 'subjects.update', 'subjects.delete',
            'trails.create', 'trails.read', 'trails.update', 'trails.delete',
            'games.create', 'games.read', 'games.update', 'games.delete',
            'reports.read',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
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
        }

        // Criar roles do tenant
        $roles = [
            'admin' => [
                'permissions' => $permissions,
            ],
            'tutor' => [
                'permissions' => [
                    'users.read',
                    'classes.read',
                    'series.read',
                    'subjects.read',
                    'trails.create', 'trails.read', 'trails.update', 'trails.delete',
                    'games.create', 'games.read', 'games.update', 'games.delete',
                    'reports.read',
                ],
            ],
            'aluno' => [
                'permissions' => [
                    'trails.read',
                    'games.read',
                    'games.play',
                ],
            ],
        ];

        foreach ($roles as $roleKey => $roleData) {
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

            // Atribuir permissões ao role
            foreach ($roleData['permissions'] as $permissionName) {
                $permission = Permission::where('name', $permissionName)
                    ->where('tenant_id', $tenant->id)
                    ->first();
                
                if ($permission && !$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}