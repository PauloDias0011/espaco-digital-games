<!--**********************************
    Sidebar Super Admin start
***********************************-->
<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            {{-- Dashboard --}}
            <li class="{{ request()->routeIs('superadmin.dashboard') ? 'mm-active' : '' }}">
                <a href="{{ route('superadmin.dashboard') }}" aria-expanded="false">
                    <i class="bi bi-speedometer2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            {{-- Gestão de Unidades --}}
            <li class="{{ request()->routeIs('superadmin.tenants*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="{{ request()->routeIs('superadmin.tenants*') ? 'true' : 'false' }}">
                    <i class="bi bi-building"></i>
                    <span class="nav-text">Unidades</span>
                </a>
                <ul aria-expanded="{{ request()->routeIs('superadmin.tenants*') ? 'true' : 'false' }}">
                    <li><a href="{{ route('superadmin.tenants.index') }}" class="{{ request()->routeIs('superadmin.tenants.index') ? 'mm-active' : '' }}">Listar Unidades</a></li>
                    <li><a href="{{ route('superadmin.tenants.create') }}" class="{{ request()->routeIs('superadmin.tenants.create') ? 'mm-active' : '' }}">Nova Unidade</a></li>
                </ul>
            </li>

            {{-- Usuários (futuro) --}}
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                    <i class="bi bi-people"></i>
                    <span class="nav-text">Usuários</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="javascript:void(0);">Listar Usuários</a></li>
                    <li><a href="javascript:void(0);">Novo Usuário</a></li>
                    <li><a href="javascript:void(0);">Permissões</a></li>
                </ul>
            </li>

            {{-- Módulos (futuro) --}}
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                    <i class="bi bi-puzzle"></i>
                    <span class="nav-text">Módulos</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="javascript:void(0);">Trilhas</a></li>
                    <li><a href="javascript:void(0);">Jogos</a></li>
                    <li><a href="javascript:void(0);">Classificações</a></li>
                </ul>
            </li>

            {{-- Relatórios --}}
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                    <i class="bi bi-bar-chart"></i>
                    <span class="nav-text">Relatórios</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="javascript:void(0);">Uso por Unidade</a></li>
                    <li><a href="javascript:void(0);">Usuários Ativos</a></li>
                    <li><a href="javascript:void(0);">Performance</a></li>
                </ul>
            </li>

            {{-- Configurações --}}
            <li>
                <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                    <i class="bi bi-gear"></i>
                    <span class="nav-text">Configurações</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="javascript:void(0);">Gerais</a></li>
                    <li><a href="javascript:void(0);">E-mail</a></li>
                    <li><a href="javascript:void(0);">Backup</a></li>
                    <li><a href="javascript:void(0);">Logs</a></li>
                </ul>
            </li>

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar Super Admin end
***********************************-->
