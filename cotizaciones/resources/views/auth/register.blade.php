@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Registro</h2>

    <form method="POST" action="{{ route('validar-registro') }}">
        @csrf

        <!-- Nombre -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nombre</label>
            <input id="name" type="text"
                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Correo electrónico</label>
            <input id="email" type="email"
                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                name="email" value="{{ old('email') }}" required>
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

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirmar contraseña</label>
            <input id="password_confirmation" type="password"
                class="w-full border rounded px-3 py-2 @error('password_confirmation') border-red-500 @enderror"
                name="password_confirmation" required>
            @error('password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <div id="password-match-error" class="text-red-500 text-sm mt-1 hidden">
                Las contraseñas no coinciden
            </div>
        </div>
        <!-- Rol -->
        <div class="mb-4">
            <label for="rol_ventas" class=" block text-gray-700 mb-2">Selecciona tu rol:</label>

            <div class="space-y-1">
                <label for="rol_ventas" class="inline-flex items-center gap-x-1 text-gray-700 cursor-pointer mr-3">
                    <input type="radio" name="role" value="ventas" id="rol_ventas"
                        class="accent-blue-500" {{ old('role') === 'ventas' ? 'checked' : '' }}>
                    <span>Ventas</span>
                </label>

                <label for="rol_costeos" class="inline-flex items-center gap-x-1 text-gray-700 cursor-pointer">
                    <input type="radio" name="role" value="costeos" id="rol_costeos"
                        class="accent-blue-500" {{ old('role') === 'costeos' ? 'checked' : '' }}>
                    <span>Costeos</span>
                </label>
            </div>

            @error('role')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>



        <div class="flex justify-between">
            <button type="submit" id="submit-btn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Registrarse
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const passwordError = document.getElementById('password-match-error');
    const submitBtn = document.getElementById('submit-btn');

    function validatePasswords() {
        if (password.value && passwordConfirmation.value) {
            if (password.value !== passwordConfirmation.value) {
                passwordError.classList.remove('hidden');
                passwordConfirmation.classList.add('border-red-500');
                passwordConfirmation.classList.remove('border-gray-300');
                return false;
            } else {
                passwordError.classList.add('hidden');
                passwordConfirmation.classList.remove('border-red-500');
                passwordConfirmation.classList.add('border-gray-300');
                return true;
            }
        }
        return password.value === '' && passwordConfirmation.value === '';
    }

    function validateAllFields() {
        const isNameValid = name.value.trim() !== '';
        const isEmailValid = email.value.trim() !== '';
        const isPasswordValid = password.value.trim() !== '';
        const isPasswordConfirmationValid = passwordConfirmation.value.trim() !== '';
        const arePasswordsMatching = validatePasswords();

        const allFieldsValid = isNameValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid && arePasswordsMatching;

        if (allFieldsValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        return allFieldsValid;
    }

    // Validar en tiempo real todos los campos
    name.addEventListener('input', validateAllFields);
    email.addEventListener('input', validateAllFields);
    password.addEventListener('input', validateAllFields);
    passwordConfirmation.addEventListener('input', validateAllFields);

    // Validación inicial
    validateAllFields();

    // Validar antes del envío del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const missingFields = [];
        
        if (name.value.trim() === '') missingFields.push('Nombre');
        if (email.value.trim() === '') missingFields.push('Correo electrónico');
        if (password.value.trim() === '') missingFields.push('Contraseña');
        if (passwordConfirmation.value.trim() === '') missingFields.push('Confirmar contraseña');

        if (missingFields.length > 0) {
            e.preventDefault();
            alert('Por favor, completa los siguientes campos:\n- ' + missingFields.join('\n- '));
            return;
        }

        if (!validatePasswords()) {
            e.preventDefault();
            alert('Las contraseñas no coinciden. Por favor, corríjalas antes de continuar.');
            return;
        }
    });
});
</script>
@endsection