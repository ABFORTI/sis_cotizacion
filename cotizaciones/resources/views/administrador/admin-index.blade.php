@extends('layouts.app')

@section('title', 'Panel Administrativo')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-red-800">
                    <i class="fa-solid fa-shield-halved mr-2"></i> Panel Administrativo
                </h1>
                <p class="text-gray-600 mt-2">Gestión completa de cotizaciones del sistema</p>
            </div>
        </div>

        <!-- Barra de búsqueda y filtros mejorada -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('administrador.admin.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Búsqueda -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar por nombre
                        </label>
                        <input 
                            type="text" 
                            id="search"
                            name="search" 
                            placeholder="Buscar por proyecto, cliente, usuario..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                            value="{{ request('search') }}"
                        >
                    </div>

                    <!-- Filtrar por rol -->
                    <div class="md:col-span-3">
                        <label for="role_filter" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-user-tag mr-1"></i> Filtrar por rol
                        </label>
                        <select 
                            id="role_filter"
                            name="role_filter" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                        >
                            <option value="">Todos los roles</option>
                            <option value="ventas" {{ request('role_filter') == 'ventas' ? 'selected' : '' }}>Ventas</option>
                            <option value="gerente_ventas" {{ request('role_filter') == 'gerente_ventas' ? 'selected' : '' }}>Gerente de Ventas</option>
                            <option value="costeos" {{ request('role_filter') == 'costeos' ? 'selected' : '' }}>Costeos</option>
                            <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <!-- Ordenar por estado -->
                    <div class="md:col-span-3">
                        <label for="status_filter" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-filter mr-1"></i> Ordenar por
                        </label>
                        <select 
                            id="status_filter"
                            name="status_filter" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                        >
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('status_filter') == 'pendiente' ? 'selected' : '' }}>Pendiente envío</option>
                            <option value="enviado_costeos" {{ request('status_filter') == 'enviado_costeos' ? 'selected' : '' }}>Enviado a Costeos</option>
                            <option value="devuelto_ventas" {{ request('status_filter') == 'devuelto_ventas' ? 'selected' : '' }}>Devuelto a Ventas</option>
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="md:col-span-2 flex gap-2">
                        <button 
                            type="submit" 
                            class="flex-1 px-4 py-2.5 bg-red-800 text-white font-semibold rounded-lg hover:bg-red-900 transition duration-200 shadow-lg hover:shadow-xl"
                            title="Aplicar filtros"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <a 
                            href="{{ route('administrador.admin.index') }}" 
                            class="flex-1 px-4 py-2.5 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition duration-200 text-center"
                            title="Limpiar filtros"
                        >
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Resultados de búsqueda -->
                @if(request('search') || request('role_filter') || request('status_filter'))
                <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Mostrando {{ $cotizaciones->count() }} de {{ $cotizaciones->total() }} cotizaciones
                        @if(request('search'))
                            <span class="font-semibold">· Búsqueda: "{{ request('search') }}"</span>
                        @endif
                        @if(request('role_filter'))
                            <span class="font-semibold">· Rol: {{ ucwords(str_replace('_', ' ', request('role_filter'))) }}</span>
                        @endif
                        @if(request('status_filter'))
                            <span class="font-semibold">· Estado: {{ ucfirst(str_replace('_', ' ', request('status_filter'))) }}</span>
                        @endif
                    </div>
                </div>
                @endif
            </form>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="admin-stats-card bg-blue-100 p-4 rounded-lg text-center shadow">
                <div class="flex items-center justify-center mb-2">
                    <span class="text-2xl mr-2">📊</span>
                    <h3 class="font-bold text-blue-800">Total Cotizaciones</h3>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['total'] ?? 0 }}</p>
            </div>
            <div class="admin-stats-card bg-yellow-100 p-4 rounded-lg text-center shadow">
                <div class="flex items-center justify-center mb-2">
                    <span class="text-2xl mr-2">⏳</span>
                    <h3 class="font-bold text-yellow-800">Pendientes</h3>
                </div>
                <p class="text-3xl font-bold text-yellow-600">{{ $estadisticas['pendientes'] ?? 0 }}</p>
            </div>
            <div class="admin-stats-card bg-orange-100 p-4 rounded-lg text-center shadow">
                <div class="flex items-center justify-center mb-2">
                    <span class="text-2xl mr-2">⚙️</span>
                    <h3 class="font-bold text-orange-800">En Costeos</h3>
                </div>
                <p class="text-3xl font-bold text-orange-600">{{ $estadisticas['en_costeos'] ?? 0 }}</p>
            </div>
            <div class="admin-stats-card bg-green-100 p-4 rounded-lg text-center shadow">
                <div class="flex items-center justify-center mb-2">
                    <span class="text-2xl mr-2">✅</span>
                    <h3 class="font-bold text-green-800">Completadas</h3>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $estadisticas['completadas'] ?? 0 }}</p>
            </div>
        </div>
        

        <div class="table-container overflow-x-auto">
            <table class="styled-table w-full text-sm">
                <thead>
                    <tr>
                        <th>No. Proyecto</th>
                        <th>Cliente</th>
                        <th>Fecha Creación</th>
                        <th>Nombre del Proyecto</th>
                        <th>Creado por</th>
                        <th>Estado del Flujo</th>
                        <th>Fechas de Envío</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cotizaciones as $cotizacion)
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">{{ $cotizacion->no_proyecto }}</td>
                        <td>{{ $cotizacion->cliente }}</td>
                        <td>{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $cotizacion->nombre_del_proyecto }}</td>

                        <!-- Creado por -->
                        <td>
                            @if($cotizacion->user)
                            <div class="text-sm">
                                <div class="font-medium">{{ $cotizacion->user->name }}</div>
                                <div class="text-gray-500">{{ $cotizacion->user->email }}</div>
                                <span class="inline-block px-2 py-1 text-xs rounded-full 
                                    {{ $cotizacion->user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                    ($cotizacion->user->role === 'ventas' ? 'bg-blue-100 text-blue-800' : 
                                    ($cotizacion->user->role === 'gerente_ventas' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800')) }}">
                                    {{ ucwords(str_replace('_', ' ', $cotizacion->user->role)) }}
                                </span>
                            </div>
                            @else
                            <span class="text-gray-500">Usuario eliminado</span>
                            @endif
                        </td>

                        <!-- Estado del Flujo -->
                        <td class="text-center">
                            @if(!$cotizacion->enviado_a_costeos)
                            <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-700 font-semibold">
                                📝 Borrador
                            </span>
                            @elseif($cotizacion->enviado_a_costeos && !$cotizacion->enviado_a_ventas)
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                ⚙️ En Costeos
                            </span>
                            @elseif($cotizacion->enviado_a_ventas)
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-semibold">
                                ✅ Completada
                            </span>
                            @endif
                        </td>

                        <!-- Fechas de Envío -->
                        <td class="text-xs">
                            @if($cotizacion->fecha_envio_ventas)
                            <div class="mb-2 p-2 bg-blue-50 rounded border">
                                <div class="font-semibold text-blue-700">📤 Enviado a Costeos:</div>
                                <div>{{ \Carbon\Carbon::parse($cotizacion->fecha_envio_ventas)->format('d/m/Y H:i') }}</div>
                                @if($cotizacion->enviadoPorVentas)
                                <div class="text-gray-600">Por:
                                    {{ $cotizacion->enviadoPorVentas->name }} ({{ $cotizacion->enviadoPorVentas->email }})
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($cotizacion->fecha_envio_costeos)
                            <div class="mb-2 p-2 bg-green-50 rounded border">
                                <div class="font-semibold text-green-700">🔄 Devuelto a Ventas:</div>
                                <div>{{ \Carbon\Carbon::parse($cotizacion->fecha_envio_costeos)->format('d/m/Y H:i') }}</div>
                                @if($cotizacion->enviadoPorCosteos)
                                <div class="text-gray-600">Por:
                                    {{ $cotizacion->enviadoPorCosteos->name }} ({{ $cotizacion->enviadoPorCosteos->email }})
                                </div>
                                @endif
                            </div>
                            @endif

                            @if(!$cotizacion->fecha_envio_ventas && !$cotizacion->fecha_envio_costeos)
                            <span class="text-gray-400 italic">Sin movimientos</span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="action-buttons text-center">
                            <div class="space-y-2">
                                <!-- Ver cotización -->
                                
                                <div>
                                    <a href="{{ route('cotizacion.form', $cotizacion) }}" class="btn-view text-xs block">Ver Cotización</a>
                                </div>

                                <!-- Calcular costeo (si está disponible) -->
                                @if($cotizacion->requisicionCotizacion)
                                <div>
                                    <a href="{{ route('costeo.create', $cotizacion->id) }}"
                                        class="btn-view text-xs block">Ver Calcular Costeo</a>
                                </div>
                                @endif

                                <!-- Acciones de administración -->
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="btn-edit text-xs block">Editar</a>

                                    <!-- Reenviar a costeos (solo si no está enviado) -->
                                    @if(!$cotizacion->enviado_a_costeos)
                                    <form id="enviar-form-{{ $cotizacion->id }}" action="{{ route('cotizaciones.enviar', $cotizacion) }}" method="POST" class="inline w-full"
                                        data-loading="true"
                                        data-loading-title="Enviando cotizacion..."
                                        data-loading-message="Enviando a Costeos, por favor espera"
                                        data-loading-button-text="Enviando, por favor espera...">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="showConfirmModal('¿Enviar cotización?', '¿Estás seguro de enviar la cotización {{ $cotizacion->no_proyecto }} a Costeos?', function() { submitManagedForm('enviar-form-{{ $cotizacion->id }}'); })" class="btn-enviar bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-xs w-full transition duration-200 font-semibold">Enviar</button>
                                    </form>
                                    @endif
                                    <!-- Eliminar -->
                                    <div class="flex justify-center">
                                        <form id="delete-form-{{ $cotizacion->id }}" action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" class="inline w-full"
                                            data-loading="true"
                                            data-loading-title="Eliminando cotizacion..."
                                            data-loading-message="Eliminando la cotizacion, por favor espera"
                                            data-loading-button-text="Eliminando, por favor espera...">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="showConfirmModal('¿Eliminar cotización?', '¿Estás seguro de eliminar la cotización {{ $cotizacion->no_proyecto }}? Esta acción no se puede deshacer.', function() { submitManagedForm('delete-form-{{ $cotizacion->id }}'); })" class="btn-danger text-xs w-full">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            No hay cotizaciones que coincidan con los filtros seleccionados.
                        </td>
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

<!-- Estilos adicionales para el panel admin -->
<style>
    .form-select {
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .admin-stats-card {
        transition: transform 0.2s;
    }

    .admin-stats-card:hover {
        transform: translateY(-2px);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar mensajes de éxito o error si existen en la sesión
        @if(session('success'))
            showSuccessMessage("{{ session('success') }}");
        @endif

        @if(session('error'))
            showErrorMessage("{{ session('error') }}");
        @endif
    });
</script>
@endpush

@endsection