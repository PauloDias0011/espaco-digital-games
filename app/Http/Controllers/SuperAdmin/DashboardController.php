<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Modules\Tenancy\App\Models\Tenant;

class DashboardController extends Controller
{
    public function index(): View
    {
        $page_title = 'Dashboard Super Admin';
        $page_description = 'Visão geral do sistema e unidades';

        // Estatísticas das Unidades
        $tenants = Tenant::all();
        $totalTenants = $tenants->count();
        $activeTenants = $tenants->filter(fn($t) => $t->isActive())->count();
        $inactiveTenants = $tenants->filter(fn($t) => $t->isInactive())->count();
        $totalDomains = DB::table('domains')->count();

        // Unidades criadas recentemente
        $recentTenants = Tenant::with('domains')
            ->latest()
            ->take(5)
            ->get();

        // Informações do Sistema
        $systemInfo = $this->getSystemInfo();

        // Uso de Recursos
        $resources = $this->getResourceUsage();

        // Logs de Erro (últimos 10)
        $errorLogs = $this->getRecentErrorLogs();

        // Estatísticas por Status
        $tenantsByStatus = [
            'active' => $activeTenants,
            'inactive' => $inactiveTenants,
        ];

        // Informações do Banco de Dados
        $databaseInfo = $this->getDatabaseInfo();

        return view('superadmin.dashboard.index', compact(
            'page_title',
            'page_description',
            'totalTenants',
            'activeTenants',
            'inactiveTenants',
            'totalDomains',
            'recentTenants',
            'systemInfo',
            'resources',
            'errorLogs',
            'tenantsByStatus',
            'databaseInfo'
        ));
    }

    /**
     * Obter informações do sistema
     */
    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'server_os' => PHP_OS,
            'server_name' => gethostname(),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
        ];
    }

    /**
     * Obter uso de recursos do servidor
     */
    private function getResourceUsage(): array
    {
        $resources = [
            'memory_limit' => ini_get('memory_limit'),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'memory_peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
        ];

        // CPU Usage (se disponível no Linux)
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $resources['cpu_load'] = [
                '1min' => round($load[0], 2),
                '5min' => round($load[1], 2),
                '15min' => round($load[2], 2),
            ];
        }

        // Disk Space
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;
        
        $resources['disk'] = [
            'total' => $this->formatBytes($diskTotal),
            'used' => $this->formatBytes($diskUsed),
            'free' => $this->formatBytes($diskFree),
            'percent' => round(($diskUsed / $diskTotal) * 100, 2),
        ];

        return $resources;
    }

    /**
     * Obter logs de erro recentes
     */
    private function getRecentErrorLogs(): array
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logFile)) {
            $content = File::get($logFile);
            $lines = explode("\n", $content);
            
            // Pegar as últimas 20 linhas e filtrar por erro
            $recentLines = array_slice(array_reverse($lines), 0, 100);
            
            foreach ($recentLines as $line) {
                if (str_contains($line, '[error]') || str_contains($line, 'ERROR') || str_contains($line, 'Exception')) {
                    $logs[] = [
                        'message' => substr($line, 0, 150),
                        'full' => $line,
                    ];
                    
                    if (count($logs) >= 10) {
                        break;
                    }
                }
            }
        }

        return $logs;
    }

    /**
     * Obter informações do banco de dados
     */
    private function getDatabaseInfo(): array
    {
        try {
            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");
            
            $info = [
                'driver' => $driver,
                'connection' => $connection,
            ];

            // Informações específicas do MySQL/MariaDB
            if ($driver === 'mysql') {
                $result = DB::select('SELECT VERSION() as version');
                $info['version'] = $result[0]->version ?? 'N/A';
                
                // Tamanho do banco
                $dbName = config("database.connections.{$connection}.database");
                $sizeQuery = DB::select("
                    SELECT 
                        SUM(data_length + index_length) as size
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?
                ", [$dbName]);
                
                $info['size'] = $this->formatBytes($sizeQuery[0]->size ?? 0);
                $info['database'] = $dbName;
            }

            return $info;
        } catch (\Exception $e) {
            return [
                'driver' => 'N/A',
                'error' => 'Não foi possível obter informações do banco',
            ];
        }
    }

    /**
     * Formatar bytes para tamanho legível
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
