{{-- Extends layout --}}
@extends('layout.default')

{{-- Page title --}}
@section('title', 'Meu Perfil')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Aluno</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Perfil</a></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Meu Perfil</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Sucesso!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <form method="POST" action="{{ route('student.profile.update') }}" class="form-validate">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Nome <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name', $student->first_name) }}" 
                                               required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Sobrenome <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name', $student->last_name) }}" 
                                               required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="birthdate" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('birthdate') is-invalid @enderror" 
                                           id="birthdate" 
                                           name="birthdate" 
                                           value="{{ old('birthdate', $student->birthdate->format('Y-m-d')) }}" 
                                           max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                           required>
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Informações da Conta</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Código do Aluno:</label>
                                        <p class="text-primary fs-4 fw-bold">{{ $student->user_code }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Série:</label>
                                        <p>{{ $student->series ? $student->series->name : 'Não informada' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Turma:</label>
                                        <p>{{ $student->classroom ? $student->classroom->name : 'Não informada' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status:</label>
                                        <span class="badge bg-{{ $student->isActive() ? 'success' : 'danger' }}">
                                            {{ $student->isActive() ? 'Ativo' : 'Suspenso' }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Data de Cadastro:</label>
                                        <p>{{ $student->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="d-grid">
                                    <a href="{{ route('trail.current') }}" class="btn btn-success">
                                        <i class="fas fa-play me-2"></i>
                                        Continuar Trilha
                                    </a>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="d-grid">
                                    <a href="{{ route('student.logout') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Sair
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação do formulário
    const form = document.querySelector('.form-validate');
    if (form) {
        form.addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const birthdate = document.getElementById('birthdate').value;

            if (!firstName || !lastName || !birthdate) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                return false;
            }
        });
    }
});
</script>
@endpush
