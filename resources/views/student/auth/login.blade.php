{{-- Extends layout --}}
@extends('layout.default')

{{-- Page title --}}
@section('title', 'Login de Aluno')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Aluno</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Login</a></li>
        </ol>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Login de Aluno</h4>
                    <p class="text-muted">Digite seus dados para acessar sua conta</p>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Sucesso!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erro!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.login') }}" class="form-validate">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Nome <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name') }}" 
                                       placeholder="Digite seu nome"
                                       required
                                       autocomplete="given-name">
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
                                       value="{{ old('last_name') }}" 
                                       placeholder="Digite seu sobrenome"
                                       required
                                       autocomplete="family-name">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="birthdate" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('birthdate') is-invalid @enderror" 
                                   id="birthdate" 
                                   name="birthdate" 
                                   value="{{ old('birthdate') }}" 
                                   required
                                   autocomplete="bday">
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Entrar
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Não possui cadastro? 
                            <a href="{{ route('student.register') }}" class="text-primary fw-bold">Cadastre-se aqui</a>
                        </p>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informações Importantes</h6>
                            <ul class="mb-0">
                                <li>Use exatamente os mesmos dados do seu cadastro</li>
                                <li>A data de nascimento deve estar no formato correto</li>
                                <li>Em caso de dúvidas, entre em contato com a administração</li>
                            </ul>
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

    // Auto-focus no primeiro campo
    const firstNameInput = document.getElementById('first_name');
    if (firstNameInput) {
        firstNameInput.focus();
    }
});
</script>
@endpush
