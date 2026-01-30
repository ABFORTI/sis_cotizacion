@props(['cotizacion' => null])

@php
$aux = optional($cotizacion?->requisicionCotizacion);

$tipo_estiba = oldValue('tipo_estiba', $aux);
$numero_parte = oldValue('numero_parte', $aux);
$numero_parte_descripcion = oldValue('numero_parte_descripcion', $aux);//agregar campo en base de datos
$tipo_material = oldValue('tipo_material', $aux);
$logo_cliente = oldValue('logo_cliente', $aux);
$logo_innovet = oldValue('logo_innovet', $aux);
$sin_grabado = oldValue('sin_grabado', $aux);
$requisicion_otro = oldValue('requisicion_otro', $aux);
$otros = oldValue('otros', $aux);
$tipo_flujo_carga = oldValue('tipo_flujo_carga', $aux);
$pared = oldValue('pared', $aux);
$movimiento = oldValue('movimiento', $aux);
$sujecion = oldValue('sujecion', $aux);
$temperaturas_expuestas = oldValue('temperaturas_expuestas', $aux);
$temperaturas_expuestas_descripcion = oldValue('temperaturas_expuestas_descripcion', $aux); //agregar campo en base de datos
$proceso_de_inocuidad = oldValue('proceso_de_inocuidad', $aux);

$aux = optional($cotizacion?->termoformado);
$pieza_mejorar = oldValue('pieza_mejorar', $aux);
$pieza_fisica_proteger = oldValue('pieza_fisica_proteger', $aux);
$plano_pieza_termoformada = oldValue('plano_pieza_termoformada', $aux);
$igs_componente = oldValue('igs_componente', $aux);
$igs_pieza_termoformada = oldValue('igs_pieza_termoformada', $aux);
$contenedor = oldValue('contenedor', $aux);
$plano_pieza_pdf = oldValue('plano_pieza_pdf', $aux);
$nc = oldValue('nc', $aux);
$na = oldValue('na', $aux);
$termoformado_otro_checkbox = oldValue('termoformado_otro_checkbox', $aux);
$termoformado_otro_info = oldValue('termoformado_otro_info', $aux);

$aux = optional($cotizacion?->usoCliente);

$manipulacion_interna_info = oldValue('manipulacion_interna_info', $aux);
$proceso_interno_manual_info = oldValue('proceso_interno_manual_info', $aux);
$proceso_interno_robotizado_info = oldValue('proceso_interno_robotizado_info', $aux);
$envio_unica_cliente_info = oldValue('envio_unica_cliente_info', $aux);
$envio_cliente_retornable_info = oldValue('envio_cliente_retornable_info', $aux);
$exhibicion_info = oldValue('exhibicion_info', $aux);
$exhibicion_sello_info = oldValue('exhibicion_sello_info', $aux);
$componente_int_automotriz_info = oldValue('componente_int_automotriz_info', $aux);
$componente_ext_automotriz_info = oldValue('componente_ext_automotriz_info', $aux);
$uso_cliente_otro_checkbox = oldValue('uso_cliente_otro_checkbox', $aux);
$uso_cliente_otro = oldValue('uso_cliente_otro', $aux);

$aux = optional($cotizacion?->cajaCliente);

$caja_largo = oldValue('caja_largo', $aux);//////estos en resumen van a concatenarse y especificar que son mm no cm
$caja_ancho = oldValue('caja_ancho', $aux);
$caja_alto = oldValue('caja_alto', $aux);
$dedales = oldValue('dedales', $aux);

@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
    toggleTemperaturasExpuestas();
    });
    function toggleTemperaturasExpuestas() {
    const selectElement = document.getElementById('temperaturas_expuestas');
    const especificarDiv = document.getElementById('temperaturas_expuestas_especificar_apartado');
    const especificarInput = document.getElementById('temperaturas_expuestas_descripcion');

   if (selectElement.value === 'Especificar') {
    especificarDiv.classList.remove('is-hidden');
    especificarInput.setAttribute('required', 'required');
} else {
    especificarDiv.classList.add('is-hidden');
    especificarInput.removeAttribute('required');
    especificarInput.value = '';
}
    }
</script>

