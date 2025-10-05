{{-- Extends layout --}}
@extends('layout.default')

{{-- Page Title --}}
@section('title', 'Dashboard Super Admin')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Super Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>

    {{-- Cards de Estatísticas --}}
    <div class="row">
        {{-- Total de Unidades --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card" style="border-left: 4px solid #2ECC71;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success-light me-3">
                            <i class="bi bi-building" style="font-size: 2rem; color: #2ECC71;"></i>
                        </div>
                        <div>
                            <h2 class="mb-0 text-success">{{ $totalTenants }}</h2>
                            <p class="mb-0">Total de Unidades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Unidades Ativas --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card" style="border-left: 4px solid #5B6FA8;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary-light me-3">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: #5B6FA8;"></i>
                        </div>
                        <div>
                            <h2 class="mb-0" style="color: #5B6FA8;">{{ $activeTenants }}</h2>
                            <p class="mb-0">Unidades Ativas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Unidades Inativas --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card" style="border-left: 4px solid #FF5757;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-danger-light me-3">
                            <i class="bi bi-pause-circle" style="font-size: 2rem; color: #FF5757;"></i>
                        </div>
                        <div>
                            <h2 class="mb-0" style="color: #FF5757;">{{ $inactiveTenants }}</h2>
                            <p class="mb-0">Unidades Inativas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total de Domínios --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card" style="border-left: 4px solid #FFB84D;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-warning-light me-3">
                            <i class="bi bi-globe" style="font-size: 2rem; color: #FFB84D;"></i>
                        </div>
                        <div>
                            <h2 class="mb-0" style="color: #FFB84D;">{{ $totalDomains }}</h2>
                            <p class="mb-0">Domínios Cadastrados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Segunda Linha de Cards --}}
    <div class="row">
        {{-- Informações do Sistema --}}
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-info-circle me-2"></i>Informações do Sistema</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>PHP</strong></td>
                                    <td>{{ $systemInfo['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Laravel</strong></td>
                                    <td>{{ $systemInfo['laravel_version'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sistema Operacional</strong></td>
                                    <td>{{ $systemInfo['server_os'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Servidor</strong></td>
                                    <td>{{ $systemInfo['server_software'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hostname</strong></td>
                                    <td>{{ $systemInfo['server_name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Timezone</strong></td>
                                    <td>{{ $systemInfo['timezone'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ambiente</strong></td>
                                    <td>
                                        <span class="badge {{ $systemInfo['environment'] === 'production' ? 'badge-success' : 'badge-warning' }}">
                                            {{ strtoupper($systemInfo['environment']) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Modo Debug</strong></td>
                                    <td>
                                        <span class="badge {{ $systemInfo['debug_mode'] ? 'badge-danger' : 'badge-success' }}">
                                            {{ $systemInfo['debug_mode'] ? 'ATIVADO' : 'DESATIVADO' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Uso de Recursos --}}
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-speedometer2 me-2"></i>Uso de Recursos</h4>
                </div>
                <div class="card-body">
                    {{-- Memória --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <i class="bi bi-memory text-success"></i> <strong>Memória</strong>
                                <span class="badge badge-sm badge-secondary ms-1" id="memory-status">
                                    <i class="bi bi-circle-fill blink"></i> Tempo Real
                                </span>
                            </span>
                            <span id="memory-usage">{{ $resources['memory_usage'] }} / {{ $resources['memory_limit'] }}</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            @php
                                $memoryPercent = min(90, rand(30, 70)); // Simulado
                            @endphp
                            <div class="progress-bar bg-success" id="memory-bar" style="width: {{ $memoryPercent }}%"></div>
                        </div>
                        <small class="text-muted">Pico: <span id="memory-peak">{{ $resources['memory_peak'] }}</span></small>
                    </div>

                    {{-- CPU Load --}}
                    @if(isset($resources['cpu_load']))
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <i class="bi bi-cpu text-primary"></i> <strong>Carga do CPU</strong>
                                <span class="badge badge-sm badge-secondary ms-1" id="cpu-status">
                                    <i class="bi bi-circle-fill blink"></i> Tempo Real
                                </span>
                            </span>
                            <span id="cpu-percent" class="badge badge-info">0%</span>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="p-2 border rounded" id="cpu-1min-container">
                                    <h5 class="mb-0" style="color: #5B6FA8;" id="cpu-1min">{{ $resources['cpu_load']['1min'] }}</h5>
                                    <small class="text-muted">1 min</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded" id="cpu-5min-container">
                                    <h5 class="mb-0" style="color: #5B6FA8;" id="cpu-5min">{{ $resources['cpu_load']['5min'] }}</h5>
                                    <small class="text-muted">5 min</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded" id="cpu-15min-container">
                                    <h5 class="mb-0" style="color: #5B6FA8;" id="cpu-15min">{{ $resources['cpu_load']['15min'] }}</h5>
                                    <small class="text-muted">15 min</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Disco --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-hdd text-warning"></i> <strong>Armazenamento</strong></span>
                            <span>{{ $resources['disk']['used'] }} / {{ $resources['disk']['total'] }}</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar {{ $resources['disk']['percent'] > 80 ? 'bg-danger' : 'bg-warning' }}" 
                                 style="width: {{ $resources['disk']['percent'] }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $resources['disk']['percent'] }}% utilizado - {{ $resources['disk']['free'] }} livre</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Terceira Linha --}}
    <div class="row">
        {{-- Banco de Dados --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-database me-2"></i>Banco de Dados</h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Driver:</strong></td>
                                <td>{{ strtoupper($databaseInfo['driver']) }}</td>
                            </tr>
                            @if(isset($databaseInfo['version']))
                            <tr>
                                <td><strong>Versão:</strong></td>
                                <td>{{ $databaseInfo['version'] }}</td>
                            </tr>
                            @endif
                            @if(isset($databaseInfo['database']))
                            <tr>
                                <td><strong>Database:</strong></td>
                                <td><code>{{ $databaseInfo['database'] }}</code></td>
                            </tr>
                            @endif
                            @if(isset($databaseInfo['size']))
                            <tr>
                                <td><strong>Tamanho:</strong></td>
                                <td>{{ $databaseInfo['size'] }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Conexão:</strong></td>
                                <td><span class="badge badge-success">Ativa</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Unidades Recentes --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-clock-history me-2"></i>Unidades Criadas Recentemente</h4>
                    <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-sm btn-primary">Ver Todas</a>
                </div>
                <div class="card-body">
                    @if($recentTenants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Status</th>
                                    <th>Domínios</th>
                                    <th>Criado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTenants as $tenant)
                                <tr>
                                    <td><strong>{{ $tenant->getName() }}</strong></td>
                                    <td>
                                        <span class="badge {{ $tenant->isActive() ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $tenant->isActive() ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($tenant->domains && $tenant->domains->count() > 0)
                                            @foreach($tenant->domains as $domain)
                                                <span class="badge badge-primary badge-sm">{{ $domain->domain }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $tenant->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-2">Nenhuma unidade cadastrada ainda</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Logs de Erro --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="bi bi-exclamation-triangle me-2"></i>Logs de Erro Recentes</h4>
                    <button class="btn btn-sm btn-secondary" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Atualizar
                    </button>
                </div>
                <div class="card-body">
                    @if(count($errorLogs) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Mensagem</th>
                                    <th width="100">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($errorLogs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <code style="font-size: 0.85rem;">{{ $log['message'] }}</code>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" 
                                                onclick="alert('{{ addslashes($log['full']) }}')"
                                                title="Ver completo">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-success text-center">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Nenhum erro registrado!</strong> O sistema está funcionando perfeitamente.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.bg-success-light {
    background-color: rgba(46, 204, 113, 0.1);
}

.bg-primary-light {
    background-color: rgba(91, 111, 168, 0.1);
}

.bg-danger-light {
    background-color: rgba(255, 87, 87, 0.1);
}

.bg-warning-light {
    background-color: rgba(255, 184, 77, 0.1);
}

.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Animação de pulsação para indicar tempo real */
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.blink {
    animation: blink 2s infinite;
}

/* Transições suaves para as atualizações */
.progress-bar, #cpu-1min, #cpu-5min, #cpu-15min, #cpu-percent {
    transition: all 0.5s ease;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let updateInterval;
    let isVisible = true;

    // Função para atualizar os recursos
    function updateResources() {
        fetch('{{ route('superadmin.api.resources') }}')
            .then(response => response.json())
            .then(data => {
                updateMemory(data.memory);
                updateCpu(data.cpu);
                updateDisk(data.disk);
            })
            .catch(error => {
                console.error('Erro ao atualizar recursos:', error);
            });
    }

    // Atualizar informações de memória
    function updateMemory(memory) {
        const memoryBar = document.getElementById('memory-bar');
        const memoryUsage = document.getElementById('memory-usage');
        const memoryPeak = document.getElementById('memory-peak');

        if (memoryBar && memoryUsage) {
            memoryUsage.textContent = `${memory.used} / ${memory.limit}`;
            memoryBar.style.width = `${memory.percent}%`;
            
            // Mudar cor baseado no uso
            memoryBar.className = 'progress-bar';
            if (memory.percent > 90) {
                memoryBar.classList.add('bg-danger');
            } else if (memory.percent > 70) {
                memoryBar.classList.add('bg-warning');
            } else {
                memoryBar.classList.add('bg-success');
            }
        }

        if (memoryPeak) {
            memoryPeak.textContent = memory.peak;
        }
    }

    // Atualizar informações de CPU
    function updateCpu(cpu) {
        if (!cpu.available) return;

        const cpu1min = document.getElementById('cpu-1min');
        const cpu5min = document.getElementById('cpu-5min');
        const cpu15min = document.getElementById('cpu-15min');
        const cpuPercent = document.getElementById('cpu-percent');

        if (cpu1min) cpu1min.textContent = cpu.load['1min'];
        if (cpu5min) cpu5min.textContent = cpu.load['5min'];
        if (cpu15min) cpu15min.textContent = cpu.load['15min'];
        
        if (cpuPercent) {
            cpuPercent.textContent = `${cpu.percent}%`;
            
            // Mudar cor do badge baseado no uso
            cpuPercent.className = 'badge badge-sm';
            if (cpu.percent > 80) {
                cpuPercent.classList.add('badge-danger');
            } else if (cpu.percent > 50) {
                cpuPercent.classList.add('badge-warning');
            } else {
                cpuPercent.classList.add('badge-success');
            }
        }

        // Mudar cor dos containers baseado na carga
        updateCpuContainerColor('cpu-1min-container', cpu.load['1min'], cpu.cores);
        updateCpuContainerColor('cpu-5min-container', cpu.load['5min'], cpu.cores);
        updateCpuContainerColor('cpu-15min-container', cpu.load['15min'], cpu.cores);
    }

    // Atualizar cor do container de CPU
    function updateCpuContainerColor(containerId, load, cores) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const percent = cores > 0 ? (load / cores) * 100 : load * 20;
        
        container.style.borderColor = '';
        if (percent > 80) {
            container.style.borderColor = '#FF5757';
            container.style.borderWidth = '2px';
        } else if (percent > 50) {
            container.style.borderColor = '#FFB84D';
            container.style.borderWidth = '2px';
        } else {
            container.style.borderColor = '#2ECC71';
            container.style.borderWidth = '1px';
        }
    }

    // Atualizar informações de disco
    function updateDisk(disk) {
        // Disco não precisa de atualização tão frequente, mas podemos implementar se necessário
    }

    // Detectar se a página está visível
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            isVisible = false;
            if (updateInterval) {
                clearInterval(updateInterval);
            }
        } else {
            isVisible = true;
            updateResources(); // Atualizar imediatamente
            startMonitoring();
        }
    });

    // Iniciar monitoramento
    function startMonitoring() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
        updateInterval = setInterval(updateResources, 3000); // Atualizar a cada 3 segundos
    }

    // Parar monitoramento quando sair da página
    window.addEventListener('beforeunload', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });

    // Iniciar monitoramento
    updateResources(); // Primeira atualização imediata
    startMonitoring();

    console.log('✓ Monitoramento em tempo real ativado (atualização a cada 3s)');
});
</script>
@endpush
