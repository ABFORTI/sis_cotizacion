@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Título -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-red-800">
                <i class="fa-solid fa-user-pen mr-2"></i> Editar Usuario
            </h1>
            <p class="text-gray-600 mt-2">Modifique los datos del usuario <span class="font-semibold">{{ $usuario->name }}</span></p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Información Básica -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fa-solid fa-id-card mr-2 text-red-800"></i> Información Básica
                    </h2>
                    
                    <!-- Grid responsivo -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="{{ old('name', $usuario->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                placeholder="Ingrese el nombre completo"
                                required
                            >
                            @error('name')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Correo -->
                        <div class="form-group">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                value="{{ old('email', $usuario->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                placeholder="ejemplo@innovet.com"
                                required
                            >
                            @error('email')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="form-group md:col-span-2">
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                                Rol <span class="text-red-600">*</span>
                            </label>
                            <select 
                                id="role"
                                name="role" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                required
                            >
                                <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="ventas" {{ old('role', $usuario->role) == 'ventas' ? 'selected' : '' }}>Ventas</option>
                                <option value="costeos" {{ old('role', $usuario->role) == 'costeos' ? 'selected' : '' }}>Costeos</option>
                            </select>
                            @error('role')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fa-solid fa-key mr-2 text-red-800"></i> Cambiar Contraseña
                    </h2>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Deja estos campos vacíos si no deseas cambiar la contraseña del usuario.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nueva Contraseña -->
                        <div class="form-group">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                placeholder="Mínimo 8 caracteres"
                            >
                            @error('password')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="form-group">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirmar Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                placeholder="Repite la contraseña"
                            >
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end mt-8 pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('usuarios.index') }}" 
                        class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition duration-200 text-center"
                    >
                        <i class="fa-solid fa-times mr-2"></i> Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-red-800 text-white font-semibold rounded-lg hover:bg-red-900 transition duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="fa-solid fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
