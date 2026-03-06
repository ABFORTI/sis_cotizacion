@extends('layouts.app')

@section('title', 'Listado de Cotizaciones')

@section('content')

<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <h1 class="text-xl font-semibold text-white mb-4">
            @yield('title')
        </h1>
    </div>
    <div class="bg-slate-200 rounded-xl shadow-xl p-6">
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <form action="{{ route('cotizaciones.index') }}"
                    method="GET"
                    class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">

                    <input
                        type="text"
                        name="search"
                        placeholder="Buscar: No. Proyecto, Cliente, Proyecto"
                        value="{{ request('search') }}"
                        class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base ps-9 text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body"
                    />

                   <select
                        name="estado_filter"
                        class="bg-white border border-slate-300 text-slate-700
                           rounded-lg px-3 py-2
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           w-full sm:w-56">
                    <option value="">Estados</option>

                         @if(Auth::user()->role === 'ventas')
                                <option value="pendiente" {{ request('estado_filter') == 'pendiente' ? 'selected' : '' }}>📤 Pendiente envío</option>
                                <option value="enviada" {{ request('estado_filter') == 'enviada' ? 'selected' : '' }}>✅ Enviada a Costeos</option>
                                <option value="devuelta" {{ request('estado_filter') == 'devuelta' ? 'selected' : '' }}>🔄 Devuelta por Costeos</option>
                            @elseif(Auth::user()->role === 'costeos')
                                <option value="pendiente" {{ request('estado_filter') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="recibida" {{ request('estado_filter') == 'recibida' ? 'selected' : '' }}>✅ Recibida</option>
                                <option value="terminada" {{ request('estado_filter') == 'terminada' ? 'selected' : '' }}>☑ Costeo Terminado</option>
                        @endif
                    </select>

                    <button type="submit" class="btn-submit px-5 py-2 whitespace-nowrap">
                        Buscar
                    </button>
                </form>

                @if(Auth::user()->role === 'ventas')
                    <a href="{{ route('cotizaciones.create') }}"
                        class="btn-submit px-5 py-2 whitespace-nowrap">
                        + Crear cotización
                    </a>
                @endif
            </div>
        </div>

        <div class="table-container" style="overflow-x: auto; overflow-y: visible;">
            <table class="styled-table w-full">
                <thead class="bg-slate-700 text-slate-300 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">No. Proyecto</th>
                        <th class="px-4 py-3 text-left">Cliente</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3 text-center">Proyecto</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Días</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cotizaciones as $cotizacion)
                    <tr class="border-b border-slate-700 hover:bg-slate-700 transition">
                        <td class="px-4 py-3">{{ $cotizacion->no_proyecto }}</td>
                        <td class="px-4 py-3">{{ $cotizacion->cliente }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $cotizacion->nombre_del_proyecto }}</td>
                        <!-- Envío a Costeos -->
                        <td class="text-align center px-4 py-3">
                            
                            @if(Auth::user()->role === 'ventas')
                                @if($cotizacion->enviadoPorCosteos)
                                <div class="mt-2  rounded p-2 flex flex-col items-center text-xs text-slate-500 gap-0.5">
                                    <span class="font-medium text-blue-600">
                                        Devuelta por Costeos
                                    </span>
                                    <span>
                                        {{ $cotizacion->enviadoPorCosteos->name }}
                                        <span class="text-blue-700">· {{ $cotizacion->enviadoPorCosteos->email }}</span>
                                    </span>
                                    <span>
                                        {{ \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos)
                                        ->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                @endif
                                    @if(!$cotizacion->enviado_a_costeos)
                                        <form action="{{ route('cotizaciones.enviar', $cotizacion) }}" method="POST" id="enviar-form-{{ $cotizacion->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex justify-center">
                                                <button type="button" onclick="showConfirmModal('¿Enviar cotización?', '¿Estás seguro de enviar la cotización {{ $cotizacion->no_proyecto }} a Costeos?', function() { document.getElementById('enviar-form-{{ $cotizacion->id }}').submit(); })" class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-semibold hover:bg-yellow-200 transition">
                                                    ⬈ Enviar
                                                </button>
                                            </div>
                                        </form>
                                    @elseif($cotizacion->enviadoPorCosteos)
                                        <div class="flex justify-center">
                                            <a href="{{ route('cotizaciones.matrizRiesgos', $cotizacion->id) }}"
                                                class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                                                Ver Matriz de Riesgos
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex justify-center">
                                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                                                ✓ Enviada
                                            </span>
                                        </div>
                                    @endif
                            @elseif(Auth::user()->role === 'costeos')
                                @if($cotizacion->enviado_a_ventas)
                                    <div class="mt-2 rounded p-2 flex flex-col items-center text-xs text-slate-500 gap-0.5">
                                        <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium text-sm">
                                            ☑ Costeo Terminado
                                        </span>
                                        <div class="text-center">
                                            <span class="font-medium text-blue-600">
                                                ✓ Devuelta a Ventas
                                            </span>
                                            <p class="text-xs text-gray-600">
                                               {{ \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @elseif($cotizacion->enviado_a_costeos)
                                    <div class="flex justify-center">
                                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                                            ✓ Recibida
                                        </span>
                                    </div>
                                @if($cotizacion->enviadoPorVentas)
                                    <div class="text-xs text-gray-700 mt-2 leading-tight">
                                        <div class="mt-2 rounded p-2 flex flex-col items-center text-xs text-slate-500 gap-0.5">
                                            <span class="font-medium text-blue-600">
                                                Enviada por Ventas
                                            </span>
                                            <span>
                                                {{ $cotizacion->enviadoPorVentas->name }}
                                                <span class="text-blue-700">·{{ $cotizacion->enviadoPorVentas->email }}</span>
                                            </span>
                                            <span>
                                                {{ \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos)
                                                                    ->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500 mt-2">Información del remitente no disponible</div>
                                @endif
                                @else
                                    <span class="px-3 py-1 text-sm rounded-full bg-gray-200 text-gray-600 font-semibold">
                                        ⏳ Pendiente
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center space-y-1">
                            @if(!$cotizacion->enviado_a_costeos)
                                <span class="text-gray-400 italic text-sm">
                                    Pendiente de envío
                                </span>
                            @else
                                @php
                                    $dias = $cotizacion->dias_en_costos;

                                    if ($dias <= 2) {
                                        $bg = 'bg-green-100 text-green-800';
                                    } elseif ($dias <= 5) {
                                        $bg = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $bg = 'bg-red-100 text-red-800';
                                    }
                                @endphp

                                <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-semibold {{ $bg }}">
                                    {{ $dias }} días
                                </span>
                            @endif
                        </td>
                        <td class="action-buttons text-center">
                            <div class="flex flex-col gap-2 items-center">
                                @if(Auth::user()->role === 'ventas')
                                    @if($cotizacion->enviado_a_ventas)
                                        <a href="{{ route('cotizacion.resumen.page', $cotizacion->id) }}" class="btn-view w-full">Ver resumen de Costos</a>
                                    @elseif($cotizacion->enviado_a_costeos)
                                        <span class="text-gray-500 italic text-sm">En espera de Costeos</span>
                                    @endif
                                @elseif(Auth::user()->role === 'costeos')

                                <div class="relative inline-block w-full dropdown-container">
                                    <button 
                                        class="btn-view w-full flex items-center justify-between dropdown-toggle"
                                        type="button"
                                        data-dropdown-id="{{ $cotizacion->id }}"
                                    >
                                        <span>Opciones de Cotización</span>
                                        <svg 
                                            class="w-4 h-4 ml-2 transition-transform duration-200 dropdown-icon" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu absolute z-10 w-full mt-2 bg-white rounded-md shadow-lg border border-gray-200" style="display: none;" data-dropdown-id="{{ $cotizacion->id }}">
                                        <div class="py-1">
                                            <a 
                                                href="{{ route('cotizacion.form', $cotizacion) }}" 
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                                            >
                                                Ver Resumen de Cotización
                                            </a>
                                            <a 
                                                href="{{ route('costeo.create', $cotizacion->requisicionCotizacion->id) }}" 
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                                            >
                                                Calcular Costeo
                                            </a>
                                            @if(isset($cotizacion->cotizacionAdicional) && ($cotizacion->cotizacionAdicional->corrida_piloto == 1 || $cotizacion->cotizacionAdicional->corrida_piloto === '1'))
                                                <a 
                                                    href="{{ route('costeo.create', ['id' => $cotizacion->requisicionCotizacion->id, 'btn_corrida_piloto' => 'corrida_piloto']) }}" 
                                                    class="block px-4 py-2 text-sm text-blue-700 hover:bg-blue-50 transition-colors font-medium"
                                                >
                                                    Calcular Corrida Piloto
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @endif
                                @if(!$cotizacion->enviado_a_ventas)
                                    @if(Auth::user()->role === 'ventas')
                                        <div class="grid h-56 grid-cols-3 content-center gap-4">
                                        @if(!$cotizacion->enviado_a_costeos)
                                            <a href="{{ route('cotizaciones.edit', $cotizacion) }}"
                                            class="btn-edit inline-flex items-center justify-center
                                                    w-10 h-10 rounded-base
                                                    bg-brand text-white
                                                    hover:bg-brand-strong
                                                    focus:ring-4 focus:ring-brand-medium
                                                    shadow-xs">
                                                        ✏️
                                            </a>
                                            <form id="delete-form-{{ $cotizacion->id }}"
                                                    action="{{ route('cotizaciones.destroy', $cotizacion) }}"
                                                    method="POST">
                                                @csrf
                                                @method('DELETE')
                                                    <button type="button"
                                                        onclick="showConfirmModal('¿Eliminar cotización?', 'Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $cotizacion->id }}').submit(); })"
                                                        class="inline-flex items-center justify-center
                                                            w-10 h-10 rounded-base
                                                            bg-red-500 text-white
                                                            hover:bg-red-600
                                                            focus:ring-4 focus:ring-red-300
                                                            shadow-xs"> 🗑
                                                    </button>
                                                </form>
                                        @else
                                            <span></span>
                                            <span></span>
                                        @endif
                                        <form id="clone-form-{{ $cotizacion->id }}"
                                              action="{{ route('cotizaciones.clone', $cotizacion) }}"
                                              method="POST">
                                            @csrf
                                            <button type="button"
                                                onclick="showConfirmModal('¿Clonar requisición?', 'Se creará una copia de la requisición con fecha actual. Podrás modificar los valores antes de enviarla.', function() { document.getElementById('clone-form-{{ $cotizacion->id }}').submit(); })"
                                                class="inline-flex items-center justify-center
                                                    w-10 h-10 rounded-base
                                                    bg-blue-500 text-white
                                                    hover:bg-blue-600
                                                    focus:ring-4 focus:ring-blue-300
                                                    shadow-xs">
                                                📋
                                            </button>
                                        </form>
                                        @if($cotizacion->oculta_para_costeos)
                                            <form id="delete-form-{{ $cotizacion->id }}" action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" class="w-full col-span-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="showConfirmModal('¿Eliminar cotización?', 'Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $cotizacion->id }}').submit(); })"
                                                    class="btn-submit w-full">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    @elseif(Auth::user()->role === 'costeos')
                                        <div class="grid h-56 grid-cols-2 content-center gap-4">
                                            <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="btn-edit inline-flex items-center justify-center
                                                w-10 h-10 rounded-base
                                                bg-brand text-white
                                                hover:bg-brand-strong
                                                focus:ring-4 focus:ring-brand-medium
                                                shadow-xs"> ✏️
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
                                                        function() { 
                                                            document.getElementById('ocultar-form-{{ $cotizacion->id }}').submit();
                                                            }
                                                        )"
                                                    class="inline-flex items-center justify-center
                                                        w-10 h-10 rounded-base
                                                        bg-red-500 text-white
                                                        hover:bg-red-600
                                                        focus:ring-4 focus:ring-red-300
                                                        shadow-xs"> 🗑
                                                </button>
                                            </form>
                                    @endif
                                        </div>
                                @endif
                                        </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center px-4 py-3">No hay cotizaciones registradas.</td>
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

@endsection

{{-- Estilos del dropdown --}}
<style>
    .dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dropdown-icon {
        width: 1rem;
        height: 1rem;
        margin-left: 0.5rem;
        transition: transform 0.2s ease;
    }

    .dropdown-menu {
        position: absolute;
        z-index: 99999; /* Aumentado */
        width: 100%;
        margin-top: 0.5rem;
        background-color: white;
        border-radius: 0.375rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        min-width: 250px; /* Asegura que no sea muy angosto */
    }

.dropdown-menu a {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: #374151 !important; /* Añade el !important aquí */
    text-decoration: none;
    transition: background-color 0.15s ease;
    cursor: pointer;
}

    .dropdown-menu a:hover {
        background-color: #f3f4f6;
    }

        /* Asegura que la tabla no corte el dropdown */
    .table-container {
        overflow: visible !important;
    }


</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mensajes de sesión
        @if(session('success'))
            showSuccessMessage("{{ session('success') }}");
        @endif
        @if(session('error'))
            showErrorMessage("{{ session('error') }}");
        @endif

        // Manejo de dropdowns
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdownId = this.getAttribute('data-dropdown-id');
                const menu = document.querySelector(`.dropdown-menu[data-dropdown-id="${dropdownId}"]`);
                const icon = this.querySelector('.dropdown-icon');
                
                // Cerrar otros dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                    if (otherMenu !== menu && otherMenu.style.display === 'block') {
                        otherMenu.style.display = 'none';
                        const otherId = otherMenu.getAttribute('data-dropdown-id');
                        const otherToggle = document.querySelector(`.dropdown-toggle[data-dropdown-id="${otherId}"]`);
                        if (otherToggle) {
                            const otherIcon = otherToggle.querySelector('.dropdown-icon');
                            if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                        }
                    }
                });
                
                // Toggle el dropdown actual
                if (menu.style.display === 'none' || menu.style.display === '') {
                    menu.style.display = 'block';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    menu.style.display = 'none';
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
        
        // Cerrar dropdowns al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
                document.querySelectorAll('.dropdown-icon').forEach(icon => {
                    icon.style.transform = 'rotate(0deg)';
                });
            }
        });
    });
</script>
@endpush

