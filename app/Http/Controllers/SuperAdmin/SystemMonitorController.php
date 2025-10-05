<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SystemMonitorController extends Controller
{
    /**
     * Obter informações de recursos em tempo real
     */
    public function resources(): JsonResponse
    {
        $resources = [
            'memory' => $this->getMemoryUsage(),
            'cpu' => $this->getCpuUsage(),
            'disk' => $this->getDiskUsage(),
            'timestamp' => now()->format('H:i:s'),
        ];

        return response()->json($resources);
    }

    /**
     * Obter uso de memória
     */
    private function getMemoryUsage(): array
    {
        $memoryLimit = $this->convertToBytes(ini_get('memory_limit'));
        $memoryUsage = memory_get_usage(true);
        $memoryPercent = $memoryLimit > 0 ? round(($memoryUsage / $memoryLimit) * 100, 2) : 0;

        return [
            'used' => $this->formatBytes($memoryUsage),
            'used_bytes' => $memoryUsage,
            'limit' => $this->formatBytes($memoryLimit),
            'limit_bytes' => $memoryLimit,
            'percent' => $memoryPercent,
            'peak' => $this->formatBytes(memory_get_peak_usage(true)),
        ];
    }

    /**
     * Obter uso de CPU
     */
    private function getCpuUsage(): array
    {
        $cpuData = [
            'available' => function_exists('sys_getloadavg'),
        ];

        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $cpuData['load'] = [
                '1min' => round($load[0], 2),
                '5min' => round($load[1], 2),
                '15min' => round($load[2], 2),
            ];

            // Estimar percentual baseado no número de cores
            $cores = $this->getCpuCores();
            if ($cores > 0) {
                $cpuData['percent'] = min(100, round(($load[0] / $cores) * 100, 2));
            } else {
                $cpuData['percent'] = min(100, round($load[0] * 20, 2)); // Estimativa
            }
            
            $cpuData['cores'] = $cores;
        } else {
            $cpuData['load'] = null;
            $cpuData['percent'] = 0;
        }

        return $cpuData;
    }

    /**
     * Obter uso de disco
     */
    private function getDiskUsage(): array
    {
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;

        return [
            'total' => $this->formatBytes($diskTotal),
            'used' => $this->formatBytes($diskUsed),
            'free' => $this->formatBytes($diskFree),
            'percent' => round(($diskUsed / $diskTotal) * 100, 2),
        ];
    }

    /**
     * Obter número de cores do CPU
     */
    private function getCpuCores(): int
    {
        $cores = 0;

        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $cores = count($matches[0]);
        } elseif (DIRECTORY_SEPARATOR === '\\') {
            // Windows
            $process = @popen('wmic cpu get NumberOfCores', 'rb');
            if (false !== $process) {
                fgets($process);
                $cores = intval(fgets($process));
                pclose($process);
            }
        }

        return $cores;
    }

    /**
     * Converter string de memória para bytes
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;

        switch ($last) {
            case 'g':
                $value *= 1024;
                // no break
            case 'm':
                $value *= 1024;
                // no break
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Formatar bytes para tamanho legível
     */
    private function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
