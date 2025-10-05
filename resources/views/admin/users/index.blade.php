{{-- Extends layout --}}
@extends('layout.default')

{{-- Page title --}}
@section('title', 'Gestão de Usuários')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Admin</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Usuários</a></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Gestão de Usuários</h4>
                    <div>
                        <button type="button" class="btn btn-primary" id="btnCreateUser">
                            <i class="fas fa-plus me-2"></i>
                            Cadastrar Aluno
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Filtros --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3" id="filterForm">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Buscar</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Nome, sobrenome ou código">
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="series_id" class="form-label">Série</label>
                                    <select class="form-select" id="series_id" name="series_id">
                                        <option value="">Todas as séries</option>
                                        @foreach($series as $serie)
                                            <option value="{{ $serie->id }}" {{ request('series_id') == $serie->id ? 'selected' : '' }}>
                                                {{ $serie->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="class_id" class="form-label">Turma</label>
                                    <select class="form-select" id="class_id" name="class_id">
                                        <option value="">Todas as turmas</option>
                                        @foreach($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}" {{ request('class_id') == $classroom->id ? 'selected' : '' }}>
                                                {{ $classroom->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspenso</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Ordenar</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Data de Cadastro</option>
                                        <option value="first_name" {{ request('sort') == 'first_name' ? 'selected' : '' }}>Nome</option>
                                        <option value="last_name" {{ request('sort') == 'last_name' ? 'selected' : '' }}>Sobrenome</option>
                                        <option value="user_code" {{ request('sort') == 'user_code' ? 'selected' : '' }}>Código</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Ações em lote --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnSelectAll">
                                    <i class="fas fa-check-square me-1"></i>
                                    Selecionar Todos
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="btnBulkActivate" disabled>
                                    <i class="fas fa-check me-1"></i>
                                    Ativar Selecionados
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="btnBulkSuspend" disabled>
                                    <i class="fas fa-pause me-1"></i>
                                    Suspender Selecionados
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="btnBulkDelete" disabled>
                                    <i class="fas fa-trash me-1"></i>
                                    Excluir Selecionados
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabela de usuários --}}
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Nome</th>
                                    <th>Código</th>
                                    <th>Série</th>
                                    <th>Turma</th>
                                    <th>Pontuação</th>
                                    <th>Status</th>
                                    <th>Última Atividade</th>
                                    <th width="150">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $user->full_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $user->birthdate->format('d/m/Y') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $user->user_code }}</span>
                                        </td>
                                        <td>
                                            {{ $user->series ? $user->series->name : 'Não informada' }}
                                        </td>
                                        <td>
                                            {{ $user->classroom ? $user->classroom->name : 'Não informada' }}
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">{{ $user->total_score }} pts</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->isActive() ? 'success' : 'danger' }}">
                                                {{ $user->isActive() ? 'Ativo' : 'Suspenso' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->last_activity }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-edit" 
                                                        data-user-id="{{ $user->id }}"
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-{{ $user->isActive() ? 'warning' : 'success' }} btn-toggle-status" 
                                                        data-user-id="{{ $user->id }}"
                                                        title="{{ $user->isActive() ? 'Suspender' : 'Ativar' }}">
                                                    <i class="fas fa-{{ $user->isActive() ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-reset-progress" 
                                                        data-user-id="{{ $user->id }}"
                                                        title="Resetar Progresso">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-delete" 
                                                        data-user-id="{{ $user->id }}"
                                                        title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <p>Nenhum aluno encontrado.</p>
                                                <button type="button" class="btn btn-primary" id="btnCreateUserEmpty">
                                                    <i class="fas fa-plus me-2"></i>
                                                    Cadastrar Primeiro Aluno
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação --}}
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Container --}}
<div id="modalContainer"></div>

