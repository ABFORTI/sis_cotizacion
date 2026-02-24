@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp
@php
$placas = [
1 => "320 x 420 mm",
2 => "350 x 560 mm",
3 => "355 x 590 mm",
4 => "420 x 420 mm",
5 => "420 x 700 mm",
6 => "455 x 480 mm",
7 => "455 x 610 mm",
8 => "450 x 620 mm",
9 => "460 x 520 mm",
10 => "480 x 630 mm",
11 => "490 x 600 mm",
12 => "520 x 455 mm",
13 => "520 x 1000 mm",
14 => "600 x 650 mm",
15 => "650 x 592 mm",
16 => "700 x 1200 mm",
17 => "800 x 940 mm",
18 => "1175 x 1390 mm",
19 => "1450 x 1630 mm",
20 => "1450 x 3000 mm"
];

$indicePlaca = optional(optional($cotizacion)->costeoRequisicion)->placa_de_enfriamiento ?? '';
$valorPlaca = $placas[$indicePlaca] ?? "No aplica";
@endphp
@php
$grabadosMap = [
'numero_parte' => 'Número de parte',
'tipo_material' => 'Tipo de material',
'logo_cliente' => 'Logo cliente',
'logo_innovet' => 'Logo Innovet',
];

$req = $cotizacion->requisicionCotizacion;

// Construimos una lista con los que valen 1
$grabadosSeleccionados = [];

foreach ($grabadosMap as $campo => $etiqueta) {
if (!empty($req->$campo) && $req->$campo == 1) {
$grabadosSeleccionados[] = $etiqueta;
}
}

// Si "sin_grabado" es 1 y no hay otros
if (!empty($req->sin_grabado) && $req->sin_grabado == 1 && empty($grabadosSeleccionados)) {
$grabadoFinal = "Sin grabado";
} else {
$grabadoFinal = !empty($grabadosSeleccionados)
? implode(', ', $grabadosSeleccionados)
: "Sin grabado";
}
@endphp
@php
// 1. Verificamos si hay medidas en cajaCliente
$largo = $cotizacion->cajaCliente->caja_largo ?? '';
$ancho = $cotizacion->cajaCliente->caja_ancho ?? '';
$alto = $cotizacion->cajaCliente->caja_alto ?? '';

// Esta variable nos dice si existen medidas (para no mostrar " x x ")
$tieneMedidas = !empty($largo) || !empty($ancho) || !empty($alto);

// 2. Definimos el valor por defecto para "Medidas de contenedor"
$defaultMedidas = '';
if ($tieneMedidas) {
$defaultMedidas = $largo . ' x ' . $ancho . ' x ' . $alto;
}

// 3. Definimos el valor por defecto para "Contenedor del cliente"
$defaultContenedor = $tieneMedidas ? 'Si' : 'No';
@endphp
@php
// 1. Definimos el "mapa" de los checkboxes
$proporcionaMap = [
'pieza_mejorar' => 'Pieza a mejorar',
'pieza_fisica_proteger' => 'Pieza física a proteger',
'plano_pieza_termoformada' => 'Plano pieza termoformada',
'igs_componente' => 'IGS componente',
'igs_pieza_termoformada' => 'IGS pieza termoformada',
'contenedor' => 'Contenedor',
'plano_pieza_pdf' => 'Plano de la Pieza PDF',
'nc' => 'NC',
'na' => 'NA',
];

// 2. Apuntamos al objeto CORRECTO
$infoTermoformado = $cotizacion->termoformado;
$clienteProporcionaItems = [];

// 3. Verificamos que el objeto exista
if ($infoTermoformado) {

// 4. Recorremos el mapa
foreach ($proporcionaMap as $campo => $etiqueta) {
if (!empty($infoTermoformado->$campo) && $infoTermoformado->$campo == 1) {
$clienteProporcionaItems[] = $etiqueta;
}
}

// 5. Añadimos el campo "Otro"
if (!empty($infoTermoformado->termoformado_otro_checkbox) && $infoTermoformado->termoformado_otro_checkbox == 1 && !empty($infoTermoformado->termoformado_otro_info)) {
$clienteProporcionaItems[] = $infoTermoformado->termoformado_otro_info;
}
}

// 6. Creamos el string final
$defaultClienteProporciona = implode(', ', $clienteProporcionaItems);
@endphp

