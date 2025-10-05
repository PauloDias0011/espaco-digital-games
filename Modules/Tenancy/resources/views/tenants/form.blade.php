{{-- Extends layout --}}
@extends('layout.default')

{{-- Page Title --}}
@section('title', $tenant->exists ? 'Editar Unidade' : 'Nova Unidade')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.tenants.index') }}">Super Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ route('superadmin.tenants.index') }}">Unidades</a></li>
            <li class="breadcrumb-item active">{{ $tenant->exists ? 'Editar' : 'Nova' }}</li>
        </ol>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $tenant->exists ? 'Editar Unidade' : 'Nova Unidade' }}</h4>
                    <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>
                <div class="card-body">
                    {{-- Mensagens de erro --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <strong>Corrija os seguintes erros:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                            </button>
                        </div>
                    @endif

                    {{-- Formulário --}}
                    <form action="{{ $tenant->exists ? route('superadmin.tenants.update', $tenant) : route('superadmin.tenants.store') }}" 
                          method="POST" 
                          class="form-valide">
                        @csrf
                        @if($tenant->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            {{-- Nome da Unidade --}}
                            <div class="col-xl-6 col-lg-6 mb-3">
                                <label class="form-label">
                                    Nome da Unidade <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $tenant->name) }}" 
                                       placeholder="Ex: Espaço Digital Demo"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Nome identificador da unidade no sistema</small>
                            </div>

                            {{-- Slug --}}
                            <div class="col-xl-6 col-lg-6 mb-3">
                                <label class="form-label">
                                    Slug <span class="text-muted">(Gerado automaticamente)</span>
                                </label>
                                <input type="text" 
                                       id="slug" 
                                       name="slug" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug', $tenant->slug) }}" 
                                       placeholder="espaco-digital-demo"
                                       readonly>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Identificador único (URL friendly)</small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Status --}}
                            <div class="col-xl-6 col-lg-6 mb-3">
                                <label class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="form-control default-select @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="">Selecione o status</option>
                                    <option value="active" {{ old('status', $tenant->status) === 'active' ? 'selected' : '' }}>
                                        Ativo
                                    </option>
                                    <option value="inactive" {{ old('status', $tenant->status) === 'inactive' ? 'selected' : '' }}>
                                        Inativo
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Apenas unidades ativas podem ser acessadas</small>
                            </div>

                            {{-- Data de Criação (somente edição) --}}
                            @if($tenant->exists)
                                <div class="col-xl-6 col-lg-6 mb-3">
                                    <label class="form-label">Criado em</label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $tenant->created_at->format('d/m/Y H:i:s') }}" 
                                           readonly>
                                    <small class="form-text text-muted">Data e hora de criação da unidade</small>
                                </div>
                            @endif
                        </div>

                        {{-- Configurações JSON --}}
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">
                                    Configurações (JSON)
                                    <span class="badge badge-info badge-sm ms-2">Opcional</span>
                                </label>
                                <textarea id="settings" 
                                          name="settings" 
                                          class="form-control @error('settings') is-invalid @enderror" 
                                          rows="8" 
                                          placeholder='{"theme": "light", "language": "pt_BR", "timezone": "America/Sao_Paulo"}'>{{ old('settings', $tenant->settings ? json_encode($tenant->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                                @error('settings')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Configurações específicas da unidade em formato JSON. Deixe vazio para usar configurações padrão.
                                </small>
                            </div>
                        </div>

                        {{-- Domínios Cadastrados (somente edição) --}}
                        @if($tenant->exists && $tenant->domains->count() > 0)
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Domínios Cadastrados</label>
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($tenant->domains as $domain)
                                                    <div class="col-md-4 col-sm-6 mb-2">
                                                        <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                                                            <div>
                                                                <span class="badge badge-primary">{{ $domain->domain }}</span>
                                                                <small class="d-block text-muted mt-1">
                                                                    <i class="bi bi-calendar-event"></i> {{ $domain->created_at->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <small class="form-text text-muted mt-2">
                                                <i class="bi bi-lightbulb me-1"></i>
                                                Para adicionar ou remover domínios, use o botão "Gerenciar Domínios" na listagem
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Botões de ação --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>
                                        {{ $tenant->exists ? 'Atualizar' : 'Criar' }} Unidade
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts adicionais --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    // Gerar slug automaticamente apenas ao criar (não ao editar)
    @if(!$tenant->exists)
    nameInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove acentos
            .replace(/[^a-z0-9\s-]/g, '')     // Remove caracteres especiais
            .replace(/\s+/g, '-')              // Substitui espaços por hífens
            .replace(/-+/g, '-')               // Remove hífens duplicados
            .replace(/^-|-$/g, '');            // Remove hífens no início e fim
        
        slugInput.value = slug;
    });
    @endif
    
    // Validação do JSON
    const settingsTextarea = document.getElementById('settings');
    settingsTextarea.addEventListener('blur', function() {
        const value = this.value.trim();
        
        if (value === '') {
            this.classList.remove('is-invalid');
            return;
        }
        
        try {
            JSON.parse(value);
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } catch (e) {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
            
            // Criar feedback de erro se não existir
            let feedback = this.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.insertBefore(feedback, this.nextElementSibling);
            }
            feedback.textContent = 'JSON inválido: ' + e.message;
        }
    });
});
</script>
@endpush