@extends('layouts.app')

@section('content')
<!-- Estilos personalizados para la plantilla de cotización -->
<style>
    /* Cabecera principal (celdas oscuras) */
    .cotizacion-header {
        background-color: #2b2b2b;
        color: #ffffff;
    }

    /* Títulos en rojo intenso como en la imagen */
    .cotizacion-title-red {
        color: #b50b0b;
        font-weight: 700;
    }

    /* Tabla de especificaciones dentro de la celda (charola) */
    .spec-table {
        border-collapse: collapse;
        width: 100%;
        font-size: .875rem;
        margin: 0;
    }

    .spec-table th,
    .spec-table td {
        border: 1px solid #cfcfcf;
        padding: .35rem .5rem;
        margin: 0;
    }

    .spec-table th {
        background-color: #d9d9d9;
        color: #000;
        font-weight: 700;
        text-align: center;
    }

    .spec-table td {
        background-color: #bfbfbf;
        text-align: left;
    }

    .spec-table td.center {
        background-color: #bfbfbf;
        text-align: center;
    }

    .spec-col-dim {
        background-color: #2b2b2b;
        width: 40%;
    }

    .spec-col-small {
        background-color: #2b2b2b;
        width: 15%;
    }

    /* Texto de contacto en rojo más oscuro */
    .contacto-rojo {
        color: #b50b0b;
        font-weight: 600;
    }

    /* Fondo de celdas secundarias */
    .cell-bg-light {
        background-color: #efefef;
    }

    .cell-bg-lighter {
        background-color: #f7f7f7;
    }

    .celdasGrises {
        background-color: #bfbfbf;
        color: black;
        font-weight: 700;
        padding: 0.5rem 0;
        box-sizing: border-box;
    }

    .celdasGrisesclaritas {
        background-color: #d9d9d9;
        color: black;
    }

    /* Celdas de precio en verde brillante */
    .precio-verde {
        background-color: #92d050;
        color: #ffffff;
        font-weight: 700;
        box-sizing: border-box;
    }

    /* Texto oscuro para labels */
    .label-dark {
        color: #bfbfbf;
    }

    /* Ajustes de tamaño y espaciado para que coincida con el diseño */
    .cotizacion-table th,
    .cotizacion-table td {
        vertical-align: middle;
    }

    .cotizacion-table .index-cell {
        width: 3.5rem;
        text-align: center;
    }

    .cotizacion-table .desc-cell {
        padding: .25rem 0;
    }

    /* ===== RESPONSIVE STYLES ===== */
    
    /* Indicador de scroll para tablas */
    .table-wrapper {
        position: relative;
    }

    @media (max-width: 768px) {
        /* Reducir padding del contenedor principal */
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        /* Indicador visual de scroll horizontal */
        .table-wrapper::after {
            content: '→ Desliza →';
            position: absolute;
            bottom: 0.5rem;
            right: 0.5rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            pointer-events: none;
            opacity: 0.8;
            animation: fadeInOut 3s ease-in-out infinite;
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.9; }
        }

        /* Hacer el header apilable en móvil */
        .header-mobile {
            flex-direction: column;
            align-items: stretch !important;
        }

        .header-mobile > div:first-child {
            width: 100%;
            text-align: center;
            margin-bottom: 1rem;
        }

        .header-mobile > div:last-child {
            width: 100% !important;
        }

        /* Ajustar logo en móvil */
        .header-mobile img {
            max-width: 180px !important;
            margin: 0 auto;
        }

        /* Botones apilados en móvil */
        .btn-container-mobile {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }

        .btn-container-mobile a {
            width: 100%;
            padding: 0.5rem 1rem !important;
            font-size: 0.75rem !important;
        }

        /* Información de contacto apilada */
        .contact-mobile {
            grid-template-columns: 1fr !important;
            gap: 0.5rem !important;
        }

        .contact-mobile > div {
            text-align: center !important;
        }

        /* Tabla responsive con scroll horizontal */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -0.5rem;
            padding: 0 0.5rem;
        }

        /* Reducir tamaño de fuente en tablas */
        .cotizacion-table {
            font-size: 0.7rem;
        }

        .cotizacion-table th,
        .cotizacion-table td {
            padding: 0.5rem 0.25rem;
        }

        /* Especificaciones en móvil */
        .spec-table {
            font-size: 0.65rem;
        }

        .spec-table th,
        .spec-table td {
            padding: 0.25rem 0.15rem;
            word-break: break-word;
        }

        /* Ajustar anchos mínimos para móvil */
        .cotizacion-table .index-cell {
            width: 2rem;
        }

        /* Precio verde más compacto */
        .precio-verde {
            font-size: 0.85rem;
            padding: 0.5rem 0.25rem;
        }

        /* Título del proyecto más pequeño */
        .cotizacion-title-red {
            font-size: 0.9rem;
        }

        /* Imágenes responsive */
        .file-thumb {
            max-width: 100% !important;
            max-height: 250px !important;
        }

        /* Footer más pequeño */
        .text-xs {
            font-size: 0.65rem;
        }
    }

    @media (max-width: 480px) {
        /* Para pantallas muy pequeñas */
        .cotizacion-table {
            font-size: 0.65rem;
        }

        .spec-table {
            font-size: 0.6rem;
        }

        .btn-container-mobile a {
            font-size: 0.7rem !important;
        }

        /* Cliente y puesto más pequeños */
        .client-name-mobile {
            font-size: 1.25rem !important;
        }

        .client-position-mobile {
            font-size: 0.9rem !important;
        }
    }
