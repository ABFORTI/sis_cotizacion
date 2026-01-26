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
    <!-- Header -->
    <div class="flex items-start justify-between mb-4 header-mobile">
        <div>
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
                <a href="{{ route('cotizacion.excel.completo', $cotizacion->id) }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition-colors text-center">
                    <i class="fas fa-file-excel"></i> Descargar Excel
                </a>
                <a href="{{ route('cotizacion.pdf.completo', $cotizacion->id) }}"  target="_blank" class="inline-block bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition-colors text-center">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </a>

            </div>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="text-center mb-2">
        <div class="text-2xl font-semibold client-name-mobile">{{ $cotizacion->cliente }}</div>
            <p><strong>Proyecto:</strong> {{ $cotizacion->nombre_del_proyecto }}</p>
                <div class="mb-6 p-4 bg-gray-50 rounded-lg flex flex-col md:flex-row justify-center items-center gap-4">
                    <p><strong>Puesto:</strong> {{ $cotizacion->puesto }}</p>
                    <p><strong>Email:</strong> {{ $cotizacion->correo }}</p>
                    <p><strong>Teléfono:</strong> {{ $cotizacion->telefono }}</p>
                </div>
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
    <!-- Segunda tabla -->
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

    <!-- SECCION DE LINEAMIENTOS -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Encabezado -->
        <div class="flex items-start justify-between mb-4 header-mobile">
            <div>
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
            
            </div>
        </div>

        <!-- Lineamientos -->
        <form id="form-lineamientos" method="POST" action="{{ route('cotizacion.lineamientos.save', $cotizacion->id) }}" class="mb-6">
        @csrf
        @method('PUT') 
            <h1 class="text-2xl font-bold text-gray-800">Lineamientos del Proyecto</h1>
            <div class="space-y-4">
                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_1" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_1 ?? 'Precios en USD. No incluyen I.V.A. Se considera fabricación, facturación y entrega en una sola exhibición.' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_2" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_2 ?? 'Los precios pueden ajustarse en respuesta a cambios en aranceles, impuestos o restricciones fiscales y comerciales establecidos por la autoridad.' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_3" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_3 ?? 'La vigencia de la presente cotización es de 12 meses y/o incrementos en MP superior al 5%.' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_4" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_4 ?? 'Condiciones de pago son por anticipado.' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tiempo de desarrollo de herramentales y muestras para liberación:</label>
                        <input type="text" name="tiempo_herramentales" placeholder="Ej: 4" value="{{ $cotizacion->tiempo_herramentales ?? '' }}" class="w-24 border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-red-600"> semanas.
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_5" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_5 ?? 'Tiempo de entrega de producto terminado: ' . ($cotizacion->costeoRequisicion ? ceil((is_numeric($cotizacion->costeoRequisicion->tiempo_pt ?? 0) ? $cotizacion->costeoRequisicion->tiempo_pt : 0) / 5) : 'N/C') . ' semanas (todos los tiempos se confirman con disponibilidad de maquinaria).' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_6" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_6 ?? 'El producto se entrega en: ' . ($cotizacion->lugar_entrega ?? '') }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_7" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_7 ?? 'Considerar una variación ±10% en la entrega de producto terminado, sobre lote de producción (MOQ cotizado).' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_8" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_8 ?? 'Especificación de empaque: se confirma después de la 1ª. producción.' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_9" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="2">{{ $cotizacion->lineamiento_9 ?? 'Cualquier condición distinta al escenario cotizado implica una revisión de costos.' }}</textarea>
                </div>

                <div class="p-4 rounded border border-gray-300">
                    <textarea name="lineamiento_10" class="w-full bg-transparent border-none p-0 m-0 focus:ring-2 focus:ring-red-600 rounded" rows="3">{{ $cotizacion->lineamiento_10 ?? 'La responsabilidad respecto de la mercancía producida por INNOVET, es única y exclusivamente por defectos de fabricación. La inspección de la pieza deformada o fuera de calor, causa deformaciones e invalida garantías. Es responsabilidad del CLIENTE aquellos desperfectos que sufra el producto por mal uso, transportación, almacenamiento o análogas derivadas de la actividad del CLIENTE.' }}</textarea>
                </div>
            </div>

            <!-- Sección Atentamente dentro del formulario -->
            <div class="mt-8 p-4 bg-gray-10">
                <h3 class="text-lg font-semibold text-red-600 mb-4">Atentamente,</h3>
                    <p class="mb-2 text-left">
                        <p class="text-black">{{ $cotizacion->nombre_contacto ?? Auth::user()->name }}</p>
                    </p>
                    <p class="text-left text-gray-600">
                        <input id="puesto_contacto_input" name="puesto_contacto" value="{{ $cotizacion->puesto_contacto ?? 'Puesto' }}" class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-red-600 focus:outline-none" placeholder="Ingrese su puesto">
                    </p>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded font-semibold hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 shadow-md">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Guardar Lineamientos
                </button>
            </div>

        </form>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Función para generar el documento
        function generarDocumento(tipo) {
            var nombre = document.getElementById('nombre_contacto_input').value;
            var puesto = document.getElementById('puesto_contacto_input').value;

            var params = new URLSearchParams();
            params.append('nombre_contacto', nombre);
            params.append('puesto_contacto', puesto);
            
            var baseUrl = '';

            if (tipo === 'pdf') {
                baseUrl = "{{ route('cotizacion.lineamientos.pdf', ['id' => $cotizacion->id]) }}"; 
            } else if (tipo === 'excel') {
                baseUrl = "{{ route('cotizacion.lineamientos.excel', ['id' => $cotizacion->id]) }}"; 
            }

            var fullUrl = baseUrl + '?' + params.toString();
            window.open(fullUrl, '_blank');
        }

        // Asignar los eventos a los botones
        const btnGenerarPdf = document.getElementById('btn-generar-pdf');
        if (btnGenerarPdf) {
            btnGenerarPdf.addEventListener('click', function() {
                generarDocumento('pdf');
            });
        }

        const btnGenerarExcel = document.getElementById('btn-generar-excel');
        if (btnGenerarExcel) {
            btnGenerarExcel.addEventListener('click', function() {
                generarDocumento('excel');
            });
        }

        // Manejo del formulario de lineamientos
        const formLineamientos = document.getElementById('form-lineamientos');
        if (formLineamientos) {
            formLineamientos.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="inline-block w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Guardando...';
            });
        }

    });
</script>

    <!-- Footer address -->
        <div class=" mt-8 pt-4 border-t border-gray-300 text-center text-sm text-gray-600">
            <p>Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
            <p class="mt-2">ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05</p>
        </div>

</div>    
</div>

<!-- Revisar si agregamos lo del correo -->


@endsection