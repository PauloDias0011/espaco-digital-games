<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name'))</title>

  {{-- CSS do tema getSkills --}}
  <link rel="stylesheet" href="{{ asset('getskills/vendor/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('getskills/vendor/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('getskills/vendor/perfect-scrollbar/css/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('getskills/css/style.css') }}">

  {{-- Seus assets via Vite (mantenha se precisar) --}}
  {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
  @stack('styles')
</head>
<body>
  @include('partials.getskills.header')
  <div class="app-wrapper d-flex" style="min-height:100vh;">
    @include('partials.getskills.sidebar')
    <main class="flex-fill p-3">
      @yield('content')
    </main>
  </div>
  @include('partials.getskills.footer')

  {{-- JavaScript do tema getSkills --}}
  <script src="{{ asset('getskills/vendor/global/global.min.js') }}"></script>
  <script src="{{ asset('getskills/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('getskills/vendor/perfect-scrollbar/js/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('getskills/vendor/chart.js/Chart.bundle.min.js') }}"></script>
  <script src="{{ asset('getskills/js/custom.min.js') }}"></script>
  <script src="{{ asset('getskills/js/dlabnav-init.js') }}"></script>

  @stack('scripts')
</body>
</html>
