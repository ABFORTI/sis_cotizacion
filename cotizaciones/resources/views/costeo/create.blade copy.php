@extends('layouts.app')
@section('content')

@php

$requisicion = optional($cotizacion);
$no_proyecto = oldValue('no_proyecto', $requisicion);
$tipo_de_empaque = oldValue('tipo_de_empaque', $requisicion);
$nombre_del_proyecto = oldValue('nombre_del_proyecto', $requisicion);


$especificacion_proyecto = optional($requisicion->especificacionProyecto);
$frecuencia_compra = oldValue('frecuencia_compra', $especificacion_proyecto);
$lote_compra = oldValue('lote_compra', $especificacion_proyecto);
$material = oldValue('material', $especificacion_proyecto);
$calibre = oldValue('calibre', $especificacion_proyecto);
$color = oldValue('color', $especificacion_proyecto);
$franja_color = oldValue('franja_color', $especificacion_proyecto);
$pieza_largo = oldValue('pieza_largo', $especificacion_proyecto);
$pieza_ancho = oldValue('pieza_ancho', $especificacion_proyecto);
$pieza_alto = oldValue('pieza_alto', $especificacion_proyecto);

//REQUICIOENES DE COTIZACION
$requisicionCotizacion = optional($requisicion->requisicionCotizacion);
$pared = oldValue('pared', $requisicionCotizacion);
$tipo_estiba = oldValue('tipo_estiba', $requisicionCotizacion);
$proceso_de_inocuidad = oldValue('proceso_de_inocuidad', $requisicionCotizacion);

//RELACION ESPECIFICACIONEMPAQUE
$especificacionEmpaque = optional($requisicion->especificacionEmpaque);
$cajas_corrugado = oldValue('cajas_corrugado', $especificacionEmpaque);
$bolsa_plastico = oldValue('bolsa_plastico', $especificacionEmpaque);
$esquineros = oldValue('esquineros', $especificacionEmpaque);
$liner = oldValue('liner', $especificacionEmpaque);


$pedimento_virtual = oldValue('pedimento_virtual', optional($requisicion->cotizacionAdicional));

$costeoRequisicion = optional($requisicion->costeoRequisicion);
$insertos = oldValue('insertos', $costeoRequisicion);
$calibre_costeo = oldValue('calibre_costeo', $costeoRequisicion);
$acomodo_ancho_medida_cantidad = oldValue('acomodo_ancho_medida_cantidad', $costeoRequisicion);
$acomodo_ancho_orillas_mm = oldValue('acomodo_ancho_orillas_mm', $costeoRequisicion);
$acomodo_ancho_orillas_cantidad = oldValue('acomodo_ancho_orillas_cantidad', $costeoRequisicion);
$acomodo_ancho_orillas_total = oldValue('acomodo_ancho_orillas_total', $costeoRequisicion);
$acomodo_ancho_medianiles_mm = oldValue('acomodo_ancho_medianiles_mm', $costeoRequisicion);
$acomodo_ancho_medianiles_cantidad = oldValue('acomodo_ancho_medianiles_cantidad', $costeoRequisicion);
$acomodo_ancho_medianiles_total = oldValue('acomodo_ancho_medianiles_total', $costeoRequisicion);
$acomodo_avance_medida_cantidad = oldValue('acomodo_avance_medida_cantidad', $costeoRequisicion);
$acomodo_avance_medida_total = oldValue('acomodo_avance_medida_total', $costeoRequisicion);
$acomodo_avance_orillas_mm = oldValue('acomodo_avance_orillas_mm', $costeoRequisicion);
$acomodo_avance_orillas_cantidad = oldValue('acomodo_avance_orillas_cantidad', $costeoRequisicion);
$acomodo_avance_orillas_total = oldValue('acomodo_avance_orillas_total', $costeoRequisicion);
$acomodo_avance_medianiles_mm = oldValue('acomodo_avance_medianiles_mm', $costeoRequisicion);
$acomodo_avance_medianiles_cantidad = oldValue('acomodo_avance_medianiles_cantidad', $costeoRequisicion);
$acomodo_avance_medianiles_total = oldValue('acomodo_avance_medianiles_total', $costeoRequisicion);
$molde_ancho = oldValue('molde_ancho', $costeoRequisicion);
$molde_avance = oldValue('molde_avance', $costeoRequisicion);
$hoja_ancho = oldValue('hoja_ancho', $costeoRequisicion);
$hoja_avance = oldValue('hoja_avance', $costeoRequisicion);
$placa_de_enfriamiento = oldValue('placa_de_enfriamiento', $costeoRequisicion);
//select dinamico
$opciones = [
"320 x 420 mm",
"350 x 560 mm",
"355 x 590 mm",
"420 x 420 mm",
"420 x 700 mm",
"455 x 480 mm",
"455 x 610 mm",
"450 x 620 mm TCH",
"460 x 520 mm",
"480 x 630 mm",
"490 x 600 mm",
"520 x 455 mm",
"520 x 1000 mm",
"600 x 650 mm",
"650 x 592 mm",
"700 x 1200 mm",
"800 x 940 mm M",
"1175 x 1390 mm",
"1450 x 1630 mm",
"1450 x 3000 mm"
];
$peso_especifico = oldValue('peso_especifico', $costeoRequisicion);
$area_formado_hoja = oldValue('area_formado_hoja', $costeoRequisicion);
$cantidad_hojas = oldValue('cantidad_hojas', $costeoRequisicion);
$peso_pieza = oldValue('peso_pieza', $costeoRequisicion);
$peso_neto_hoja = oldValue('peso_neto_hoja', $costeoRequisicion);
$coeficiente_merma = oldValue('coeficiente_merma', $costeoRequisicion);
$peso_merma = oldValue('peso_merma', $costeoRequisicion);
$peso_bruto_hoja = oldValue('peso_bruto_hoja', $costeoRequisicion);
$peso_total = oldValue('peso_total', $costeoRequisicion);
$PRM = oldValue('PRM', $costeoRequisicion);
$PZRM = oldValue('PZRM', $costeoRequisicion);
$peso_neto = oldValue('peso_neto', $costeoRequisicion);
$costo_kilo = oldValue('costo_kilo', $costeoRequisicion);
$TC = oldValue('TC', $costeoRequisicion);
$costo_flete = oldValue('costo_flete', $costeoRequisicion);
$precio_kg = oldValue('precio_kg', $costeoRequisicion);
$costo_lamina = oldValue('costo_lamina', $costeoRequisicion);
$TC_lamina = oldValue('TC_lamina', $costeoRequisicion);
$costo_flete_lamina = oldValue('costo_flete_lamina', $costeoRequisicion);
$precio_lamina = oldValue('precio_lamina', $costeoRequisicion);
$sugerencia_costos_mp = oldValue('sugerencia_costos_mp', $costeoRequisicion);

$hojas_del_pedido = oldValue('hojas_del_pedido', $costeoRequisicion);

// VARIABLES PARA LOS PROCESOS DE MAQUINAS
$nombre_maquina_termoformado = oldValue('nombre_maquina_termoformado', $costeoRequisicion);
$nombre_maquina_suaje = oldValue('nombre_maquina_suaje', $costeoRequisicion);

// Variables para los campos de termoformado
$no_personas_termoformado = oldValue('no_personas_termoformado', $costeoRequisicion);
$bajadas_por_minuto_termoformado = oldValue('bajadas_por_minuto_termoformado', $costeoRequisicion);
$total_hojas_turno_termoformado = oldValue('total_hojas_turno_termoformado', $costeoRequisicion);
$total_dias_turnos_termoformado = oldValue('total_dias_turnos_termoformado', $costeoRequisicion);
$costo_termoformado = oldValue('costo_termoformado', $costeoRequisicion);

// Variables para los campos de suaje
$no_personas_suaje = oldValue('no_personas_suaje', $costeoRequisicion);
$bajadas_por_minuto_suaje = oldValue('bajadas_por_minuto_suaje', $costeoRequisicion);
$total_hojas_turno_suaje = oldValue('total_hojas_turno_suaje', $costeoRequisicion);
$total_piezas_turno_suaje = oldValue('total_piezas_turno_suaje', $costeoRequisicion);
$total_dias_turnos_suaje = oldValue('total_dias_turnos_suaje', $costeoRequisicion);
$costo_suaje = oldValue('costo_suaje', $costeoRequisicion);

$procesos = $costeoRequisicion->procesos ?? collect([]);
@endphp

@if(request()->has('btn_corrida_piloto') && request()->input('btn_corrida_piloto') === 'corrida_piloto')
@php
$esCorridaPiloto = true;
@endphp
@else
@php
$esCorridaPiloto = false;
@endphp
@endif

