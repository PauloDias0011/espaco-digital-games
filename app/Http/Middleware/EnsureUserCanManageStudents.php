<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManageStudents
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Se não estiver autenticado, redirecionar para login
        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar se o usuário pode gerenciar estudantes
        if (!$user->canManageStudents()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acesso negado. Você não tem permissão para gerenciar estudantes.');
        }

        // Verificar se o usuário pertence ao tenant atual
        $currentTenant = tenant();
        
        if (!$currentTenant || $user->tenant_id !== $currentTenant->id) {
            Auth::logout();
            
            return redirect()->route('login')
                ->with('error', 'Você não tem acesso a esta unidade.');
        }

        // Verificar se o usuário está ativo
        if (!$user->isActive()) {
            Auth::logout();
            
            return redirect()->route('login')
                ->with('error', 'Sua conta está suspensa. Entre em contato com a administração.');
        }

        return $next($request);
    }
}