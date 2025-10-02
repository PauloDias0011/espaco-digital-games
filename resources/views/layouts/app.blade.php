{{-- Compatibilidade: manter @extends('layouts.app') apontando para o novo layout --}}
@extends('layouts.getskills')

@section('title')
  @hasSection('title') @yield('title') @else {{ config('app.name') }} @endif
@endsection

@section('content')
  @yield('content')
@endsection
