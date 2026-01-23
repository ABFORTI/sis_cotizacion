@extends('layouts.app')

@section('title', 'Listado de Cotizaciones')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-4">
        <h1 class="page-title">@yield('title')</h1>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto">

            <!-- Formulario de búsqueda -->
            <form action="{{ route('cotizaciones.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-grow">
                <input type="text" name="search" placeholder="Buscar por proyecto, cliente..."
                    class="form-input flex-grow w-full"
                    value="{{ request('search') }}">
                
                <!-- Filtro por Estado -->
                <select name="estado_filter" class="form-input whitespace-nowrap">
                    <option value="">Todos los estados</option>
                    @if(Auth::user()->role === 'ventas')
                        <option value="pendiente" {{ request('estado_filter') == 'pendiente' ? 'selected' : '' }}>📤 Pendiente envío</option>
                        <option value="enviada" {{ request('estado_filter') == 'enviada' ? 'selected' : '' }}>✅ Enviada a Costeos</option>
                        <option value="devuelta" {{ request('estado_filter') == 'devuelta' ? 'selected' : '' }}>🔄 Devuelta por Costeos</option>
                    @elseif(Auth::user()->role === 'costeos')
                        <option value="pendiente" {{ request('estado_filter') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                        <option value="recibida" {{ request('estado_filter') == 'recibida' ? 'selected' : '' }}>✅ Recibida</option>
                        <option value="terminada" {{ request('estado_filter') == 'terminada' ? 'selected' : '' }}>🎉 Costeo Terminado</option>
                    @endif
                </select>
                
                <button type="submit" class="btn-submit whitespace-nowrap">Buscar</button>
            </form>
            @if(Auth::user()->role === 'ventas')
            <a href="{{ route('cotizaciones.create') }}" class="btn-submit whitespace-nowrap text-center">Crear Nueva Cotización</a>
            @endif
        </div>
    </div>

    <div class="table-container overflow-x-auto">
        <table class="styled-table w-full">
            <thead>
                <tr>
                    <th>No. Proyecto</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Nombre del Proyecto</th>
                    <th>Estados</th>
                    <th>Días en Costeos</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->no_proyecto }}</td>
                    <td>{{ $cotizacion->cliente }}</td>
                    <td>{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $cotizacion->nombre_del_proyecto }}</td>

                    <!-- Envío a Costeos -->
                    <td class="text-align center">
                        @if(Auth::user()->role === 'ventas')
                        @if($cotizacion->enviadoPorCosteos)
                        <div class="text-xs text-gray-700 mt-2 leading-tight bg-blue-50 p-2 rounded border">
                            <div class="text-green-700 font-semibold mb-1">🔄 Devuelta por Costeos</div>
                            ✉ <strong>{{ $cotizacion->enviadoPorCosteos->name }}</strong><br>
                            📧 <span class="text-blue-700">{{ $cotizacion->enviadoPorCosteos->email }}</span><br>
                            📅 {{ \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos)->format('d/m/Y H:i') }}
                        </div>
                        @endif
                        @if(!$cotizacion->enviado_a_costeos)
                        <!-- Aún no se ha enviado -->
                        <form action="{{ route('cotizaciones.enviar', $cotizacion) }}" method="POST" id="enviar-form-{{ $cotizacion->id }}">
                            @csrf
                            @method('PATCH')
                            <button type="button" onclick="showConfirmModal('¿Enviar cotización?', '¿Estás seguro de enviar la cotización {{ $cotizacion->no_proyecto }} a Costeos?', function() { document.getElementById('enviar-form-{{ $cotizacion->id }}').submit(); })" class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-semibold hover:bg-yellow-200 transition">
                                📤 Enviar
                            </button>
                        </form>

                        @elseif($cotizacion->enviadoPorCosteos)
                        <!-- Devuelta por Costeos -->
                        <a href="{{ route('cotizaciones.matrizRiesgos', $cotizacion->id) }}"
                            class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-700 font-semibold hover:bg-blue-200 transition">
                            🧩 Ver Matriz de Riesgos
                        </a>

                        @else
                        <!-- Enviada pero aún no devuelta -->
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                            ✅ Enviada
                        </span>
                        @endif
                        @elseif(Auth::user()->role === 'costeos')
                        @if($cotizacion->enviado_a_ventas)
                        <!-- Ciclo completado - Ya devuelta a Ventas -->
                        <div class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold inline-block">
                            🎉 Costeo Terminado
                        </div>
                        <div class="text-xs text-gray-700 mt-2 leading-tight bg-blue-50 p-2 rounded border">
                            <div class="text-blue-700 font-semibold mb-1">✅ Devuelta a Ventas</div>
                            📅 {{ \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos)->format('d/m/Y H:i') }}
                        </div>
                        @elseif($cotizacion->enviado_a_costeos)
                        <!-- En proceso - Recibida pero no devuelta -->
                        <div class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold inline-block">
                            ✅ Recibida
                        </div>

                        @if($cotizacion->enviadoPorVentas)
                        <div class="text-xs text-gray-700 mt-2 leading-tight">
                            ✉ <strong>{{ $cotizacion->enviadoPorVentas->name }}</strong>
                            (<span class="text-blue-700">{{ $cotizacion->enviadoPorVentas->email }}</span>)<br>
                            📅 {{ \Carbon\Carbon::parse($cotizacion->fecha_envio_ventas)->format('d/m/Y H:i') }}
                        </div>
                        @else
                        <div class="text-xs text-gray-500 mt-2">Información del remitente no disponible</div>
                        @endif
                        @else
                        <!-- No enviada aún -->
                        <span class="px-3 py-1 text-sm rounded-full bg-gray-200 text-gray-600 font-semibold">
                            ⏳ Pendiente
                        </span>
                        @endif
                        @endif
                    </td>

                    <!-- Días en Costeos -->
                    <td class="text-center">
                        @php
                            $diasTranscurridos = 0;
                            if ($cotizacion->fecha_envio_ventas && $cotizacion->fecha_envio_costeos) {
                                $fechaEnvio = \Carbon\Carbon::parse($cotizacion->fecha_envio_ventas);
                                $fechaRetorno = \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos);

                                if ($fechaEnvio->isSameDay($fechaRetorno)) {
                                    $diasTranscurridos = 0;
                                } else {
                                    $diasTranscurridos = $fechaEnvio
                                    ->startOfDay()
                                    ->diffInDays($fechaRetorno->startOfDay());
                                 }
                            }
                        @endphp
                        {{ $diasTranscurridos }}
                    </td>

                    <!-- Acciones -->
                    <td class="action-buttons text-center">
                        <div class="flex flex-col gap-2 items-center">

                            @if(Auth::user()->role === 'ventas')
                            @if($cotizacion->enviado_a_ventas)
                            <a href="{{ route('cotizacion.resumen.page', $cotizacion->id) }}" class="btn-view w-full">Ver resumen de Costos</a>
                            @elseif($cotizacion->enviado_a_costeos)
                            <span class="text-gray-500 italic text-sm">⏳ En espera de Costeos</span>
                            @endif
                            @elseif(Auth::user()->role === 'costeos')
                            <a href="{{ route('cotizacion.form', $cotizacion) }}" class="btn-view w-full">Ver Resumen de Cotización</a>
                            <a href="{{ route('costeo.create', $cotizacion->requisicionCotizacion->id) }}" class="btn-view w-full">Calcular Costeo</a>
                            @if(isset($cotizacion->cotizacionAdicional) && ($cotizacion->cotizacionAdicional->corrida_piloto == 1 || $cotizacion->cotizacionAdicional->corrida_piloto === '1'))
                                <a href="{{ route('costeo.create', ['id' => $cotizacion->requisicionCotizacion->id, 'btn_corrida_piloto' => 'corrida_piloto']) }}" class="btn-pilot w-full">Calcular Corrida Piloto</a>
                            @endif
                            @endif
                            @if(!$cotizacion->enviado_a_ventas)

                                @if(Auth::user()->role === 'ventas')
                                    {{-- Mostrar editar sólo si aún NO fue enviado a Costeos --}}
                                    @if(!$cotizacion->enviado_a_costeos)
                                        <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="btn-edit w-full">
                                            Editar
                                        </a>
                                        {{-- Eliminar sólo si aún NO fue enviado a Costeos--}}
                                        <form id="delete-form-{{ $cotizacion->id }}" action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="showConfirmModal(
                                                    '¿Eliminar cotización?',
                                                    'Esta acción no se puede deshacer.',
                                                    function() { document.getElementById('delete-form-{{ $cotizacion->id }}').submit(); }
                                                )"
                                                class="btn-submit w-full">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Mostrar eliminar en Ventas sólo si Costeos la ocultó --}}
                                    @if($cotizacion->oculta_para_costeos)
                                        <form id="delete-form-{{ $cotizacion->id }}" action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="showConfirmModal(
                                                    '¿Eliminar cotización?',
                                                    'Esta acción no se puede deshacer.',
                                                    function() { document.getElementById('delete-form-{{ $cotizacion->id }}').submit(); }
                                                )"
                                                class="btn-submit w-full">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif

                                @elseif(Auth::user()->role === 'costeos')
                                        <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="btn-edit w-full">
                                            Ver Cotizacion/Editar
                                        </a>
                                    {{-- OCULTAR (NO BORRAR) --}}
                                    <form id="ocultar-form-{{ $cotizacion->id }}" action="{{ route('cotizaciones.ocultarCosteos', $cotizacion->id) }}"
                                        method="POST"
                                        class="w-full">
                                        @csrf
                                        @method('PATCH')

                                        <button type="button"
                                            onclick="showConfirmModal(
                                                '¿Eliminar cotización?',
                                                'Esta acción no se puede deshacer.',
                                                function() { document.getElementById('ocultar-form-{{ $cotizacion->id }}').submit(); }
                                            )"
                                            class="btn-submit w-full bg-gray-500 hover:bg-gray-600">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif

                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay cotizaciones registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    

    <div class="pagination mt-6">
        {{ $cotizaciones->links('vendor.pagination.tailwind') }}
    </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar modal de éxito si hay mensaje de sesión
        @if(session('success'))
            showSuccessMessage("{{ session('success') }}");
        @endif

        // Mostrar modal de error si hay mensaje de error
        @if(session('error'))
            showErrorMessage("{{ session('error') }}");
        @endif
    });
</script>
@endpush

@endsection