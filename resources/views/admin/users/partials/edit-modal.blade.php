<!-- Modal de Edição -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fas fa-user-edit me-2"></i>
                    Editar Aluno: {{ $user->full_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" data-user-id="{{ $user->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_first_name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="edit_first_name" 
                                   name="first_name" 
                                   value="{{ $user->first_name }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_last_name" class="form-label">Sobrenome <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="edit_last_name" 
                                   name="last_name" 
                                   value="{{ $user->last_name }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_birthdate" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control" 
                               id="edit_birthdate" 
                               name="birthdate" 
                               value="{{ $user->birthdate->format('Y-m-d') }}"
                               max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                               required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_series_id" class="form-label">Série <span class="text-danger">*</span></label>
                            <select class="form-select" 
                                    id="edit_series_id" 
                                    name="series_id" 
                                    required>
                                <option value="">Selecione uma série</option>
                                @foreach($series as $serie)
                                    <option value="{{ $serie->id }}" {{ $user->series_id == $serie->id ? 'selected' : '' }}>
                                        {{ $serie->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_class_id" class="form-label">Turma</label>
                            <select class="form-select" 
                                    id="edit_class_id" 
                                    name="class_id">
                                <option value="">Selecione uma turma</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" 
                                            data-series="{{ $classroom->series_id }}"
                                            {{ $user->class_id == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" 
                                id="edit_status" 
                                name="status" 
                                required>
                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspenso</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informações do Aluno:</strong><br>
                        <strong>Código:</strong> {{ $user->user_code }}<br>
                        <strong>Data de Cadastro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}<br>
                        <strong>Última Atividade:</strong> {{ $user->last_activity }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editUserForm');
    const seriesSelect = document.getElementById('edit_series_id');
    const classSelect = document.getElementById('edit_class_id');
    const userId = form.dataset.userId;

    // Carregar turmas quando série mudar
    seriesSelect.addEventListener('change', function() {
        const seriesId = this.value;
        const options = classSelect.querySelectorAll('option[data-series]');
        
        // Resetar seleção se a série mudou
        if (seriesId !== '{{ $user->series_id }}') {
            classSelect.value = '';
        }
        
        // Mostrar/esconder opções baseado na série
        options.forEach(option => {
            if (seriesId === '' || option.dataset.series === seriesId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Submissão do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Limpar erros anteriores
        clearErrors();
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        fetch(`/admin/users/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message);
                
                // Fechar modal e recarregar página
                bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showErrors(data.errors || { general: [data.error] });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showErrors({ general: ['Erro ao atualizar aluno. Tente novamente.'] });
        });
    });

    function clearErrors() {
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = '';
            }
        });
    }

    function showErrors(errors) {
        Object.keys(errors).forEach(field => {
            if (field === 'general') {
                // Mostrar erro geral
                showAlert(errors[field][0], 'danger');
            } else {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = errors[field][0];
                    }
                }
            }
        });
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.querySelector('.modal-body').insertBefore(alertDiv, document.querySelector('.modal-body').firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    function showSuccessMessage(message) {
        showAlert(message, 'success');
    }
});
</script>
