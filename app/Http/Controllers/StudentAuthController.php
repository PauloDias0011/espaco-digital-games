<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StudentLoginRequest;
use App\Http\Requests\StudentRegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Modules\Identity\App\Models\Series;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;

class StudentAuthController extends Controller
{
    /**
     * Show the student registration form.
     */
    public function showRegisterForm(): View
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            abort(404, 'Tenant não encontrado.');
        }

        $series = Series::ofTenant($currentTenant->id)
            ->ordered()
            ->get();

        return view('student.auth.register', compact('series'));
    }

    /**
     * Handle student registration.
     */
    public function register(StudentRegisterRequest $request): RedirectResponse
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            return redirect()->back()
                ->with('error', 'Erro: Unidade não encontrada.')
                ->withInput();
        }

        // Verificar se já existe um aluno com os mesmos dados
        $existingStudent = User::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('birthdate', $request->birthdate)
            ->where('tenant_id', $currentTenant->id)
            ->where('role', 'aluno')
            ->first();

        if ($existingStudent) {
            return redirect()->back()
                ->with('error', 'Já existe um aluno cadastrado com estes dados.')
                ->withInput();
        }

        // Criar o aluno
        $student = User::create([
            'tenant_id' => $currentTenant->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'role' => 'aluno',
            'series_id' => $request->series_id,
            'status' => 'active',
            'password' => Hash::make('default_password'), // Senha padrão temporária
        ]);

        // Atribuir role de aluno
        $student->assignTenantRole('aluno');

        return redirect()->route('student.login')
            ->with('success', 'Cadastro realizado com sucesso! Seu código de acesso é: ' . $student->user_code);
    }

    /**
     * Show the student login form.
     */
    public function showLoginForm(): View
    {
        return view('student.auth.login');
    }

    /**
     * Handle student login.
     */
    public function login(StudentLoginRequest $request): RedirectResponse
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            return redirect()->back()
                ->with('error', 'Erro: Unidade não encontrada.')
                ->withInput();
        }

        // Buscar o aluno
        $student = User::findByStudentCredentials(
            $request->first_name,
            $request->last_name,
            $request->birthdate,
            $currentTenant->id
        );

        if (!$student) {
            return redirect()->back()
                ->with('error', 'Credenciais inválidas. Verifique seus dados.')
                ->withInput();
        }

        // Fazer login do aluno
        Auth::login($student, true); // true = remember me

        return redirect()->intended(route('trail.current'))
            ->with('success', 'Login realizado com sucesso! Bem-vindo, ' . $student->first_name . '!');
    }

    /**
     * Handle student logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login')
            ->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Show student profile.
     */
    public function profile(): View
    {
        $student = Auth::user();
        
        if (!$student || !$student->isStudent()) {
            abort(403, 'Acesso negado.');
        }

        return view('student.profile', compact('student'));
    }

    /**
     * Update student profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $student = Auth::user();
        
        if (!$student || !$student->isStudent()) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today'],
        ], [
            'first_name.required' => 'O nome é obrigatório.',
            'last_name.required' => 'O sobrenome é obrigatório.',
            'birthdate.required' => 'A data de nascimento é obrigatória.',
            'birthdate.before' => 'A data de nascimento deve ser anterior a hoje.',
        ]);

        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
        ]);

        return redirect()->back()
            ->with('success', 'Perfil atualizado com sucesso!');
    }
}