{{-- Extends layout --}}
@extends('layout.default')

{{-- Page title --}}
@section('title', 'Minha Trilha')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Aluno</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Trilha Atual</a></li>
        </ol>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Minha Trilha de Aprendizado</h4>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-route fa-5x text-primary"></i>
                        </div>
                        
                        <h3 class="mb-3">Trilha em Desenvolvimento</h3>
                        
                        <p class="text-muted mb-4">
                            O sistema de trilhas de aprendizado está sendo desenvolvido e estará disponível em breve!
                        </p>
                        
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Em breve você poderá:</h6>
                            <ul class="mb-0 text-start">
                                <li>Acessar jogos educativos personalizados</li>
                                <li>Acompanhar seu progresso de aprendizagem</li>
                                <li>Earnar badges e conquistas</li>
                                <li>Competir com outros alunos da sua turma</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('student.profile') }}" class="btn btn-primary me-2">
                                <i class="fas fa-user me-2"></i>
                                Meu Perfil
                            </a>
                            
                            <a href="{{ route('student.logout') }}" class="btn btn-outline-secondary">
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
@endsection
