<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentBelongsToTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Se não estiver autenticado, redirecionar para login
        if (!$user) {
            return redirect()->route('student.login');
        }

        // Verificar se o usuário é um aluno
        if (!$user->isStudent()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Esta área é restrita a alunos.');
        }

        // Verificar se o aluno pertence ao tenant atual
        $currentTenant = tenant();
        
        if (!$currentTenant || $user->tenant_id !== $currentTenant->id) {
            Auth::logout();
            
            return redirect()->route('student.login')
                ->with('error', 'Você não tem acesso a esta unidade. Verifique seus dados de login.');
        }

        // Verificar se o aluno está ativo
        if (!$user->isActive()) {
            Auth::logout();
            
            return redirect()->route('student.login')
                ->with('error', 'Sua conta está suspensa. Entre em contato com a administração.');
        }

        return $next($request);
    }
}