@section('content')
<div>
    <div class="container mx-auto px-4 py-6 font-sans text-sm">            
        <div class="bg-white rounded-lg shadow-lg p-6">
            <a href="{{ route('cotizaciones.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded text-xs transition duration-150 ease-in-out shadow-md">
                <span class="text-base">←</span>
            </a>
            {{-- Header con Logo --}}
            <div class="flex justify-between items-start mb-6">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/innovet-logo.png') }}" alt="Innovet" style="max-width: 220px; width: 100%; height: auto;">
                </div>
                <div class="flex-1 text-center">
                    <h1 class="text-xl font-semibold text-white mb-4800">RESUMEN</h1>
                </div>
                <div class="flex-shrink-0 flex flex-col space-y-2">
                    <div class="mt-2 flex justify-end gap-2 font-bold btn-container-mobile">
                    <a href="{{ route('cotizacion.pdf.completo', $cotizacion->id) }}"  target="_blank" class="inline-block bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition-colors text-center">
                        <i class="fas fa-file-pdf"></i> Descargar PDF
                    </a>
                    <a href="{{ route('cotizacion.excel.completo', $cotizacion->id) }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition-colors text-center">
                        <i class="fas fa-file-excel"></i> Descargar Excel
                    </a>
                    </div>
                </div>
            </div>

            <div class="p-6 mx-auto font-sans text-xs">
                <table class="w-full border-collapse border border-black text-xs">
                    <tr>
                        <td class="p-1 border border-black bg-blue-200 font-bold">Cliente:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->cliente}}</td>
                        <td class="p-1 border border-black bg-blue-200 font-bold">Nombre del Proyecto:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->nombre_del_proyecto}}</td>
                        <td class="p-1 border border-black bg-blue-200 font-bold">Folio:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->no_proyecto}}</td>
                        <td class="p-1 border border-black bg-blue-200 font-bold">Fecha:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->fecha}}</td>
                    </tr>

                    <tr>
                        <td colspan="4" class="p-2 border border-black bg-gray-300 text-center font-bold">Descripción del proyecto</td>
                        <td colspan="4" class="p-2 border border-black bg-gray-300 text-center font-bold">Datos críticos:</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Tipo de producto:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->tipo_de_empaque}}</td>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Estiba</td>
                        <td colspan="3" class="p-1 border border-black bg-white">• {{ $cotizacion->requisicionCotizacion->tipo_estiba}}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">MOQ cotizado:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->lote_compra }} piezas</td>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Flujo de carga</td>
                        <td colspan="3" class="p-1 border border-black bg-white">• {{ $cotizacion->requisicionCotizacion->tipo_flujo_carga }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Frecuencia de compra:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->frecuencia_compra }}</td>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Dedales</td>
                        <td colspan="3" class="p-1 border border-black bg-white">• {{ $cotizacion->cajaCliente->dedales }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Dimensiones finales de pieza:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->pieza_largo}} x {{ $cotizacion->especificacionProyecto->pieza_ancho }} x {{ $cotizacion->especificacionProyecto->pieza_alto }} mm</td>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Tipo de pared</td>
                        <td colspan="3" class="p-1 border border-black bg-white">• {{ $cotizacion->requisicionCotizacion->pared}}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Dimensiones finales de molde:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">
                            @php
                            $req = optional(optional($cotizacion)->costeoRequisicion);
                            $espec = optional(optional($cotizacion)->especificacionProyecto);
                            @endphp

                            @if($req->insertos == 1)
                            {{ $espec->pieza_largo ?? '' }} x
                            {{ $espec->pieza_ancho ?? '' }} x
                            {{ $espec->pieza_alto ?? '' }} mm
                            @else
                            {{ $req->molde_ancho ?? '' }} x
                            {{ $req->molde_avance ?? '' }} x
                            {{ $espec->pieza_alto ?? '' }} mm
                            @endif
                        </td>

                        <td class="p-1 border border-black bg-gray-100 font-bold"># Cavidades</td>
                        <td colspan="3" class="p-1 border border-black bg-white">
                            • {{ optional(optional($cotizacion)->cotizacionAdicional)->componentes_por_charola ?? '' }}
                        </td>
                    </tr>


                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Fabricación de prototipo:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->cotizacionAdicional-> prototipo ?? '' }}</td>
                        <td rowspan="4" class="p-1 border border-black bg-gray-100 font-bold align-top">Otra Información</td>
                        <td colspan="3" rowspan="4" class="p-0 border border-black bg-white align-top">
                            <textarea
                                id="datos_criticos_input" {{-- Le damos un ID --}}
                                name="datos_criticos_adicionales"
                                class="w-full h-full border-none p-1 focus:ring-0 resize-none"
                                rows="4">• {{ $cotizacion->especificacionEmpaque->datos_criticos ?? '' }}</textarea>
                        </td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Especificación de material:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->material ?? '' }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Color:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->color ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Franja de color en caso de aplicar:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->franja_color ?? '' }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Calibre:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->especificacionProyecto->calibre ?? '' }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Ancho de material:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->hoja_ancho ??'' }}</td>
                        <td colspan="2" class="p-1 border border-black bg-white">mm</td>
                    </tr>

                    <tr>
                        <td rowspan="5" class="p-1 border border-black bg-gray-200 font-bold align-top">
                            Orillas
                    <tr>
                        <td class="p-1 border border-black bg-gray-300 font-bold">Vertical (cadenas):</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->acomodo_ancho_orillas_mm ?? '' }}</td>
                        <td class="p-1 border border-black bg-white">mm</td>
                    </tr>
                    <tr>
                        <td class="p-1 border border-black bg-gray-300 font-bold">Medianil vertical:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->acomodo_ancho_medianiles_mm ?? ''}}</td>
                        <td class="p-1 border border-black bg-white">mm</td>
                    </tr>
                    <tr>
                        <td class="p-1 border border-black bg-gray-300 font-bold">Horizontal:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->acomodo_avance_orillas_mm ?? '' }}</td>
                        <td class="p-1 border border-black bg-white">mm</td>
                    </tr>
                    <tr>
                        <td class="p-1 border border-black bg-gray-300 font-bold">Medianil horizontal:</td>
                        <td class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->acomodo_avance_medianiles_mm ?? '' }}</td>
                        <td class="p-1 border border-black bg-white">mm</td>
                    </tr>
                    </td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Insertos:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->insertos ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Placa de refrigeración:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">
                            {{ $valorPlaca  }}
                        </td>
                    </tr>


                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Máquina donde se produce:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->costeoRequisicion->nombre_maquina_termoformado ?? ''}},{{ $cotizacion->costeoRequisicion->nombre_maquina_suaje ?? ''}} </td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Broche:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->requisicionCotizacion->movimiento ?? '' }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-100 font-bold">Pestaña:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">{{ $cotizacion->cotizacionAdicional->pestana ?? '' }}</td>
                    </tr>

                    <tr>
                        <td class="p-1 border border-black bg-gray-200 font-bold">Grabados:</td>
                        <td colspan="3" class="p-1 border border-black bg-white">
                            {{ $grabadoFinal }}
                        </td>
                    </tr>
                    <form action="{{ route('costeo.updateField') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cotizacion_id" value="{{ $cotizacion->id }}">

                        <tr>
                            <td class="p-1 border border-black bg-gray-200 font-bold">Poka yoke:</td>
                            <td colspan="3" class="p-1 border border-black bg-white">

                                {{--
          Cambiamos el <input> por un <select>.
          Mantenemos el 'name' para tu formulario de "Guardar cambios".
          Mantenemos el 'id' para tu script de exportación (PDF/Excel).
        --}}
                                <select
                                    name="poka_yoke"
                                    id="poka_yoke_input" {{-- Mantenemos este ID para el script de JS --}}
                                    class="form-control">
                                    {{--
              Verificamos el valor actual. 
              Si no hay valor (es nuevo), seleccionamos 'No' por defecto.
            --}}
                                    @php
                                    $currentValue = $resumen->poka_yoke ?? 'No';
                                    @endphp

                                    <option value="Si" {{ $currentValue == 'Si' ? 'selected' : '' }}>Si</option>
                                    <option value="No" {{ $currentValue == 'No' ? 'selected' : '' }}>No</option>
                                </select>

                            </td>
                        </tr>

                        <tr>
                            <td class="p-1 border border-black bg-gray-100 font-bold">Acomodo de pieza:</td>
                            <td colspan="3" class="p-1 border border-black bg-white">
                                <input placeholder="Ingrese campo" name="acomodo_pieza" value="{{ $resumen->acomodo_pieza ?? '' }}" class="form-control">

                            </td>
                        </tr>

                        <tr>
                            <td class="p-1 border border-black bg-gray-200 font-bold">Contenedor del cliente:</td>
                            <td colspan="3" class="p-1 border border-black bg-white">
                                <input
                                    id="contenedor_cliente_input" {{-- Mantenemos ID para el script de JS --}}
                                    placeholder="Ingrese campo"
                                    name="contenedor_cliente"
                                    {{-- 
              Prioridad:
              1. Usa el valor guardado ($resumen->contenedor_cliente).
              2. Si no hay, usa nuestro default ('Si' o 'No').
            --}}
                                    value="{{ $resumen->contenedor_cliente ?? $defaultContenedor }}"
                                    class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td class="p-1 border border-black bg-gray-100 font-bold">Medidas de contenedor:</td>
                            <td colspan="3" class="p-1 border border-black bg-white">
                                <input
                                    id="medidas_contenedor_input" {{-- Mantenemos ID para el script de JS --}}
                                    name="medidas_contenedor"
                                    class="form-control"
                                    {{-- 
              Prioridad:
              1. Usa el valor guardado ($resumen->medidas_contenedor).
              2. Si no hay, usa nuestro default (que ya evita " x x ").
            --}}
                                    value="{{ $resumen->medidas_contenedor ?? $defaultMedidas }}">
                            </td>

                        </tr>


                        <tr>
                            <td class="p-1 border border-black bg-gray-200 font-bold">Estiba por contenedor:</td>
                            <td colspan="3" class="p-1 border border-black bg-white">
                                <input placeholder="Ingrese campo" name="estiba_contenedor" value="{{ $resumen->estiba_contenedor ?? '' }}" class="form-control">

                            </td>
                        </tr>

                        <tr>
                            <td class="p-1 border border-black bg-gray-100 font-bold">Cliente proporciona:</td>

                            {{-- CAMBIO: p-1 -> p-0, input -> textarea --}}
                            <td colspan="3" class="p-0 border border-black bg-white align-top">
                                <textarea
                                    id="cliente_proporciona_input" {{-- Mantenemos ID para el script de JS --}}
                                    name="cliente_proporciona" {{-- Mantenemos NAME para el formulario de Guardar --}}
                                    class="w-full h-full border-none p-1 focus:ring-0 resize-none"
                                    rows="3" {{-- Ajusta las filas según necesites --}}
                                    placeholder="No se proporcionó información">{{ $resumen->cliente_proporciona ?? $defaultClienteProporciona }}</textarea>
                            </td>


                        </tr>
                        <tr>
                            <td class="p-1 border border-black bg-gray-200 font-bold">Cargar Archivo:</td>
                            <td colspan="3" class="p-1 border border-black bg-white">
                                {{--
          Añadimos un input de tipo "file". 
          El 'name' será 'archivo_adjunto'.
        --}}
                                <input
                                 type="file"
                                 name="archivo_adjunto[]"
                                 multiple
                                 class="form-control">
                                 
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right p-2">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                    Guardar cambios
                                </button>
                            </td>
                        </tr>
                    </form>

                </table>
                <div class="mt-6">
    <fieldset class="upload-section mt-6">
    <legend>Archivos Adjuntos</legend>

    <div class="form-group full-width">
        <label for="archivos" class="block mb-2 font-semibold text-gray-700">
            Cargar archivos del resumen:
        </label>

        {{-- ÁREA DRAG & DROP --}}
        <div id="drop_zone" class="drop-zone">
            
            <input
                type="file"
                id="archivos"
                name="archivo_adjunto[]"
                multiple
                class="hidden">
        </div>

        {{-- ARCHIVOS YA GUARDADOS --}}
        @if($resumen && $resumen->archivos->isNotEmpty())
            <div class="mt-4">
                <p class="font-semibold mb-2">Archivos cargados previamente:</p>

                <div class="preview-grid" id="archivos_cargados">
                    @foreach($resumen->archivos as $archivo)
                        <div class="file-card">

                            @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $archivo->path))
                                <img
                                    src="{{ asset('storage/' . $archivo->path) }}"
                                    class="file-thumb mb-2"
                                    alt="Miniatura">
                            @else
                                <span class="block mb-2">
                                    {{ $archivo->nombre_original }}
                                </span>
                            @endif

                            <a
                                href="{{ asset('storage/' . $archivo->path) }}"
                                class="btn btn-download"
                                download>
                                Descargar
                            </a>

                            <button
                                type="button"
                                class="btn btn-delete"
                                onclick="eliminarArchivoResumen({{ $archivo->id }}, this)">
                                Quitar
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- PREVIEW DE ARCHIVOS NUEVOS --}}
        <div id="archivos_preview" class="mt-4 preview-grid"></div>
    </div>