<fieldset>
    <legend>Requisición de Cotización</legend>
    <div class="form-grid">
        <div class="form-group">
            <div id="tipo_estiba_apartado">
                <label for="tipo_estiba">Tipo de estiba:</label>
                <select id="tipo_estiba" name="tipo_estiba">
                    <option value="Sin estiba" {{ $tipo_estiba == 'Sin estiba' ? 'selected' : '' }}>"Sin estiba"</option>
                    <option value="0°" {{ $tipo_estiba == '0°' ? 'selected' : '' }}>0°</option>
                    <option value="180°" {{ $tipo_estiba == '180°' ? 'selected' : '' }}>180°</option>
                </select>
            </div>
            <div id="grabados_apartado" class="mb-4">
                <label >Tipo de grabado:</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="hidden" name="numero_parte" value="0">
                        <input type="checkbox" name="numero_parte" value="1"
                        data-toggle="numero_parte_descripcion_apartado" {{ $numero_parte ?? false ? 'checked' : '' }}>
                        <span class="ml-2">Número de parte</span>
                    </label>

                    <div class="form-group {{ $numero_parte ? '' : 'is-hidden' }}" id="numero_parte_descripcion_apartado">
                        <label for="descripcion_parte">Descripción número de parte:</label>
                        <input type="text" id="descripcion_parte" name="descripcion_parte"
                            value="{{ old('descripcion_parte', $cotizacion?->requisicionCotizacion->descripcion_parte ?? '') }}"
                            placeholder="Ingrese el número de parte a grabar" data-required="false">
                    </div>

                    <label class="flex items-center">
                        <input type="hidden" name="tipo_material" value="0">
                        <input type="checkbox" name="tipo_material" value="1"
                            {{ $tipo_material ?? false ? 'checked' : '' }}>
                        <span class="ml-2">Tipo de material</span>
                    </label>

                    <label class="flex items-center">
                        <input type="hidden" name="logo_cliente" value="0">
                        <input type="checkbox" name="logo_cliente" value="1"
                            {{ $logo_cliente ?? false ? 'checked' : '' }}>
                        <span class="ml-2">Logo cliente</span>
                    </label>

                    <label class="flex items-center">
                        <input type="hidden" name="logo_innovet" value="0">
                        <input type="checkbox" name="logo_innovet" value="1"
                            {{ $logo_innovet ?? false ? 'checked' : '' }}>
                        <span class="ml-2">Logo Innovet</span>
                    </label>

                    <label class="flex items-center">
                        <input type="hidden" name="sin_grabado" value="0">
                        <input type="checkbox" name="sin_grabado" value="1"
                            {{ $sin_grabado ?? false ? 'checked' : '' }}>
                        <span class="ml-2">Sin grabado</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="requisicion_otro_checkbox">Habilitar Otro
                    <input type="hidden" name="requisicion_otro" value="0">
                    <input type="checkbox" id="requisicion_otro_checkbox" name="requisicion_otro" value="1" data-toggle="requisicion_otro_apartado"
                        {{ $requisicion_otro ? 'checked' : '' }}>
                </label>
            </div>
            <div class="form-group is-hidden" id="requisicion_otro_apartado">
                <label for="otros">Otro:</label>
                <input type="text" id="otros" name="otros" placeholder="Otros"
                    value="{{ $otros }}">
            </div>
            <div id="flujo_carga_apartado">
                <label for="tipo_flujo_carga">Tipo de flujo de carga:</label>
                <select name="tipo_flujo_carga" id="tipo_flujo_carga">
                    <option value="NA" {{ $tipo_flujo_carga == 'NA' ? 'selected' : '' }}>"Sin especificar"</option>
                    <option value="Entre componentes" {{ $tipo_flujo_carga == 'Entre componentes' ? 'selected' : '' }}>Entre componentes</option>
                    <option value="Sobre charola" {{ $tipo_flujo_carga == 'Sobre charola' ? 'selected' : '' }}>Sobre charola</option>
                </select>
            </div>
            <div id="pared_apartado">
                <label for="pared">Tipo de pared:</label>
                <select name="pared" id="pared">
                    <option value="Sin pared" {{ $pared == 'Sin pared' ? 'selected' : '' }}>"Sin pared"</option>
                    <option value="Alta" {{ $pared == 'Alta' ? 'selected' : '' }}>Alta</option>
                    <option value="Media" {{ $pared == 'Media' ? 'selected' : '' }}>Media</option>
                </select>
            </div>

            <div class="form-group" id="sujecion_apartado">
                <label>Tipo de sujeción:</label>
                <select name="sujecion" id="sujecion">
                    <option value="Sin broche" {{ $sujecion == 'Sin broche' ? 'selected' : '' }}>"Sin Broche"</option>
                    <option value="Broche" {{ $sujecion == 'Broche' ? 'selected' : '' }}>Broche</option>
                    <option value="Juego" {{ $sujecion == 'Juego' ? 'selected' : '' }}>Juego</option>
                </select>
            </div>

            <div id="movimiento_apartado">
                <label for="movimiento">Tipo de movimiento:</label>
                <select name="movimiento" id="movimiento">
                    <option value="Sin movimiento" {{ ($movimiento ?? 'Sin movimiento') == 'Sin movimiento' ? 'selected' : '' }}>
                        "Sin movimiento"
                    </option>
                    <option value="Movimiento limitado vertical" {{ $movimiento == 'Movimiento limitado vertical' ? 'selected' : '' }}>
                        Movimiento limitado vertical
                    </option>
                    <option value="Movimiento limitado horizontal" {{ $movimiento == 'Movimiento limitado horizontal' ? 'selected' : '' }}>
                        Movimiento limitado horizontal
                    </option>
                    <option value="Movimiento limitado vertical y horizontal" {{ $movimiento == 'Movimiento limitado vertical y horizontal' ? 'selected' : '' }}>
                        Movimiento limitado en horizontal y vertical
                    </option>
                </select>
            </div>
            <div id="temperaturas_expuestas_apartado">
                <label for="temperaturas_expuestas">Temperaturas expuestas:</label>
                    <select name="temperaturas_expuestas" id="temperaturas_expuestas" onchange="toggleTemperaturasExpuestas()">
                        <option value="NC" {{ ($temperaturas_expuestas ?? 'NC') == 'NC' ? 'selected' : '' }}>"NC"</option>
                        <option value="Especificar" {{ $temperaturas_expuestas == 'Especificar' ? 'selected' : '' }}>Especificar</option>
                    </select>
            </div>
            <div class="form-group {{ ($temperaturas_expuestas ?? 'NC') == 'Especificar' ? '' : 'is-hidden' }}" id="temperaturas_expuestas_especificar_apartado">
                <label>Especificar temperatura expuesta: </label>
                <input type="text" id="temperaturas_expuestas_descripcion" name="temperaturas_expuestas_descripcion"
                    value="{{ $temperaturas_expuestas_descripcion }}"
                    placeholder="Describa las temperaturas a las que estará expuesto el producto" {{ ($temperaturas_expuestas ?? 'NC') == 'Especificar' ? 'required' : '' }}>
            </div>
            <div>
                <input type="hidden" name="proceso_de_inocuidad" value="0">
                <input type="checkbox" id="Proceso_de_inocuidad_checkbox" name="proceso_de_inocuidad" value="1"
                    {{ $proceso_de_inocuidad ? 'checked' : '' }}>
                <label for="Proceso_de_inocuidad_checkbox">Proceso de inocuidad</label>
            </div>

            <!-- Información de termoformado -->
            <div class="form-group">
                <legend class="sub-label">
                    <div>
                        <span id="label_termoformado">Información de Termoformado</span>
                    </div>
                </legend>
            </div>
            <div class="form-group">
                <div>
                    <input type="hidden" name="pieza_mejorar" value="0">
                    <input type="checkbox" id="pieza_mejorar_checkbox" name="pieza_mejorar" value="1"
                        {{ $pieza_mejorar ? 'checked' : '' }}>
                    <label for="pieza_mejorar_checkbox">Pieza a mejorar</label>
                </div>
                <div>
                    <input type="hidden" name="pieza_fisica_proteger" value="0">
                    <input type="checkbox" id="pieza_fisica_proteger_checkbox" name="pieza_fisica_proteger" value="1"
                        {{ $pieza_fisica_proteger ? 'checked' : '' }}>
                    <label for="pieza_fisica_proteger_checkbox">Pieza física a proteger</label>
                </div>
                <div>
                    <input type="hidden" name="plano_pieza_termoformada" value="0">
                    <input type="checkbox" id="plano_pieza_termoformada_checkbox" name="plano_pieza_termoformada" value="1"
                        {{ $plano_pieza_termoformada ? 'checked' : '' }}>
                    <label for="plano_pieza_termoformada_checkbox">Plano pieza termoformada</label>
                </div>
                <div>
                    <input type="hidden" name="igs_componente" value="0">
                    <input type="checkbox" id="igs_componente_checkbox" name="igs_componente" value="1"
                        {{ $igs_componente ? 'checked' : '' }}>
                    <label for="igs_componente_checkbox">IGS componente</label>
                </div>
                <div>
                    <input type="hidden" name="igs_pieza_termoformada" value="0">
                    <input type="checkbox" id="igs_pieza_termoformada_checkbox" name="igs_pieza_termoformada" value="1"
                        {{ $igs_pieza_termoformada ? 'checked' : '' }}>
                    <label for="igs_pieza_termoformada_checkbox">IGS pieza termoformada</label>
                </div>
                <div>
                    <input type="hidden" name="contenedor" value="0">
                    <input type="checkbox" id="contenedor_checkbox" name="contenedor" value="1"
                        {{ $contenedor ? 'checked' : '' }}>
                    <label for="contenedor_checkbox">Contenedor</label>
                </div>
                <div>
                    <input type="hidden" name="plano_pieza_pdf" value="0">
                    <input type="checkbox" id="plano_pieza_pdf_checkbox" name="plano_pieza_pdf" value="1"
                        {{ $plano_pieza_pdf ? 'checked' : '' }}>
                    <label for="plano_pieza_pdf_checkbox">Plano de la Pieza PDF</label>
                </div>
                <div>
                    <input type="hidden" name="nc" value="0">
                    <input type="checkbox" id="nc_checkbox" name="nc" value="1"
                        {{ $nc ? 'checked' : '' }}>
                    <label for="nc_checkbox">NC</label>
                </div>
                <div>
                    <input type="hidden" name="na" value="0">
                    <input type="checkbox" id="na_checkbox" name="na" value="1"
                        {{ $na ? 'checked' : '' }}>
                    <label for="na_checkbox">NA</label>
                </div>
                <div>
                    <label for="termoformado_otro_checkbox">Habilitar Otro </label>
                    <input type="hidden" name="termoformado_otro_checkbox" value="0">
                    <input type="checkbox" id="termoformado_otro_checkbox" name="termoformado_otro_checkbox" value="1"
                        data-toggle="termoformado_otro_apartado"
                        {{ $termoformado_otro_checkbox ? 'checked' : '' }}>
                </div>
                <div class="form-group is-hidden" id="termoformado_otro_apartado">
                    <label for="termoformado_otro_info">Otro:</label>
                    <input type="text" id="termoformado_otro_info" name="termoformado_otro_info" placeholder="Sugerencia u otro dato"
                        value="{{ $termoformado_otro_info }}">
                </div>
            </div>
        </div>

        <!-- Información uso cliente -->
        <div class="form-group">
            <legend class="sub-label">
                <span id="label_cliente">Uso Cliente </span>
            </legend>
            <div class="form-group">
                <div>
                    <input type="hidden" name="manipulacion_interna_info" value="0">
                    <input type="checkbox" id="manipulacion_interna_info" name="manipulacion_interna_info" value="1"
                        {{ $manipulacion_interna_info ? 'checked' : '' }}>
                    <label for="manipulacion_interna_info">Manipulación Interna</label>
                </div>
                <div>
                    <input type="hidden" name="proceso_interno_manual_info" value="0">
                    <input type="checkbox" id="proceso_interno_manual_info" name="proceso_interno_manual_info" value="1"
                        {{ $proceso_interno_manual_info ? 'checked' : '' }}>
                    <label for="proceso_interno_manual_info">Proceso Interno Manual</label>
                </div>
                <div>
                    <input type="hidden" name="proceso_interno_robotizado_info" value="0">
                    <input type="checkbox" id="proceso_interno_robotizado_info" name="proceso_interno_robotizado_info" value="1"
                        {{ $proceso_interno_robotizado_info ? 'checked' : '' }}>
                    <label for="proceso_interno_robotizado_info">Proceso Interno Robotizado</label>
                </div>
                <div>
                    <input type="hidden" name="envio_unica_cliente_info" value="0">
                    <input type="checkbox" id="envio_unica_cliente_info" name="envio_unica_cliente_info" value="1"
                        {{ $envio_unica_cliente_info ? 'checked' : '' }}>
                    <label for="envio_unica_cliente_info">Envío Única Cliente</label>
                </div>
                <div>
                    <input type="hidden" name="envio_cliente_retornable_info" value="0">
                    <input type="checkbox" id="envio_cliente_retornable_info" name="envio_cliente_retornable_info" value="1"
                        {{ $envio_cliente_retornable_info ? 'checked' : '' }}>
                    <label for="envio_cliente_retornable_info">Envío Cliente Retornable</label>
                </div>
                <div>
                    <input type="hidden" name="exhibicion_info" value="0">
                    <input type="checkbox" id="exhibicion_info" name="exhibicion_info" value="1"
                        {{ $exhibicion_info ? 'checked' : '' }}>
                    <label for="exhibicion_info">Exhibición</label>
                </div>
                <div>
                    <input type="hidden" name="exhibicion_sello_info" value="0">
                    <input type="checkbox" id="exhibicion_sello_info" name="exhibicion_sello_info" value="1"
                        {{ $exhibicion_sello_info ? 'checked' : '' }}>
                    <label for="exhibicion_sello_info">Exhibición Sello</label>
                </div>
                <div>
                    <input type="hidden" name="componente_int_automotriz_info" value="0">
                    <input type="checkbox" id="componente_int_automotriz_info" name="componente_int_automotriz_info" value="1"
                        {{ $componente_int_automotriz_info ? 'checked' : '' }}>
                    <label for="componente_int_automotriz_info">Componente INT Automotriz</label>
                </div>
                <div>
                    <input type="hidden" name="componente_ext_automotriz_info" value="0">
                    <input type="checkbox" id="componente_ext_automotriz_info" name="componente_ext_automotriz_info" value="1"
                        {{ $componente_ext_automotriz_info ? 'checked' : '' }}>
                    <label for="componente_ext_automotriz_info">Componente EXT Automotriz</label>
                </div>
                <div>
                    <label for="uso_cliente_otro_checkbox">Habilitar Otro </label>
                    <input type="hidden" name="uso_cliente_otro_checkbox" value="0">
                    <input type="checkbox" id="uso_cliente_otro_checkbox" name="uso_cliente_otro_checkbox" value="1" data-toggle="uso_cliente_otro"
                        {{ $uso_cliente_otro_checkbox ? 'checked' : '' }}>
                </div>
                <div class="form-group is-hidden" id="uso_cliente_otro">
                    <label for="uso_cliente_otro">Otro:</label>
                    <input type="text" id="uso_cliente_otro" name="uso_cliente_otro" placeholder="Sugerencia u otro dato"
                        value="{{ $uso_cliente_otro }}">
                </div>
            </div>

            <!-- Información caja cliente y dedales -->

            <div class="form-group" id="caja_cliente_apartado">
                <fieldset class="sub-fieldset">
                    <legend class="sub-legend">Dimensiones de la caja cliente:</legend>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="caja_largo">Largo (mm):</label>
                            <input type="number" id="caja_largo" name="caja_largo" step="0.10" placeholder="0.00"
                                value="{{ $caja_largo }}">
                        </div>
                        <div class="form-group">
                            <label for="caja_ancho">Ancho (mm):</label>
                            <input type="number" id="caja_ancho" name="caja_ancho" step="0.10" placeholder="0.00"
                                value="{{ $caja_ancho }}">
                        </div>
                        <div class="form-group">
                            <label for="caja_alto">Alto (mm):</label>
                            <input type="number" id="caja_alto" name="caja_alto" step="0.10" placeholder="0.00"
                                value="{{ $caja_alto }}">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="form-group" id="dedales_apartado">
                <label for="dedales" id="label_dedales">Dedales:</label>

                <select name="dedales" id="dedales" aria-labelledby="label_dedales">
                    <option value="NA" {{ $dedales == 'NA' ? 'selected' : ''}}>"Sin especificar"</option>
                    <option value="90" {{ $dedales == '90' ? 'selected' : '' }}>90°</option>
                    <option value="120" {{ $dedales == '120' ? 'selected' : '' }}>120°</option>
                    <option value="180" {{ $dedales == '180' ? 'selected' : ''}}>180°</option>
                </select>
            </div>
        </div>
    </div>
</fieldset>