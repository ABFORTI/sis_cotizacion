@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Título -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-red-800">Crear Nuevo Usuario</h1>
            <p class="text-gray-600 mt-2">Complete el formulario para crear un nuevo usuario</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <form action="{{ route('usuarios.store') }}" method="POST"
                data-loading="true"
                data-loading-title="Creando usuario..."
                data-loading-message="Guardando el nuevo usuario, por favor espera"
                data-loading-button-text="Creando usuario, por favor espera...">
                @csrf
                
                <!-- Grid responsivo: 1 columna en móvil, 2 en tablet y desktop -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                            placeholder="Ingrese el nombre completo"
                            required
                            value="{{ old('name') }}"
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                            placeholder="ejemplo@innovet.com"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contraseña <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                            placeholder="Mínimo 8 caracteres"
                            required
                        >
                        @error('password')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="form-group">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirmar Contraseña <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                            placeholder="Repita la contraseña"
                            required
                        >
                        @error('password_confirmation')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
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
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="ventas" {{ old('role') == 'ventas' ? 'selected' : '' }}>Ventas</option>
                            <option value="costeos" {{ old('role') == 'costeos' ? 'selected' : '' }}>Costeos</option>
                        </select>
                        @error('role')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end mt-8 pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('usuarios.index') }}" 
                        class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition duration-200 text-center"
                    >
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-red-800 text-white font-semibold rounded-lg hover:bg-red-900 transition duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="fa-solid fa-save mr-2"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
