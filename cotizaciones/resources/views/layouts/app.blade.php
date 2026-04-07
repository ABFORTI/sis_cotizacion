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

<body class="flex flex-col min-h-screen" style="background-color: #e9e8e8;">
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
            width: 2.5rem;
            height: 2.5rem;
            aspect-ratio: 1 / 1;
            flex-shrink: 0;
        }

        #loadingRequisicionModal .loader-ring svg {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }

        @media (min-width: 640px) {
            #loadingRequisicionModal .loader-ring {
                width: 4rem;
                height: 4rem;
            }

            #loadingRequisicionModal .loader-ring svg {
                width: 2rem;
                height: 2rem;
            }
        }
    </style>

    <x-navbar />
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

    <div id="loadingRequisicionModal"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden"
    style="display: none;"
    aria-hidden="true">

    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm text-center">

        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <h3 id="loadingRequisicionTitle" class="text-lg font-semibold text-gray-800">
            Generando requisición
        </h3>

        <p id="loadingRequisicionMessage" class="text-sm text-gray-500 mt-2">
            Procesando archivo, por favor espera...
        </p>

        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div id="loadingRequisicionProgress" class="bg-blue-500 h-2 rounded-full w-0 transition-all duration-500"></div>
        </div>

    </div>
</div>

    <script>
        // Funciones para el modal de loading de requisición
        function showLoadingRequisicionModal(config = {}) {
            const modal = document.getElementById('loadingRequisicionModal');
            const titleEl = document.getElementById('loadingRequisicionTitle');
            const messageEl = document.getElementById('loadingRequisicionMessage');
            const progressBar = document.getElementById('loadingRequisicionProgress');

            if (!modal || !messageEl || !progressBar) {
                return;
            }

            const defaultTitle = 'Generando requisicion...';
            const defaultMessage = 'Procesando archivo, por favor espera';
            const messages = Array.isArray(config.messages) && config.messages.length
                ? config.messages
                : [
                    'Procesando archivo...',
                    'Validando datos...',
                    'Guardando informacion...',
                    'Generando documento...',
                    'Casi listo...'
                ];

            if (titleEl) {
                titleEl.textContent = config.title || defaultTitle;
            }

            messageEl.textContent = config.message || defaultMessage;

            // Mostrar el modal
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');

            // Reset y inicia progreso
            progressBar.style.width = '10%';

            let messageIndex = 0;
            let progressValue = 10;

            // Actualizar barra de progreso
            const progressInterval = setInterval(() => {
                progressValue += Math.random() * 25;
                if (progressValue > 90) progressValue = 90;
                progressBar.style.width = progressValue + '%';
            }, 600);

            // Actualizar mensajes
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
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                progressBar.style.width = '0%';

                if (titleEl) {
                    titleEl.textContent = 'Generando requisicion...';
                }

                if (messageEl) {
                    messageEl.textContent = 'Procesando archivo, por favor espera';
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
    </script>

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
            
            okBtn.onclick = function() {
                modal.classList.add('hidden');
                if (onConfirm) onConfirm();
            };
            
            cancelBtn.onclick = function() {
                modal.classList.add('hidden');
            };
            
            modal.onclick = function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            };
            
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        }

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

        function showErrorMessage(message) {
            const modal = document.getElementById('confirmModal');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const okBtn = document.getElementById('confirmModalOk');
            const cancelBtn = document.getElementById('confirmModalCancel');
            
            titleEl.textContent = '❌ Error';
            messageEl.textContent = message;
            
            cancelBtn.style.display = 'none';
            okBtn.textContent = 'Aceptar';
            okBtn.className = 'px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-150';
            
            modal.classList.remove('hidden');
            
            okBtn.onclick = function() {
                modal.classList.add('hidden');
                cancelBtn.style.display = 'block';
            };
        }
    </script>

    <!-- Loading Indicator Overlay -->
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes shimmer {
            0% { width: 0%; opacity: 1; }
            50% { opacity: 0.8; }
            100% { width: 100%; opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>

    <!-- Loading Overlay Simple -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-99999" style="display: none !important; opacity: 0; transition: opacity 0.3s ease;">
        <!-- Barra de progreso superior -->
        <div id="progressBar" style="position: fixed; top: 0; left: 0; height: 3px; background: linear-gradient(90deg, #a51e24, #6ac043); width: 0%; z-index: 100001;"></div>

        <!-- Modal -->
        <div style="background: white; border-radius: 24px; padding: 48px; max-width: 450px; width: 90%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: slideUp 0.5s ease-out; z-index: 100000; position: relative;">
            <!-- Spinner -->
            <div style="display: flex; justify-content: center; margin-bottom: 32px;">
                <div style="position: relative; width: 112px; height: 112px;">
                    <div style="position: absolute; inset: 0; border-radius: 50%; border: 8px solid #f0f0f0;"></div>
                    <div style="position: absolute; inset: 0; border-radius: 50%; border: 8px solid transparent; border-top: 8px solid #a51e24; border-right: 8px solid #6ac043; animation: spin 2s linear infinite;"></div>
                    <div style="position: absolute; inset: 0; border-radius: 50%; border: 4px solid #ddd; opacity: 0.3; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                </div>
            </div>

            <!-- Título -->
            <h2 style="font-size: 28px; font-weight: bold; margin-bottom: 12px; text-align: center; color: #a51e24;">Generando requisición</h2>

            <!-- Mensaje dinámico -->
            <p id="loadingMessage" style="color: #666; text-align: center; margin-bottom: 24px; height: 24px; font-size: 16; opacity: 1; transition: opacity 0.3s ease;">Procesando archivo...</p>

            <!-- Dots -->
            <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 24px;">
                <div style="width: 12px; height: 12px; background: #a51e24; border-radius: 50%; animation: bounce 1.4s infinite; animation-delay: 0s;"></div>
                <div style="width: 12px; height: 12px; background: #6ac043; border-radius: 50%; animation: bounce 1.4s infinite; animation-delay: 0.2s;"></div>
                <div style="width: 12px; height: 12px; background: #6f6f71; border-radius: 50%; animation: bounce 1.4s infinite; animation-delay: 0.4s;"></div>
            </div>

            <!-- Barra interna -->
            <div style="width: 100%; background: #e5e7eb; height: 3px; border-radius: 10px; overflow: hidden;">
                <div id="internalProgress" style="background: linear-gradient(90deg, #a51e24, #6ac043); height: 100%; width: 0%; animation: shimmer 1.5s infinite;"></div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
<x-footer />

</html>