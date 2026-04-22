@extends('layouts.app')

@section('title', 'Supervisión de Ventas')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-center">
        <h1 class="text-xl font-semibold text-white mb-4">@yield('title')</h1>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-3 gap-4">
            <aside class="lg:col-span-1 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-800 text-center">Usuarios de ventas</h2>
                </div>

                <div class="max-h-[28rem] overflow-y-auto col-span-2" id="ventas-sidebar">
                    @forelse($usuariosVentas as $usuario)
                        <button
                            type="button"
                            class="user-activity-trigger w-full text-left px-4 py-3 border-b border-gray-100 hover:bg-green-80 transition"
                            data-user-id="{{ $usuario->id }}"
                            data-url="{{ route('gerente.ventas.actividad', $usuario) }}"
                        >
                            <div class="font-semibold text-gray-900">{{ $usuario->name }}</div>
                            <div class="text-sm text-gray-500">{{ $usuario->email }}</div>
                        </button>
                    @empty
                        <div class="px-4 py-6 text-sm text-gray-500">
                            No hay usuarios con rol de ventas registrados.
                        </div>
                    @endforelse
                </div>
            </aside>

            <section class="lg:col-span-2 border border-gray-200 rounded-lg p-6 bg-gray-50">
                <div id="activity-empty" class="text-center text-gray-500 py-10">
                    Selecciona un usuario de ventas para ver su actividad.
                </div>

                <div id="activity-loading" class="hidden text-center text-gray-500 py-10">
                    Cargando actividad...
                </div>

                <div id="activity-error" class="hidden bg-green-80 border border-green-200 text-green-70 rounded-lg px-4 py-3 mb-4"></div>

                <div id="activity-panel" class="hidden space-y-5">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <h2 class="text-2xl text-left font-bold text-gray-900" id="actividad-nombre"></h2>
                            <p class="text-gray-600 text-left" id="actividad-correo"></p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <p class="text-sm text-center text-gray-500 uppercase tracking-wide">Total de requisiciones</p>
                            <p class="text-3xl text-center font-bold text-red-800 mt-2" id="actividad-total">0</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-center text-gray-800 mb-3">Clientes registrados</h3>
                        <div id="actividad-clientes" class="space-y-2"></div>
                        <p id="actividad-sin-clientes" class="hidden text-gray-500">Este usuario aún no tiene clientes registrados.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const triggers = document.querySelectorAll('.user-activity-trigger');
    const panel = document.getElementById('activity-panel');
    const emptyState = document.getElementById('activity-empty');
    const loadingState = document.getElementById('activity-loading');
    const errorBox = document.getElementById('activity-error');
    const nombre = document.getElementById('actividad-nombre');
    const correo = document.getElementById('actividad-correo');
    const total = document.getElementById('actividad-total');
    const clientes = document.getElementById('actividad-clientes');
    const sinClientes = document.getElementById('actividad-sin-clientes');
    const resumenCostosUrlTemplate = @json(route('cotizacion.resumen.page', ['id' => '__ID__']));

    const setActiveUser = (activeButton) => {
        triggers.forEach((button) => button.classList.remove('bg-green-100'));
        activeButton.classList.add('bg-green-100');
    };

    const renderActivity = (data) => {
        nombre.textContent = data.nombre || 'Sin nombre';
        correo.textContent = data.correo || 'Sin correo';
        total.textContent = data.total_requisiciones ?? 0;
        clientes.innerHTML = '';

        if (Array.isArray(data.clientes) && data.clientes.length > 0) {
            sinClientes.classList.add('hidden');

            data.clientes.forEach((cliente) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'border border-gray-200 rounded-lg overflow-hidden';

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-green-80 transition text-left';
                button.innerHTML = `
                    <span>
                        <span class="font-semibold text-gray-800">${cliente.nombre}</span>
                        <span class="text-sm text-gray-500 ml-2">(${cliente.total_cotizaciones} cotizacion${cliente.total_cotizaciones === 1 ? '' : 'es'})</span>
                    </span>
                    <span class="text-sm text-gray-500">▼</span>
                `;

                const detail = document.createElement('div');
                detail.className = 'hidden px-4 py-3 bg-white border-t border-gray-100';

                if (Array.isArray(cliente.proyectos) && cliente.proyectos.length > 0) {
                    const list = document.createElement('ul');
                    list.className = 'space-y-2';

                    cliente.proyectos.forEach((proyecto) => {
                        const item = document.createElement('li');
                        item.className = 'text-sm text-gray-700 border border-gray-100 rounded-md p-2';

                        let estadoClase = 'bg-gray-100 text-gray-700';
                        if (proyecto.estado_flujo === 'En revisión de costeos') {
                            estadoClase = 'bg-yellow-100 text-yellow-800';
                        } else if (proyecto.estado_flujo === 'Regresada al usuario') {
                            estadoClase = 'bg-green-100 text-green-800';
                        }

                        const resumenUrl = proyecto.id
                            ? resumenCostosUrlTemplate.replace('__ID__', proyecto.id)
                            : '#';

                        item.innerHTML = `
                            <div class="flex flex-col gap-2">
                                <div>
                                    <span class="font-semibold text-red-800">${proyecto.folio}</span> — ${proyecto.nombre_proyecto || 'Sin nombre de proyecto'}
                                </div>
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ${estadoClase}">
                                        ${proyecto.estado_flujo || 'Sin estado'}
                                    </span>
                                    <a
                                        href="${resumenUrl}"
                                        class="inline-block rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors"
                                    >
                                        Ver resumen de costos
                                    </a>
                                </div>
                            </div>
                        `;
                        list.appendChild(item);
                    });

                    detail.appendChild(list);
                } else {
                    detail.innerHTML = '<p class="text-sm text-gray-500">No hay proyectos registrados para este cliente.</p>';
                }

                button.addEventListener('click', () => {
                    detail.classList.toggle('hidden');
                });

                wrapper.appendChild(button);
                wrapper.appendChild(detail);
                clientes.appendChild(wrapper);
            });
        } else {
            sinClientes.classList.remove('hidden');
        }

        emptyState.classList.add('hidden');
        loadingState.classList.add('hidden');
        errorBox.classList.add('hidden');
        panel.classList.remove('hidden');
    };

    const loadActivity = async (button) => {
        setActiveUser(button);
        loadingState.classList.remove('hidden');
        emptyState.classList.add('hidden');
        panel.classList.add('hidden');
        errorBox.classList.add('hidden');

        try {
            const response = await fetch(button.dataset.url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('No se pudo obtener la actividad del usuario.');
            }

            const data = await response.json();
            renderActivity(data);
        } catch (error) {
            loadingState.classList.add('hidden');
            errorBox.textContent = error.message;
            errorBox.classList.remove('hidden');
        }
    };

    triggers.forEach((button) => {
        button.addEventListener('click', () => loadActivity(button));
    });

    if (triggers.length > 0) {
        loadActivity(triggers[0]);
    }
});
</script>
@endsection