</style>

<div class="container mx-auto px-4 py-6 font-sans text-sm">
    <div class="bg-white rounded-lg shadow-lg p-6 md:p-6 p-3">
    <!-- Header: logo left, folio/fecha right -->
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
                <!-- Botón para generar Excel -->
                <a href="{{ route('cotizacion.excel', $cotizacion->id) }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition-colors text-center">
                    <i class="fas fa-file-excel"></i> Descargar Excel
                </a>
                <!-- Botón para generar PDF -->
                <a href="{{ route('cotizacion.pdf', $cotizacion->id) }}"  target="_blank" class="inline-block bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition-colors text-center">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>
                <!-- Botón para ir a Lineamientos -->
                <a href="{{ route('cotizacion.lineamientos', $cotizacion->id) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors text-center">
                    <i class="fas fa-info-circle"></i> Lineamientos Proyecto
                </a>
            </div>
        </div>
    </div>

    <!-- Nombre cliente centrado y contacto -->
    <div class="text-center mb-2">
        <div class="text-2xl font-semibold client-name-mobile">{{ $cotizacion->cliente }}</div>
        <div class="text-lg text-gray-600 client-position-mobile">{{ $cotizacion->puesto }}</div>
    </div>

    <div class="grid grid-cols-12 gap-2 items-center mb-6 contact-mobile">
        <div class="col-span-9 text-sm contacto-rojo">{{ $cotizacion->correo }}</div>
        <div class="col-span-3 text-right text-sm text-gray-700">Tel. <span class="font-semibold contacto-rojo">{{ $cotizacion->telefono }}</span></div>
    </div>

    <!-- Tabla principal -->
    <div class="shadow-sm border border-gray-200 table-wrapper">
        <table class="w-full border-collapse cotizacion-table" style="min-width: 650px;">
            <thead>
                <tr class="cotizacion-header text-sm">
                    <th class="p-3 w-12">&nbsp;</th>
                    <th class="p-3 text-left">Descripcion del proyecto</th>
                    <th class="p-3 w-28 text-center">Piezas<br />(MOQ)</th>
                    <th class="p-3 w-40 text-center">Precio Unitario<br />(MXN)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1: Charola 15 cavidades -->
                <tr class="border-t border-gray-200">
                    <td class="celdasGrisesclaritas align-top p-4 text-center text-gray-700 font-semibold">1</td>
                    <td style="padding: 0; margin: 0; border: none;">
                        <div class="celdasGrises" style="padding: 0.75rem 1rem; margin: 0;">
                            <div class="cotizacion-title-red font-semibold text-center">{{ $cotizacion->nombre_del_proyecto }}</div>
                        </div>
                        <div style="margin: 0; padding: 0; border: none;">
                            <table class="spec-table" style="border-top: none; margin: 0;">
                                <thead>
                                    <tr>
                                        <th class="spec-col-dim">Dimensiones</th>
                                        <th class="spec-col-small">Frecuencia de compra</th>
                                        <th class="spec-col-small">Especificacion del material</th>
                                        <th class="spec-col-small">Espesor</th>
                                        <th class="spec-col-small">Color</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="spec-col-dim center font-semibold">{{ $cotizacion->especificacionProyecto->pieza_largo }} x {{ $cotizacion->especificacionProyecto->pieza_ancho }} x {{ $cotizacion->especificacionProyecto->pieza_alto }} mm</td>
                                        <td class="spec-col-small center font-semibold">{{ $cotizacion->especificacionProyecto->frecuencia_compra }}</td>
                                        <td class="spec-col-small center font-semibold">{{ $cotizacion->especificacionProyecto->material }}</td>
                                        <td class="spec-col-small center font-semibold">{{ $cotizacion->especificacionProyecto->calibre }}</td>
                                        <td class="spec-col-small center font-semibold">{{$cotizacion->especificacionProyecto->color}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td class="celdasGrises align-top p-4 text-center font-semibold">{{$cotizacion->especificacionProyecto->lote_compra}} </td>
                    <td class="align-top p-4 text-center precio-verde">
                        <div>
                            @php
                                $ventasResumen = $cotizacion->ventasResumen ?? null;
                                $precioUnitario = $ventasResumen->resumen_total_costo_unit ?? ($cotizacion->costeoRequisicion->resumen_total_costo_unit ?? null);
                            @endphp
                            $ {{ $precioUnitario ? number_format($precioUnitario, 2) : 'N/C' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Segunda tabla con espacio -->
    <div class="shadow-sm border border-gray-200 mt-10 table-wrapper">
        <table class="w-full border-collapse cotizacion-table" style="min-width: 650px;">
            <thead>
                <tr class="cotizacion-header text-sm">
                    <th class="p-3 w-12">&nbsp;</th>
                    <th class="p-3 text-left">Desarrollo de Herramientas.</th>
                    <th class="p-3 w-28 text-center"></th>
                    <th class="p-3 w-40 text-center ">Precio Unitario<br />(MXN)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 2: Desarrollo de Herramientas -->
                <tr class="border-t border-gray-200">
                    <td class="celdasGrisesclaritas align-top p-4 text-center font-semibold">2</td>
                    <td class="cotizacion-title-red" style="padding: 0; margin: 0;">
                        <div class="celdasGrises" style="padding: 0.75rem 1rem; margin: 0;">
                            <div class="cotizacion-title-red font-semibold text-center">Desarrollo de Herramentales</div>
                        </div>
                        <div class="celdasGrisesclaritas text-sm text-center" style="padding: 0.75rem 1rem; margin: 0; border-top: 1px solid #cfcfcf;">
                            <input name="nota_herramentales" value="Se considera entrega de 3 muestras para liberación" class="w-full bg-transparent border-none p-0 m-0 text-center focus:ring-0 focus:outline-none" style="font-size:inherit; color:inherit; font-weight:inherit;">
                        </div>
                    </td>
                    <td class="celdasGrises align-top p-4 text-center font-semibold">
                        <input name="cantidad_herramentales" value="0" class="w-full bg-transparent border-none p-0 m-0 text-center focus:ring-0 focus:outline-none" style="font-size:inherit; color:inherit; font-weight:inherit;">
                    </td>
                    <td class="align-top p-4 text-center precio-verde">
                        <div>
                            @php
                                $ventasResumen = $cotizacion->ventasResumen ?? null;
                                $precioHerramentales = $ventasResumen->resumen_total_precio_venta ?? ($cotizacion->costeoRequisicion->TOTAL_VENTAS ?? null);
                            @endphp
                            $ {{ $precioHerramentales ? number_format($precioHerramentales, 2) : 'N/C' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <!-- Imagen ilustrativa espacio -->
    <br><br><br>
    {{-- Archivos ya cargados (solo imágenes) --}}
    {{-- SUBIR / REEMPLAZAR IMAGEN --}}
<form action="{{ route('archivos.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="mt-10 text-center">
    @csrf

    <input type="hidden" name="cotizacion_id" value="{{ $cotizacion->id }}">

    <div class="flex flex-col items-center gap-3">

        <input type="file"
               name="archivo"
               accept="image/*"
               class="block w-full max-w-xs text-sm border rounded p-2">

        <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            📤 Subir imagen
        </button>

    </div>
</form>

    @php
    $imagen = $cotizacion->archivosAdjuntos
        ->filter(fn($a) => preg_match('/\.(jpg|jpeg|png|gif)$/i', $a->path))
        ->last();
@endphp

@if(isset($imagen))
<div class="mt-6 text-center">
    <div class="inline-block border rounded-lg p-4 shadow">

        <img src="{{ asset('storage/' . $imagen->path) }}"
            style="max-width:320px; max-height:320px; object-fit:contain;"
            class="mb-3">

        <div class="flex justify-center gap-3">

            <a href="{{ asset('storage/' . $imagen->path) }}"
               download
               class="px-4 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                ⬇ Descargar
            </a>

            <form action="{{ route('archivos.destroy', $imagen->id) }}"
                  method="POST">
                @csrf
                @method('DELETE')
                <button type="button"
                    onclick="showConfirmModal(
                        '¿Eliminar imagen?',
                        'Esta imagen será eliminada.',
                        () => this.closest('form').submit()
                    )"
                    class="px-4 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                    🗑 Eliminar
                </button>
            </form>

        </div>
    </div>
</div>
@endif
    <div class="mt-10 mb-4 text-center text-red-600 font-semibold">Imágen ilustrativa:</div>

    <!-- Footer address -->

    <div class="text-gray-600 text-xs border-t pt-4 mt-8">
        <p>Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
        <p class="mt-1">ACF10 | Fecha de efectividad: 01-septiembre-2025 | Revisión: 03</p>
    </div>
</div>
</div>
<!-- ✅ BOTÓN FLOTANTE 
<button id="abrirCorreoModal"
    class="fixed bottom-6 right-6 bg-[#991B1B] text-white font-bold px-6 py-3 rounded-full shadow-lg hover:bg-[#7f1515] transition-transform transform hover:scale-110 z-40">
    📧 Enviar Excel
</button>

✅ MODAL FLOTANTE 
<div id="correoModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md relative animate-fadeIn">
         Botón de cerrar 
        <button id="cerrarCorreoModal"
            class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl leading-none">
            &times;
        </button>

        Título 
        <h2 class="text-2xl font-bold text-[#991B1B] mb-4 text-center">
            Enviar Cotización por Correo
        </h2>

        FORMULARIO 
        <form id="formEnviarCorreo" method="POST" 
              action="{{ route('cotizacion.enviarCorreo', $cotizacion->id) }}">
            @csrf

            Campo de correo 
            <div class="mb-4">
                <label for="correo_destino" class="block text-gray-700 font-semibold mb-2">
                    Correo destino:
                </label>
                <input type="email" id="correo_destino" name="correo_destino" required
                    placeholder="ejemplo@empresa.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#991B1B]">
            </div>

            Botones 
            <div class="flex justify-center gap-4 mt-6">
                <button type="submit"
                    class="bg-[#991B1B] text-white px-6 py-2 rounded-lg hover:bg-[#7f1515] transition-colors">
                    📤 Enviar Excel
                </button>

                <button type="button" id="cancelarCorreo"
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
-->
<!-- ✅ ESTILOS Y SCRIPT 
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.4s ease-out forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const abrirModal = document.getElementById('abrirCorreoModal');
    const cerrarModal = document.getElementById('cerrarCorreoModal');
    const cancelarCorreo = document.getElementById('cancelarCorreo');
    const modal = document.getElementById('correoModal');

    // 🟢 Mostrar modal
    abrirModal.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    // 🔴 Cerrar modal
    const cerrar = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    cerrarModal.addEventListener('click', cerrar);
    cancelarCorreo.addEventListener('click', cerrar);

    // 🖱️ Cerrar si se hace click fuera del cuadro
    modal.addEventListener('click', (e) => {
        if (e.target === modal) cerrar();
    });
});
</script>
-->


@endsection