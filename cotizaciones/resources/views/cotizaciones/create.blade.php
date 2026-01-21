@extends('layouts.app')

@section('title', 'Crear Cotización')

@section('content')
<div class="container">
    <div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="font-bold text-center">@yield('title')</h1>

    <form action="{{ route('cotizaciones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('cotizaciones._form', ['cotizacion' => null])
        <div class="button-container">
            <button type="submit" class="btn-submit">Crear Requisición</button>
            @auth
            @if (Auth::user()->role === 'ventas')
            <a href="{{ route('home') }}" class="btn-cancel">Cancelar</a>
            @else
            <a href="{{ route('cotizaciones.index') }}" class="btn-cancel">Cancelar</a>
            @endif
            @endauth
        </div>
    </form>
    </div>
</div>
@endsection