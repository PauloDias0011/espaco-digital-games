<!-- Modal de Cadastro -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    Cadastrar Novo Aluno
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_first_name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="create_first_name" 
                                   name="first_name" 
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create_last_name" class="form-label">Sobrenome <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="create_last_name" 
                                   name="last_name" 
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="create_birthdate" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control" 
                               id="create_birthdate" 
                               name="birthdate" 
                               max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                               required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_series_id" class="form-label">Série <span class="text-danger">*</span></label>
                            <select class="form-select" 
                                    id="create_series_id" 
                                    name="series_id" 
                                    required>
                                <option value="">Selecione uma série</option>
                                @foreach($series as $serie)
                                    <option value="{{ $serie->id }}">{{ $serie->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create_class_id" class="form-label">Turma</label>
                            <select class="form-select" 
                                    id="create_class_id" 
                                    name="class_id">
                                <option value="">Selecione uma turma</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" data-series="{{ $classroom->series_id }}">
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informação:</strong> Um código único será gerado automaticamente para o aluno.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Cadastrar Aluno
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    const seriesSelect = document.getElementById('create_series_id');
    const classSelect = document.getElementById('create_class_id');

    // Carregar turmas quando série mudar
    seriesSelect.addEventListener('change', function() {
        const seriesId = this.value;
        const options = classSelect.querySelectorAll('option[data-series]');
        
        // Resetar seleção
        classSelect.value = '';
        
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
        
        fetch('{{ route("admin.users.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar código gerado
                showSuccessMessage(data.message + ' Código: ' + data.user_code);
                
                // Fechar modal e recarregar página
                bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showErrors(data.errors || { general: [data.error] });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showErrors({ general: ['Erro ao cadastrar aluno. Tente novamente.'] });
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
