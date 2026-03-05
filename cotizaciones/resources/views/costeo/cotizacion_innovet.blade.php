@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp


<!-- Styles plantilla de cotización -->
<style>
    .cotizacion-header {
        background-color: #2b2b2b;
        color: #ffffff;
    }

    .cotizacion-title-red {
        color: #b50b0b;
        font-weight: 700;
    }

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

    .contacto-rojo {
        color: #b50b0b;
        font-weight: 600;
    }

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

    .precio-verde {
        background-color: #92d050;
        color: #ffffff;
        font-weight: 700;
        box-sizing: border-box;
    }

    .label-dark {
        color: #bfbfbf;
    }

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
    .table-wrapper {
        position: relative;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

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

        .header-mobile img {
            max-width: 180px !important;
            margin: 0 auto;
        }

        .btn-container-mobile {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }

        .btn-container-mobile a {
            width: 100%;
            padding: 0.5rem 1rem !important;
            font-size: 0.75rem !important;
        }

        .contact-mobile {
            grid-template-columns: 1fr !important;
            gap: 0.5rem !important;
        }

        .contact-mobile > div {
            text-align: center !important;
        }

        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -0.5rem;
            padding: 0 0.5rem;
        }

        .cotizacion-table {
            font-size: 0.7rem;
        }

        .cotizacion-table th,
        .cotizacion-table td {
            padding: 0.5rem 0.25rem;
        }

        .spec-table {
            font-size: 0.65rem;
        }

        .spec-table th,
        .spec-table td {
            padding: 0.25rem 0.15rem;
            word-break: break-word;
        }

        .cotizacion-table .index-cell {
            width: 2rem;
        }

        .precio-verde {
            font-size: 0.85rem;
            padding: 0.5rem 0.25rem;
        }

        .cotizacion-title-red {
            font-size: 0.9rem;
        }

        .file-thumb {
            max-width: 100% !important;
            max-height: 250px !important;
        }

        .text-xs {
            font-size: 0.65rem;
        }
    }

    @media (max-width: 480px) {
        .cotizacion-table {
            font-size: 0.65rem;
        }

        .spec-table {
            font-size: 0.6rem;
        }

        .btn-container-mobile a {
            font-size: 0.7rem !important;
        }

        .client-name-mobile {
            font-size: 1.25rem !important;
        }

        .client-position-mobile {
            font-size: 0.9rem !important;
        }
        
        @media (max-width: 768px) {
        .client-name-mobile {
            font-size: 1.75rem !important;
        }
    }

        @media (max-width: 480px) {
            .client-name-mobile {
                font-size: 1.5rem !important;
            }
        }
    }
</style>

