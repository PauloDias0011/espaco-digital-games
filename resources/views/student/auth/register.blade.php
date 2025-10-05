{{-- Extends layout --}}
@extends('layout.default')

{{-- Page title --}}
@section('title', 'Cadastro de Aluno')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Aluno</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Cadastro</a></li>
        </ol>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cadastro de Aluno</h4>
                    <p class="text-muted">Preencha os dados abaixo para se cadastrar como aluno</p>
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

                    <form method="POST" action="{{ route('student.register') }}" class="form-validate">
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
                                       value="{{ old('last_name') }}" 
                                       placeholder="Digite seu sobrenome"
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
                                   value="{{ old('birthdate') }}" 
                                   max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                   required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="series_id" class="form-label">Série <span class="text-danger">*</span></label>
                            <select class="form-select @error('series_id') is-invalid @enderror" 
                                    id="series_id" 
                                    name="series_id" 
                                    required>
                                <option value="">Selecione sua série</option>
                                @foreach($series as $serie)
                                    <option value="{{ $serie->id }}" {{ old('series_id') == $serie->id ? 'selected' : '' }}>
                                        {{ $serie->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('series_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Cadastrar
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Já possui cadastro? 
                            <a href="{{ route('student.login') }}" class="text-primary fw-bold">Faça login aqui</a>
                        </p>
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
            const seriesId = document.getElementById('series_id').value;

            if (!firstName || !lastName || !birthdate || !seriesId) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                return false;
            }
        });
    }

    // Máscara para data de nascimento
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput) {
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 5, today.getMonth(), today.getDate());
        birthdateInput.max = maxDate.toISOString().split('T')[0];
    }
});
</script>
@endpush