</fieldset>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Función para generar el documento
            function generarDocumento(tipo) {
                // 1. Lee el valor ACTUAL del textarea
                var datosCriticos = document.getElementById('datos_criticos_input').value;

                // 2. Prepara el parámetro para la URL (esta es tu "variable aparte")
                var params = new URLSearchParams();
                params.append('datos_criticos_adicionales', datosCriticos);

                var baseUrl = '';

                // 3. Elige la URL base correcta (¡usa las rutas de tus enlaces <a> originales!)
                if (tipo === 'pdf') {
                    baseUrl = "{{ route('costeo.export.resumen.pdf', ['id' => $cotizacion->id]) }}";
                } else if (tipo === 'excel') {
                    baseUrl = "{{ route('costeo.export.resumen', ['id' => $cotizacion->id]) }}";
                }

                // 4. Combina la URL + el parámetro
                // (Los otros campos como poka_yoke no se envían aquí)
                var fullUrl = baseUrl + '?' + params.toString();

                // 5. Abre la descarga en una nueva pestaña
                window.open(fullUrl, '_blank');
            }

            // Asigna la función a los clics de los botones
            document.getElementById('btn-generar-pdf').addEventListener('click', function() {
                generarDocumento('pdf');
            });

            document.getElementById('btn-generar-excel').addEventListener('click', function() {
                generarDocumento('excel');
            });

        });
    </script>
    @endpush
    @endsection