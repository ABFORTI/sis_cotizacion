@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-4xl bg-white rounded-lg shadow overflow-hidden">

        {{-- Imagen --}}
        <div class="hidden md:flex items-center justify-center bg-gray-100">
            <img
                src="{{ asset('images/innovet-logo.png') }}"
                alt="Innovet"
                class="max-w-full max-h-full object-contain"
            >
        </div>

        {{-- Login --}}
        <div class="p-4 flex flex-col justify-center">
            @if ($errors->has('login_error'))
                <div class="mb-4 text-red-600 text-sm bg-red-100 border border-red-300 rounded px-4 py-2">
                    {{ $errors->first('login_error') }}
                </div>
            @endif

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                Iniciar Sesión
            </h2>

            <form method="POST" action="{{ route('inicia-sesion') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="tunombre@ab-forti.com"
                        required
                        autofocus
                        class="w-full border rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2 focus:ring-green-500
                               @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="*******"
                        required
                        class="w-full border rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2 focus:ring-green-500
                               @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="mr-2">
                    <label for="remember" class="text-gray-700">
                        Recordar contraseña
                    </label>
                </div>

                {{-- Button --}}
                <button
                    type="submit"
                    class="w-full bg-green-500 hover:bg-green-600
                           text-white font-semibold py-2 rounded-lg
                           transition duration-200 shadow-md"
                >
                    Ingresar
                </button>

                {{-- Register --}}
                <p class="text-center text-sm text-gray-600 mt-4">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="text-green-600 hover:underline font-medium">
                        Regístrate
                    </a>
                </p>
            </form>
        </div>

    </div>
</div>
@endsection