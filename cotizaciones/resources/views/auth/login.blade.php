@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    @if ($errors->has('login_error'))
    <div class="mb-4 text-red-600 text-sm bg-red-100 border border-red-300 rounded px-4 py-2">
        {{ $errors->first('login_error') }}
    </div>
    @endif
    <h2 class="text-xl font-bold mb-4">Iniciar Sesión</h2>

    <form method="POST" action="{{ route('inicia-sesion') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Correo electrónico</label>
            <input id="email" type="email"
                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Contraseña</label>
            <input id="password" type="password"
                class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror"
                name="password" required>
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-gray-700">Recordar contraseña</label>
        </div>

        <div class="flex justify-between">
            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Ingresar
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">¿No tienes una cuenta? Regístrate</a>
        </div>
    </form>
</div>
@endsection