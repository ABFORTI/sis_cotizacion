@extends('layouts.app')

@section('title', 'Detalle de Cotización')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="page-title">@yield('title'): {{ $cotizacion->no_proyecto }}</h1>

    <div class="table-container">
        <table class="styled-table">
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizacion->getAttributes() as $key => $value)
                <tr>
                    <td><strong>{{ str_replace('_', ' ', Str::title($key)) }}</strong></td>
                    <td>
                        @if (is_string($value) && ($json = json_decode($value, true)) && json_last_error() == JSON_ERROR_NONE)
                        <pre class="whitespace-pre-wrap break-all">{{ json_encode($json, JSON_PRETTY_PRINT) }}</pre>
                        @elseif (is_bool($value))
                        {{ $value ? 'Sí' : 'No' }}
                        @else
                        {{ $value ?? 'N/A' }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="button-container">
        <a href="{{ route('cotizaciones.index') }}" class="btn-submit">Volver al Listado</a>
    </div>
</div>
@endsection