{{-- Confirmação de Exclusão --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este aluno?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variáveis globais
    let currentUserId = null;
    let selectedUsers = [];

    // Event listeners
    document.getElementById('btnCreateUser')?.addEventListener('click', openCreateModal);
    document.getElementById('btnCreateUserEmpty')?.addEventListener('click', openCreateModal);
    document.getElementById('selectAll')?.addEventListener('change', toggleSelectAll);
    document.getElementById('confirmDelete')?.addEventListener('click', confirmDelete);

    // Bulk actions
    document.getElementById('btnBulkActivate')?.addEventListener('click', () => bulkAction('activate'));
    document.getElementById('btnBulkSuspend')?.addEventListener('click', () => bulkAction('suspend'));
    document.getElementById('btnBulkDelete')?.addEventListener('click', () => bulkAction('delete'));

    // User actions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-edit')) {
            const userId = e.target.closest('.btn-edit').dataset.userId;
            openEditModal(userId);
        }
        
        if (e.target.closest('.btn-toggle-status')) {
            const userId = e.target.closest('.btn-toggle-status').dataset.userId;
            toggleUserStatus(userId);
        }
        
        if (e.target.closest('.btn-reset-progress')) {
            const userId = e.target.closest('.btn-reset-progress').dataset.userId;
            resetUserProgress(userId);
        }
        
        if (e.target.closest('.btn-delete')) {
            const userId = e.target.closest('.btn-delete').dataset.userId;
            showDeleteModal(userId);
        }
    });

    // Funções
    function openCreateModal() {
        fetch('{{ route("admin.users.create") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalContainer').innerHTML = data.modal;
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('Erro ao carregar formulário de cadastro.', 'danger');
            });
    }

    function openEditModal(userId) {
        fetch(`/admin/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalContainer').innerHTML = data.modal;
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('Erro ao carregar formulário de edição.', 'danger');
            });
    }

    function toggleUserStatus(userId) {
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                location.reload();
            } else {
                showAlert(data.error || 'Erro ao alterar status.', 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('Erro ao alterar status do usuário.', 'danger');
        });
    }

    function resetUserProgress(userId) {
        if (!confirm('Tem certeza que deseja resetar o progresso deste aluno?')) {
            return;
        }

        fetch(`/admin/users/${userId}/reset-progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            showAlert(data.message, data.success ? 'success' : 'danger');
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('Erro ao resetar progresso.', 'danger');
        });
    }

    function showDeleteModal(userId) {
        currentUserId = userId;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function confirmDelete() {
        if (!currentUserId) return;

        fetch(`/admin/users/${currentUserId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                location.reload();
            } else {
                showAlert(data.error || 'Erro ao excluir usuário.', 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('Erro ao excluir usuário.', 'danger');
        });

        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    }

    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.user-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateBulkButtons();
    }

    function updateBulkButtons() {
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');
        const hasSelection = checkboxes.length > 0;
        
        document.getElementById('btnBulkActivate').disabled = !hasSelection;
        document.getElementById('btnBulkSuspend').disabled = !hasSelection;
        document.getElementById('btnBulkDelete').disabled = !hasSelection;
        
        selectedUsers = Array.from(checkboxes).map(cb => cb.value);
    }

    function bulkAction(action) {
        if (selectedUsers.length === 0) {
            showAlert('Selecione pelo menos um usuário.', 'warning');
            return;
        }

        if (action === 'delete' && !confirm(`Tem certeza que deseja excluir ${selectedUsers.length} usuário(s)?`)) {
            return;
        }

        const formData = new FormData();
        formData.append('user_ids', JSON.stringify(selectedUsers));
        formData.append('action', action);

        fetch('/admin/users/bulk-update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                location.reload();
            } else {
                showAlert(data.error || 'Erro ao processar ação.', 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('Erro ao processar ação em lote.', 'danger');
        });
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Event listeners para checkboxes individuais
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            updateBulkButtons();
        }
    });

    // Carregar turmas quando série mudar
    document.getElementById('series_id')?.addEventListener('change', function() {
        const seriesId = this.value;
        const classSelect = document.getElementById('class_id');
        
        fetch(`/admin/users/classrooms?series_id=${seriesId}`)
            .then(response => response.json())
            .then(data => {
                classSelect.innerHTML = '<option value="">Todas as turmas</option>';
                data.classrooms.forEach(classroom => {
                    const option = document.createElement('option');
                    option.value = classroom.id;
                    option.textContent = classroom.name;
                    classSelect.appendChild(option);
                });
            });
    });
});
</script>
@endpush
