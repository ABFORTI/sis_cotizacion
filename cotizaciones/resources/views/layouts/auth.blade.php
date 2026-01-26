<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>@yield('title', 'Cotizaciones')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 flex flex-col min-h-screen">
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Modal de Confirmación Personalizado -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative mx-auto p-8 border w-full max-w-md shadow-2xl rounded-xl bg-white animate-fade-in">
            <div class="text-center">
                <!-- Logo de Innovet -->
                <div class="mx-auto flex items-center justify-center h-24 w-40 mb-4">
                    <img src="{{ asset('images/innovet-logo.png') }}" alt="Innovet" class="max-h-full max-w-full">
                </div>
                <!-- Título -->
                <h3 class="text-xl font-bold text-gray-900 mb-3" id="confirmModalTitle">
                    ¿Estás seguro?
                </h3>
                
                <!-- Mensaje -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600" id="confirmModalMessage">
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                
                <!-- Botones -->
                <div class="flex gap-3 justify-center">
                    <button id="confirmModalCancel" class="px-6 py-2.5 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition duration-150">
                        Cancelar
                    </button>
                    <button id="confirmModalOk" class="px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-150">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar el modal de confirmación personalizado
        function showConfirmModal(title, message, onConfirm) {
            const modal = document.getElementById('confirmModal');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const okBtn = document.getElementById('confirmModalOk');
            const cancelBtn = document.getElementById('confirmModalCancel');
            
            // Establecer el título y mensaje
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            // Mostrar el modal
            modal.classList.remove('hidden');
            
            // Manejar el clic en Aceptar
            okBtn.onclick = function() {
                modal.classList.add('hidden');
                if (onConfirm) onConfirm();
            };
            
            // Manejar el clic en Cancelar
            cancelBtn.onclick = function() {
                modal.classList.add('hidden');
            };
            
            // Cerrar al hacer clic fuera del modal
            modal.onclick = function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            };
            
            // Cerrar con la tecla Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        }

        // Función para mostrar mensajes de éxito (reutiliza el mismo modal)
        function showSuccessMessage(message) {
            const modal = document.getElementById('confirmModal');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const okBtn = document.getElementById('confirmModalOk');
            const cancelBtn = document.getElementById('confirmModalCancel');
            
            // Cambiar el contenido del modal para mostrar éxito
            titleEl.textContent = '✅ Éxito';
            messageEl.textContent = message;
            
            // Ocultar el botón Cancelar y cambiar el estilo del botón OK
            cancelBtn.style.display = 'none';
            okBtn.textContent = 'Aceptar';
            okBtn.className = 'px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition duration-150';
            
            // Mostrar el modal
            modal.classList.remove('hidden');
            
            // Solo cerrar al hacer clic en OK
            okBtn.onclick = function() {
                modal.classList.add('hidden');
                // Restaurar el estado original del modal
                cancelBtn.style.display = 'block';
                okBtn.textContent = 'Aceptar';
                okBtn.className = 'px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-150';
            };
        }

        // Función para mostrar mensajes de error (reutiliza el mismo modal)
        function showErrorMessage(message) {
            const modal = document.getElementById('confirmModal');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const okBtn = document.getElementById('confirmModalOk');
            const cancelBtn = document.getElementById('confirmModalCancel');
            
            // Cambiar el contenido del modal para mostrar error
            titleEl.textContent = '❌ Error';
            messageEl.textContent = message;
            
            // Ocultar el botón Cancelar
            cancelBtn.style.display = 'none';
            okBtn.textContent = 'Aceptar';
            // Mantener el color rojo para errores
            okBtn.className = 'px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-150';
            
            // Mostrar el modal
            modal.classList.remove('hidden');
            
            // Solo cerrar al hacer clic en OK
            okBtn.onclick = function() {
                modal.classList.add('hidden');
                // Restaurar el estado original del modal
                cancelBtn.style.display = 'block';
            };
        }
    </script>

    @stack('scripts')
</body>
<x-footer />

</html>
