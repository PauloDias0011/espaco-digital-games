<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Identity\App\Models\Classroom;
use Modules\Identity\App\Models\Series;
use Modules\Identity\App\Models\User;
use Modules\Tenancy\App\Models\Tenant;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            abort(404, 'Tenant não encontrado.');
        }

        // Buscar séries e turmas para filtros
        $series = Series::ofTenant($currentTenant->id)->ordered()->get();
        $classrooms = Classroom::ofTenant($currentTenant->id)->with('series')->get();

        // Query base para usuários do tenant atual
        $query = User::ofTenant($currentTenant->id)
            ->with(['series', 'classroom', 'tenant'])
            ->where('role', 'aluno');

        // Aplicar filtros
        if ($request->filled('series_id')) {
            $query->where('series_id', $request->series_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('user_code', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'series', 'classrooms'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): JsonResponse
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            return response()->json(['error' => 'Tenant não encontrado.'], 404);
        }

        $series = Series::ofTenant($currentTenant->id)->ordered()->get();
        $classrooms = Classroom::ofTenant($currentTenant->id)->with('series')->get();

        $modalContent = view('admin.users.partials.create-modal', compact('series', 'classrooms'))->render();

        return response()->json([
            'modal' => $modalContent
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            return response()->json(['error' => 'Tenant não encontrado.'], 404);
        }

        // Verificar se já existe um aluno com os mesmos dados
        $existingStudent = User::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('birthdate', $request->birthdate)
            ->where('tenant_id', $currentTenant->id)
            ->where('role', 'aluno')
            ->first();

        if ($existingStudent) {
            return response()->json([
                'error' => 'Já existe um aluno cadastrado com estes dados.'
            ], 422);
        }

        // Criar o aluno
        $user = User::create([
            'tenant_id' => $currentTenant->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'role' => 'aluno',
            'series_id' => $request->series_id,
            'class_id' => $request->class_id,
            'status' => 'active',
            'password' => bcrypt('default_password'), // Senha padrão temporária
        ]);

        // Atribuir role de aluno
        $user->assignTenantRole('aluno');

        return response()->json([
            'success' => true,
            'message' => 'Aluno cadastrado com sucesso!',
            'user' => $user->load(['series', 'classroom']),
            'user_code' => $user->user_code
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $user->load(['series', 'classroom', 'tenant']);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $currentTenant = tenant();
        $series = Series::ofTenant($currentTenant->id)->ordered()->get();
        $classrooms = Classroom::ofTenant($currentTenant->id)->with('series')->get();

        $modalContent = view('admin.users.partials.edit-modal', compact('user', 'series', 'classrooms'))->render();

        return response()->json([
            'modal' => $modalContent
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'series_id' => $request->series_id,
            'class_id' => $request->class_id,
            'status' => $request->status,
        ]);

        $user->load(['series', 'classroom']);

        return response()->json([
            'success' => true,
            'message' => 'Aluno atualizado com sucesso!',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Aluno removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao remover aluno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user status (suspend/activate).
     */
    public function toggleStatus(User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'ativado' : 'suspenso';

        return response()->json([
            'success' => true,
            'message' => "Aluno {$statusText} com sucesso!",
            'user' => $user->fresh(['series', 'classroom'])
        ]);
    }

    /**
     * Reset user progress (stub for Trail module).
     */
    public function resetProgress(User $user): JsonResponse
    {
        $this->authorize('update', $user);

        // TODO: Implementar quando o módulo Trail estiver pronto
        // Por enquanto, apenas um stub

        return response()->json([
            'success' => true,
            'message' => 'Progresso resetado com sucesso! (Funcionalidade será implementada no módulo Trail)'
        ]);
    }

    /**
     * Get classrooms for a specific series.
     */
    public function getClassrooms(Request $request): JsonResponse
    {
        $currentTenant = tenant();
        
        if (!$currentTenant) {
            return response()->json(['error' => 'Tenant não encontrado.'], 404);
        }

        $seriesId = $request->series_id;
        
        $classrooms = Classroom::ofTenant($currentTenant->id)
            ->when($seriesId, function ($query) use ($seriesId) {
                return $query->where('series_id', $seriesId);
            })
            ->with('series')
            ->get();

        return response()->json([
            'classrooms' => $classrooms
        ]);
    }

    /**
     * Bulk update users.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,suspend,delete,assign_series,assign_class',
            'series_id' => 'required_if:action,assign_series',
            'class_id' => 'required_if:action,assign_class',
        ]);

        $userIds = $request->user_ids;
        $action = $request->action;

        try {
            switch ($action) {
                case 'activate':
                    User::whereIn('id', $userIds)->update(['status' => 'active']);
                    $message = 'Alunos ativados com sucesso!';
                    break;

                case 'suspend':
                    User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                    $message = 'Alunos suspensos com sucesso!';
                    break;

                case 'delete':
                    User::whereIn('id', $userIds)->delete();
                    $message = 'Alunos removidos com sucesso!';
                    break;

                case 'assign_series':
                    User::whereIn('id', $userIds)->update(['series_id' => $request->series_id]);
                    $message = 'Série atribuída com sucesso!';
                    break;

                case 'assign_class':
                    User::whereIn('id', $userIds)->update(['class_id' => $request->class_id]);
                    $message = 'Turma atribuída com sucesso!';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar ação em lote: ' . $e->getMessage()
            ], 500);
        }
    }
}