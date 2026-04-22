@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container mx-auto px-4 py-8">
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-red-800">
                    <i class="fa-solid fa-users mr-2"></i> Gestión de Usuarios
                </h1>
                <p class="text-gray-600 mt-2">Administra los usuarios del sistema</p>
            </div>
            <a href="{{ route('usuarios.create') }}"
               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-lg hover:bg-green-700 hover:shadow-xl transition duration-200">
                <i class="fa-solid fa-user-plus mr-2"></i> Nuevo Usuario
            </a>
        </div>

        <!-- Barra de búsqueda y filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('usuarios.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Búsqueda por nombre -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar por nombre
                        </label>
                        <input 
                            type="text" 
                            id="search"
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Buscar usuario..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                        >
                    </div>

                    <!-- Filtro por rol -->
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
                            <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="ventas" {{ request('role_filter') == 'ventas' ? 'selected' : '' }}>Ventas</option>
                            <option value="gerente_ventas" {{ request('role_filter') == 'gerente_ventas' ? 'selected' : '' }}>Gerente de Ventas</option>
                            <option value="costeos" {{ request('role_filter') == 'costeos' ? 'selected' : '' }}>Costeos</option>
                        </select>
                    </div>

                    <!-- Ordenar por -->
                    <div class="md:col-span-3">
                        <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa-solid fa-sort mr-1"></i> Ordenar por
                        </label>
                        <select 
                            id="sort"
                            name="sort" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                        >
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                            <option value="email_asc" {{ request('sort') == 'email_asc' ? 'selected' : '' }}>Correo (A-Z)</option>
                            <option value="email_desc" {{ request('sort') == 'email_desc' ? 'selected' : '' }}>Correo (Z-A)</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="md:col-span-2 flex items-end gap-2">
                        <button 
                            type="submit" 
                            class="flex-1 px-4 py-2.5 bg-red-800 text-white font-semibold rounded-lg hover:bg-red-900 transition duration-200 shadow-lg hover:shadow-xl"
                            title="Aplicar filtros"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <a 
                            href="{{ route('usuarios.index') }}" 
                            class="flex-1 px-4 py-2.5 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition duration-200 text-center"
                            title="Limpiar filtros"
                        >
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Resultados de búsqueda -->
                @if(request('search') || request('role_filter') || request('sort'))
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Mostrando {{ $usuarios->count() }} de {{ $usuarios->total() }} usuarios
                        @if(request('search'))
                            <span class="font-semibold">· Búsqueda: "{{ request('search') }}"</span>
                        @endif
                        @if(request('role_filter'))
                            <span class="font-semibold">· Rol: {{ ucwords(str_replace('_', ' ', request('role_filter'))) }}</span>
                        @endif
                    </div>
                </div>
                @endif
            </form>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Usuarios</p>
                        <p class="text-3xl font-bold mt-2">{{ $usuarios->total() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Administradores</p>
                        <p class="text-3xl font-bold mt-2">{{ $usuarios->where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Roles Operativos</p>
                        <p class="text-3xl font-bold mt-2">{{ $usuarios->whereIn('role', ['ventas', 'gerente_ventas', 'costeos'])->count() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fa-solid fa-briefcase text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-red-800 to-red-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Correo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-semibold text-sm">
                                    {{ $usuario->id }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $usuario->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fa-solid fa-envelope mr-2 text-gray-400"></i>
                                    {{ $usuario->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($usuario->role === 'admin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fa-solid fa-user-shield mr-1"></i> Administrador
                                    </span>
                                @elseif($usuario->role === 'ventas')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-handshake mr-1"></i> Ventas
                                    </span>
                                @elseif($usuario->role === 'gerente_ventas')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                        <i class="fa-solid fa-chart-line mr-1"></i> Gerente de Ventas
                                    </span>
                                @elseif($usuario->role === 'costeos')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fa-solid fa-calculator mr-1"></i> Costeos
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ ucfirst($usuario->role) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('usuarios.edit', $usuario) }}"
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg shadow hover:bg-blue-700 transition duration-200"
                                       title="Editar usuario">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Editar
                                    </a>
                                    <form id="delete-form-{{ $usuario->id }}" action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline"
                                        data-loading="true"
                                        data-loading-title="Eliminando usuario..."
                                        data-loading-message="Eliminando el usuario, por favor espera"
                                        data-loading-button-text="Eliminando, por favor espera...">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="showConfirmModal('¿Eliminar usuario?', '¿Estás seguro de eliminar al usuario {{ $usuario->name }}? Esta acción no se puede deshacer.', function() { submitManagedForm('delete-form-{{ $usuario->id }}'); })"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg shadow hover:bg-red-700 transition duration-200"
                                                title="Eliminar usuario">
                                            <i class="fa-solid fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-users-slash text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">No hay usuarios registrados</p>
                                    <p class="text-sm mt-2">Comienza creando el primer usuario</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($usuarios->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $usuarios->links('vendor.pagination.tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>
</div>

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