<div class="container mx-auto p-4">
    @if($esCorridaPiloto)
    <h1 class="text-3xl font-bold mb-6 text-center">Calculo de Corrida Piloto</h1>
    @else
    <h1 class="text-3xl font-bold mb-6 text-center">Calculo de Costeo</h1>
    @endif
    <form action="{{ route('costeo.store', $cotizacion->id) }}" method="POST" id="costeoForm">
        @csrf
        <div class="mb-8 p-6 border-2 border-blue-200 bg-blue-50 rounded-lg">
            <h2 class="text-2xl font-bold text-blue-800 mb-4">Información de la Requisición</h2>

            <div class=" grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="font-bold block mb-2">Fecha:</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Folio:</label>
                    <input type="text" name="no_proyecto" value="{{ $no_proyecto }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Tipo de Empaque:</label>
                    <input type="text" name="tipo_de_empaque" value="{{ $tipo_de_empaque }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Nombre del Proyecto:</label>
                    <input type="text" name="nombre_del_proyecto" value="{{ $nombre_del_proyecto }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Frecuencia de Compra:</label>
                    <input type="text" name="frecuencia_compra" value="{{ $frecuencia_compra }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                @if($esCorridaPiloto)
                <div>
                    <label class="font-bold block mb-2">MOQ:</label>
                    <input type="number" name="lote_compra" value="{{ $lote_compra}}"
                        oninput="if(this.value > 0) { calcularCantidadHojas(); calcularPesoNeto(); calcularPesoTotal(); calcularPesoBrutoHoja(); 
                        calcularPiezasPorCaja(); calcularHojasDelPedido(); calcularCostoMontaje2(); calcularCostoAmortizacionHerramentales2(); 
                        calcularCostoEnergiaE2(); calcularCostoAmortizacionMaquinaria2(); calcularTotalHojasPorTurnoTermoformado(); 
                        calcularTotalHojasPorTurnoSuaje(); calcularTotalCostoBolsas(); calcularCostoInocuidad(); calcularParedMedia(); 
                        calcularEstaticida(); asignarLoteCompraEnResumenPiezas();  calcularCostosUnit(); calcularMargenAdministrativo(); calcularCostoTotalResumen(); }"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                @else
                <div>
                    <label class="font-bold block mb-2">MOQ:</label>
                    <input type="number" name="lote_compra" value="{{ $lote_compra}}" disabled
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                @endif

            </div>

            <!-- ESPECIFICACIONES DE MATERIAL -->
            <div>
                <h2 class="text-2xl font-bold text-blue-800 mb-4">Especificaciones de material</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="font-bold block mb-2">Material:</label>
                    <input type="text" name="material" value="{{ $material }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Calibre ("):</label>
                    <input type="number" step="0.0001" name="calibre_costeo" value="{{ $calibre_costeo ?: $calibre }}"
                        class="w-full border-gray-300 border rounded-md p-2" onchange="calcularPesoNeto(), calcularPesoEstimadoPieza(), calcularPesoTotal()">
                </div>
                <div>
                    <label class="font-bold block mb-2">Color:</label>
                    <input type="text" name="color" value="{{ $color }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Franja de Color:</label>
                    <input type="text" name="franja_color" value="{{ $franja_color }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
            </div>

            <!-- DIMENSIONES -->
            <div>
                <h2 class="text-2xl font-bold text-blue-800 mb-4">Dimensiones</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="font-bold block mb-2">Largo (mm):</label>
                    <input type="number" step="0.01" name="largo" value="{{ $pieza_largo }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Ancho (mm):</label>
                    <input type="number" step="0.01" name="ancho" value="{{ $pieza_ancho }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
                <div>
                    <label class="font-bold block mb-2">Alto (mm):</label>
                    <input type="number" step="0.01" name="alto" value="{{ $pieza_alto }}"
                        class="w-full border-gray-300 border rounded-md p-2" disabled>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: DISTRIBUCIÓN DE HERRAMENTAL -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Distribución de Herramental</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-bold block mb-2">Insertos:</label>
                    <input type="number" step="1" name="insertos"
                        value="{{$insertos}}"
                        placeholder="Número de insertos"
                        class="w-full border-gray-300 border rounded-md p-2"
                        oninput="calcularPesoNeto(),calcularCantidadHojas(), document.querySelector('input[name=&quot;resumen_piezas_procesos&quot;]').value = this.value">
                </div>
                <div>
                    <label class="font-bold block mb-2">Corte:</label>
                    <input type="text" value="{{$pared}}" placeholder="NA" disabled>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: ACOMODO ANCHO -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Acomodo Ancho</h2>

            <table class="w-full text-center border-collapse border border-gray-400 mb-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2"></th>
                        <th class="border border-gray-300 p-2">Medida (mm)</th>
                        <th class="border border-gray-300 p-2">Cantidad</th>
                        <th class="border border-gray-300 p-2">Total (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Pieza</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_ancho_medida_1"
                                value="{{ $pieza_ancho }}"
                                class="w-full border-gray-300 border rounded-md p-1" disabled>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="acomodo_ancho_cantidad_1"
                                value="{{ $acomodo_ancho_medida_cantidad }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularInsertos(), calcularMedianilesAncho(), calcularAcomodoAncho()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_ancho_total_1" readonly
                                value="{{$pieza_alto}}" class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Orillas</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_ancho_medida_2"
                                value="{{ $acomodo_ancho_orillas_mm }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAncho()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="acomodo_ancho_cantidad_2"
                                value="{{ $acomodo_ancho_orillas_cantidad }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAncho()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_ancho_total_2" readonly
                                value="{{ $acomodo_ancho_orillas_total }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Medianiles</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_ancho_medida_3"
                                value="{{ $acomodo_ancho_medianiles_mm }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAncho()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="acomodo_ancho_cantidad_3"
                                value="{{ $acomodo_ancho_medianiles_cantidad}}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAncho()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_ancho_total_3" readonly
                                value="{{ $acomodo_ancho_medianiles_total }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr class="bg-gray-100">
                        <td class="font-bold border border-gray-300 p-2" colspan="3">Total Ancho Molde</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="total_ancho_molde" readonly
                                class="w-full bg-gray-200 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>
            function calcularAcomodoAncho() {
                let total = 0;
                for (let i = 1; i <= 3; i++) {
                    const medida = parseFloat(document.querySelector(`input[name="acomodo_ancho_medida_${i}"]`).value) || 0;
                    const cantidad = parseInt(document.querySelector(`input[name="acomodo_ancho_cantidad_${i}"]`).value) || 0;
                    const totalFila = medida * cantidad;
                    document.querySelector(`input[name="acomodo_ancho_total_${i}"]`).value = totalFila.toFixed(2);
                    total += totalFila;
                }
                document.querySelector('input[name="total_ancho_molde"]').value = total.toFixed(2);
                document.querySelector('input[name="molde_ancho"]').value = total.toFixed(2);
                document.querySelector('input[name="molde_ancho_copia"]').value = total.toFixed(2);
                asignarPlacaEnfriamiento();
                calcularAjustesHerramentales();
            }

            function calcularAcomodoAvance() {
                let total = 0;
                for (let i = 1; i <= 3; i++) {
                    const medida = parseFloat(document.querySelector(`input[name="acomodo_avance_medida_${i}"]`).value) || 0;
                    const cantidad = parseInt(document.querySelector(`input[name="acomodo_avance_cantidad_${i}"]`).value) || 0;
                    const totalFila = medida * cantidad;
                    document.querySelector(`input[name="acomodo_avance_total_${i}"]`).value = totalFila.toFixed(2);
                    total += totalFila;
                }
                document.querySelector('input[name="total_avance_molde"]').value = total.toFixed(2);
                document.querySelector('input[name="molde_avance"]').value = total.toFixed(2);
                document.querySelector('input[name="molde_avance_copia"]').value = total.toFixed(2);
                asignarPlacaEnfriamiento();
                calcularAjustesHerramentales();
            }

            function calcularInsertos() {
                const ancho = parseFloat(document.querySelector('input[name="acomodo_ancho_cantidad_1"]').value) || 0;
                const avance = parseFloat(document.querySelector('input[name="acomodo_avance_cantidad_1"]').value) || 0;
                const resultado = ancho * avance;
                document.querySelector('input[name="insertos"]').value = resultado.toFixed(2);
                document.querySelector('input[name="resumen_piezas_procesos"]').value = resultado.toFixed(2);
                calcularCantidadHojas();
            }

            function calcularMedianilesAncho() {
                const cantidad = parseFloat(document.querySelector('input[name="acomodo_ancho_cantidad_1"]').value) || 0;
                const resultado = cantidad - 1;
                document.querySelector('input[name="acomodo_ancho_cantidad_3"]').value = resultado;
            }

            function calcularMedianilesAvance() {
                const cantidad = parseFloat(document.querySelector('input[name="acomodo_avance_cantidad_1"]').value) || 0;
                const resultado = cantidad - 1;
                document.querySelector('input[name="acomodo_avance_cantidad_3"]').value = resultado;
            }

            function calcularCantidadHojas() {
                const moq = parseFloat(document.querySelector('input[name="lote_compra"]').value) || 0;
                const insertos = parseFloat(document.querySelector('input[name="insertos"]').value) || 0;
                const resultado = moq / insertos;
                document.querySelector('input[name="cantidad_hojas"]').value = resultado.toFixed(2);
                calcularPesoNetoHoja();

            }
        </script>

        <!-- SECCIÓN: ACOMODO AVANCE -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Acomodo Avance</h2>

            <table class="w-full text-center border-collapse border border-gray-400 mb-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2"></th>
                        <th class="border border-gray-300 p-2">Medida (mm)</th>
                        <th class="border border-gray-300 p-2">Cantidad</th>
                        <th class="border border-gray-300 p-2">Total (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Pieza</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_avance_medida_1"
                                value="{{ $pieza_largo}}"
                                class="w-full border-gray-300 border rounded-md p-1" disabled>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="acomodo_avance_cantidad_1"
                                value="{{ $acomodo_avance_medida_cantidad }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularInsertos(), calcularMedianilesAvance(), calcularAcomodoAvance()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_avance_total_1" readonly
                                value="{{ $acomodo_avance_medida_total }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Orillas</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_avance_medida_2"
                                value="{{ $acomodo_avance_orillas_mm }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAvance()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="acomodo_avance_cantidad_2"
                                value="{{ $acomodo_avance_orillas_cantidad }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAvance()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_avance_total_2" readonly
                                value="{{ $acomodo_avance_orillas_total }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Medianiles</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_avance_medida_3"
                                value="{{ $acomodo_avance_medianiles_mm }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAvance()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="acomodo_avance_cantidad_3"
                                value="{{ $acomodo_avance_medianiles_cantidad }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularAcomodoAvance()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="acomodo_avance_total_3" readonly
                                value="{{ $acomodo_avance_medianiles_total }}"
                                class=" w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr class="bg-gray-100">
                        <td class="font-bold border border-gray-300 p-2" colspan="3">Total Avance Molde</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="total_avance_molde" readonly
                                class="w-full bg-gray-200 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: HOJA Y PLACA DE ENFRIAMIENTO -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Hoja y Placa de Enfriamiento</h2>

            <!-- Tabla de molde -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-blue-700 mb-2">Molde</h3>
                <table class="w-full border-collapse border border-gray-400 text-center">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 p-2">Molde</th>
                            <th class="border border-gray-300 p-2">Ancho (mm)</th>
                            <th class="border border-gray-300 p-2">Avance (mm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2 font-medium">Molde</td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="molde_ancho"
                                    value="{{ $molde_ancho }}"
                                    class="w-full bg-gray-200 border-gray-300 border rounded-md p-1" readonly>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="molde_avance"
                                    value="{{ $molde_avance }}"
                                    class="w-full bg-gray-200 border-gray-300 border rounded-md p-1" readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Tabla de Hoja -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-blue-700 mb-2">Hoja</h3>
                <table class="w-full border-collapse border border-gray-400 text-center">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 p-2">Hoja</th>
                            <th class="border border-gray-300 p-2">Ancho (mm)</th>
                            <th class="border border-gray-300 p-2">Avance (mm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2">
                                <button type="button" onclick="toggleAuxInputs()" 
                                    class="font-medium px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">
                                    Hoja
                                </button> 
                            </td>
                            <td class="border border-gray-300 p-2">
                                <div class="grid gap-1" id="grid-ancho">
                                    <input type="number" name="hoja_ancho" step="0.5" id="input-hoja-ancho"
                                        value="{{ $hoja_ancho }}" placeholder="Ingrese medida hoja ancho" oninput="calcularAreaFormadoHoja(), calcularParedMedia()"
                                        class="border-gray-300 border rounded-md p-1">
                                    <input type="number" name="aux_hoja_ancho" id="aux-hoja-ancho" value="{{ old('aux_hoja_ancho', $costeoRequisicion->aux_hoja_ancho) }}" placeholder="ajuste"
                                        class="border-gray-300 border rounded-md p-1 hidden" oninput="calcularHojaAncho(), calcularAreaFormadoHoja(), calcularParedMedia()">
                                </div>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <div class="grid gap-1" id="grid-avance">
                                    <input type="number" name="hoja_avance" step="0.5" id="input-hoja-avance"
                                        value="{{ $hoja_avance }}" placeholder="Ingrese medida hoja avance" oninput="calcularAreaFormadoHoja(), calcularParedMedia()"
                                        class="border-gray-300 border rounded-md p-1">
                                    <input type="number" name="aux_hoja_avance" id="aux-hoja-avance" value="{{ old('aux_hoja_avance', $costeoRequisicion->aux_hoja_avance) }}" placeholder="ajuste"
                                        class="border-gray-300 border rounded-md p-1 hidden" oninput="calcularHojaAvance(), calcularAreaFormadoHoja() , calcularParedMedia()">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <script>
                function toggleAuxInputs() {
                    // Obtener los elementos
                    const auxAncho = document.getElementById('aux-hoja-ancho');
                    const auxAvance = document.getElementById('aux-hoja-avance');
                    const gridAncho = document.getElementById('grid-ancho');
                    const gridAvance = document.getElementById('grid-avance');
                    
                    // Verificar si están ocultos
                    const isHidden = auxAncho.classList.contains('hidden');
                    
                    if (isHidden) {
                        // Mostrar inputs auxiliares
                        auxAncho.classList.remove('hidden');
                        auxAvance.classList.remove('hidden');
                        // Cambiar a grid de 5 columnas
                        gridAncho.classList.remove('grid-cols-1');
                        gridAncho.classList.add('grid-cols-5');
                        gridAvance.classList.remove('grid-cols-1');
                        gridAvance.classList.add('grid-cols-5');
                        // Ajustar el input principal a 4 columnas
                        document.getElementById('input-hoja-ancho').classList.add('col-span-4');
                        document.getElementById('input-hoja-avance').classList.add('col-span-4');
                        // Ajustar el aux a 1 columna
                        auxAncho.classList.add('col-span-1');
                        auxAvance.classList.add('col-span-1');
                    } else {
                        // Ocultar inputs auxiliares
                        auxAncho.classList.add('hidden');
                        auxAvance.classList.add('hidden');
                        // Cambiar a grid de 1 columna (ocupa todo el espacio)
                        gridAncho.classList.remove('grid-cols-5');
                        gridAncho.classList.add('grid-cols-1');
                        gridAvance.classList.remove('grid-cols-5');
                        gridAvance.classList.add('grid-cols-1');
                        // Quitar las clases de columnas
                        document.getElementById('input-hoja-ancho').classList.remove('col-span-4');
                        document.getElementById('input-hoja-avance').classList.remove('col-span-4');
                        auxAncho.classList.remove('col-span-1');
                        auxAvance.classList.remove('col-span-1');
                    }
                }

                function calcularHojaAncho() {
                    const ancho = parseFloat(document.querySelector('input[name="total_ancho_molde"]').value) || 0;
                    const aux = parseFloat(document.querySelector('input[name="aux_hoja_ancho"]').value) || 0;
                    const resultado = ancho + aux;
                    document.querySelector('input[name="hoja_ancho"]').value = resultado.toFixed(2);
                }

                function calcularHojaAvance() {
                    const avance = parseFloat(document.querySelector('input[name="total_avance_molde"]').value) || 0;
                    const aux = parseFloat(document.querySelector('input[name="aux_hoja_avance"]').value) || 0;
                    const resultado = avance + aux;
                    document.querySelector('input[name="hoja_avance"]').value = resultado.toFixed(2);
                }
            </script>

            <!-- Select de Placa de Enfriamiento -->
            <div>
                <h3 class="text-lg font-semibold text-blue-700 mb-2">Placa de Enfriamiento</h3>
                <label class="font-bold block mb-2">Selecciona una medida:</label>
                <select name="placa_de_enfriamiento" class="w-full border-gray-300 border rounded-md p-2" oninput="calcularPlacaFijacion()">
                    <option value="">Seleccione una opción</option>
                    @foreach ($opciones as $index => $texto)
                    <option value="{{ $index + 1 }}" {{ $placa_de_enfriamiento == ($index + 1) ? 'selected' : '' }}>
                        {{ $texto }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <script>
            function asignarPlacaEnfriamiento() {
                const moldeancho = parseFloat(document.querySelector('input[name="total_ancho_molde"]').value) || 0;
                const moldeavance = parseFloat(document.querySelector('input[name="total_avance_molde"]').value) || 0;
                const valoresPlaca = [{
                        ancho: 320,
                        avance: 420
                    },
                    {
                        ancho: 350,
                        avance: 560
                    },
                    {
                        ancho: 355,
                        avance: 590
                    },
                    {
                        ancho: 420,
                        avance: 420
                    },
                    {
                        ancho: 420,
                        avance: 700
                    },
                    {
                        ancho: 455,
                        avance: 480
                    },
                    {
                        ancho: 455,
                        avance: 610
                    },
                    {
                        ancho: 450,
                        avance: 620
                    },
                    {
                        ancho: 460,
                        avance: 520
                    },
                    {
                        ancho: 480,
                        avance: 630
                    },
                    {
                        ancho: 490,
                        avance: 600
                    },
                    {
                        ancho: 520,
                        avance: 455
                    },
                    {
                        ancho: 520,
                        avance: 1000
                    },
                    {
                        ancho: 600,
                        avance: 650
                    },
                    {
                        ancho: 650,
                        avance: 592
                    },
                    {
                        ancho: 700,
                        avance: 1200
                    },
                    {
                        ancho: 800,
                        avance: 940
                    },
                    {
                        ancho: 1175,
                        avance: 1390
                    },
                    {
                        ancho: 1450,
                        avance: 1630
                    },
                    {
                        ancho: 1450,
                        avance: 3000
                    }
                ];
                let placaSeleccionada = null;
                for (const [index, placa] of valoresPlaca.entries()) {
                    if (moldeancho <= placa.ancho && moldeavance <= placa.avance) {
                        placaSeleccionada = index + 1; // Índice de la placa (1-based)
                        break;
                    }
                }
                if (placaSeleccionada !== null) {
                    document.querySelector('select[name="placa_de_enfriamiento"]').value = placaSeleccionada;
                } else {
                    document.querySelector('select[name="placa_de_enfriamiento"]').value = "";
                }
                calcularPlacaFijacion();
            }

            function calcularPlacaFijacion() {
                const placas = parseInt(document.querySelector('select[name="placa_de_enfriamiento"]').value) || 0;
                // Mapeo de índices a valores de placa
                const valoresPlaca = [
                    600.00, // 1: 320 x 420 mm
                    874.21, // 2: 350 x 560 mm
                    912.22, // 3: 355 x 590 mm
                    784.61, // 4: 420 x 420 mm
                    1292.31, // 5: 420 x 700 mm
                    928.50, // 6: 455 x 480 mm
                    1221.72, // 7: 455 x 610 mm
                    1221.72, // 8: 450 x 620 mm TCH
                    1083.26, // 9: 460 x 520 mm
                    1289.59, // 10: 480 x 630 mm
                    1303.16, // 11: 490 x 600 mm
                    1026.24, // 12: 520 x 455 mm
                    2280.54, // 13: 520 x 1000 mm
                    1694.11, // 14: 600 x 650 mm
                    1694.11, // 15: 650 x 592 mm
                    3648.86, // 16: 700 x 1200 mm
                    3301.35, // 17: 800 x 940 mm M
                    7018.09, // 18: 1175 x 1390 mm
                    10235.27, // 19: 1450 x 1630 mm
                    18738.42 // 20: 1450 x 3000 mm
                ];
                let resultado = 0;
                if (placas > 0 && placas <= valoresPlaca.length) {
                    resultado = valoresPlaca[placas - 1];
                }
                document.querySelector('input[name="costo_placa_fijacion"]').value = resultado.toFixed(2);
                document.querySelector('input[name="total_costo_placa_fijacion"]').value = resultado.toFixed(2);
            }
        </script>

        <!-- SECCIÓN: CÁLCULOS DE MATERIAL -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Cálculos de Material Prima</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="font-bold block mb-2">Peso Específico (g/cm³):</label>
                    <input type="number" step="0.0001" name="peso_especifico"
                        value="{{ $peso_especifico }}" oninput="calcularPesoEstimadoPieza()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Área de Formado de Hoja (mm):</label>
                    <input type="number" step="0.0001" name="area_formado_hoja"
                        value="{{ $area_formado_hoja }}"
                        class="w-full border-gray-300 border rounded-md p-2 bg-gray-50">
                </div>
                <div>
                    <label class="font-bold block mb-2">Cantidad de Hojas:</label>
                    <input type="number" step="0.01" name="cantidad_hojas"
                        value="{{ $cantidad_hojas }}" oninput="calcularPesoNetoHoja(), calcularPesoBrutoHoja(), calcularHojasDelPedido()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Peso estimado x Pieza (kg):</label>
                    <input type="number" step="0.0001" name="peso_pieza"
                        value="{{ $peso_pieza}}"
                        class="w-full border-gray-300 border rounded-md p-2 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="font-bold block mb-2">Peso neto de hoja (kg):</label>
                    <input type="number" step="0.0001" name="peso_neto_hoja"
                        value="{{ $peso_neto_hoja }}"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Coeficiente de merma (%):</label>
                    <input type="number" step="1" name="coeficiente_merma" oninput="calcularPesoTotal(), calcularHojasDelPedido(), calcularPrecioLamina()"
                        value="{{ $coeficiente_merma }}" placeholder="Ingrese el porcentaje %"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Peso merma (kg):</label>
                    <input type="number" step="0.0001" name="peso_merma"
                        value="{{ $peso_merma }}"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Peso bruto de hoja (kg):</label>
                    <input type="number" step="0.0001" name="peso_bruto_hoja"
                        value="{{ $peso_bruto_hoja }}" oninput="calcularCostoMP()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Peso neto (kg):</label>
                    <input type="number" step="0.0001" name="peso_neto"
                        value="{{ $peso_neto }}" oninput="calcularPesoNetoHoja(), calcularPesoMerma()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <button type="button" onclick="calcularPesoTotalFormula2()" 
                        class="font-bold block px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">
                        Peso total (kg)
                    </button>
                    <input type="number" step="0.0001" name="peso_total"
                        value="{{ $peso_total }}" oninput="calcularPesoBrutoHoja(), calcularPesoMerma(), calcularPrecioPorKg()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <button type="button" onclick="togglePRMAuxInputs()" 
                        class="font-bold block px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">
                        PRM
                    </button>
                    <div class="grid gap-2 items-center" id="grid-prm">
                        <input type="number" step="0.0001" name="PRM" id="input-prm"
                            value="{{ $PRM }}" placeholder="Calcular"
                            class="border-gray-300 border rounded-md p-2">
                        <input type="number" name="divisor_prm" id="aux-divisor-prm" value="{{ $costeoRequisicion->divisor_prm }}" placeholder="/divisor"
                            class="border-gray-300 border rounded-md p-2 hidden" oninput="calcularPRM(), calcularPesoTotal()">
                        <input type="number" name="sumador_prm" id="aux-sumador-prm" value="{{ $costeoRequisicion->sumador_prm }}" placeholder="+sumador"
                            class="border-gray-300 border rounded-md p-2 hidden" oninput="calcularPRM(), calcularPesoTotal()">
                    </div>
                </div>
                <div>
                    <label class="font-bold block mb-2">PZRM:</label>
                    <input type="number" step="0.0001" name="PZRM"
                        value="{{ $PZRM }}" placeholder="Calcular PZRM"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
            </div>
        </div>
        <script>
            //FUNCION A CAMBIAR POR EL CRUD DE MATERIALES SE OBTENDRA DE AHI
            function calcularPesoEspecifico() {
                const tablaPesos = {
                    ABS: 1.02,
                    PS: 1.08,
                    PET: 1.35,
                    HDPE: 1.02,
                    PP: 0.93,
                    "PET ESD": 1.35,
                    "PET-POLIPROPILENO": 1.3,
                    "GRADO ALIMENTICIO": 1.35,
                    Otros:1.35
                };

                const materialInput = document.querySelector('input[name="material"]');
                const pesoInput = document.querySelector('input[name="peso_especifico"]');

                if (!materialInput || !pesoInput) return;

                const clave = materialInput.value.trim();
                const pesoEspecifico = tablaPesos[clave] ?? '';

                pesoInput.value = pesoEspecifico;
                calcularPesoEstimadoPieza();
            }

            function calcularAreaFormadoHoja() {
                const largo = parseFloat(document.querySelector('input[name="hoja_avance"]').value) || 0;
                const ancho = parseFloat(document.querySelector('input[name="hoja_ancho"]').value) || 0;
                const area = (largo * ancho) / 1000000;
                document.querySelector('input[name="area_formado_hoja"]').value = area.toFixed(4);
                calcularPesoNeto();
            }

            function calcularPesoEstimadoPieza() {
                const largo = parseFloat(document.querySelector('input[name="largo"]').value) || 0;
                const ancho = parseFloat(document.querySelector('input[name="ancho"]').value) || 0;
                const calibre = parseFloat(document.querySelector('input[name="calibre_costeo"]').value) || 0;
                const pesoEspecifico = parseFloat(document.querySelector('input[name="peso_especifico"]').value) || 0;

                const peso = (((largo / 10) * (ancho / 10) * ((calibre * 25.4) / 10000)) * pesoEspecifico) / 1000;
                document.querySelector('input[name="peso_pieza"]').value = peso.toFixed(4);
            }

            function calcularPesoNetoHoja() {
                const pesoneto = parseFloat(document.querySelector('input[name="peso_neto"]').value) || 0;
                const cantidadhojas = parseFloat(document.querySelector('input[name="cantidad_hojas"]').value) || 0;
                let resultado = 0;
                if (cantidadhojas !== 0) {
                    resultado = pesoneto / cantidadhojas;
                }
                const input = document.querySelector('input[name="peso_neto_hoja"]');
                if (!isNaN(resultado)) {
                    input.value = resultado.toFixed(4);
                } else {
                    input.value = '0';
                }
                calcularPZRM();
            }

            function calcularPesoNeto() {
                const moq = parseFloat(document.querySelector('input[name="lote_compra"]').value) || 0;
                const insertos = parseFloat(document.querySelector('input[name="insertos"]').value) || 0;
                const areaFormadoHoja = parseFloat(document.querySelector('input[name="area_formado_hoja"]').value) || 0;
                const calibre = parseFloat(document.querySelector('input[name="calibre_costeo"]').value) || 0;
                const pesoEspecifico = parseFloat(document.querySelector('input[name="peso_especifico"]').value) || 1.02;
                let resultado = 0;
                if (insertos !== 0) {
                    resultado = (moq / insertos) * areaFormadoHoja * 10000 * (calibre / 393.7) * pesoEspecifico / 1000;
                }
                const input = document.querySelector('input[name="peso_neto"]');
                if (!isNaN(resultado)) {
                    input.value = resultado.toFixed(4);
                } else {
                    input.value = '0';
                }
                calcularPesoNetoHoja();
            }

            function calcularPesoMerma() {
                const pesoneto = parseFloat(document.querySelector('input[name="peso_neto"]').value) || 0;
                const pesototal = parseFloat(document.querySelector('input[name="peso_total"]').value) || 0;
                const resultado = pesototal - pesoneto;
                document.querySelector('input[name="peso_merma"]').value = resultado.toFixed(4);
                calcularPZRM();
            }

            function calcularPesoBrutoHoja() {
                const hojas = parseFloat(document.querySelector('input[name="cantidad_hojas"]').value) || 0;
                const pesototal = parseFloat(document.querySelector('input[name="peso_total"]').value) || 0;
                const resultado = pesototal / hojas;
                document.querySelector('input[name="peso_bruto_hoja"]').value = resultado.toFixed(4);
            }

            function calcularHojasDelPedido() {
                const coeficienteMerma = parseFloat(document.querySelector('input[name="coeficiente_merma"]').value) || 0;
                const hojas = parseFloat(document.querySelector('input[name="cantidad_hojas"]').value) || 0;
                const resultado = (hojas * (coeficienteMerma / 100)) + hojas;
                document.querySelector('input[name="hojas_del_pedido"]').value = resultado.toFixed(4);
            }

            function calcularPRM() {
                const pesoNetoHoja = parseFloat(document.querySelector('input[name="peso_neto_hoja"]').value) || 0;
                const pesoNeto = parseFloat(document.querySelector('input[name="peso_neto"]').value) || 0;
                const sumador = parseFloat(document.querySelector('input[name="sumador_prm"]').value) || 0;
                const divisor = parseFloat(document.querySelector('input[name="divisor_prm"]').value) || 1;
                const resultado = ((30 * pesoNetoHoja) * (pesoNeto / divisor)) + sumador;
                document.querySelector('input[name="PRM"]').value = resultado.toFixed(4);
            }

            function calcularPesoTotal() {
                const pesoneto = parseFloat(document.querySelector('input[name="peso_neto"]').value) || 0;
                const prm = parseFloat(document.querySelector('input[name="PRM"]').value) || 0;
                const resultado = pesoneto + prm;
                document.querySelector('input[name="peso_total"]').value = resultado.toFixed(3);
                calcularPesoBrutoHoja();
                calcularPesoMerma();
            }

            function calcularPesoTotalFormula2() {
                const moq = parseFloat(document.querySelector('input[name="lote_compra"]').value) || 0;
                const insertos = parseFloat(document.querySelector('input[name="insertos"]').value) || 0;
                const areaFormadoHoja = parseFloat(document.querySelector('input[name="area_formado_hoja"]').value) || 0;
                const calibre = parseFloat(document.querySelector('input[name="calibre_costeo"]').value) || 0;
                const pesoEspecifico = parseFloat(document.querySelector('input[name="peso_especifico"]').value) || 1.02;
                const coeficienteMerma = parseFloat(document.querySelector('input[name="coeficiente_merma"]').value) || 0;

                const hojas = moq / insertos;
                const volumen = hojas * areaFormadoHoja * 10000 * (calibre / 393.7);
                const pesoNeto = volumen * pesoEspecifico / 1000;
                const pesoConMerma = pesoNeto * (1 + (coeficienteMerma / 100));

                const resultado = Math.round(pesoConMerma * 10) / 10;

                document.querySelector('input[name="peso_total"]').value = resultado.toFixed(1);

                calcularPesoBrutoHoja();
                calcularPesoMerma();
            }

            function calcularPZRM() {
                const pesoNetoHoja = parseFloat(document.querySelector('input[name="peso_neto_hoja"]').value) || 0;
                const pesoMerma = parseFloat(document.querySelector('input[name="peso_merma"]').value) || 0;
                let resultado = 0;
                if (pesoNetoHoja !== 0) {
                    resultado = Math.round(pesoMerma / pesoNetoHoja);
                }
                const pzrmInput = document.querySelector('input[name="PZRM"]');
                if (!isNaN(resultado)) {
                    pzrmInput.value = resultado.toFixed(4);
                } else {
                    pzrmInput.value = '0';
                }
            }

            function togglePRMAuxInputs() {

                const auxDivisor = document.getElementById('aux-divisor-prm');
                const auxSumador = document.getElementById('aux-sumador-prm');
                const gridPrm = document.getElementById('grid-prm');
                
                
                const isHidden = auxDivisor.classList.contains('hidden');
                
                if (isHidden) {
                    auxDivisor.classList.remove('hidden');
                    auxSumador.classList.remove('hidden');
                    gridPrm.classList.add('grid-cols-3');
                } else {
                    auxDivisor.classList.add('hidden');
                    auxSumador.classList.add('hidden');

                    gridPrm.classList.remove('grid-cols-3');
                }
            }
        </script>

        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Costos de Material Prima</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="font-bold block mb-2">Costo por Kilo ($MXN):</label>
                    <input type="number" step="0.01" name="costo_kilo"
                        value="{{ $costo_kilo }}" placeholder="Ingrese el costo"
                        class="w-full border-gray-300 border rounded-md p-2" oninput="calcularPrecioPorKg()">
                </div>

                <div>
                    <label class="font-bold block mb-2">Tipo de Cambio:</label>
                    <input type="number" step="0.0001" name="TC" placeholder="Ingrese tipo de cambio"
                        value="{{ $TC }}" oninput="calcularPrecioPorKg()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>

                <div>
                    <label class="font-bold block mb-2">Costo Flete MP:</label>
                    <input type="number" step="0.01" name="costo_flete"
                        value="{{ $costo_flete }}" placeholder="Ingrese costo del flete"
                        class="w-full border-gray-300 border rounded-md p-2" oninput="calcularPrecioPorKg()">
                </div>

                <div>
                    <label class="font-bold block mb-2">Precio por KG ($):</label>
                    <input type="number" step="0.0001" name="precio_kg"
                        value="{{ $precio_kg}}" oninput="calcularCostoMP()"
                        class="w-full border-gray-300 border rounded-md p-2 bg-gray-50">
                </div>
                <div>
                    <label class="font-bold block mb-2">Costo Lámina ($):</label>
                    <input type="number" step="0.01" name="costo_lamina" placeholder="ingrese el costo de lamina"
                        value="{{ $costo_lamina }}" oninput="calcularPrecioLamina()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Tipo de Cambio:</label>
                    <input type="number" step="0.0001" name="TC_lamina" placeholder="ingrese tipo de cambio"
                        value="{{ $TC_lamina }}" oninput="calcularPrecioLamina()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Costo de Flete ($):</label>
                    <input type="number" step="0.0001" name="costo_flete_lamina" placeholder="Ingrese el costo de flete"
                        value="{{ $costo_flete_lamina }}" oninput="calcularPrecioLamina()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div>
                    <label class="font-bold block mb-2">Precio Lámina ($):</label>
                    <input type="number" step="0.0001" name="precio_lamina"
                        value="{{ $precio_lamina }}" oninput="calcularCostoMP()"
                        class="w-full border-gray-300 border rounded-md p-2">
                </div>
                <div class="md:col-span-4">
                    <label class="font-bold block mb-2 text-center">Sugerencias Costos MP:</label>
                    <textarea name="sugerencia_costos_mp" rows="4"
                        class="w-full border-gray-300 border rounded-md p-2 text-center resize-vertical"
                        placeholder="Escribe aquí tus observaciones o notas...">{{ $sugerencia_costos_mp }}</textarea>
                </div>
            </div>
        </div>
        <script>
            function calcularPrecioPorKg() {
                const costoKilo = parseFloat(document.querySelector('input[name="costo_kilo"]').value) || 0;
                const tipoCambio = parseFloat(document.querySelector('input[name="TC"]').value) || 0;
                const costoFlete = parseFloat(document.querySelector('input[name="costo_flete"]').value) || 0;
                const pesoTotal = parseFloat(document.querySelector('input[name="peso_total"]').value) || 1;
                const resultado = (costoKilo * tipoCambio) + (costoFlete / pesoTotal);
                document.querySelector('input[name="precio_kg"]').value = resultado.toFixed(4);
            }

            function calcularPrecioLamina() {
                const costoLamina = parseFloat(document.querySelector('input[name="costo_lamina"]').value) || 0;
                const coeficienteMerma = parseFloat(document.querySelector('input[name="coeficiente_merma"]').value) || 0;
                const TC = parseFloat(document.querySelector('input[name="TC_lamina"]').value) || 0;
                const fleteLamina = parseFloat(document.querySelector('input[name="costo_flete_lamina"]').value) || 0;
                const hojas = parseFloat(document.querySelector('input[name="cantidad_hojas"]').value) || 0;
                const resultado = (((costoLamina * (coeficienteMerma / 100)) + costoLamina) * TC) + (fleteLamina / hojas);
                document.querySelector('input[name="precio_lamina"]').value = resultado.toFixed(4);
            }
        </script>

        <!-- COSTOS DE PROCESOS -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Costos de Procesos</h2>

            <!-- Sección: Hojas del Pedido -->
            <div class="mb-6">
                <label class="font-bold block mb-2">Hojas del Pedido:</label>
                <input type="number" step="0.0001" name="hojas_del_pedido"
                    value="{{ $hojas_del_pedido}}"
                    class="w-full border-gray-300 border rounded-md p-2">
            </div>
            <!-- Máquina formado -->
            <div class="maquina-termoformado-group mb-4 p-4 ">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-md font-semibold text-gray-700">Proceso de Termoformado</h4>
                </div>

                <table class="w-full border-collapse border border-gray-400 text-center">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 p-2">Máquina</th>
                            <th class="border border-gray-300 p-2">No. de Personas</th>
                            <th class="border border-gray-300 p-2">Bajadas por Minuto</th>
                            <th class="border border-gray-300 p-2">Total Hojas por Turno</th>
                            <th class="border border-gray-300 p-2">Total Días (2 Turnos)</th>
                            <th class="border border-gray-300 p-2">Costo Termoformado ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2">
                                <select name="nombre_maquina_termoformado" class="w-full border rounded-md p-1">
                                    <option value="Máquina de Termoformado">Seleccione Máquina</option>
                                    <option value="TA-1" {{ $nombre_maquina_termoformado == 'TA-1' ? 'selected' : '' }}>TA-1</option>
                                    <option value="TA-2" {{ $nombre_maquina_termoformado == 'TA-2' ? 'selected' : '' }}>TA-2</option>
                                    <option value="TA-3" {{ $nombre_maquina_termoformado == 'TA-3' ? 'selected' : '' }}>TA-3</option>
                                    <option value="TA-4" {{ $nombre_maquina_termoformado == 'TA-4' ? 'selected' : '' }}>TA-4</option>
                                    <option value="Max-18" {{ $nombre_maquina_termoformado == 'Max-18' ? 'selected' : '' }}>Max-18</option>
                                    <option value="ILLIG 1" {{ $nombre_maquina_termoformado == 'ILLIG 1' ? 'selected' : '' }}>ILLIG 1</option>
                                    <option value="ILLIG 2" {{ $nombre_maquina_termoformado == 'ILLIG 2' ? 'selected' : '' }}>ILLIG 2</option>
                                    <option value="TCH-1" {{ $nombre_maquina_termoformado == 'TCH-1' ? 'selected' : '' }}>TCH-1</option>
                                    <option value="TCH-2" {{ $nombre_maquina_termoformado == 'TCH-2' ? 'selected' : '' }}>TCH-2</option>
                                    <option value="TCH-3" {{ $nombre_maquina_termoformado == 'TCH-3' ? 'selected' : '' }}>TCH-3</option>
                                    <option value="TCH-4" {{ $nombre_maquina_termoformado == 'TCH-4' ? 'selected' : '' }}>TCH-4</option>
                                    <option value="Monster" {{ $nombre_maquina_termoformado == 'Monster' ? 'selected' : '' }}>Monster</option>
                                    <option value="GF-1" {{ $nombre_maquina_termoformado == 'GF-1' ? 'selected' : '' }}>GF-1</option>
                                    <option value="GF-2" {{ $nombre_maquina_termoformado == 'GF-2' ? 'selected' : '' }}>GF-2</option>
                                    <option value="TA-1,TA-3" {{ $nombre_maquina_termoformado == 'TA-1,TA-3' ? 'selected' : '' }}>TA-1,TA-3</option>
                                    <option value="TA-1,TA-3,Max-18" {{ $nombre_maquina_termoformado == 'TA-1,TA-3,Max-18' ? 'selected' : '' }}>TA-1,TA-3,Max-18</option>
                                    <option value="TA-2,TA-4,TCH-1" {{ $nombre_maquina_termoformado == 'TA-2,TA-4,TCH-1' ? 'selected' : '' }}>TA-2,TA-4,TCH-1</option>
                                </select>

                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="no_personas_termoformado" step="1" value="{{ $no_personas_termoformado }}"
                                    oninput="calcularTotalHojasPorTurnoTermoformado(),calcularCostoInocuidad()" placeholder="Ingrese el número de personas" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="bajadas_por_minuto_termoformado" step="0.0001" value="{{ $bajadas_por_minuto_termoformado }}"
                                    oninput="calcularTotalHojasPorTurnoTermoformado()" placeholder="Ingrese bajadas por minuto" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="total_hojas_turno_termoformado" step="1" value="{{ $total_hojas_turno_termoformado }}"
                                    placeholder="calcular total de hojas" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="total_dias_turnos_termoformado" step="0.01" value="{{ $total_dias_turnos_termoformado }}"
                                    placeholder="calcular total de días" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="costo_termoformado" step="0.01" value="{{ $costo_termoformado }}"
                                    class="w-full border rounded-md p-1" placeholder="calcular costo">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <script>
                function calcularTotalHojasPorTurnoTermoformado() {
                    const bajadasPorMinuto = parseFloat(document.querySelector('input[name="bajadas_por_minuto_termoformado"]').value) || 0;
                    const totalHojasPorTurno = bajadasPorMinuto * 60 * 11; // 11 horas por turno
                    document.querySelector('input[name="total_hojas_turno_termoformado"]').value = totalHojasPorTurno.toFixed(0);
                    calcularTotalDiasTurnosTermoformado();
                }

                function calcularTotalDiasTurnosTermoformado() {
                    const hojasDelPedido = parseFloat(document.querySelector('input[name="hojas_del_pedido"]').value) || 0;
                    const totalHojasPorTurno = parseFloat(document.querySelector('input[name="total_hojas_turno_termoformado"]').value) || 1;
                    const totalDias = (hojasDelPedido / totalHojasPorTurno) / 2; // 2 turnos por día
                    document.querySelector('input[name="total_dias_turnos_termoformado"]').value = totalDias.toFixed(2);
                    calcularCostoTermoformado();
                }

                function calcularCostoTermoformado() {
                    const noPersonas = parseFloat(document.querySelector('input[name="no_personas_termoformado"]').value) || 0;
                    const costoPorPersonaPorDia = 560; // Costo fijo por persona por día
                    const totalHojasPorTurno = parseFloat(document.querySelector('input[name="total_hojas_turno_termoformado"]').value) || 1;
                    const costoTotal = (costoPorPersonaPorDia * noPersonas) / totalHojasPorTurno;
                    document.querySelector('input[name="costo_termoformado"]').value = costoTotal.toFixed(2);
                }
            </script>

            <!-- Máquina suaje -->
            <div class="maquina-suaje-group mb-4 p-4 ">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-md font-semibold text-gray-700">Proceso de Suaje</h4>
                </div>

                <table class="w-full border-collapse border border-gray-400 text-center">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 p-2">Máquina</th>

                            <th class="border border-gray-300 p-2">No. de Personas</th>
                            <th class="border border-gray-300 p-2">Bajadas por Minuto</th>
                            <th class="border border-gray-300 p-2">Total Hojas por Turno</th>
                            <th class="border border-gray-300 p-2">Total Piezas por Turno</th>
                            <th class="border border-gray-300 p-2">Total Días (2 Turnos)</th>
                            <th class="border border-gray-300 p-2">Costo Suaje ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2">
                                <select name="nombre_maquina_suaje" class="w-full border rounded-md p-1">
                                    <option value="Máquina de Suaje">Seleccione Máquina</option>
                                    <option value="SH1" {{ $nombre_maquina_suaje == 'SH1' ? 'selected' : '' }}>SH1</option>
                                    <option value="SH2" {{ $nombre_maquina_suaje == 'SH2' ? 'selected' : '' }}>SH2</option>
                                    <option value="SH3" {{ $nombre_maquina_suaje == 'SH3' ? 'selected' : '' }}>SH3</option>
                                    <option value="SH4" {{ $nombre_maquina_suaje == 'SH4' ? 'selected' : '' }}>SH4</option>
                                    <option value="Suajadora de golpe" {{ $nombre_maquina_suaje == 'Suajadora de golpe' ? 'selected' : '' }}>Suajadora de golpe</option>
                                    <option value="Router" {{ $nombre_maquina_suaje == 'Router' ? 'selected' : '' }}>Router</option>
                                </select>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="no_personas_suaje" step="1" value="{{ $no_personas_suaje }}"
                                    oninput="calcularTotalHojasPorTurnoSuaje(), calcularCostoInocuidad()" placeholder="Ingrese el número de personas" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="bajadas_por_minuto_suaje" step="0.0001" value="{{ $bajadas_por_minuto_suaje }}"
                                    oninput="calcularTotalHojasPorTurnoSuaje(), calcularCostoInocuidad()" placeholder="Ingrese bajadas por minuto" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="total_hojas_turno_suaje" step="1" value="{{ $total_hojas_turno_suaje }}"
                                    placeholder="calcular total de hojas" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="total_piezas_turno_suaje" step="1" value="{{ $total_piezas_turno_suaje }}"
                                    placeholder="calcular total de piezas" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="total_dias_turnos_suaje" step="0.0001" value="{{ $total_dias_turnos_suaje }}"
                                    placeholder="calcular total de días" class="w-full border rounded-md p-1">
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="number" name="costo_suaje" step="0.01" value="{{ $costo_suaje }}"
                                    placeholder="calcular costo suaje" class="w-full border rounded-md p-1">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <script>
                function calcularTotalHojasPorTurnoSuaje() {
                    const bajadasPorMinuto = parseFloat(document.querySelector('input[name="bajadas_por_minuto_suaje"]').value) || 0;
                    const totalHojasPorTurno = bajadasPorMinuto * 60 * 11; // 11 horas por turno
                    document.querySelector('input[name="total_hojas_turno_suaje"]').value = totalHojasPorTurno.toFixed(0);
                    calcularTotalPiezasPorTurnoSuaje();
                }

                function calcularTotalPiezasPorTurnoSuaje() {
                    const insertos = parseFloat(document.querySelector('input[name="insertos"]').value) || 1;
                    const totalHojasPorTurno = parseFloat(document.querySelector('input[name="total_hojas_turno_suaje"]').value) || 0;
                    const totalPiezas = totalHojasPorTurno * insertos;
                    document.querySelector('input[name="total_piezas_turno_suaje"]').value = totalPiezas.toFixed(0);
                    calcularTotalDiasTurnosSuaje();
                }

                function calcularTotalDiasTurnosSuaje() {
                    const hojasDelPedido = parseFloat(document.querySelector('input[name="hojas_del_pedido"]').value) || 0;
                    const insertos = parseFloat(document.querySelector('input[name="insertos"]').value) || 1;
                    const totalHojasPorTurno = parseFloat(document.querySelector('input[name="total_hojas_turno_suaje"]').value) || 1;
                    const totalDiasTurnos = ((hojasDelPedido / insertos) / totalHojasPorTurno) / 2; // 2 turnos por día
                    document.querySelector('input[name="total_dias_turnos_suaje"]').value = totalDiasTurnos.toFixed(4);
                    calcularCostoSuaje();
                }

                function calcularCostoSuaje() {
                    const noPersonas = parseFloat(document.querySelector('input[name="no_personas_suaje"]').value) || 0;
                    const costoPorPersonaPorDia = 560; // Costo fijo por persona por día
                    const totalHojasPorTurno = parseFloat(document.querySelector('input[name="total_hojas_turno_suaje"]').value) || 1;
                    const costoTotal = (costoPorPersonaPorDia * noPersonas) / totalHojasPorTurno;
                    document.querySelector('input[name="costo_suaje"]').value = costoTotal.toFixed(2);
                }
            </script>

            <!-- Sección: Costos de Procesos -->
            <div class="mb-4 p-4 ">
                <h3 class="text-lg font-semibold text-blue-700 mb-4">Costos de Procesos</h3>

                <table class="w-full border border-gray-400 text-sm text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-400 p-2 w-1/3 text-left">Concepto</th>
                            <th class="border border-gray-400 p-2 w-1/3">Costo ($)</th>
                            <th class="border border-gray-400 p-2 w-1/3">Costo por Hoja ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-400 p-2 text-left font-bold">Costo de Montaje</td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_montaje"
                                    value="{{ old('costo_montaje', $costeoRequisicion->costo_montaje) }}"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center" placeholder="Ingrese costo de montaje ($MXN)"
                                    oninput="calcularCostoMontaje2()">
                            </td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_montaje2"
                                    value="{{ old('costo_montaje2', $costeoRequisicion->costo_montaje2) }}" placeholder="calcular costo de montaje"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center">
                            </td>
                        </tr>

                        <tr>
                            <td class="border border-gray-400 p-2 text-left font-bold">Costo de amortización de herramentales</td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_amortizacion_herramentales"
                                    value="{{ old('costo_amortizacion_herramentales', $costeoRequisicion->costo_amortizacion_herramentales) }}"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center" placeholder="ingrese costo de amortización"
                                    oninput="calcularCostoAmortizacionHerramentales2()">
                            </td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_amortizacion_herramentales2"
                                    value="{{ old('costo_amortizacion_herramentales2', $costeoRequisicion->costo_amortizacion_herramentales2) }}"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center" placeholder="calcular costo de amortización">
                            </td>
                        </tr>

                        <tr>
                            <td class="border border-gray-400 p-2 text-left font-bold">Costo de E. eléctrica</td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_electricidad"
                                    value="{{ old('costo_electricidad', $costeoRequisicion->costo_electricidad) }}" placeholder="Ingrese costo de electricidad ($MXN)"
                                    oninput="calcularCostoEnergiaE2()" class="w-full border-gray-300 border rounded-md p-1 text-center">
                            </td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_electricidad2"
                                    value="{{ old('costo_electricidad2', $costeoRequisicion->costo_electricidad2) }}" placeholder="calcular costo de electricidad"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center">
                            </td>
                        </tr>

                        <tr>
                            <td class="border border-gray-400 p-2 text-left font-bold">Amortización maquinaria</td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="amortizacion_maquinaria"
                                    value="{{ old('amortizacion_maquinaria', $costeoRequisicion->amortizacion_maquinaria) }}" placeholder="Ingrese amortizacion maquinaria"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center" oninput="calcularCostoAmortizacionMaquinaria2()">
                            </td>
                            <td class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="amortizacion_maquinaria2"
                                    value="{{ old('amortizacion_maquinaria2', $costeoRequisicion->amortizacion_maquinaria2) }}"
                                    class="w-full border-gray-300 border rounded-md p-1 text-center" placeholder="calcular amortizacion maquinaria">
                            </td>
                        </tr>

                        <!-- Fila separada para totales -->
                        <tr class="font-semibold">
                            <td class="border border-gray-400 p-2 text-left font-bold">Costo de Fabricación</td>
                            <td colspan="2" class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_fabricacion"
                                    value="{{ old('costo_fabricacion', $costeoRequisicion->costo_fabricacion) }}" placeholder="calcular costo de fabricacion"
                                    class="w-full border-gray-300 border bg-gray-200 rounded-md p-1 text-center font-bold">
                            </td>
                        </tr>

                        <tr class="font-semibold">
                            <td class="border border-gray-400 p-2 text-left font-bold">Costo MP</td>
                            <td colspan="2" class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_mp"
                                    value="{{ old('costo_mp', $costeoRequisicion->costo_mp) }}" placeholder="calcular costo de material prima"
                                    class="w-full border-gray-300 border bg-gray-200 rounded-md p-1 text-center font-bold">
                            </td>
                        </tr>

                        <tr class="font-bold">
                            <td class="border border-gray-400 p-2 text-left font-bold">Costo sTotal</td>
                            <td colspan="2" class="border border-gray-400 p-2">
                                <input type="number" step="0.0001" name="costo_total_procesos"
                                    value="{{ old('costo_total_procesos', $costeoRequisicion->costo_total_procesos) }}" placeholder="calcular costo total"
                                    class="w-full border-gray-300 border bg-gray-200 rounded-md p-1 text-center font-bold">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            function calcularCostoMontaje2() {
                const costoMontaje = parseFloat(document.querySelector('input[name="costo_montaje"]').value) || 0;
                const hojas = parseFloat(document.querySelector('input[name="hojas_del_pedido"]').value) || 0;
                const resultado = costoMontaje / hojas;
                document.querySelector('input[name="costo_montaje2"]').value = resultado.toFixed(2);
            }

            function calcularCostoAmortizacionHerramentales2() {
                const costo = parseFloat(document.querySelector('input[name="costo_amortizacion_herramentales"]').value) || 0;
                const hojas = parseFloat(document.querySelector('input[name="hojas_del_pedido"]').value) || 0;
                const resultado = costo / hojas;
                document.querySelector('input[name="costo_amortizacion_herramentales2"]').value = resultado.toFixed(2);
            }

            function calcularCostoEnergiaE2() {
                //  ((11*(total dias 2turnos maquina1*2))+(11*(total dias 2turnos maquina1*2)))/((cantidad de hojas+(cantidad de hojas*coeficiente de merma))/insertos)
                const totalDiasMaquina1 = parseFloat(document.querySelector('input[name="total_dias_turnos_termoformado"]').value) || 0;
                const totalDiasMaquina2 = parseFloat(document.querySelector('input[name="total_dias_turnos_suaje"]').value) || 0;
                const cantidad_hojas = parseFloat(document.querySelector('input[name="cantidad_hojas"]').value) || 0;
                const coeficienteMerma = parseFloat(document.querySelector('input[name="coeficiente_merma"]').value) || 0;
                const insertos = parseFloat(document.querySelector('input[name="insertos"]').value) || 1;
                const parte1 = (11 * (totalDiasMaquina1 * 2));
                const parte2 = (11 * (totalDiasMaquina2 * 2));
                const parte3 = cantidad_hojas + (cantidad_hojas * (coeficienteMerma / 100));
                const parte4 = parte3 / insertos;
                const resultado = (parte1 + parte2) / parte4;
                document.querySelector('input[name="costo_electricidad2"]').value = resultado.toFixed(2);
            }

            function calcularCostoAmortizacionMaquinaria2() {
                const costo = parseFloat(document.querySelector('input[name="amortizacion_maquinaria"]').value) || 0;
                const hojastermoformado = parseFloat(document.querySelector('input[name="total_hojas_turno_termoformado"]').value) || 0;
                const hojasuaje = parseFloat(document.querySelector('input[name="total_hojas_turno_suaje"]').value) || 0;
                const resultado = costo / ((hojastermoformado + hojasuaje) / 2);
                document.querySelector('input[name="amortizacion_maquinaria2"]').value = resultado.toFixed(2);
                calcularCostoFabricacion();
            }

            function calcularCostoFabricacion() {
                const costoTermoformado = parseFloat(document.querySelector('input[name="costo_termoformado"]').value) || 0;
                const costoSuaje = parseFloat(document.querySelector('input[name="costo_suaje"]').value) || 0;
                const costoMontaje2 = parseFloat(document.querySelector('input[name="costo_montaje2"]').value) || 0;
                const costoAmortizacionHerramentales2 = parseFloat(document.querySelector('input[name="costo_amortizacion_herramentales2"]').value) || 0;
                const costoElectricidad2 = parseFloat(document.querySelector('input[name="costo_electricidad2"]').value) || 0;
                const amortizacionMaquinaria2 = parseFloat(document.querySelector('input[name="amortizacion_maquinaria2"]').value) || 0;
                const resultado = costoTermoformado + costoSuaje + costoMontaje2 + costoAmortizacionHerramentales2 + costoElectricidad2 + amortizacionMaquinaria2;
                document.querySelector('input[name="costo_fabricacion"]').value = resultado.toFixed(2);
                calcularCostoMP();
            }

            function calcularCostoMP() {
                const pesoBrutoHoja = parseFloat(document.querySelector('input[name="peso_bruto_hoja"]').value) || 0;
                const precioKg = parseFloat(document.querySelector('input[name="precio_kg"]').value) || 0;
                const precioLamina = parseFloat(document.querySelector('input[name="precio_lamina"]').value) || 0;
                const resultado = precioKg >= 0.01 ? (precioKg * pesoBrutoHoja) : precioLamina;
                document.querySelector('input[name="costo_mp"]').value = resultado.toFixed(2);
                calcularCostoTotal();
            }

            function calcularCostoTotal() {
                const costoFabricacion = parseFloat(document.querySelector('input[name="costo_fabricacion"]').value) || 0;
                const costoMP = parseFloat(document.querySelector('input[name="costo_mp"]').value) || 0;
                const resultado = costoFabricacion + costoMP;
                document.querySelector('input[name="costo_total_procesos"]').value = resultado.toFixed(2);
                document.querySelector('input[name="resumen_costo_procesos"]').value = resultado.toFixed(2);
            }
        </script>

        <!-- SECCIÓN: EMPAQUE -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Empaque</h2>
            <!-- Tabla de Características de Empaque -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-blue-700 mb-2">Características de Empaque</h3>
                <table class="w-full border-collapse border border-gray-400 text-center mb-4">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 p-2">Tipo de Estiba</th>
                            <th class="border border-gray-300 p-2">Cajas Corrugado</th>
                            <th class="border border-gray-300 p-2">Bolsa Plástico</th>
                            <th class="border border-gray-300 p-2">Esquineros</th>
                            <th class="border border-gray-300 p-2">Liner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2">
                                <input type="text" name="tipo_estiba" class="w-full border-gray-300 border rounded-md p-1 {{ $tipo_estiba !== 'Sin estiba' ? 'bg-blue-100 text-blue-800 font-bold' : '' }}"
                                    value="{{ $tipo_estiba }}" disabled>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="text" name="cajas_corrugado" class="w-full border-gray-300 border rounded-md p-1 {{ $cajas_corrugado == 1 ? 'bg-blue-100 text-blue-800 font-bold' : '' }}"
                                    value="{{ $cajas_corrugado == 1 ? 'Cajas de corrugado' : ($cajas_corrugado == 0 ? 'Sin cajas de corrugado' : $cajas_corrugado) }}" disabled>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="text" name="bolsa_plastico" class="w-full border-gray-300 border rounded-md p-1 {{ $bolsa_plastico == 1 ? 'bg-blue-100 text-blue-800 font-bold' : '' }}"
                                    value="{{ $bolsa_plastico == 1 ? 'Bolsa de plástico' : ($bolsa_plastico == 0 ? 'Sin bolsa de plástico' : $bolsa_plastico) }}" disabled>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="text" name="esquineros" class="w-full border-gray-300 border rounded-md p-1 {{ $esquineros == 1 ? 'bg-blue-100 text-blue-800 font-bold' : '' }}"
                                    value="{{ $esquineros == 1 ? 'Esquineros' : ($esquineros == 0 ? 'Sin esquineros' : $esquineros) }}" disabled>
                            </td>
                            <td class="border border-gray-300 p-2">
                                <input type="text" name="liner" class="w-full border-gray-300 border rounded-md p-1 {{ $liner == 1 ? 'bg-blue-100 text-blue-800 font-bold' : '' }}"
                                    value="{{ $liner == 1 ? 'Liner' : ($liner == 0 ? 'Sin liner' : $liner) }}" disabled>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="font-bold block mb-2">Corrugado:</label>
                    <select name="costo_corrugado" class="w-full border border-gray-300 rounded-md p-1" oninput=" asignarCajasPorTarima(), calcularTotalCostoCorrugado(), calcularCostoTotalEmpaque()">
                        <option value="0">Seleccione una opción</option>
                        <option value="20.10" {{ old('costo_corrugado', $costeoRequisicion->costo_corrugado ?? '') == '20.10' ? 'selected' : '' }}>500x390x300 ----- $20.10</option>
                        <option value="22.60" {{ old('costo_corrugado', $costeoRequisicion->costo_corrugado ?? '') == '22.60' ? 'selected' : '' }}>500x500x200 ----- $22.60</option>
                        <option value="26.33" {{ old('costo_corrugado', $costeoRequisicion->costo_corrugado ?? '') == '26.33' ? 'selected' : '' }}>540x500x350 ----- $26.33</option>
                        <option value="28.97" {{ old('costo_corrugado', $costeoRequisicion->costo_corrugado ?? '') == '28.97' ? 'selected' : '' }}>540x500x360 ----- $28.97</option>
                        <option value="32.14" {{ old('costo_corrugado', $costeoRequisicion->costo_corrugado ?? '') == '32.14' ? 'selected' : '' }}>590x400x610 ----- $32.14</option>
                    </select>
                </div>
                <div>
                    <label class="font-bold block mb-2">Bolsa:</label>
                    <select name="costo_bolsa" class="w-full border border-gray-300 rounded-md p-1" oninput="asignarBolsa(), calcularTotalCostoBolsas()">
                        <option value="0">Seleccione una opción</option>
                        <option value="1.28" {{ old('costo_bolsa', $costeoRequisicion->costo_bolsa) == '1.28' ? 'selected' : '' }}>33x92 ----- $1.28</option>
                        <option value="1.42" {{ old('costo_bolsa', $costeoRequisicion->costo_bolsa) == '1.42' ? 'selected' : '' }}>37x92 ----- $1.42</option>
                        <option value="9.14" {{ old('costo_bolsa', $costeoRequisicion->costo_bolsa) == '9.14' ? 'selected' : '' }}>Doble bolsa ----- $9.14</option>
                        <option value="4.57" {{ old('costo_bolsa', $costeoRequisicion->costo_bolsa) == '4.57' ? 'selected' : '' }}>Estándar ----- $4.57</option>
                    </select>
                </div>
                <div>
                    <label class="font-bold block mb-2">Tarima:</label>
                    <select name="costo_tarima" class="w-full border border-gray-300 rounded-md p-1" oninput="asignarTarima(), calcularTotalCostoTarimas()">
                        <option value="0">Seleccione una opción</option>
                        <option value="122.22" {{ 
                            ($esquineros == 0 && $liner == 0) ? 'selected' : '' 
                        }}>Tarima ----- $122.22</option>
                        <option value="178.22" {{ 
                            ($esquineros == 1 && $liner == 0) ? 'selected' : '' 
                        }}>Tarima+esquineros ----- $178.22</option>
                        <option value="146.15" {{ 
                            ($esquineros == 0 && $liner == 1) ? 'selected' : '' 
                        }}>Tarima+liner ----- $146.15</option>
                        <option value="202.15" {{ 
                            ($esquineros == 1 && $liner == 1) ? 'selected' : '' 
                        }}>Tarima+liner+esquineros ----- $202.15</option>
                    </select>
                </div>
            </div>
            <table class="w-full border-collapse border border-gray-400 text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Cajas</th>
                        <th class="border border-gray-300 p-2">Inputs Cajas</th>
                        <th class="border border-gray-300 p-2">Bolsas</th>
                        <th class="border border-gray-300 p-2">Inputs Bolsas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="border border-gray-300 p-2">Piezas por Caja</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="piezas_por_caja" step="1" class="w-full border-gray-300 border rounded-md p-1"
                                value="{{ old('piezas_por_caja', $costeoRequisicion->piezas_por_caja) }}">
                        </td>
                        <th class="border border-gray-300 p-2">
                            <button type="button" onclick="togglePiezasPorBolsa()" 
                                class="font-medium px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">
                                Piezas por Bolsa
                            </button>
                        </th>
                        <td class="border border-gray-300 p-2">
                            <div class="grid gap-2" id="grid-piezas-bolsa">
                                <input type="number" name="piezas_por_bolsa" id="input-piezas-bolsa" step="1" class="w-full border-gray-300 border rounded-md p-1"
                                    value="{{ old('piezas_por_bolsa', $costeoRequisicion->piezas_por_bolsa) }}" oninput="calcularPiezasPorCaja()">
                                <input type="number" name="aux_piezas_por_bolsa" id="aux-piezas-bolsa" step="1" class="w-full border-gray-300 border rounded-md p-1 hidden"
                                    placeholder="Bolsas X caja" value="{{ old('aux_piezas_por_bolsa', $costeoRequisicion->aux_piezas_por_bolsa) }}" oninput="calcularPiezasPorCaja()">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="border border-gray-300 p-2">Cajas por Tarima</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="cajas_por_tarima" step="1" class="w-full border-gray-300 border rounded-md p-1" oninput="calcularTotalTarimasCajas(), calcularTotalCostoTarimas()"
                                value="{{ old('cajas_por_tarima', $costeoRequisicion->cajas_por_tarima) }}">
                        </td>
                        <th class="border border-gray-300 p-2">Bolsas por Tarima</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="bolsas_por_tarima" step="1" class="w-full border-gray-300 border rounded-md p-1" oninput="calcularTotalTarimasBolsas(), calcularTotalCostoTarimas()"
                                value="{{ old('bolsas_por_tarima', $costeoRequisicion->bolsas_por_tarima) }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="border border-gray-300 p-2">Total Cajas</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="total_cajas" step="1" class="w-full border-gray-300 border rounded-md p-1" oninput="calcularTotalTarimasCajas(), calcularTotalCostoCorrugado(),calcularTotalCostoTarimas()"
                                value="{{ old('total_cajas', $costeoRequisicion->total_cajas) }}">
                        </td>
                        <th class="border border-gray-300 p-2">Total Bolsas</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="total_bolsas" step="1" class="w-full border-gray-300 border rounded-md p-1" oninput="calcularTotalTarimasBolsas(), calcularTotalCostoBolsas(),calcularTotalCostoTarimas()"
                                value="{{ old('total_bolsas', $costeoRequisicion->total_bolsas) }}">
                        </td>
                    </tr>
                    <tr>
                        <th class="border border-gray-300 p-2">Tarimas Totales Cajas</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="tarimas_totales_cajas" step="0.01" class="w-full border-gray-300 border rounded-md p-1"
                                value="{{ old('tarimas_totales_cajas', $costeoRequisicion->tarimas_totales_cajas) }}" oninput="calcularTotalCostoTarimas()">
                        </td>
                        <th class="border border-gray-300 p-2">Tarimas Totales Bolsas</th>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="tarimas_totales_bolsas" step="0.01" class="w-full border-gray-300 border rounded-md p-1"
                                value="{{ old('tarimas_totales_bolsas', $costeoRequisicion->tarimas_totales_bolsas) }}" oninput="calcularTotalCostoTarimas()">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>
            function calcularPiezasPorCaja() {
                const piezas = parseInt(document.querySelector('input[name="piezas_por_bolsa"]').value) || 0;
                const mult = parseInt(document.querySelector('input[name="aux_piezas_por_bolsa"]').value) || 0;
                const resultado = piezas * mult;
                document.querySelector('input[name="piezas_por_caja"]').value = resultado;
                calcularBolsasPorTarima();
                calcularTotalCajas();
                calcularTotalBolsas();
                calcularTotalTarimasCajas();
                calcularTotalTarimasBolsas();
                calcularTotalCostoCorrugado();
                calcularTotalCostoBolsas();
                calcularCostoTotalEmpaque();
            }

            function calcularBolsasPorTarima() {
                const cajas_por_tarima = parseInt(document.querySelector('input[name="cajas_por_tarima"]').value) || 0;
                const mult = parseInt(document.querySelector('input[name="aux_piezas_por_bolsa"]').value) || 0;
                const resultado = cajas_por_tarima * mult;
                document.querySelector('input[name="bolsas_por_tarima"]').value = Math.ceil(resultado);
            }

            function calcularTotalBolsas() {
                const totalcajas = parseInt(document.querySelector('input[name="total_cajas"]').value) || 0;
                const mult = parseInt(document.querySelector('input[name="aux_piezas_por_bolsa"]').value) || 0;
                const resultado = totalcajas * mult;
                document.querySelector('input[name="total_bolsas"]').value = resultado;
            }

            function calcularTotalCajas() {
                const moq = parseInt(document.querySelector('input[name="lote_compra"]').value) || 0;
                const piezasPorCaja = parseInt(document.querySelector('input[name="piezas_por_caja"]').value) || 0;
                const resultado = piezasPorCaja > 0 ? Math.ceil(moq / piezasPorCaja) : 0;
                document.querySelector('input[name="total_cajas"]').value = resultado;
            }

            function calcularTotalTarimasCajas() {
                const totalcajas = parseInt(document.querySelector('input[name="total_cajas"]').value) || 0;
                const cajas = parseInt(document.querySelector('input[name="cajas_por_tarima"]').value) || 0;
                const resultado = cajas > 0 ? totalcajas / cajas : 0;
                document.querySelector('input[name="tarimas_totales_cajas"]').value = resultado.toFixed(2);

            }

            function asignarCajasPorTarima() {
                const costoCorrugado = parseFloat(document.querySelector('select[name="costo_corrugado"]').value) || 0;

                // Mapeo de costos de corrugado a cajas por tarima y texto
                const cajasPorTarimaMap = {
                    20.10: {
                        cajas: 24,
                        texto: "500x390x300 con costo de $20.10"
                    },
                    22.60: {
                        cajas: 24,
                        texto: "500x500x200 con costo de $22.60"
                    },
                    26.33: {
                        cajas: 16,
                        texto: "540x500x350 con costo de $26.33"
                    },
                    28.97: {
                        cajas: 16,
                        texto: "540x500x360 con costo de $28.97"
                    },
                    32.14: {
                        cajas: 15,
                        texto: "590x400x610 con costo de $32.14"
                    }
                };

                let cajasPorTarima = 0;
                let textoCorrugado = '';
                if (costoCorrugado > 0 && cajasPorTarimaMap[costoCorrugado]) {
                    cajasPorTarima = cajasPorTarimaMap[costoCorrugado].cajas;
                    textoCorrugado = cajasPorTarimaMap[costoCorrugado].texto;
                }

                document.querySelector('input[name="cajas_por_tarima"]').value = cajasPorTarima;
                document.querySelector('input[name="caja_corrugado_copia"]').value = textoCorrugado;
            }

            function calcularTotalTarimasBolsas() {
                const totalBolsas = parseInt(document.querySelector('input[name="total_bolsas"]').value) || 0;
                const bolsas = parseInt(document.querySelector('input[name="bolsas_por_tarima"]').value) || 0;
                const resultado = bolsas > 0 ? (totalBolsas / bolsas) : 0;
                document.querySelector('input[name="tarimas_totales_bolsas"]').value = resultado.toFixed(2);
                calcularCostoTotalEmpaque();
            }

            function asignarBolsa() {
                const costoBolsa = parseFloat(document.querySelector('select[name="costo_bolsa"]').value) || 0;
                // Mapeo de costos de bolsa a texto
                const bolsaMap = {
                    1.28: "33x92 con costo de $1.28",
                    1.42: "37x92 con costo de $1.42",
                    9.14: "Doble bolsa con costo de $9.14",
                    4.57: "Estándar con costo de $4.57"
                };

                let textoBolsa = '';
                if (costoBolsa > 0 && bolsaMap[costoBolsa]) {
                    textoBolsa = bolsaMap[costoBolsa];
                }

                document.querySelector('input[name="caja_bolsa_copia"]').value = textoBolsa;
            }

            function asignarTarima() {
                const costoTarima = parseFloat(document.querySelector('select[name="costo_tarima"]').value) || 0;
                // Mapeo de costos de tarima a texto
                const tarimaMap = {
                    122.22: "Tarima con costo de $122.22",
                    178.22: "Tarima+esquineros con costo de $178.22",
                    146.15: "Tarima+liner con costo de $146.15",
                    202.15: "Tarima+liner+esquineros con costo de $202.15"
                };

                let textoTarima = '';
                if (costoTarima > 0 && tarimaMap[costoTarima]) {
                    textoTarima = tarimaMap[costoTarima];
                }
                document.querySelector('input[name="caja_tarima_copia"]').value = textoTarima;
                document.querySelector('input[name="total_tarima"]').value = costoTarima;
            }

            function calcularTotalCostoTarimas() {
                const costoTarima = parseFloat(document.querySelector('select[name="costo_tarima"]').value) || 0;
                const totalCajas = parseFloat(document.querySelector('input[name="tarimas_totales_cajas"]').value) || 0;
                const totalBolsas = parseFloat(document.querySelector('input[name="tarimas_totales_bolsas"]').value) || 0;
                const totalCajasRounded = Math.ceil(totalCajas);
                const totalBolsasRounded = Math.ceil(totalBolsas);
                const resultado = totalCajas >= 0.01 ? (costoTarima * totalCajasRounded) : (costoTarima * totalBolsasRounded);
                document.querySelector('input[name="total_tarima"]').value = resultado.toFixed(2);
                calcularCostoTotalEmpaque();
            }

            function calcularTotalCostoCorrugado() {
                const costoCorrugado = parseFloat(document.querySelector('select[name="costo_corrugado"]').value) || 0;
                const totalCajas = parseInt(document.querySelector('input[name="total_cajas"]').value) || 0;
                const resultado = costoCorrugado * totalCajas;
                document.querySelector('input[name="total_corrugado"]').value = resultado.toFixed(2);
                calcularCostoTotalEmpaque();
            }

            function calcularTotalCostoBolsas() {
                const costoBolsa = parseFloat(document.querySelector('select[name="costo_bolsa"]').value) || 0;
                const totalBolsas = parseInt(document.querySelector('input[name="total_bolsas"]').value) || 0;
                const resultado = costoBolsa * totalBolsas;
                document.querySelector('input[name="total_bolsa"]').value = resultado.toFixed(2);
                calcularCostoTotalEmpaque();
            }

            function calcularCostoTotalEmpaque() {
                const totalTarima = parseFloat(document.querySelector('input[name="total_tarima"]').value) || 0;
                const totalBolsa = parseFloat(document.querySelector('input[name="total_bolsa"]').value) || 0;
                const totalCorrugado = parseFloat(document.querySelector('input[name="total_corrugado"]').value) || 0;
                const resultado = totalTarima + totalBolsa + totalCorrugado;
                document.querySelector('input[name="costo_empaque_total"]').value = resultado.toFixed(2);
                document.querySelector('input[name="resumen_costo_empaque"]').value = resultado.toFixed(2);
            }

            function togglePiezasPorBolsa() {
                // Obtener los elementos
                const auxBolsa = document.getElementById('aux-piezas-bolsa');
                const gridBolsa = document.getElementById('grid-piezas-bolsa');
                
                // Verificar si está oculto
                const isHidden = auxBolsa.classList.contains('hidden');
                
                if (isHidden) {
                    // Mostrar input auxiliar
                    auxBolsa.classList.remove('hidden');
                    // Cambiar a grid de 2 columnas
                    gridBolsa.classList.add('grid-cols-2');
                } else {
                    // Ocultar input auxiliar
                    auxBolsa.classList.add('hidden');
                    // Cambiar a grid de 1 columna (ocupa todo el espacio)
                    gridBolsa.classList.remove('grid-cols-2');
                }
            }
        </script>

        <!-- SECCIÓN: COSTOS DE EMPAQUE -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Costos de Empaque</h2>

            <table class="w-full border-collapse border border-gray-400 text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Concepto</th>
                        <th class="border border-gray-300 p-2">Costo</th>
                        <th class="border border-gray-300 p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">Corrugado</td>
                        <td class="border border-gray-300 p-2">
                            <input type="text" class="w-full border border-gray-300 rounded-md p-1 bg-gray-100"
                                name="caja_corrugado_copia" readonly>
                        </td>
                        <td class="border border-gray-300 p-2 text-gray-600">
                            <input type="number" step="0.01" class="w-full border border-gray-300 rounded-md p-1" name="total_corrugado"
                                value="{{ old('total_corrugado', $costeoRequisicion->total_corrugado)}}" oninput="calcularCostoTotalEmpaque()">
                        </td>
                    </tr>
                    <tr>
                        <td class=" border border-gray-300 p-2 font-medium">Bolsa</td>
                        <td class="border border-gray-300 p-2">
                            <input type="text" class="w-full border border-gray-300 rounded-md p-1 bg-gray-100"
                                name="caja_bolsa_copia" readonly>
                        </td>
                        <td class="border border-gray-300 p-2 text-gray-600">
                            <input type="number" step="0.01" class="w-full border border-gray-300 rounded-md p-1" name="total_bolsa"
                                value="{{ old('total_bolsa', $costeoRequisicion->total_bolsa)}}" oninput="calcularCostoTotalEmpaque()">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">Tarima</td>
                        <td class="border border-gray-300 p-2">
                            <input type="text" class="w-full border border-gray-300 rounded-md p-1 bg-gray-100"
                                name="caja_tarima_copia" readonly>
                        </td>
                        <td class="border border-gray-300 p-2 text-gray-600">
                            <input type="number" step="0.01" class="w-full border border-gray-300 rounded-md p-1" name="total_tarima"
                                value="{{ old('total_tarima', $costeoRequisicion->total_tarima)}}" oninput="calcularCostoTotalEmpaque()">
                        </td>
                    </tr>
                    <tr class="bg-gray-100 font-semibold">
                        <td class="border border-gray-300 p-2 text-right" colspan="2">Costos Totales de Empaque</td>
                        <td class="border border-gray-300 p-2 text-blue-600">
                            <input type="number" step="0.01" class="w-full border border-gray-300 rounded-md p-1" name="costo_empaque_total"
                                value="{{ old('costo_empaque_total', $costeoRequisicion->costo_empaque_total)}}">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: COSTOS ADICIONALES -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Costos de Procesos Adicionales</h2>

            <table class="w-full border-collapse border border-gray-400 text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Concepto</th>
                        <th class="border border-gray-300 p-2">Factores de Cálculo</th>
                        <th class="border border-gray-300 p-2">Valor ($MXN)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">Proceso de inocuidad</td>
                        <td class="border border-gray-300 p-2">
                            <input type="text" name="proceso_de_inocuidad" class="w-full border border-gray-300 rounded-md p-1 text-center" value="{{ $proceso_de_inocuidad == 1 ? 'Sí' : ($proceso_de_inocuidad == 0 ? 'No' : $proceso_de_inocuidad) }}" disabled>
                        </td>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const procesoInocuidad = "{{ $proceso_de_inocuidad }}";
                                const costoInocuidadInput = document.querySelector('input[name="costo_inocuidad"]');
                                if (procesoInocuidad == 0 && costoInocuidadInput) {
                                    costoInocuidadInput.value = '';
                                    costoInocuidadInput.disabled = true;
                                }
                            });
                        </script>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="costo_inocuidad" class="w-full border border-gray-300 rounded-md p-1 text-center" placeholder="$0.00"
                                value="{{old('costo_inocuidad', $costeoRequisicion->costo_inocuidad)}}">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">Tipo de pared</td>
                        <td class="border border-gray-300 p-2">
                            <input type="text" value="{{$pared}}" class="w-full border border-gray-300 rounded-md p-1 text-center" disabled>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="costo_pared" class="w-full border border-gray-300 rounded-md p-1 text-center" placeholder="$0.00"
                                value="{{old('costo_pared', $costeoRequisicion->costo_pared)}}"
                                onchange="document.querySelector('input[name=&quot;resumen_costo_polipropileno&quot;]').value = this.value" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">Aplicación de estaticida</td>
                        <td class="border border-gray-300 p-2">
                            <div class="grid gap-2" id="grid-estaticida">
                                <select class="w-full rounded-md p-1 border-blue-600 bg-blue-500 font-medium text-white [&>option]:bg-white [&>option]:text-gray-900" name="aplicacion_estaticida" onchange="toggleEstaticidaInputs()">
                                    <option value="no" {{ old('aplicacion_estaticida', $costeoRequisicion->aplicacion_estaticida ?? 'no') == 'no' ? 'selected' : '' }}>No</option>
                                    <option value="si" {{ old('aplicacion_estaticida', $costeoRequisicion->aplicacion_estaticida ?? 'no') == 'si' ? 'selected' : '' }}>Sí</option>
                                </select>
                                <script>
                                    function toggleEstaticidaInputs() {
                                        const auxPersonas = document.getElementById('aux-personas-estaticida');
                                        const auxPiezas = document.getElementById('aux-piezas-estaticida');
                                        const gridEstaticida = document.getElementById('grid-estaticida');
                                        const selectEstaticida = document.querySelector('select[name="aplicacion_estaticida"]');
                                        const costoEstaticidaInput = document.getElementById('costo-estaticida-total');
                                        
                                        const isNo = selectEstaticida.value === 'no';
                                        
                                        if (isNo) {
                                            // Ocultar inputs auxiliares
                                            auxPersonas.classList.add('hidden');
                                            auxPiezas.classList.add('hidden');
                                            // Cambiar a grid de 1 columna
                                            gridEstaticida.classList.remove('grid-cols-3');
                                            // Deshabilitar costo total
                                            costoEstaticidaInput.disabled = true;
                                            costoEstaticidaInput.value = '';
                                            document.querySelector('input[name="resumen_costo_estaticidad"]').value = '';
                                        } else {
                                            // Mostrar inputs auxiliares
                                            auxPersonas.classList.remove('hidden');
                                            auxPiezas.classList.remove('hidden');
                                            // Cambiar a grid de 3 columnas
                                            gridEstaticida.classList.add('grid-cols-3');
                                            // Habilitar costo total
                                            costoEstaticidaInput.disabled = false;
                                        }
                                    }

                                    // Ejecutar al cargar la página para establecer el estado inicial
                                    //document.addEventListener('DOMContentLoaded', function() {
                                    //    toggleEstaticidaInputs();
                                    //});
                                </script>
                                <input type="number" step="1" name="no_personas_estaticida" id="aux-personas-estaticida" value="{{ old('no_personas_estaticida', $costeoRequisicion->no_personas_estaticida) }}" placeholder="Ingrese No. de personas"
                                    class="w-full border border-gray-300 rounded-md p-1 hidden" oninput="calcularEstaticida()">
                                <input type="number" step="1" name="piezas_por_hora_estaticida" id="aux-piezas-estaticida" value="{{ old('piezas_por_hora_estaticida', $costeoRequisicion->piezas_por_hora_estaticida) }}" placeholder="Ingrese Piezas x hora"
                                    class="w-full border border-gray-300 rounded-md p-1 hidden" oninput="calcularEstaticida()">
                            </div>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="costo_estaticida_total" id="costo-estaticida-total" class="w-full border border-gray-300 rounded-md p-1 text-center" placeholder="$0.00"
                                value="{{old('costo_estaticida_total', $costeoRequisicion->costo_estaticida_total)}}"
                                oninput="document.querySelector('input[name=&quot;resumen_costo_estaticidad&quot;]').value = this.value">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 p-2 font-medium">Maquila</td>
                        <td class="border border-gray-300 p-2">
                            <div class="grid grid-cols-1 gap-2">
                                <select class="w-full border border-gray-300 rounded-md p-1" name="maquila" onchange="toggleMaquilaInput()">
                                    <option value="no" {{ old('maquila', $costeoRequisicion->maquila ?? 'no') == 'no' ? 'selected' : '' }}>No</option>
                                    <option value="si" {{ old('maquila', $costeoRequisicion->maquila ?? 'no') == 'si' ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="costo_maquila_total" id="costo-maquila-total" class="w-full border border-gray-300 rounded-md p-1 text-center" placeholder="$0.00"
                                value="{{old('costo_maquila_total', $costeoRequisicion->costo_maquila_total)}}"
                                oninput="document.querySelector('input[name=&quot;resumen_costo_maquila&quot;]').value = this.value; calcularCostosUnit();">
                        </td>
                    </tr>
                    <script>
                        function toggleMaquilaInput() {
                            const selectMaquila = document.querySelector('select[name="maquila"]');
                            const costoMaquilaInput = document.getElementById('costo-maquila-total');
                            
                            if (selectMaquila.value === 'no') {
                                costoMaquilaInput.disabled = true;
                                costoMaquilaInput.value = '';
                                document.querySelector('input[name="resumen_costo_maquila"]').value = '';
                            } else {
                                costoMaquilaInput.disabled = false;
                            }
                        }

                        // Ejecutar al cargar la página para establecer el estado inicial
                        document.addEventListener('DOMContentLoaded', function() {
                            toggleMaquilaInput();
                        });
                    </script>
                </tbody>
            </table>
        </div>
        <script>
            function calcularCostoInocuidad() {
                const procesoInocuidad = "{{$proceso_de_inocuidad }}";
                if (procesoInocuidad == 1) {

                    const personas = parseInt(document.querySelector('input[name="no_personas_termoformado"]').value) || 0;
                    const precioBataBlanca = 40;
                    const guantes = 3.2;
                    const cubreBocas = 3.2;
                    const gel = 55;
                    const costoInocuidadXturno = (precioBataBlanca * personas) + (guantes * 5) + (cubreBocas * 2) + (gel * .25);
                    const total_dias_turnos_termoformado = parseFloat(document.querySelector('input[name="total_dias_turnos_termoformado"]').value) || 0;
                    const rem = costoInocuidadXturno * (total_dias_turnos_termoformado * 2);

                    const personasSuaje = parseInt(document.querySelector('input[name="no_personas_suaje"]').value) || 0;
                    const costoInocuidadXturnoSuaje = (precioBataBlanca * personasSuaje) + (guantes * 5) + (cubreBocas * 2) + (gel * .25);
                    const total_dias_turnos_suaje = parseFloat(document.querySelector('input[name="total_dias_turnos_suaje"]').value) || 0;
                    const emilia = costoInocuidadXturnoSuaje * (total_dias_turnos_suaje * 2);

                    const limpieza = 20 * (total_dias_turnos_termoformado + total_dias_turnos_suaje);

                    const resultado = rem + emilia + limpieza;
                    document.querySelector('input[name="costo_inocuidad"]').value = resultado.toFixed(4);
                    document.querySelector('input[name="resumen_costo_inocuidad"]').value = resultado.toFixed(4);
                    calcularCostosUnit();
                }
            }

            function calcularParedMedia() {
                const pared = "{{ $pared }}"; // Obtener el valor de $pared desde el servidor
                if (pared !== "Media") {
                    return; // Salir si $pared no es "Media"
                }

                const tablaPolipropileno48 = 1219.2;
                const tablaPolipropileno96 = 2438.4;

                const hoja_ancho = parseFloat(document.querySelector('input[name="hoja_ancho"]').value) || 0;
                const hoja_avance = parseFloat(document.querySelector('input[name="hoja_avance"]').value) || 0;

                const rem = Math.floor(tablaPolipropileno48 / hoja_ancho);
                const ram = Math.floor(tablaPolipropileno96 / hoja_avance);
                const cantidadCortes = rem * ram;
                const tablaPolipropileno = 2782.17;
                const emilia = tablaPolipropileno / cantidadCortes;
                cantidad_hojas = parseInt(document.querySelector('input[name="cantidad_hojas"]').value) || 0;
                const resultado = (cantidad_hojas / 250) * (emilia + 20);
                document.querySelector('input[name="costo_pared"]').value = resultado.toFixed(4);
                document.querySelector('input[name="resumen_costo_polipropileno"]').value = resultado.toFixed(4);
                calcularCostosUnit();
            }

            function calcularEstaticida() {
                const costoDiarioPersona = 450;
                const estaticidaPorPieza = .53;
                const personas = parseInt(document.querySelector('input[name="no_personas_estaticida"]').value) || 0;
                const piezasPorHora = parseInt(document.querySelector('input[name="piezas_por_hora_estaticida"]').value) || 1;
                const piezasPorTurno = piezasPorHora * 11; // 11 horas por turno
                const mo = (personas * costoDiarioPersona) / piezasPorTurno;
                const resultado = mo + estaticidaPorPieza;
                document.querySelector('input[name="costo_estaticida_total"]').value = resultado.toFixed(4);
                document.querySelector('input[name="resumen_costo_estaticidad"]').value = resultado.toFixed(4);
                calcularCostosUnit();
            }
        </script>

        <!-- COSTEO DE HERRAMENTALES -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Costeo Herramentales</h2>

            <table class="table-auto w-full border border-gray-300 text-sm text-center mb-6">
                <thead class="bg-gray-100">
                    <tr class="border-b font-semibold">
                        <th class="px-2 py-1">Medidas</th>
                        <th class="px-2 py-1">Ajuste ''</th>
                        <th class="px-2 py-1">Medida bloque</th>
                        <th class="px-2 py-1">Kilos</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="border-b h-14">
                        <td> <input type="number" name="molde_ancho_copia" class="w-full border rounded px-1 py-1 text-right" disabled> </td>
                        <td> <input type="number" step="0.01" name="ajuste_ancho" value="{{old('ajuste_ancho', $costeoRequisicion->ajuste_ancho)}}" class="w-full border rounded px-1 py-1 text-right" oninput="calcularMedidasBloques()"></td>
                        <td><input type="number" step="0.01" name="medida_bloque_ancho" value="{{old('medida_bloque_ancho', $costeoRequisicion->medida_bloque_ancho)}}" class="w-full border rounded px-1 py-1 text-right"></td>
                    </tr>
                    <tr class="border-b h-14">
                        <td><input type="number" name="molde_avance_copia" class="w-full border rounded px-1 py-1 text-right" disabled></td>
                        <td><input type="number" step="0.01" name="ajuste_avance" class="w-full border rounded px-1 py-1 text-right" value="{{old('ajuste_avance', $costeoRequisicion->ajuste_avance)}}" oninput="calcularMedidasBloques()"></td>
                        <td><input type="number" step="0.01" name="medida_bloque_avance" class="w-full border rounded px-1 py-1 text-right" value="{{old('medida_bloque_avance', $costeoRequisicion->medida_bloque_avance)}}"></td>
                        <td>
                            <input type="number" name="kilos"
                                class="w-full h-full border rounded-none text-right block"
                                style="box-sizing: border-box;" value="{{old('kilos', $costeoRequisicion->kilos)}}">
                        </td>
                    </tr>
                    <tr class="border-b h-14">
                        <td><input type="number" value="{{$pieza_alto}}" class="w-full border rounded px-1 py-1 text-right" disabled></td>
                        <td><input type="number" step="0.01" name="ajuste_alto" value="{{old('ajuste_alto', $costeoRequisicion->ajuste_alto)}}" class="w-full border rounded px-1 py-1 text-right" oninput="calcularMedidasBloques()"></td>
                        <td><input type="number" step="0.01" name="medida_bloque_alto" value="{{old('medida_bloque_alto', $costeoRequisicion->medida_bloque_alto)}}" class="w-full border rounded px-1 py-1 text-right"></td>
                    </tr>
                    <tr class="border-2 border-gray-500 bg-gray-50 font-semibold">
                        <td></td>
                        <td class="text-center text-gray-700">Totales</td>
                        <td></td>
                        <td><input type="number" name="constante_empujador" value="{{old('constante_empujador', $costeoRequisicion->constante_empujador)}}" class="w-full border rounded px-1 py-1 text-right font-bold text-gray-700"></td>
                    </tr>
                </tbody>
            </table>

            <script>
                function calcularAjustesHerramentales() {
                    const molde_ancho = parseFloat(document.querySelector('input[name="molde_ancho"]').value) || 0;
                    const molde_avance = parseFloat(document.querySelector('input[name="molde_avance"]').value) || 0;
                    const molde_alto = parseFloat(document.querySelector('input[name="alto"]').value) || 0;
                    const resultado_ancho = (Math.ceil(molde_ancho / 25.4)) + 1;
                    const resultado_avance = (Math.ceil(molde_avance / 25.4)) + 1;
                    //const resultado_alto = (Math.ceil(molde_alto / 25.4)) + 1;
                    document.querySelector('input[name="ajuste_ancho"]').value = resultado_ancho.toFixed(2);
                    document.querySelector('input[name="ajuste_avance"]').value = resultado_avance.toFixed(2);
                    //document.querySelector('input[name="ajuste_alto"]').value = resultado_alto.toFixed(2);
                    calcularMedidasBloques();
                }

                function calcularMedidaBloqueAlto() {
                    const ajusteAlto = parseFloat(document.querySelector('input[name="ajuste_alto"]').value) || 0;
                    const resultado = 25.4 * ajusteAlto;
                    document.querySelector('input[name="medida_bloque_alto"]').value = resultado.toFixed(2);
                }

                function calcularMedidaBloqueAncho() {
                    const ajusteAncho = parseFloat(document.querySelector('input[name="ajuste_avance"]').value) || 0;
                    const resultado = 25.4 * ajusteAncho;
                    document.querySelector('input[name="medida_bloque_avance"]').value = resultado.toFixed(2);
                }

                function calcularMedidaBloqueLargo() {
                    const ajusteLargo = parseFloat(document.querySelector('input[name="ajuste_ancho"]').value) || 0;
                    const resultado = 25.4 * ajusteLargo;
                    document.querySelector('input[name="medida_bloque_ancho"]').value = resultado.toFixed(2);
                }

                function calcularMedidasBloques() {
                    calcularMedidaBloqueAncho();
                    calcularMedidaBloqueAlto();
                    calcularMedidaBloqueLargo();
                    calcularKilos();
                }

                function calcularKilos() {
                    const ajusteAlto = parseFloat(document.querySelector('input[name="medida_bloque_alto"]').value) || 0;
                    const ajusteAvance = parseFloat(document.querySelector('input[name="medida_bloque_avance"]').value) || 0;
                    const ajusteAncho = parseFloat(document.querySelector('input[name="medida_bloque_ancho"]').value) || 0;
                    const resultado = Math.ceil(((ajusteAncho / 1000) * (ajusteAvance / 1000) * ajusteAlto * 2.82));
                    document.querySelector('input[name="kilos"]').value = resultado.toFixed(2);
                    calcularConstanteEmpujador();
                    calcularCostoMaterialMolde();
                    calcularCostoMaterialEmpujador();
                }

                function calcularConstanteEmpujador() {
                    //=+(REDONDEAR.MAS(((E108/1000)*(E109/1000)*E110*1.16),0))
                    const ajusteAlto = parseFloat(document.querySelector('input[name="medida_bloque_alto"]').value) || 0;
                    const ajusteAvance = parseFloat(document.querySelector('input[name="medida_bloque_avance"]').value) || 0;
                    const ajusteAncho = parseFloat(document.querySelector('input[name="medida_bloque_ancho"]').value) || 0;
                    
                    const resultado = Math.ceil(((ajusteAncho / 1000) * (ajusteAvance / 1000) * ajusteAlto * 1.16));
                    document.querySelector('input[name="constante_empujador"]').value = resultado.toFixed(2);
                }
            </script>   

            <table class="table-auto w-full border border-gray-500 text-sm text-center">
                <thead class="bg-gray-100">
                    <tr class="border-b font-semibold">
                        <th class="px-2 py-2">Concepto</th>
                        <th class="px-2 py-2">$ Material</th>
                        <th class="px-2 py-2">Hrs maquinado</th>
                        <th class="px-2 py-2">$ TOTAL</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="grid gap-2 items-center" id="grid-molde">
                                <button type="button" onclick="toggleCostoAluminio()" class="font-medium px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 text-center">
                                    MOLDE
                                </button>
                                <input type="number" name="costo_aluminio" id="input-costo-aluminio" placeholder="Ingrese costo del aluminio" class="w-full border rounded px-1 py-1 text-right hidden" value="{{ old('costo_aluminio', $costeoRequisicion->costo_aluminio) }}" oninput="calcularCostoMaterialMolde()">
                            </div>
                            <script>
                                function toggleCostoAluminio() {
                                    const inputAluminio = document.getElementById('input-costo-aluminio');
                                    const gridMolde = document.getElementById('grid-molde');
                                    
                                    const isHidden = inputAluminio.classList.contains('hidden');
                                    
                                    if (isHidden) {
                                        // Mostrar input
                                        inputAluminio.classList.remove('hidden');
                                        gridMolde.classList.add('grid-cols-2');
                                    } else {
                                        // Ocultar input
                                        inputAluminio.classList.add('hidden');
                                        gridMolde.classList.remove('grid-cols-2');
                                    }
                                }
                            </script>
                        </td>
                        <td>
                            <input type="number" name="costo_molde" value="{{old('costo_molde', $costeoRequisicion->costo_molde)}}" step="0.01"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo material molde" oninput="calcularTotalMolde(),copiarCostosATotales()">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="hrs_maquinada_molde" value="{{old('hrs_maquinada_molde', $costeoRequisicion->hrs_maquinada_molde)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Horas maquinado molde" oninput="calcularTotalMolde(), calcularTotalFinal()">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="total_molde" value="{{old('total_molde', $costeoRequisicion->total_molde)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Total molde" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="grid gap-2 items-center" id="grid-empujador">
                                <button type="button" onclick="toggleCostoEmpujador()" class="font-medium px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 text-center">
                                    EMPUJADOR
                                </button>
                                <input type="number" name="aux_empujador" id="input-aux-empujador" placeholder="Ingrese costo del empujador" class="w-full border rounded px-1 py-1 text-right hidden" value="{{ old('aux_empujador', $costeoRequisicion->aux_empujador) }}" oninput="calcularCostoMaterialEmpujador()">
                            </div>
                            <script>
                                function toggleCostoEmpujador() {
                                    const inputEmpujador = document.getElementById('input-aux-empujador');
                                    const gridEmpujador = document.getElementById('grid-empujador');
                                    
                                    const isHidden = inputEmpujador.classList.contains('hidden');
                                    
                                    if (isHidden) {
                                        // Mostrar input
                                        inputEmpujador.classList.remove('hidden');
                                        gridEmpujador.classList.add('grid-cols-2');
                                    } else {
                                        // Ocultar input
                                        inputEmpujador.classList.add('hidden');
                                        gridEmpujador.classList.remove('grid-cols-2');
                                    }
                                }
                            </script>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_empujador" value="{{old('costo_empujador', $costeoRequisicion->costo_empujador)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo material empujador" oninput="calcularTotalEmpujador(),copiarCostosATotales()">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="hrs_maquinada_empujador" value="{{old('hrs_maquinada_empujador', $costeoRequisicion->hrs_maquinada_empujador)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Horas maquinado empujador" oninput="calcularTotalEmpujador(), calcularTotalFinal()">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="total_empujador" value="{{old('total_empujador', $costeoRequisicion->total_empujador)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Total empujador" oninput="calcularTotalFinal()">
                        </td>
                    </tr>
                    <script>
                        function calcularCostoMaterialMolde() {
                            const kilos = parseFloat(document.querySelector('input[name="kilos"]').value) || 0;
                            const costo_aluminio = parseFloat(document.querySelector('input[name="costo_aluminio"]').value) || 235; // Valor por defecto $235
                            const resultado = kilos * costo_aluminio;
                            document.querySelector('input[name="costo_molde"]').value = resultado.toFixed(2);
                        }

                        function calcularCostoMaterialEmpujador() {
                            const constanteEmpujador = parseFloat(document.querySelector('input[name="constante_empujador"]').value) || 0;
                            const costo = parseFloat(document.querySelector('input[name="aux_empujador"]').value) || 493; // Valor por defecto $493
                            const resultado = constanteEmpujador * costo;
                            document.querySelector('input[name="costo_empujador"]').value = resultado.toFixed(2);
                            calcularTotalEmpujador();
                        }

                        function calcularTotalEmpujador() {
                            const costoEmpujador = parseFloat(document.querySelector('input[name="costo_empujador"]').value) || 0;
                            const hrsMaquinadaEmpujador = parseFloat(document.querySelector('input[name="hrs_maquinada_empujador"]').value) || 0;
                            const resultado = costoEmpujador + (hrsMaquinadaEmpujador * 60);
                            document.querySelector('input[name="total_empujador"]').value = resultado.toFixed(2);
                            calcularTotalFinal();
                        }

                        function calcularTotalMolde() {
                            const costoMolde = parseFloat(document.querySelector('input[name="costo_molde"]').value) || 0;
                            const hrsMaquinadaMolde = parseFloat(document.querySelector('input[name="hrs_maquinada_molde"]').value) || 0;
                            const resultado = costoMolde + (hrsMaquinadaMolde * 60);
                            document.querySelector('input[name="total_molde"]').value = resultado.toFixed(2); //aqui
                        }

                        function calcularCostoEmpujador() {
                            const constanteEmpujador = parseFloat(document.querySelector('input[name="constante_empujador"]').value) || 0;
                            const resultado = constanteEmpujador * 493;
                            document.querySelector('input[name="costo_empujador"]').value = resultado.toFixed(2);
                        }

                        function calcularCostoMolde() {
                            const kilos = parseFloat(document.querySelector('input[name="kilos"]').value) || 0;
                            const resultado = kilos * 235;
                            document.querySelector('input[name="costo_molde"]').value = resultado.toFixed(2);
                        }

                        function calcularCostoMuestra() {
                            const NoMuestras = parseFloat(document.querySelector('input[name="no_muestras"]').value) || 0;
                            const auxMuestras = parseFloat(document.querySelector('input[name="aux_muestras"]').value) || 0;
                            const resultado = NoMuestras * auxMuestras;
                            document.querySelector('input[name="costo_muestras"]').value = resultado.toFixed(2);
                            document.querySelector('input[name="total_costo_muestras"]').value = resultado.toFixed(2);
                        }
                    </script>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="flex justify-center items-center">
                                <span class="text-center">SUAJE</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_suaje_base" value="{{old('costo_suaje_base', $costeoRequisicion->costo_suaje_base)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo suaje" oninput="copiarCostosATotales()"
                                oninput="document.querySelector('input[name=\'total_suaje_base\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_suaje_base" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total suaje" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="grid gap-2 items-center" id="grid-muestras">
                                <button type="button" onclick="toggleCostoMuestras()" class="font-medium px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 text-center">
                                    MUESTRAS
                                </button>
                                <div class="grid grid-cols-2 gap-1 hidden" id="inputs-muestras">
                                    <input type="number" name="no_muestras" id="input-no-muestras" placeholder="No. de muestras" value="{{ old('no_muestras', $costeoRequisicion->no_muestras) }}" class="w-full border rounded px-1 py-1 text-right" oninput="calcularCostoMuestra()">
                                    <input type="number" name="aux_muestras" id="input-aux-muestras" placeholder="Costo por muestra" value="{{ old('aux_muestras', $costeoRequisicion->aux_muestras) }}" class="w-full border rounded px-1 py-1 text-right" oninput="calcularCostoMuestra()">
                                </div>
                            </div>
                            <script>
                                function toggleCostoMuestras() {
                                    const inputsMuestras = document.getElementById('inputs-muestras');
                                    const gridMuestras = document.getElementById('grid-muestras');
                                    
                                    const isHidden = inputsMuestras.classList.contains('hidden');
                                    
                                    if (isHidden) {
                                        // Mostrar inputs
                                        inputsMuestras.classList.remove('hidden');
                                        gridMuestras.classList.add('grid-cols-2');
                                    } else {
                                        // Ocultar inputs
                                        inputsMuestras.classList.add('hidden');
                                        gridMuestras.classList.remove('grid-cols-2');
                                    }
                                }
                            </script>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_muestras" value="{{old('costo_muestras', $costeoRequisicion->costo_muestras)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo muestras"
                                oninput="document.querySelector('input[name=\'total_costo_muestras\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_costo_muestras" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total muestras" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="flex justify-center items-center">
                                <span class="text-center">PLACA DE FIJACIÓN</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_placa_fijacion" value="{{old('costo_placa_fijacion', $costeoRequisicion->costo_placa_fijacion)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo placa fijación" oninput="document.querySelector('input[name=\'total_costo_placa_fijacion\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_costo_placa_fijacion" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total placa fijación" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="grid gap-2 items-center" id="grid-madera-campana">
                                <button type="button" onclick="toggleCostoMaderaCampana()" class="font-medium px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 text-center">
                                    MADERA CAMPANA
                                </button>
                                <div class="grid grid-cols-2 gap-1 hidden" id="inputs-madera-campana">
                                    <input type="number" name="dividendo" id="input-dividendo" placeholder="Ingrese número" class="w-full border rounded px-1 py-1 text-right" value="{{ old('dividendo', $costeoRequisicion->dividendo) }}" oninput="calcularCostoMaderaCampana()">
                                    <input type="number" name="divisor" id="input-divisor" placeholder="Ingrese divisor" class="w-full border rounded px-1 py-1 text-right" value="{{ old('divisor', $costeoRequisicion->divisor) }}" oninput="calcularCostoMaderaCampana()">
                                </div>
                            </div>
                            <script>
                                function toggleCostoMaderaCampana() {
                                    const inputsMaderaCampana = document.getElementById('inputs-madera-campana');
                                    const gridMaderaCampana = document.getElementById('grid-madera-campana');
                                    
                                    const isHidden = inputsMaderaCampana.classList.contains('hidden');
                                    
                                    if (isHidden) {
                                        // Mostrar inputs
                                        inputsMaderaCampana.classList.remove('hidden');
                                        gridMaderaCampana.classList.add('grid-cols-2');
                                    } else {
                                        // Ocultar inputs
                                        inputsMaderaCampana.classList.add('hidden');
                                        gridMaderaCampana.classList.remove('grid-cols-2');
                                    }
                                }
                            </script>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_madera_campana" value="{{old('costo_madera_campana', $costeoRequisicion->costo_madera_campana)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo madera campana" oninput="copiarCostosATotales()"
                                oninput="document.querySelector('input[name=\'total_costo_madera_campana\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_costo_madera_campana" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total madera campana" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="flex justify-center items-center">
                                <span class="text-center">PROTOTIPO</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_prototipo" value="{{old('costo_prototipo', $costeoRequisicion->costo_prototipo)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo prototipo" oninput="copiarCostosATotales()"
                                oninput="document.querySelector('input[name=\'total_costo_prototipo\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_costo_prototipo" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total prototipo" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="flex justify-center items-center">
                                <span class="text-center">TORNILLERIA</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_tornilleria" value="{{old('costo_tornilleria', $costeoRequisicion->costo_tornilleria)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo tornillería" oninput="copiarCostosATotales()"
                                oninput="document.querySelector('input[name=\'total_costo_tornilleria\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_costo_tornilleria" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total tornillería" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <tr class="border-b h-12">
                        <td class="text-left px-2 font-medium">
                            <div class="flex justify-center items-center">
                                <span class="text-center">PEDIMENTO</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="costo_pedimento_herramental" value="{{old('costo_pedimento_herramental', $costeoRequisicion->costo_pedimento_herramental)}}"
                                class="w-full border rounded px-1 py-1 text-right"
                                placeholder="Costo pedimento" oninput="copiarCostosATotales()"
                                oninput="document.querySelector('input[name=\'total_costo_pedimento_herramental\']').value = this.value">
                        </td>
                        <td></td>
                        <td>
                            <input type="number" name="total_costo_pedimento_herramental" class="w-full border rounded px-1 py-1 text-right" readonly placeholder="Total pedimento" oninput="calcularTotalFinal()">
                        </td>
                    </tr>

                    <script>
                        function calcularPedimentoHerramental() {
                            const pedimentoVirtual = '{{ $pedimento_virtual }}';
                            const costoPedimento = pedimentoVirtual * 2500;
                            document.querySelector('input[name="costo_pedimento_herramental"]').value = costoPedimento.toFixed(2);
                            document.querySelector('input[name="total_costo_pedimento_herramental"]').value = costoPedimento.toFixed(2);
                            document.querySelector('input[name="resumen_costo_pedimento"]').value = costoPedimento.toFixed(2);
                        }

                        function calcularCostoMaderaCampana() {
                            const dividendo = parseFloat(document.querySelector('input[name="dividendo"]').value) || 0;
                            const divisor = parseFloat(document.querySelector('input[name="divisor"]').value) || 1;
                            const resultado = (dividendo / divisor);
                            document.querySelector('input[name="costo_madera_campana"]').value = resultado.toFixed(2);
                            document.querySelector('input[name="total_costo_madera_campana"]').value = resultado.toFixed(2);
                        }

                        function calcularTotalVentas() {
                            const totalFinal = parseFloat(document.querySelector('input[name="TOTAL_FINAL"]').value) || 0;
                            //const resultado = totalFinal * 1.5;
                            const resultado = totalFinal * 1;
                            document.querySelector('input[name="TOTAL_VENTAS"]').value = resultado.toFixed(2);
                        }
                    </script>

                    <!-- FILAS DE TOTALES -->
                    <tr class="border-2 border-gray-500 bg-gray-50 font-semibold">
                        <td colspan="3" class="text-right text-gray-700 px-2 ">TOTAL (MXN)</td>
                        <td>
                            <input type="number" step="0.01" name="TOTAL_FINAL" value="{{old('TOTAL_FINAL', $costeoRequisicion->TOTAL_FINAL)}}"
                                class="w-full border rounded px-1 py-1 text-right font-bold text-gray-700"
                                placeholder="Total general" oninput="calcularTotalVentas()">
                        </td>
                    </tr>
                    <tr class="border-2 border-gray-500 bg-gray-50 font-semibold">
                        <td colspan="3" class="text-right text-gray-700 px-2" onclick="calcularTotalVentas()">TOTAL VENTAS (MXN)</td>
                        <td>
                            <input type="number" step="0.01" name="TOTAL_VENTAS" value="{{old('TOTAL_VENTAS', $costeoRequisicion->TOTAL_VENTAS)}}"
                                class="w-full border rounded px-1 py-1 text-right font-bold text-gray-700"
                                placeholder="Total ventas MXN">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓN: RESUMEN COSTOS DE PROCESOS ADICIONALES -->
        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Resumen Costos de Procesos Adicionales</h2>

            <table class="w-full text-center border-collapse border border-gray-400">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Concepto</th>
                        <th class="border border-gray-300 p-2">Costo total</th>
                        <th class="border border-gray-300 p-2">Piezas</th>
                        <th class="border border-gray-300 p-2">Costo Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Inocuidad -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Inocuidad</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_inocuidad"
                                value="{{ old('resumen_costo_inocuidad', $costeoRequisicion->resumen_costo_inocuidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_inocuidad"
                                value="{{ old('resumen_piezas_inocuidad', $costeoRequisicion->resumen_piezas_inocuidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_inocuidad" readonly
                                value="{{ old('resumen_costo_unit_inocuidad', $costeoRequisicion->resumen_costo_unit_inocuidad) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Polipropileno -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Polipropileno</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_polipropileno"
                                value="{{ old('resumen_costo_polipropileno', $costeoRequisicion->resumen_costo_polipropileno) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_polipropileno"
                                value="{{ old('resumen_piezas_polipropileno', $costeoRequisicion->resumen_piezas_polipropileno) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_polipropileno" readonly
                                value="{{ old('resumen_costo_unit_polipropileno', $costeoRequisicion->resumen_costo_unit_polipropileno) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Estaticidad -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Estaticidad</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_estaticidad"
                                value="{{ old('resumen_costo_estaticidad', $costeoRequisicion->resumen_costo_estaticidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_estaticidad"
                                value="{{ old('resumen_piezas_estaticidad', $costeoRequisicion->resumen_piezas_estaticidad) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_estaticidad" readonly
                                value="{{ old('resumen_costo_unit_estaticidad', $costeoRequisicion->resumen_costo_unit_estaticidad) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <!-- Maquila -->
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Maquila</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_maquila"
                                value="{{ old('resumen_costo_maquila', $costeoRequisicion->resumen_costo_maquila) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_maquila"
                                value="{{ old('resumen_piezas_maquila', $costeoRequisicion->resumen_piezas_maquila) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_maquila" readonly
                                value="{{ old('resumen_costo_unit_maquila', $costeoRequisicion->resumen_costo_unit_maquila) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Etiqueta</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.01" name="resumen_costo_etiqueta"
                                value="{{ old('resumen_costo_etiqueta', $costeoRequisicion->resumen_costo_etiqueta) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_etiqueta"
                                value="{{ old('resumen_piezas_etiqueta', $costeoRequisicion->resumen_piezas_etiqueta) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_etiqueta" readonly
                                value="{{ old('resumen_costo_unit_etiqueta', $costeoRequisicion->resumen_costo_unit_etiqueta) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-yellow-100 font-bold">
                        <td class="border border-gray-300 p-2">TOTAL</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_total_costo_adicionales" readonly
                                value="{{ old('resumen_total_costo_adicionales', $costeoRequisicion->resumen_total_costo_adicionales ?? '') }}"
                                class="w-full bg-yellow-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_total_piezas_adicionales" readonly value="1"
                                class="w-full bg-yellow-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_total_costo_unit_adicionales" readonly
                                value="{{ old('resumen_total_costo_unit_adicionales', $costeoRequisicion->resumen_total_costo_unit_adicionales ?? '') }}"
                                class="w-full bg-yellow-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>6

        @if($esCorridaPiloto)
        <div class="mt-4 p-4 bg-blue-100 border border-blue-300 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-800">Comentarios de costeo</h3>
            <p class="text-blue-700">Los costos de la Fabricación de CP no se reflejan en el resumen de costos final. Se puede encontrar en la tabla inferior "COSTOS DE CORRIDA PILOTO".</p>
        </div>
        <br>
        @endif

        <div class="mb-8 p-6 border-2 border-gray-800 rounded-lg">
            @if($esCorridaPiloto)
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Resumen de Costos Corrida Piloto</h2>
            @else
            <h2 class="text-2xl font-bold border-b-2 border-gray-800 mb-4">Resumen de Costos</h2>
            @endif

            <table class="w-full text-center border-collapse border border-gray-400">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Concepto</th>
                        <th class="border border-gray-300 p-2">Costo total</th>
                        <th class="border border-gray-300 p-2">Piezas</th>
                        <th class="border border-gray-300 p-2">Costo Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Procesos de Maquinaria</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_procesos"
                                value="{{ old('resumen_costo_procesos', $costeoRequisicion->resumen_costo_procesos) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class=" border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_procesos"
                                value="{{ old('resumen_piezas_procesos', $costeoRequisicion->resumen_piezas_procesos) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class=" border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_procesos" readonly
                                value="{{ old('resumen_costo_unit_procesos', $costeoRequisicion->resumen_costo_unit_procesos) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Empaque</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_empaque"
                                value="{{ old('resumen_costo_empaque', $costeoRequisicion->resumen_costo_empaque) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_empaque"
                                value="{{ old('resumen_piezas_empaque', $costeoRequisicion->resumen_piezas_empaque) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_empaque" readonly
                                value="{{ old('resumen_costo_unit_empaque', $costeoRequisicion->resumen_costo_unit_empaque) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Flete</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_flete_total"
                                value="{{ old('resumen_costo_flete_total', $costeoRequisicion->resumen_costo_flete_total) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_flete"
                                value="{{ old('resumen_piezas_flete', $costeoRequisicion->resumen_piezas_flete) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_flete" readonly
                                value="{{ old('resumen_costo_unit_flete', $costeoRequisicion->resumen_costo_unit_flete) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold border border-gray-300 p-2">Pedimento</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_pedimento"
                                value="{{ old('resumen_costo_pedimento', $costeoRequisicion->resumen_costo_pedimento) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_pedimento"
                                value="{{ old('resumen_piezas_pedimento', $costeoRequisicion->resumen_piezas_pedimento) }}"
                                class="w-full border-gray-300 border rounded-md p-1" oninput="calcularCostosUnit()">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_pedimento" readonly
                                value="{{ old('resumen_costo_unit_pedimento', $costeoRequisicion->resumen_costo_unit_pedimento) }}"
                                class="w-full bg-gray-100 border-gray-300 border rounded-md p-1">
                        </td>
                    </tr>

                    <tr class="bg-yellow-100">
                        <td class="font-bold border border-gray-300 p-2">Total Procesos Adicionales</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_adicionales_en_resumen" readonly
                                value="{{ old('resumen_total_costo_adicionales', $costeoRequisicion->resumen_total_costo_adicionales ?? '') }}"
                                class="w-full bg-yellow-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" name="resumen_piezas_adicionales_en_resumen" readonly value="1"
                                class="w-full bg-yellow-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_costo_unit_adicionales_en_resumen" readonly
                                value="{{ old('resumen_total_costo_unit_adicionales', $costeoRequisicion->resumen_total_costo_unit_adicionales ?? '') }}"
                                class="w-full bg-yellow-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-blue-100 font-bold">
                        <td colspan="3" class="text-right border border-gray-300 p-2">Costo Unitario</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_total_costo_unit" readonly
                                value="{{ old('resumen_total_costo_unit', $costeoRequisicion->resumen_total_costo_unit ?? '') }}"
                                class="w-full bg-blue-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                    </tr>
                </tfoot>
                <script>
                    function calcularCostosUnit() {

                        const conceptosAdicionales = ['inocuidad', 'polipropileno', 'estaticidad', 'maquila', 'etiqueta'];
                        let sumaCostosAdicionales = 0;
                        let sumaCostoUnitAdicionales = 0;
                        let sumaPrecioVentaAdicionales = 0;

                        conceptosAdicionales.forEach(concepto => {
                            const costoInput = document.querySelector(`input[name="resumen_costo_${concepto}"]`);
                            const piezasInput = document.querySelector(`input[name="resumen_piezas_${concepto}"]`);
                            const unitInput = document.querySelector(`input[name="resumen_costo_unit_${concepto}"]`);

                            if (costoInput && piezasInput && unitInput) {
                                const costoTotal = parseFloat(costoInput.value) || 0;
                                const piezas = parseFloat(piezasInput.value) || 1;
                                const costoUnit = piezas ? (costoTotal / piezas) : 0;
                                unitInput.value = costoUnit.toFixed(4);

                                sumaCostosAdicionales += costoTotal;
                                sumaCostoUnitAdicionales += costoUnit;
                            }
                        });

                        document.querySelector('input[name="resumen_total_costo_adicionales"]').value = sumaCostosAdicionales.toFixed(4);
                        document.querySelector('input[name="resumen_total_costo_unit_adicionales"]').value = sumaCostoUnitAdicionales.toFixed(4);

                        document.querySelector('input[name="resumen_costo_adicionales_en_resumen"]').value = sumaCostosAdicionales.toFixed(4);
                        document.querySelector('input[name="resumen_costo_unit_adicionales_en_resumen"]').value = sumaCostoUnitAdicionales.toFixed(4);

                        const conceptos = ['procesos', 'empaque', 'flete', 'pedimento'];
                        let sumaTotales = sumaCostoUnitAdicionales; 

                        conceptos.forEach(concepto => {
                            let costoInput, piezasInput, unitInput;
                            if (concepto === 'flete') {
                                costoInput = document.querySelector(`input[name="resumen_costo_flete_total"]`);
                                piezasInput = document.querySelector(`input[name="resumen_piezas_flete"]`);
                                unitInput = document.querySelector(`input[name="resumen_costo_unit_flete"]`);
                            } else {
                                costoInput = document.querySelector(`input[name="resumen_costo_${concepto}"]`);
                                piezasInput = document.querySelector(`input[name="resumen_piezas_${concepto}"]`);
                                unitInput = document.querySelector(`input[name="resumen_costo_unit_${concepto}"]`);
                            }
                            if (costoInput && piezasInput && unitInput) {
                                const costoTotal = parseFloat(costoInput.value) || 0;
                                const piezas = parseFloat(piezasInput.value) || 1;
                                const costoUnit = piezas ? (costoTotal / piezas) : 0;
                                unitInput.value = costoUnit.toFixed(4);
                                sumaTotales += costoUnit;
                            }
                        });

                        const totalInput = document.querySelector('input[name="resumen_total_costo_unit"]');
                        if (totalInput) {
                            totalInput.value = sumaTotales.toFixed(4);
                        }
                        calcularMargenAdministrativo();
                        calcularCostoTotalResumen();
                    }
                    
                    function calcularMargenAdministrativo() {
                        const costo = parseFloat(document.querySelector('input[name="resumen_total_costo_unit"]').value) || 0;
                        const resultado = costo * 0.05;
                        document.querySelector('input[name="resumen_margen_administrativo"]').value = resultado.toFixed(4);
                    }
                     function calcularCostoTotalResumen() {
                        const resumen_total_costo_unit = parseFloat(document.querySelector('input[name="resumen_total_costo_unit"]').value) || 0;
                        const lote_compra = parseFloat(document.querySelector('input[name="lote_compra"]').value) || 0;
                        const coeficiente_merma = parseFloat(document.querySelector('input[name="coeficiente_merma"]').value) || 0;
                        let totalCosto = resumen_total_costo_unit * (lote_compra + (lote_compra * (coeficiente_merma / 100)));
                        document.querySelector('input[name="costo_total"]').value = totalCosto.toFixed(2);
                    }
                    </script>
                <tfoot>
                    <tr class="bg-blue-100 font-bold">
                        <td colspan="3" class="text-right border border-gray-300 p-2">Margen Administrativo</td>
                        <td class="border border-gray-300 p-2">
                            <input type="number" step="0.0001" name="resumen_margen_administrativo" readonly
                                value="{{ old('resumen_margen_administrativo', $costeoRequisicion->resumen_margen_administrativo ?? '') }}"
                                class="w-full bg-blue-50 border-gray-300 border rounded-md p-1 font-bold">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mb-4">
            <label for="costo_total" class="block text-sm font-bold text-gray-700" >Costo total</label>
            <input type="number" step="0.01" name="costo_total" id="costo_total" class="mt-1 block font-bold w-full rounded-md border-gray-300 shadow-sm" value="{{ old('costo_total', $costeoRequisicion->costo_total) }}" disabled>
        </div>

        <h3 class="text-lg font-bold text-gray-800 mb-3 mt-6">Tiempos de entrega</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="entrega_prototipo" class="block text-sm font-medium text-gray-700">Entrega de Prototipo</label>
                <input type="text" name="entrega_prototipo" id="entrega_prototipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="NA o fecha"
                    value="{{ old('entrega_prototipo', $costeoRequisicion->entrega_prototipo) }}">
            </div>

            <div>
                <label for="tiempo_herramientas" class="block text-sm font-medium text-gray-700">Tiempo elaboración de herramientales</label>
                <input type="text" name="tiempo_herramientas" id="tiempo_herramientas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ej. 15 a 20 días hábiles"
                    value="{{ old('tiempo_herramientas', $costeoRequisicion->tiempo_herramientas) }}">
            </div>

            <div>
                <label for="tiempo_pt" class="block text-sm font-medium text-gray-700">Tiempo PT disponible</label>
                <input type="text" name="tiempo_pt" id="tiempo_pt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ej. 30 días hábiles"
                    value="{{ old('tiempo_pt', $costeoRequisicion->tiempo_pt) }}">
            </div>
        </div>

        <div class="mt-4">
            <label for="comentarios" class="block text-sm font-medium text-gray-700">Comentarios de costeo</label>
            <textarea name="comentarios" id="comentarios" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Observaciones, notas técnicas, etc.">{{ old('comentarios', $costeoRequisicion->comentarios) }}</textarea>
        </div>

        <div class="flex justify-between items-center mt-6 space-x-4">
            <div id="boton-enviar">
                @if(!$cotizacion->enviado_a_ventas)
                <button type="button"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-medium"
                    onclick="enviarAVentas({{ $cotizacion->id }})">
                    📤 Enviar a Ventas
                </button>
                @else
                <span class="text-green-700 font-semibold">✅ Enviada a Ventas</span>
                @endif
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('cotizaciones.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-3 rounded font-medium">
                    Cancelar
                </a>
                @if ($esCorridaPiloto)
                <a href="{{ route('cotizacion.resumen.costos.pdf', $cotizacion->id) }}" target="_blank"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded font-medium">
                    Descargar PDF de Resumen de Costos
                </a>
                @else
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded font-medium">
                    Guardar Costeo
                </button>
                @endif
            </div>
        </div>
    </form>
</div>
<script>
    //funcion para enviar a ventas
    function enviarAVentas(id) {
        if (!confirm('¿Deseas enviar esta cotización a Ventas?')) return;

        fetch(`/cotizaciones/${id}/enviar-a-ventas`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cambiar el botón a "Enviada"
                    document.getElementById('boton-enviar').innerHTML =
                        '<span class="text-green-700 font-semibold">✅ Enviada a Ventas</span>';
                    alert(data.success);
                } else if (data.warning) {
                    alert(data.warning);
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<script>
    function asignarLoteCompraEnResumenPiezas() {
        const loteCompra = document.querySelector('input[name="lote_compra"]')?.value || '';
        const names = [
            'resumen_piezas_empaque',
            'resumen_piezas_flete',
            'resumen_piezas_etiqueta',
            'resumen_piezas_inocuidad',
            'resumen_piezas_polipropileno',
            'resumen_piezas_maquila',
            'resumen_piezas_pedimento'
        ];
        names.forEach(name => {
            const input = document.querySelector(`input[name="${name}"]`);
            if (input) input.value = loteCompra;
        });
        document.querySelector('input[name="resumen_piezas_estaticidad"]').value = 1;
    }

    function copiarCostosATotales() {
        const campos = [
            ['costo_suaje_base', 'total_suaje_base'],
            ['costo_muestras', 'total_costo_muestras'],
            ['costo_placa_fijacion', 'total_costo_placa_fijacion'],
            ['costo_madera_campana', 'total_costo_madera_campana'],
            ['costo_prototipo', 'total_costo_prototipo'],
            ['costo_tornilleria', 'total_costo_tornilleria'],
            ['costo_pedimento_herramental', 'total_costo_pedimento_herramental']
        ];

        campos.forEach(function([costo, total]) {
            const costoInput = document.querySelector(`input[name="${costo}"]`);
            const totalInput = document.querySelector(`input[name="${total}"]`);
            if (costoInput && totalInput) {
                totalInput.value = costoInput.value;
            }
        });
        calcularTotalFinal();
    }

    function calcularTotalFinal() {
        const totalMolde = parseFloat(document.querySelector('input[name="total_molde"]').value) || 0;
        const totalEmpujador = parseFloat(document.querySelector('input[name="total_empujador"]').value) || 0;
        const totalSuajeBase = parseFloat(document.querySelector('input[name="total_suaje_base"]').value) || 0;
        const totalMuestras = parseFloat(document.querySelector('input[name="total_costo_muestras"]').value) || 0;
        const totalPlacaFijacion = parseFloat(document.querySelector('input[name="total_costo_placa_fijacion"]').value) || 0;
        const totalMaderaCampana = parseFloat(document.querySelector('input[name="total_costo_madera_campana"]').value) || 0;
        const totalPrototipo = parseFloat(document.querySelector('input[name="total_costo_prototipo"]').value) || 0;
        const totalTornilleria = parseFloat(document.querySelector('input[name="total_costo_tornilleria"]').value) || 0;
        const totalPedimento = parseFloat(document.querySelector('input[name="total_costo_pedimento_herramental"]').value) || 0;

        const resultado = totalMolde + totalEmpujador + totalSuajeBase + totalMuestras + totalPlacaFijacion + totalMaderaCampana + totalPrototipo + totalTornilleria + totalPedimento;
        document.querySelector('input[name="TOTAL_FINAL"]').value = resultado.toFixed(2);
        calcularTotalVentas();
    }

    function cargarDatos() {
        calcularPesoEspecifico();
        asignarBolsa();
        asignarCajasPorTarima(); 
        asignarTarima();
        calcularAcomodoAncho(); 
        calcularAcomodoAvance();
        calcularPedimentoHerramental();
        copiarCostosATotales();
        asignarLoteCompraEnResumenPiezas();
        calcularCostosUnit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        cargarDatos();
    });
</script>

@endsection