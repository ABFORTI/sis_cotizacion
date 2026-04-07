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
    <style>
        #confirmModal img {
            width: auto !important;
            height: auto !important;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }

        #loadingRequisicionModal .loader-ring {
            width: 4rem;
            height: 4rem;
            aspect-ratio: 1 / 1;
            flex-shrink: 0;
        }

        #loadingRequisicionModal .loader-ring svg {
            width: 2rem;
            height: 2rem;
            flex-shrink: 0;
        }
    </style>

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

    <div id="loadingRequisicionModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative mx-auto p-8 border w-full max-w-md shadow-2xl rounded-xl bg-white">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative inline-flex items-center justify-center loader-ring">
                        <div class="inline-flex rounded-full w-full h-full bg-gradient-to-tr from-blue-500 to-green-500 p-0.5">
                            <div class="flex items-center justify-center rounded-full w-full h-full bg-white">
                                <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2" id="loadingRequisicionTitle">
                    Procesando solicitud...
                </h3>

                <p class="text-sm text-gray-600 mb-6 min-h-[3rem] leading-snug px-2 break-words" id="loadingRequisicionMessage">
                    Por favor espera mientras se completa el proceso.
                </p>

                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-full rounded-full w-0 transition-all duration-500" id="loadingRequisicionProgress"></div>
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    Este proceso puede tomar algunos segundos. No recargues la página.
                </p>
            </div>
        </div>
    </div>

    <script>
        function showLoadingRequisicionModal(config = {}) {
            const modal = document.getElementById('loadingRequisicionModal');
            const titleEl = document.getElementById('loadingRequisicionTitle');
            const messageEl = document.getElementById('loadingRequisicionMessage');
            const progressBar = document.getElementById('loadingRequisicionProgress');

            if (!modal || !messageEl || !progressBar) {
                return;
            }

            const messages = Array.isArray(config.messages) && config.messages.length
                ? config.messages
                : [
                    'Validando informacion...',
                    'Preparando solicitud...',
                    'Guardando datos...',
                    'Casi listo...'
                ];

            if (titleEl) {
                titleEl.textContent = config.title || 'Procesando solicitud...';
            }

            messageEl.textContent = config.message || 'Por favor espera mientras se completa el proceso.';
            modal.classList.remove('hidden');
            progressBar.style.width = '10%'; 

            let messageIndex = 0;
            let progressValue = 10;

            const progressInterval = setInterval(() => {
                progressValue += Math.random() * 25;
                if (progressValue > 90) progressValue = 90;
                progressBar.style.width = progressValue + '%';
            }, 600);

            const messageInterval = setInterval(() => {
                messageIndex = (messageIndex + 1) % messages.length;
                messageEl.style.opacity = '0';
                setTimeout(() => {
                    messageEl.textContent = messages[messageIndex];
                    messageEl.style.opacity = '1';
                    messageEl.style.transition = 'opacity 0.3s ease';
                }, 150);
            }, 2000);

            modal.dataset.progressInterval = progressInterval;
            modal.dataset.messageInterval = messageInterval;
        }

        function hideLoadingRequisicionModal() {
            const modal = document.getElementById('loadingRequisicionModal');
            const titleEl = document.getElementById('loadingRequisicionTitle');
            const messageEl = document.getElementById('loadingRequisicionMessage');
            const progressBar = document.getElementById('loadingRequisicionProgress');

            if (!modal || !progressBar) {
                return;
            }

            if (modal.dataset.progressInterval) {
                clearInterval(modal.dataset.progressInterval);
            }

            if (modal.dataset.messageInterval) {
                clearInterval(modal.dataset.messageInterval);
            }

            progressBar.style.width = '100%';

            setTimeout(() => {
                modal.classList.add('hidden');
                progressBar.style.width = '0%';

                if (titleEl) {
                    titleEl.textContent = 'Procesando solicitud...';
                }

                if (messageEl) {
                    messageEl.textContent = 'Por favor espera mientras se completa el proceso.';
                    messageEl.style.opacity = '1';
                }
            }, 500);
        }

        function getLoadingConfigFromForm(form) {
            return {
                title: form.dataset.loadingTitle || 'Procesando solicitud...',
                message: form.dataset.loadingMessage || 'Por favor espera mientras se completa el proceso.',
                buttonText: form.dataset.loadingButtonText || 'Procesando, por favor espera...',
            };
        }

        function activateLoadingForForm(form, submitter = null, customConfig = {}) {
            if (!form || form.dataset.isLoading === 'true') {
                return false;
            }

            const config = {
                ...getLoadingConfigFromForm(form),
                ...customConfig,
            };

            const submitButtons = Array.from(form.querySelectorAll('button[type="submit"]'));

            form.dataset.isLoading = 'true';
            form.setAttribute('aria-busy', 'true');
            form.classList.add('pointer-events-none');

            submitButtons.forEach((button) => {
                if (!button.dataset.originalText) {
                    button.dataset.originalText = button.textContent.trim();
                }

                button.disabled = true;
                button.classList.add('opacity-60', 'cursor-not-allowed');
            });

            const activeButton = submitter || submitButtons[0];
            if (activeButton) {
                activeButton.textContent = config.buttonText;
            }

            showLoadingRequisicionModal({
                title: config.title,
                message: config.message,
            });

            return true;
        }

        function resetLoadingForForm(form) {
            if (!form) {
                return;
            }

            const submitButtons = Array.from(form.querySelectorAll('button[type="submit"]'));

            form.dataset.isLoading = 'false';
            form.removeAttribute('aria-busy');
            form.classList.remove('pointer-events-none');

            submitButtons.forEach((button) => {
                button.disabled = false;
                button.classList.remove('opacity-60', 'cursor-not-allowed');
                button.textContent = button.dataset.originalText || button.textContent;
            });

            hideLoadingRequisicionModal();
        }

        function bindLoadingToForm(form) {
            if (!form || form.dataset.loadingBound === 'true') {
                return;
            }

            form.dataset.loadingBound = 'true';
            form.dataset.isLoading = form.dataset.isLoading || 'false';

            form.addEventListener('submit', function(event) {
                if (event.defaultPrevented) {
                    return;
                }

                if (form.dataset.isLoading === 'true') {
                    event.preventDefault();
                    return;
                }

                activateLoadingForForm(form, event.submitter || null);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[data-loading="true"]').forEach(bindLoadingToForm);
        });

        window.addEventListener('pageshow', function() {
            document.querySelectorAll('form[data-loading="true"]').forEach(resetLoadingForForm);
        });

        window.activateLoadingForForm = activateLoadingForForm;
        window.resetLoadingForForm = resetLoadingForForm;
        window.submitManagedForm = function(formId) {
            const form = document.getElementById(formId);

            if (!form) {
                return;
            }

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
                return;
            }

            if (form.dataset.loading === 'true') {
                activateLoadingForForm(form);
            }

            form.submit();
        };

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
