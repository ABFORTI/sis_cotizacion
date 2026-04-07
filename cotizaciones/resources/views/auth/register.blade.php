@extends('layouts.app')

@section('content')

<style>
    /* Estilos adicionales específicos para la página de registro */
    :root {
    --primary: #3b82f6;      
    --primary-dark: #2563eb;
    --error: #ef4444;
    --gray-light: #f9fafb;
    --gray-border: #d1d5db;
    --text-dark: #374151;
}

body {
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

.max-w-md {
    animation: fadeInUp 0.5s ease-out;
}

.bg-white {
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

h2 {
    text-align: center;
    color: var(--text-dark);
    letter-spacing: 0.5px;
}

label {
    font-weight: 500;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    transition: all 0.2s ease;
    border: 1px solid var(--gray-border);
    background-color: var(--gray-light);
}

input:focus {
    outline: none;
    border-color: var(--primary);
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

input.border-red-500 {
    border-color: var(--error);
    background-color: #fff5f5;
}

.text-red-500 {
    font-size: 0.85rem;
    animation: shake 0.3s ease-in-out;
}

input[type="radio"] {
    transform: scale(1.1);
    cursor: pointer;
}

label.inline-flex {
    padding: 6px 10px;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

label.inline-flex:hover {
    background-color: #eef2ff;
}

button[type="submit"] {
    width: 100%;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.25s ease;
}

button[type="submit"]:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3);
}

button[type="submit"]:active {
    transform: translateY(0);
    box-shadow: none;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    50% { transform: translateX(3px); }
    75% { transform: translateX(-3px); }
    100% { transform: translateX(0); }
}
</style>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Crear cuenta</h2>

    <form method="POST" action="{{ route('validar-registro') }}"
        data-loading="true"
        data-loading-title="Creando cuenta..."
        data-loading-message="Validando informacion de registro, por favor espera"
        data-loading-button-text="Creando cuenta, por favor espera...">
        @csrf

        <!-- Nombre -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nombre</label>
            <input id="name" type="text"
                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                name="name" value="{{ old('name') }}" required autofocus placeholder="Nombre completo">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Correo electrónico</label>
            <input id="email" type="email"
                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                name="email" value="{{ old('email') }}" required placeholder="tunombre@ejemplo.com">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Contraseña</label>
            <input id="password" type="password"
                class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror"
                name="password" required placeholder="***********">
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirmar contraseña</label>
            <input id="password_confirmation" type="password"
                class="w-full border rounded px-3 py-2 @error('password_confirmation') border-red-500 @enderror"
                name="password_confirmation" required placeholder="***********">
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

            <div class="flex justify-center gap-6">
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