<div class="container mx-auto px-4 py-6 font-sans text-sm bg-gray-500">
    <div class="bg-white rounded-lg shadow-lg p-6 md:p-6 p-3">
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
    <div class="mb-8">
        <div class="text-center mb-4">
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 client-name-mobile mb-2">
                {{ $cotizacion->cliente }}
            </h2>
            <div class="inline-flex items-center gap-2 px-2 py-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-lg font-semibold text-neutral-700">Proyecto:</span>
                <span class="text-lg font-bold text-neutral-900">{{ $cotizacion->nombre_del_proyecto }}</span>
            </div>
        </div>
    </div>
    <div class=" from-neutral-50 to-neutral-100 rounded-xl p-6 border border-neutral-50 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 justify-items-center">

            <!-- Puesto -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">Puesto</span>
                </div>
                <p class="text-lg font-bold text-neutral-900">{{ $cotizacion->puesto }}</p>
            </div>
            <!-- Email -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">Email</span>
                </div>
                <a href="mailto:{{ $cotizacion->correo }}" class="text-lg font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                    {{ $cotizacion->correo }}
                </a>
            </div>
            <!-- Teléfono -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">Teléfono</span>
                </div>
                    <a href="tel:{{ $cotizacion->telefono }}" class="text-lg font-bold text-neutral-900 hover:text-blue-600 transition-colors">
                    {{ $cotizacion->telefono }}
                    </a>
                </div>
            </div>
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
                    <th class="p-3 w-40 text-center ">Precio Total<br />(MXN)</th>
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
                                $precioHerramentales = $ventasResumen->herramental_total_ventas ?? null;
                            @endphp
                            $ {{ $precioHerramentales !== null ? number_format($precioHerramentales, 2) : 'N/C' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Imagen ilustrativa espacio -->
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
                    class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 box-border border border-transparent">
                    Subir imagen
            </button>
        </div>
    </form>
    @php
    $imagen = $cotizacion->archivosAdjuntos
        ->filter(fn($a) => preg_match('/\.(jpg|jpeg|png|gif)$/i', $a->path))
        ->last();
    @endphp

    @if(isset($imagen))
    <div class="mt-8 mb-8">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="relative bg-gradient-to-br from-neutral-50 to-neutral-100 p-8 bg-gray-200">
                <div class="flex items-center justify-center min-h-[300px] max-h-[500px]">
                    <img
                        src="{{ Storage::url($imagen->path) }}"
                        alt="Imagen del documento"
                        class="max-w-full max-h-[500px] w-auto h-auto object-contain rounded-lg shadow-md"
                        loading="lazy"
                    >
                </div>
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/90 text-neutral-700 shadow-sm backdrop-blur-sm">
                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        Imagen
                    </span>
                </div>
            </div>
            <div class="px-6 py-4 bg-white border-t border-neutral-100">
                <div class="flex items-center justify-between flex-wrap gap-4">
                <!-- Información del archivo -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-neutral-900 truncate">
                        {{ $imagen->nombre ?? 'Documento' }}
                    </h3>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Subido el {{ $imagen->created_at ? $imagen->created_at->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
                <!-- Acciones -->
                <div class="flex items-center gap-2">
                    <!-- Vista previa -->
                    <a href="{{ Storage::url($imagen->path) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2
                              text-sm font-medium text-neutral-700 bg-neutral-50
                              border border-neutral-200 rounded-lg
                              hover:bg-neutral-100 hover:border-neutral-300
                              focus:outline-none focus:ring-2 focus:ring-neutral-400 focus:ring-offset-2
                              transition-all duration-200"
                       title="Abrir en nueva pestaña">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span class="hidden sm:inline">Abrir</span>
                    </a>
                    <!-- Descargar -->
                    <a href="{{ Storage::url($imagen->path) }}"
                       download="{{ $imagen->nombre ?? 'imagen' }}"
                       class="inline-flex items-center gap-2 px-4 py-2
                              text-sm font-medium text-white bg-blue-600
                              border border-blue-600 rounded-lg
                              hover:bg-blue-700 hover:border-blue-700
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                              transition-all duration-200 shadow-sm"
                       title="Descargar archivo">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Descargar</span>
                    </a>
                <!-- Menú de opciones -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            @click.away="open = false"
                            type="button"
                            class="inline-flex items-center justify-center w-9 h-9
                                   text-neutral-600 bg-neutral-50
                                   border border-neutral-200 rounded-lg
                                   hover:bg-neutral-100 hover:text-neutral-900
                                   focus:outline-none focus:ring-2 focus:ring-neutral-400 focus:ring-offset-2
                                   transition-all duration-200"
                                    title="Más opciones">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                             style="display: none;">
                            
                            <div class="py-1">
                                <!-- Opción Eliminar -->
                                <form action="{{ route('archivos.destroy', $imagen->id) }}" 
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="button"
                                            onclick="showConfirmModal(
                                                '¿Eliminar imagen?',
                                                'Esta acción es permanente y no se puede deshacer.',
                                                () => this.closest('form').submit()
                                            )"
                                            class="flex items-center w-full px-4 py-2.5 text-sm text-red-700
                                                   hover:bg-red-50 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar imagen
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Nota legal discreta -->
    <p class="text-center text-xs text-neutral-400 mt-3">
        Imagen con fines ilustrativos
    </p>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endpush
@endif

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
        <form id="form-lineamientos" method="POST"
    action="{{ route('cotizacion.lineamientos.save', $cotizacion->id) }}"
    class="mb-6">
    @csrf
    @method('PUT')

    <h2 class="text-xl font-semibold text-red-600 mb-6">
        Lineamientos del Proyecto
    </h2>

    <div class="space-y-4">

        <!-- CONDICIONES COMERCIALES -->
        <details open class="border rounded-lg p-4">
            <summary class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">
                💲 Condiciones comerciales
            </summary>

            <textarea name="lineamiento_1" rows="2"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_1 ?? config('lineamientos.lineamiento_1') }}</textarea>

            <textarea name="lineamiento_2" rows="2"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_2 ?? config('lineamientos.lineamiento_2') }}</textarea>

            <textarea name="lineamiento_3" rows="2"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_3 ?? config('lineamientos.lineamiento_3') }}</textarea>
        </details>

        <!-- CONDICIONES DE PAGO -->
        <details class="border rounded-lg p-4">
            <summary class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">
                💳 Condiciones de pago
            </summary>

            <textarea name="lineamiento_4" rows="2"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_4 ?? config('lineamientos.lineamiento_4') }}</textarea>
    
        </details>

        <!-- TIEMPOS -->
        <details class="border rounded-lg p-4">
            <summary class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">
                ⏱ Tiempos de desarrollo y entrega
            </summary>

            <div class="mt-3 flex items-center gap-2 text-sm">
                <label class="font-medium text-gray-700">
                    Desarrollo de herramentales y muestras:
                </label>

                <input type="number" name="tiempo_herramentales"
                    placeholder="Ej: 4"
                    value="{{ $cotizacion->tiempo_herramentales }}"
                    class="w-20 border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-red-600">
                <span>semanas</span>
            </div>

            <textarea name="lineamiento_5" rows="2"
            class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_5 ?? config('lineamientos.lineamiento_5') }}</textarea>
   
        </details>

        <!-- ENTREGA Y EMPAQUE -->
        <details class="border rounded-lg p-4">
            <summary class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">
                📦 Entrega y empaque
            </summary>

            <textarea name="lineamiento_6" rows="2"
            class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_6 ?? config('lineamientos.lineamiento_6') }}</textarea>

            <textarea name="lineamiento_7" rows="2"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_7 ?? config('lineamientos.lineamiento_7') }}</textarea>

            <textarea name="lineamiento_8" rows="2"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_8 ?? config('lineamientos.lineamiento_8') }}</textarea>

        </details>

        <!-- CONSIDERACIONES LEGALES -->
        <details class="border rounded-lg p-4">
            <summary class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">
                ⚠️ Consideraciones legales
            </summary>

            <textarea name="lineamiento_9" rows="2"
               class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_9 ?? config('lineamientos.lineamiento_9') }}</textarea>

            <textarea name="lineamiento_10" rows="3"
                class="textarea w-full mt-3 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-600">{{ $cotizacion->lineamiento_7 ?? config('lineamientos.lineamiento_7') }}</textarea>

        </details>

    </div>

    <!-- ATENTAMENTE -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg border">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Atentamente</h3>

        <input name="nombre_contacto"
            placeholder="Nombre del contacto"
            value="{{ $cotizacion->nombre_contacto ?? Auth::user()->name }}"
            class="w-full mb-3 border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-red-600">

        <input name="puesto_contacto"
            placeholder="Puesto"
            value="{{ $cotizacion->puesto_contacto }}"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-red-600">
    </div>

        <!-- BOTÓN -->
    <div class="mt-6 flex justify-center">
    <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
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

document.addEventListener('click', function (e) {
    // Botones dropdown
    const buttons = document.querySelectorAll('[data-dropdown-toggle]');

    buttons.forEach(button => {
        const targetId = button.getAttribute('data-dropdown-toggle');
        const dropdown = document.getElementById(targetId);

        // Click en el botón
        if (button.contains(e.target)) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        } 
        // Click fuera → cerrar
        else if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
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