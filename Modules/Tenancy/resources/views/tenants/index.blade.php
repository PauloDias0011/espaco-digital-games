{{-- Extends layout --}}
@extends('layout.default')

{{-- Page Title --}}
@section('title', 'Gestão de Unidades')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Super Admin</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Unidades</a></li>
        </ol>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gestão de Unidades</h4>
                    <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nova Unidade
                    </a>
                </div>
                <div class="card-body">
                    {{-- Mensagens de feedback --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                            <strong>Sucesso!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <strong>Erro!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                            </button>
                        </div>
                    @endif

                    {{-- Tabela de unidades --}}
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th style="width:80px;"><strong>#</strong></th>
                                    <th><strong>Nome</strong></th>
                                    <th><strong>Slug</strong></th>
                                    <th><strong>Status</strong></th>
                                    <th><strong>Domínios</strong></th>
                                    <th><strong>Criado em</strong></th>
                                    <th><strong>Ações</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tenants as $tenant)
                                    <tr>
                                        <td><strong>{{ $tenant->id }}</strong></td>
                                        <td>{{ $tenant->name }}</td>
                                        <td><span class="badge badge-rounded badge-outline-secondary">{{ $tenant->slug }}</span></td>
                                        <td>
                                            @if($tenant->status === 'active')
                                                <span class="badge light badge-success">
                                                    <i class="bi bi-check-circle me-1"></i>Ativo
                                                </span>
                                            @else
                                                <span class="badge light badge-secondary">
                                                    <i class="bi bi-pause-circle me-1"></i>Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tenant->domains->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($tenant->domains as $domain)
                                                        <span class="badge badge-primary badge-sm">{{ $domain->domain }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="bi bi-dash"></i> Sem domínios</span>
                                            @endif
                                        </td>
                                        <td>{{ $tenant->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('superadmin.tenants.edit', $tenant) }}" 
                                                   class="btn btn-primary shadow btn-xs sharp me-1"
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-info shadow btn-xs sharp me-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#domainModal{{ $tenant->id }}"
                                                        title="Gerenciar Domínios">
                                                    <i class="bi bi-globe"></i>
                                                </button>
                                                <form action="{{ route('superadmin.tenants.destroy', $tenant) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Tem certeza que deseja excluir esta unidade? Esta ação não pode ser desfeita!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger shadow btn-xs sharp" 
                                                            title="Excluir">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal para gerenciar domínios --}}
                                    <div class="modal fade" id="domainModal{{ $tenant->id }}" tabindex="-1" aria-labelledby="domainModalLabel{{ $tenant->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="domainModalLabel{{ $tenant->id }}">
                                                        <i class="bi bi-globe me-2"></i>Domínios - {{ $tenant->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- Formulário para adicionar domínio --}}
                                                    <form action="{{ route('superadmin.tenants.domains.store', $tenant) }}" method="POST" class="mb-3">
                                                        @csrf
                                                        <div class="input-group">
                                                            <input type="text" 
                                                                   name="domain" 
                                                                   class="form-control" 
                                                                   placeholder="exemplo.com.br" 
                                                                   required>
                                                            <button class="btn btn-primary" type="submit">
                                                                <i class="bi bi-plus-circle me-1"></i>Adicionar
                                                            </button>
                                                        </div>
                                                        <small class="form-text text-muted">Digite o domínio sem http:// ou https://</small>
                                                    </form>

                                                    {{-- Lista de domínios --}}
                                                    @if($tenant->domains->count() > 0)
                                                        <div class="list-group list-group-flush">
                                                            @foreach($tenant->domains as $domain)
                                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                    <div>
                                                                        <span class="badge badge-primary">{{ $domain->domain }}</span>
                                                                        <small class="text-muted d-block">Criado em {{ $domain->created_at->format('d/m/Y H:i') }}</small>
                                                                    </div>
                                                                    <form action="{{ route('superadmin.tenants.domains.destroy', [$tenant, $domain]) }}" 
                                                                          method="POST"
                                                                          onsubmit="return confirm('Remover este domínio?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info text-center">
                                                            <i class="bi bi-info-circle me-2"></i>Nenhum domínio cadastrado
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-building" style="font-size: 48px; color: #ccc;"></i>
                                            </div>
                                            <h5 class="text-muted">Nenhuma unidade cadastrada</h5>
                                            <p class="text-muted">Clique no botão acima para criar a primeira unidade</p>
                                            <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary btn-sm mt-2">
                                                <i class="bi bi-plus-circle me-1"></i>Criar Primeira Unidade
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação --}}
                    @if($tenants->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Mostrando {{ $tenants->firstItem() }} a {{ $tenants->lastItem() }} de {{ $tenants->total() }} registros
                                </p>
                            </div>
                            <div>
                                {{ $tenants->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection