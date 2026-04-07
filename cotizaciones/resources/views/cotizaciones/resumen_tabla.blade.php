@extends('layouts.app')

@section('title', 'Resumen de Costos')

@section('content')
@php
    $ventasResumenPersistido = $cotizacion->ventasResumen;
    $precioHerramentalesPersistido = $ventasResumenPersistido->herramental_total_ventas ?? null;
    $precioHerramentalesTexto = is_null($precioHerramentalesPersistido)
        ? 'N/C'
        : '$ ' . number_format($precioHerramentalesPersistido, 2);
    $highlightHerramental = session('success') && !is_null($precioHerramentalesPersistido);
@endphp
<style>
    #resumen-save-form input[type="number"],
    #herramental-section input[type="number"] {
        text-align: right;
    }

    .summary-card {
        border: 1px solid rgb(226 232 240);
        border-radius: 1rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .summary-table thead th {
        letter-spacing: 0.02em;
    }

    .herramental-kpi-glow {
        animation: herramentalPulse 1.2s ease-out;
    }

    @keyframes herramentalPulse {
        0% { box-shadow: 0 0 0 rgba(16, 185, 129, 0); }
        35% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0.18); }
        100% { box-shadow: 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header: logo left, folio/fecha right -->
        <div class="flex-1 text-center">
                    <h1 class="text-3xl font-bold text-blue-800">Resumen de Costos</h1>
        </div>
    <div class="flex items-start justify-between mb-4 header-mobile">
        <div>
            <!-- Ajusta la ruta del logo según tu proyecto -->
            <img src="{{ asset('images/innovet-logo.png') }}" alt="Innovet" style="max-width: 220px; width: 100%; height: auto;">
        </div>
        <div class="text-right w-1/2">
            <table class="ml-auto text-sm">
                <tr>
                    <td class="pr-2 text-gray-600 font-semibold">Folio:</td>
                    <td class="font-bold font-semibold">{{ $cotizacion->no_proyecto }}</td>
                </tr>
                <tr>
                    <td class="pr-2 text-gray-600 font-semibold">Fecha:</td>
                    <td class="font-semibold">{{ $cotizacion->fecha }}</td>
                </tr>
            </table>
            <div class="mt-2 flex justify-end gap-2 font-bold btn-container-mobile">
                <!-- Botón para ir a cotizaciones -->
                <a href="{{ route('cotizacion.form', $cotizacion) }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-800 transition-colors text-center">
                    <i class="fas fa-file"></i> Ver Resumen de Cotización</a>
            </div>
        </div>
    </div>

        <!-- SECCIÓN: RESUMEN DE COSTOS (Como en Excel) -->

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
            <section class="summary-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Resumen Comercial</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Costos del proyecto y herramentales</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Ajusta los importes del resumen, revisa los totales calculados y guarda al final. El valor de herramentales mostrado en el panel lateral siempre refleja el monto persistido en ventas.
                </p>
            </section>

            <aside class="summary-card p-5 {{ $highlightHerramental ? 'herramental-kpi-glow border-emerald-300' : '' }}">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">KPI Herramentales</p>
                        <h3 class="mt-2 text-sm font-medium text-slate-600">Precio de herramentales guardado</h3>
                    </div>
                    @if($highlightHerramental)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Actualizado</span>
                    @endif
                </div>
                <p class="mt-6 text-4xl font-semibold tracking-tight text-slate-900">{{ $precioHerramentalesTexto }}</p>
                <p class="mt-3 text-sm text-slate-500">
                    @if(is_null($precioHerramentalesPersistido))
                        Aun no existe un valor persistido en ventas para herramentales.
                    @else
                        Valor persistido en <span class="font-semibold text-slate-700">ventas_resumen_de_costos</span>.
                    @endif
                </p>
            </aside>
        </div>

        <form action="{{ route('cotizacion.resumen.store', $cotizacion->id) }}" method="POST" id="resumen-save-form"
            data-loading="true"
            data-loading-title="Guardando resumen..."
            data-loading-message="Actualizando resumen de costos, por favor espera"
            data-loading-button-text="Guardando resumen, por favor espera...">
            @csrf
            <section class="summary-card p-5">
                <div class="mb-4 flex flex-col gap-2 border-b border-slate-200 pb-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Seccion 1</p>
                        <h2 class="text-2xl font-semibold text-slate-900">Costos del Proyecto</h2>
                    </div>
                    <p class="text-sm text-slate-500">Los campos de calculo permanecen en solo lectura y se actualizan automaticamente.</p>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
                <table class="summary-table w-full border-collapse text-center border border-gray-400">
                <thead class="bg-[#848484] text-white">
                    <tr>
                        <th class="border border-gray-300 p-2">Concepto</th>
                        <th class="border border-gray-300 p-2">Costo total<br><span class="text-xs font-normal">(MXN)</span></th>
                        <th class="border border-gray-300 p-2">Piezas</th>
                        <th class="border border-gray-300 p-2">Costo Unit<br><span class="text-xs font-normal">(MXN)</span></th>
                        <th class="border border-gray-300 p-2">Margen</th>
                        <th class="border border-gray-300 p-2">Precio venta<br><span class="text-xs font-normal">(MXN)</span></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Procesos -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Procesos de Maquinaria</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_procesos"
                                value="{{ old('resumen_costo_procesos', $ventasResumen->resumen_costo_procesos ?? $costeoRequisicion->resumen_costo_procesos) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('procesos')">
                        </td>
                        <td class=" border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_procesos"
                                value="{{ old('resumen_piezas_procesos', $ventasResumen->resumen_piezas_procesos ?? $costeoRequisicion->resumen_piezas_procesos) }}"
                                   class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('procesos')">
                        </td>
                        <td class=" border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_procesos" readonly
                                value="{{ old('resumen_costo_unit_procesos', $ventasResumen->resumen_costo_unit_procesos ?? $costeoRequisicion->resumen_costo_unit_procesos) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class=" border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_procesos" 
                                value="{{ old('resumen_margen_procesos', $ventasResumen->resumen_margen_procesos ?? $costeoRequisicion->resumen_margen_procesos ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('procesos')">
                        </td>
                        <td class=" border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_procesos" readonly
                                value="{{ old('resumen_precio_venta_procesos', $ventasResumen->resumen_precio_venta_procesos ?? $costeoRequisicion->resumen_precio_venta_procesos) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Empaque -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Empaque</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_empaque"
                                value="{{ old('resumen_costo_empaque', $ventasResumen->resumen_costo_empaque ?? $costeoRequisicion->resumen_costo_empaque) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('empaque')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_empaque"
                                value="{{ old('resumen_piezas_empaque', $ventasResumen->resumen_piezas_empaque ?? $costeoRequisicion->resumen_piezas_empaque) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('empaque')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_empaque" readonly
                                value="{{ old('resumen_costo_unit_empaque', $ventasResumen->resumen_costo_unit_empaque ?? $costeoRequisicion->resumen_costo_unit_empaque) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_empaque" 
                                value="{{ old('resumen_margen_empaque', $ventasResumen->resumen_margen_empaque ?? $costeoRequisicion->resumen_margen_empaque ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('empaque')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_empaque" readonly
                                value="{{ old('resumen_precio_venta_empaque', $ventasResumen->resumen_precio_venta_empaque ?? $costeoRequisicion->resumen_precio_venta_empaque) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Flete -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Flete</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_flete_total"
                                value="{{ old('resumen_costo_flete_total', $ventasResumen->resumen_costo_flete_total ?? $costeoRequisicion->resumen_costo_flete_total) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('flete')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_flete"
                                value="{{ old('resumen_piezas_flete', $ventasResumen->resumen_piezas_flete ?? $costeoRequisicion->resumen_piezas_flete) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('flete')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_flete" readonly
                                value="{{ old('resumen_costo_unit_flete', $ventasResumen->resumen_costo_unit_flete ?? $costeoRequisicion->resumen_costo_unit_flete) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step=" 1" name="resumen_margen_flete" 
                                value="{{ old('resumen_margen_flete', $ventasResumen->resumen_margen_flete ?? $costeoRequisicion->resumen_margen_flete ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('flete')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_flete" readonly
                                value="{{ old('resumen_precio_venta_flete', $ventasResumen->resumen_precio_venta_flete ?? $costeoRequisicion->resumen_precio_venta_flete) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <!-- Pedimento -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Pedimento</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_pedimento"
                                value="{{ old('resumen_costo_pedimento', $ventasResumen->resumen_costo_pedimento ?? $costeoRequisicion->resumen_costo_pedimento) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('pedimento')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_pedimento"
                                value="{{ old('resumen_piezas_pedimento', $ventasResumen->resumen_piezas_pedimento ?? $costeoRequisicion->resumen_piezas_pedimento) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('pedimento')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_pedimento" readonly
                                value="{{ old('resumen_costo_unit_pedimento', $ventasResumen->resumen_costo_unit_pedimento ?? $costeoRequisicion->resumen_costo_unit_pedimento) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_pedimento" 
                                value="{{ old('resumen_margen_pedimento', $ventasResumen->resumen_margen_pedimento ?? $costeoRequisicion->resumen_margen_pedimento ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('pedimento')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_pedimento" readonly
                                value="{{ old('resumen_precio_venta_pedimento', $ventasResumen->resumen_precio_venta_pedimento ?? $costeoRequisicion->resumen_precio_venta_pedimento) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Inocuidad -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Inocuidad</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_inocuidad"
                                value="{{ old('resumen_costo_inocuidad', $ventasResumen->resumen_costo_inocuidad ?? $costeoRequisicion->resumen_costo_inocuidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('inocuidad')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_inocuidad"
                                value="{{ old('resumen_piezas_inocuidad', $ventasResumen->resumen_piezas_inocuidad ?? $costeoRequisicion->resumen_piezas_inocuidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('inocuidad')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_inocuidad" readonly
                                value="{{ old('resumen_costo_unit_inocuidad', $ventasResumen->resumen_costo_unit_inocuidad ?? $costeoRequisicion->resumen_costo_unit_inocuidad) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_inocuidad"
                                value="{{ old('resumen_margen_inocuidad', $ventasResumen->resumen_margen_inocuidad ?? $costeoRequisicion->resumen_margen_inocuidad ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('inocuidad')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_inocuidad" readonly
                                value="{{ old('resumen_precio_venta_inocuidad', $ventasResumen->resumen_precio_venta_inocuidad ?? $costeoRequisicion->resumen_precio_venta_inocuidad ?? '') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Polipropileno -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Polipropileno</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_polipropileno"
                                value="{{ old('resumen_costo_polipropileno', $ventasResumen->resumen_costo_polipropileno ?? $costeoRequisicion->resumen_costo_polipropileno) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('polipropileno')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_polipropileno"
                                value="{{ old('resumen_piezas_polipropileno', $ventasResumen->resumen_piezas_polipropileno ?? $costeoRequisicion->resumen_piezas_polipropileno) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('polipropileno')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_polipropileno" readonly
                                value="{{ old('resumen_costo_unit_polipropileno', $ventasResumen->resumen_costo_unit_polipropileno ?? $costeoRequisicion->resumen_costo_unit_polipropileno) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_polipropileno"
                                value="{{ old('resumen_margen_polipropileno', $ventasResumen->resumen_margen_polipropileno ?? $costeoRequisicion->resumen_margen_polipropileno ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('polipropileno')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_polipropileno" readonly
                                value="{{ old('resumen_precio_venta_polipropileno', $ventasResumen->resumen_precio_venta_polipropileno ?? $costeoRequisicion->resumen_precio_venta_polipropileno ?? '') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Estaticidad -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Estaticidad</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_estaticidad"
                                value="{{ old('resumen_costo_estaticidad', $ventasResumen->resumen_costo_estaticidad ?? $costeoRequisicion->resumen_costo_estaticidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('estaticidad')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_estaticidad"
                                value="{{ old('resumen_piezas_estaticidad', $ventasResumen->resumen_piezas_estaticidad ?? $costeoRequisicion->resumen_piezas_estaticidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('estaticidad')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_estaticidad" readonly
                                value="{{ old('resumen_costo_unit_estaticidad', $ventasResumen->resumen_costo_unit_estaticidad ?? $costeoRequisicion->resumen_costo_unit_estaticidad) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_estaticidad"
                                value="{{ old('resumen_margen_estaticidad', $ventasResumen->resumen_margen_estaticidad ?? $costeoRequisicion->resumen_margen_estaticidad ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('estaticidad')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_estaticidad" readonly
                                value="{{ old('resumen_precio_venta_estaticidad', $ventasResumen->resumen_precio_venta_estaticidad ?? $costeoRequisicion->resumen_precio_venta_estaticidad ?? '') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Maquila -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Maquila</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_maquila"
                                value="{{ old('resumen_costo_maquila', $ventasResumen->resumen_costo_maquila ?? $costeoRequisicion->resumen_costo_maquila) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('maquila')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_maquila"
                                value="{{ old('resumen_piezas_maquila', $ventasResumen->resumen_piezas_maquila ?? $costeoRequisicion->resumen_piezas_maquila) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('maquila')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_maquila" readonly
                                value="{{ old('resumen_costo_unit_maquila', $ventasResumen->resumen_costo_unit_maquila ?? $costeoRequisicion->resumen_costo_unit_maquila) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_maquila"
                                value="{{ old('resumen_margen_maquila', $ventasResumen->resumen_margen_maquila ?? $costeoRequisicion->resumen_margen_maquila ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('maquila')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_maquila" readonly
                                value="{{ old('resumen_precio_venta_maquila', $ventasResumen->resumen_precio_venta_maquila ?? $costeoRequisicion->resumen_precio_venta_maquila ?? '') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <!-- Etiqueta -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Etiqueta</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="resumen_costo_etiqueta"
                                value="{{ old('resumen_costo_etiqueta', $ventasResumen->resumen_costo_etiqueta ?? $costeoRequisicion->resumen_costo_etiqueta) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('etiqueta')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_etiqueta"
                                value="{{ old('resumen_piezas_etiqueta', $ventasResumen->resumen_piezas_etiqueta ?? $costeoRequisicion->resumen_piezas_etiqueta) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularFila('etiqueta')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_etiqueta" readonly
                                value="{{ old('resumen_costo_unit_etiqueta', $ventasResumen->resumen_costo_unit_etiqueta ?? $costeoRequisicion->resumen_costo_unit_etiqueta) }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="1" name="resumen_margen_etiqueta"
                                value="{{ old('resumen_margen_etiqueta', $ventasResumen->resumen_margen_etiqueta ?? $costeoRequisicion->resumen_margen_etiqueta ?? '1') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1" oninput="calcularFila('etiqueta')">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_precio_venta_etiqueta" readonly
                                value="{{ old('resumen_precio_venta_etiqueta', $ventasResumen->resumen_precio_venta_etiqueta ?? $costeoRequisicion->resumen_precio_venta_etiqueta ?? '') }}"
                                class="w-full bg-gray-50 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="2" class="text-right border border-gray-300 p-2">Margen Administrativo</td>
                        <td class="border border-gray-300 p-2"><input type="number" step="0.01" name="resumen_margen_administrativo_aux" 
                            value="{{ old('resumen_margen_administrativo_aux', $ventasResumen->resumen_margen_administrativo_aux ?? $costeoRequisicion->resumen_margen_administrativo_aux ?? '.05') }}"
                            class="w-full bg-gray-50 p-2 text-center" oninput="calcularTotales()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input step="0.0001" name="resumen_margen_administrativo" readonly
                                value="{{ old('resumen_margen_administrativo', $ventasResumen->resumen_margen_administrativo ?? $costeoRequisicion->resumen_margen_administrativo ?? '') }}"
                                class="w-full bg-gray-50 p-2 text-center">
                        </td>
                        <td class="border border-gray-300 p-2">-</td>
                        <td class="border border-gray-300 p-2">-</td>
                    </tr>
                    
                    <tr class="bg-blue-100 font-bold">
                        <td colspan="3" class="text-right border border-gray-300 p-2">Costo Unitario</td>
                        <td class="border border-gray-300 p-2">
                            <input step="0.0001" name="resumen_total_costo_unit" readonly
                                value="{{ old('resumen_total_costo_unit', $ventasResumen->resumen_total_costo_unit ?? $costeoRequisicion->resumen_total_costo_unit ?? '') }}"
                                class="w-full bg-blue-100 p-2 text-center">
                        </td>
                        <td class="border border-gray-300 p-2">-</td>
                        <td class="border border-gray-300 p-2">-</td>
                    </tr>
                    
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="4" class="text-right border border-gray-300 p-2">Comisión</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="resumen_total_comision" 
                                value="{{ old('resumen_total_comision', $ventasResumen->resumen_total_comision ?? $costeoRequisicion->resumen_total_comision ?? '') }}"
                                class="w-full bg-gray-50 p-2 text-center" oninput="calcularComision()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input step="0.0001" name="resumen_total_comision_final" readonly
                                value="{{ old('resumen_total_comision_final', $ventasResumen->resumen_total_comision_final ?? $costeoRequisicion->resumen_total_comision_final ?? '') }}"
                                class="w-full bg-gray-50 p-2 text-center">
                        </td>
                    </tr>
                    <tr class="bg-blue-100 font-bold">
                        <td colspan="4" class="text-right border border-gray-300 p-2">Total de Ventas</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.001" name="resumen_total_precio_venta_aux" title="Ingrese multiplicador de precio de venta"
                                value="{{ old('resumen_total_precio_venta_aux', $ventasResumen->resumen_total_precio_venta_aux ?? $costeoRequisicion->resumen_total_precio_venta_aux ?? '.5') }}"
                                class="w-full bg-blue-100 p-2 text-center" oninput="calcularTotales()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input step="0.0001" name="resumen_total_precio_venta" readonly
                                value="{{ old('resumen_total_precio_venta', $ventasResumen->resumen_total_precio_venta ?? $costeoRequisicion->resumen_total_precio_venta ?? '') }}"
                                class="w-full bg-blue-100 p-2 text-center">
                        </td>
                    </tr>
                </tfoot>
            </table>
                </div>

            <div class="mt-5 flex items-center justify-end gap-3 rounded-2xl bg-slate-900 px-4 py-4 text-white">
                <span class="text-base font-bold text-white">Total Final de la Cotización (MXN):</span>
                <input type="number" name="precio_venta_final" step="0.01" readonly
                    class="form-input w-48 rounded-md border-2 border-cyan-400 bg-cyan-50 p-2 text-lg font-bold text-slate-900"
                    value="{{ old('precio_venta_final', $ventasResumen->precio_venta_final ?? $costeoRequisicion->resumen_total_precio_venta ?? '') }}">
            </div>
            </section>
        </form>


        {{-- ===== RESUMEN DE HERRAMENTAL ===== --}}
            <div class="mt-6">
                @php
                    $herramentales = [
                        'Molde' => $costeoRequisicion->total_molde,
                        'Empujador' => $costeoRequisicion->total_empujador,
                        'Suaje base' => $costeoRequisicion->costo_suaje_base,
                        'Muestras' => $costeoRequisicion->costo_muestras,
                        'Placa de fijación' => $costeoRequisicion->costo_placa_fijacion,
                        'Madera / Campaña' => $costeoRequisicion->costo_madera_campana,
                        'Prototipo' => $costeoRequisicion->costo_prototipo,
                        'Tornillería' => $costeoRequisicion->costo_tornilleria,
                        'Pedimento herramental' => $costeoRequisicion->costo_pedimento_herramental,
                    ];
                    $herramentalesActivos = collect($herramentales)->filter(fn ($valor) => !is_null($valor) && (float) $valor !== 0.0);
                @endphp
                <section id="herramental-section" class="summary-card p-5">
                    <div class="mb-4 flex flex-col gap-2 border-b border-slate-200 pb-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Seccion 2</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Resumen de Herramental</h2>
                        </div>
                        <p class="text-sm text-slate-500">El total de ventas se calcula en formulario y el KPI superior muestra el valor ya guardado.</p>
                    </div>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
                    <table class="summary-table w-full text-center border-collapse border border-gray-400">
                        <thead class="bg-[#848484] text-white">
                            <tr>
                                <th class="border border-gray-300 p-2">Concepto</th>
                                <th class="border border-gray-300 p-2">Costo total<br><span class="text-xs font-normal">(MXN)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($herramentalesActivos->isEmpty())
                                <tr>
                                    <td colspan="2" class="border border-gray-300 p-4 text-sm text-slate-500">No hay conceptos con importe distinto de cero.</td>
                                </tr>
                            @else
                                @foreach($herramentales as $concepto => $valor)
                                    @if($valor)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 p-2 font-bold">{{ $concepto }}</td>
                                        <td class="border border-gray-300 p-2 font-semibold text-blue-900 text-right">$ {{ number_format($valor, 2) }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-100 font-bold">
                                <td class="border border-gray-300 p-2 text-right">Total Herramental</td>
                                <td class="border border-gray-300 p-2 text-blue-900 text-right">$ {{ number_format($costeoRequisicion->TOTAL_FINAL ?? 0, 2) }}</td>
                            </tr>
                            <tr class="bg-gray-50 font-bold">
                                <td class="border border-gray-300 p-2 text-right">Margen</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" step="0.01" id="herramental-margen" name="herramental_margen" form="resumen-save-form"
                                        value="{{ old('herramental_margen', $ventasResumen->herramental_margen ?? 1) }}"
                                        class="w-full border-gray-300 border rounded-md p-1 text-right"
                                        oninput="calcularHerramental()">
                                </td>
                            </tr>
                            <tr class="bg-green-100 font-bold">
                                <td class="border border-gray-300 p-2 text-right">Total Herramental (Ventas)</td>
                                <td class="border border-gray-300 p-2">
                                    <input type="number" step="0.01" id="herramental-ventas" name="herramental_total_ventas" form="resumen-save-form"
                                        readonly
                                        value="{{ old('herramental_total_ventas', $ventasResumen->herramental_total_ventas ?? $costeoRequisicion->TOTAL_VENTAS ?? 0) }}"
                                        class="w-full bg-green-100 border-0 p-1 text-right text-green-900">
                                </td>
                            </tr>
                            @if($costeoRequisicion->tiempo_herramientas)
                            <tr>
                                <td class="border border-gray-300 p-2 text-gray-600 font-medium">Tiempo de entrega herramentales</td>
                                <td class="border border-gray-300 p-2 text-center text-gray-700">{{ $costeoRequisicion->tiempo_herramientas }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                    </div>
                </section>
            </div>

        {{-- ===== COMENTARIOS DEL ÁREA DE COSTEOS ===== --}}
        @if($costeoRequisicion && $costeoRequisicion->comentarios)
        <div class="mt-4">
            <section class="summary-card p-5">
                <div class="mb-4 border-b border-slate-200 pb-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Seccion 3</p>
                    <h2 class="text-2xl font-semibold text-slate-900">Comentarios del Área de Costeos</h2>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded p-3 text-gray-800 whitespace-pre-line leading-relaxed">
                    {{ $costeoRequisicion->comentarios }}
                </div>
            </section>
        </div>
        @endif

        <div class="sticky bottom-4 mt-8 flex justify-end">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white/95 px-5 py-4 shadow-xl backdrop-blur">
                <div class="hidden text-right sm:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Herramentales guardados</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $precioHerramentalesTexto }}</p>
                </div>
                <button type="submit" form="resumen-save-form" class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    Guardar Resumen
                </button>
            </div>
        </div>

    </div>
</div>
<script>
// Debounce helper to avoid spamming calcularTotales
let calcularTotalesTimer = null;
function scheduleCalcularTotales(delay = 120) {
    if (calcularTotalesTimer) clearTimeout(calcularTotalesTimer);
    calcularTotalesTimer = setTimeout(() => {
        try {
            calcularTotales();
        } catch (e) {
            console.error(e);
        }
    }, delay);
}

function calcularFila(concepto, recalc = true) {

    const costoTotal = parseFloat(
        document.querySelector(`[name="resumen_costo_${concepto}"], 
        [name="resumen_costo_${concepto}_total"]`)?.value
    ) || 0;

    const piezas = parseFloat(
        document.querySelector(`[name="resumen_piezas_${concepto}"]`)?.value
    ) || 1;

    const margen = parseFloat(
        document.querySelector(`[name="resumen_margen_${concepto}"]`)?.value
    ) || 1;

    const inputCostoUnit = document.querySelector(
        `[name="resumen_costo_unit_${concepto}"]`
    );

    const inputPrecioVenta = document.querySelector(
        `[name="resumen_precio_venta_${concepto}"]`
    );

    // Costo Unitario
    const costoUnit = piezas > 0 ? costoTotal / piezas : 0;
    if (inputCostoUnit) {
        inputCostoUnit.value = costoUnit.toFixed(4);
    }

    // Precio de Venta
    const precioVenta = costoUnit * margen;
    if (inputPrecioVenta) {
        inputPrecioVenta.value = precioVenta.toFixed(4);
    }

    if (recalc) scheduleCalcularTotales();
} 



function calcularTotales() {
    const conceptos = [
        'procesos',
        'empaque',
        'flete',
        'pedimento',
        'inocuidad',
        'polipropileno',
        'estaticidad',
        'maquila',
        'etiqueta'
    ];
    
    let totalCostoUnit = 0;
    let totalPrecioVenta = 0;
    const margenAdministrativoAux = parseFloat(document.querySelector('[name="resumen_margen_administrativo_aux"]').value) || 0;

    conceptos.forEach(c => {
        totalCostoUnit += parseFloat(
            document.querySelector(`[name="resumen_costo_unit_${c}"]`)?.value
        ) || 0;

        totalPrecioVenta += parseFloat(
            document.querySelector(`[name="resumen_precio_venta_${c}"]`)?.value
        ) || 0;
    });

    const resumen_margen_administrativo = document.querySelector('[name="resumen_margen_administrativo"]');
    const inputTotalCosto = document.querySelector('[name="resumen_total_costo_unit"]');
    const inputPrecioVentaFinal = document.querySelector('[name="resumen_total_precio_venta"]');
    const resumen_total_comision_final = document.querySelector('[name="resumen_total_comision_final"]');
    const precio_venta_final = document.querySelector('[name="precio_venta_final"]');
    const lote_compra = Number(@json($ventasResumen->lote_compra ?? optional($cotizacion->especificacionProyecto)->lote_compra ?? 1));
    const resumen_total_precio_venta_aux = parseFloat(document.querySelector('[name="resumen_total_precio_venta_aux"]').value) || 0;

    if (inputTotalCosto) {
        const resultado = margenAdministrativoAux * totalCostoUnit;
        resumen_margen_administrativo.value = resultado.toFixed(4);
        const suma = totalCostoUnit + resultado;
        inputTotalCosto.value = suma.toFixed(4);
    }

    if (inputPrecioVentaFinal) {
        totalPrecioVenta += parseFloat(resumen_total_comision_final.value) || 0;
        inputPrecioVentaFinal.value = (totalPrecioVenta*resumen_total_precio_venta_aux).toFixed(4);
        precio_venta_final.value = (inputPrecioVentaFinal.value*lote_compra).toFixed(4);
    }
}

function calcularComision() {
    // Implementar si es necesario
    const resumenTotalCostoUnit = parseFloat(document.querySelector('input[name="resumen_total_costo_unit"]').value) || 0;
    const resumenTotalComisionInput = parseFloat(document.querySelector('input[name="resumen_total_comision"]').value) || 0;
    const comision = resumenTotalCostoUnit * resumenTotalComisionInput; 
    document.querySelector('input[name="resumen_total_comision_final"]').value = comision.toFixed(2);
    // Schedule totals recalculation (debounced)
    scheduleCalcularTotales();
}

function calcularHerramental() {
    const margenInput = document.getElementById('herramental-margen');
    const ventasInput = document.getElementById('herramental-ventas');

    // La sección de herramental puede no renderizarse en todos los casos.
    if (!margenInput || !ventasInput) return;

    const totalHerramental = Number(@json($costeoRequisicion->TOTAL_FINAL ?? 0)) || 0;
    const margen = parseFloat(margenInput.value) || 0;
    const totalVentas = totalHerramental * margen;

    ventasInput.value = totalVentas.toFixed(2);
}


function calcularCostoTotalYPrecioVenta() {
    const resumen_total_costo_unit = parseFloat(document.querySelector('input[name="resumen_total_costo_unit"]').value) || 0;
    const lote_compra = parseFloat(document.querySelector('input[name="lote_compra"]').value) || 0;
    const coeficiente_merma = parseFloat(document.querySelector('input[name="coeficiente_merma"]').value) || 0;

    const totalCosto = resumen_total_costo_unit * (lote_compra + (lote_compra * (coeficiente_merma / 100)));
    const precioVentaFinal = parseFloat(document.querySelector('input[name="resumen_total_precio_venta"]').value) || 0;

    const costoInput = document.querySelector('input[name="costo_total"]');
    if (costoInput) costoInput.value = isFinite(totalCosto) ? totalCosto.toFixed(2) : '';

    const precioInput = document.querySelector('input[name="precio_venta_final"]');
    if (precioInput) precioInput.value = isFinite(precioVentaFinal) ? precioVentaFinal.toFixed(2) : '';
}


document.addEventListener('DOMContentLoaded', function() {
    // Mostrar modal de éxito si hay mensaje de sesión
    @if(session('success'))
        if (typeof showSuccessMessage === 'function') {
            showSuccessMessage("{{ session('success') }}");
        } else {
            console.warn('showSuccessMessage no está definido');
        }
    @endif

    // Mostrar modal de error si hay mensaje de error
    @if(session('error'))
        if (typeof showErrorMessage === 'function') {
            showErrorMessage("{{ session('error') }}");
        } else {
            console.warn('showErrorMessage no está definido');
        }
    @endif

    try {
        calcularFila('procesos', false);
        calcularFila('empaque', false);
        calcularFila('flete', false);
        calcularFila('pedimento', false);
        calcularFila('inocuidad', false);
        calcularFila('polipropileno', false);
        calcularFila('estaticidad', false);
        calcularFila('maquila', false);
        calcularFila('etiqueta', false);
    } catch (e) { console.error(e); }
    try { calcularComision(); } catch (e) { console.error(e); }
    try { calcularHerramental(); } catch (e) { console.error(e); }
    try { scheduleCalcularTotales(0); } catch (e) { console.error(e); }

    const form = document.getElementById('resumen-save-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Recalcular totales y campos dependientes
            try { calcularTotales(); } catch (e) { console.error(e); }
            try { calcularComision(); } catch (e) { console.error(e); }
            try { calcularHerramental(); } catch (e) { console.error(e); }
            //try { calcularCostoTotalYPrecioVenta(); } catch (e) { console.error(e); }
            // Allow submit to proceed
        });
    }
});
</script>

@endsection