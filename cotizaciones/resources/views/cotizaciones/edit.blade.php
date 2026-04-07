{{-- edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Cotización')

@section('content')
<div class="container mt-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-slate-700">@yield('title')</h1>

    <form action="{{ route('cotizaciones.update', $cotizacion->id) }}" method="POST" enctype="multipart/form-data"
        data-loading="true"
        data-loading-title="Actualizando requisicion..."
        data-loading-message="Guardando cambios, por favor espera"
        data-loading-button-text="Actualizando requisicion, por favor espera...">
        @csrf
        @method('PUT')
        @include('cotizaciones._form')
        <div class="button-container">
            <button type="submit" class="btn-submit">Actualizar Requisición</button>
            <a href="{{ route('cotizaciones.index') }}" class="btn-cancel">Cancelar</a>
        </div>
    </form>
    </div>
</div>
@endsection