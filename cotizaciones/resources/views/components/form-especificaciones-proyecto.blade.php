@props(['cotizacion' => null])

@php
$aux = optional($cotizacion?->especificacionProyecto);

$frecuenciaOld = oldValue('frecuencia_compra', $aux);
$loteOld = oldValue('lote_compra', $aux);
$piezaLargoOld = oldValue('pieza_largo', $aux);
$piezaAnchoOld = oldValue('pieza_ancho', $aux);
$piezaAltoOld = oldValue('pieza_alto', $aux);
$materialOld = oldValue('material', $aux);
$calibreOld = oldValue('calibre', $aux);
$colorOld = oldValue('color', $aux);
$franjaActiva = oldValue('franja_color_si', $aux, false);
$franjaColorOld = oldValue('franja_color', $aux, 'NA');
@endphp


<fieldset>
    <legend>Especificaciones del Proyecto</legend>
    <div class="form-grid">
        <div class="form-group">
            <label for="frecuencia_compra">Frecuencia de compra: <span class="text-red-600">*</span></label>
            <select id="frecuencia_compra" name="frecuencia_compra" title="Seleccione la frecuencia de compra" required>
                <option value="Única" {{ $frecuenciaOld == 'Única' || empty($frecuenciaOld) ? 'selected' : '' }}>Única vez</option>
                <option value="Semanal" {{ $frecuenciaOld == 'Semanal' ? 'selected' : '' }}>Semanal</option>
                <option value="Mensual" {{ $frecuenciaOld == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                <option value="Bimestral" {{ $frecuenciaOld == 'Bimestral' ? 'selected' : '' }}>Bimestral</option>
                <option value="Trimestral" {{ $frecuenciaOld == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                <option value="Anual" {{ $frecuenciaOld == 'Anual' ? 'selected' : '' }}>Anual</option>
            </select>
        </div>

        <div class="form-group">
            <label for="lote_compra">Cantidad por lote de compra: <span class="text-red-600">*</span></label>
            <input type="number" id="lote_compra" name="lote_compra" title="Ingrese la cantidad por lote de compra" placeholder="Cantidad por lote de compra" value="{{ $loteOld }}" required>
        </div>
    </div>

    <fieldset class="sub-fieldset">
        <legend class="sub-legend">Dimensiones de la pieza</legend>
        <div class="form-grid">
            <div class="form-group">
                <label for="pieza_largo">Largo (mm):</label>
                <input type="number" id="pieza_largo" name="pieza_largo" step="0.10" placeholder="0.00" value="{{ $piezaLargoOld }}">
            </div>
            <div class="form-group">
                <label for="pieza_ancho">Ancho (mm):</label>
                <input type="number" id="pieza_ancho" name="pieza_ancho" step="0.10" placeholder="0.00" value="{{ $piezaAnchoOld }}">
            </div>
            <div class="form-group">
                <label for="pieza_alto">Alto (mm):</label>
                <input type="number" id="pieza_alto" name="pieza_alto" step="0.10" placeholder="0.00" value="{{ $piezaAltoOld }}">
            </div>
        </div>
    </fieldset>

    <fieldset class="sub-fieldset">
        <legend class="sub-legend">Especificaciones del material</legend>
        <div class="form-grid">
            <div class="form-group">
                <label for="material">Material:</label>
                <select name="material" id="material" title="seleccione el material">
                    <option value="" {{ $materialOld == '' ? 'selected' : '' }}>Selecciona una opción</option>
                    <option value="ABS" {{ $materialOld == 'ABS' ? 'selected' : '' }}>ABS</option>
                    <option value="PS" {{ $materialOld == 'PS' ? 'selected' : '' }}>PS</option>
                    <option value="PET" {{ $materialOld == 'PET' ? 'selected' : '' }}>PET</option>
                    <option value="HDPE" {{ $materialOld == 'HDPE' ? 'selected' : '' }}>HDPE</option>
                    <option value="PP" {{ $materialOld == 'PP' ? 'selected' : '' }}>PP</option>
                    <option value="PET ESD" {{ $materialOld == 'PET ESD' ? 'selected' : '' }}>PET ESD</option>
                    <option value="PET-POLIPROPILENO" {{ $materialOld == 'PET-POLIPROPILENO' ? 'selected' : '' }}>PET-POLIPROPILENO</option>
                </select>
            </div>
            <div class="form-group">
                <label for="calibre">Calibre (mm):</label>
                <input type="number" id="calibre" name="calibre" step="0.01" placeholder="0.00" value="{{ $calibreOld }}">
            </div>
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" value="{{ $colorOld }}">
            </div>
            <div class="form-group">
                <label for="franja_color_checkbox">Habilitar Franja de color
                    <input type="hidden" name="franja_color_si" value="0">
                    <input type="checkbox" id="franja_color_checkbox" name="franja_color_si" value="1" data-toggle="franja_color_input" {{ $franjaActiva ? 'checked' : '' }}>
                </label>
            </div>
            <div class="form-group {{ $franjaActiva ? '' : 'is-hidden' }}" id="franja_color_input">
                <label for="franja_color">Franja de color:</label>
                <input type="text" id="franja_color" name="franja_color" value="{{ $franjaColorOld }}">
            </div>
        </div>
    </fieldset>
</fieldset>