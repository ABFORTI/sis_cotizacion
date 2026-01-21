@props(['cotizacion' => null])

@php
$aux = optional($cotizacion?->especificacionEmpaque);

$cajas_corrugado = oldValue('cajas_corrugado', $aux);
$bolsa_plastico = oldValue('bolsa_plastico', $aux);
$liner = oldValue('liner', $aux);
$esquineros = oldValue('esquineros', $aux);
$otras_especificaciones_empaque = oldValue('otras_especificaciones_empaque', $aux);
$datos_criticos = oldValue('datos_criticos', $aux);
@endphp

<fieldset>
    <legend>Especificaciones de Empaque</legend>
    <div class="form-grid">
        <div class="form-group">
            <div>
                <input type="hidden" name="cajas_corrugado" value="0">
                <input type="checkbox" id="cajas_corrugado" name="cajas_corrugado" value="1"
                    {{$cajas_corrugado ? 'checked' : '' }}>
                <label for="cajas_corrugado">Cajas de corrugado</label>
            </div>

            <div>
                <input type="checkbox" id="bolsa_plastico" name="bolsa_plastico" value="1"
                    {{$bolsa_plastico ? 'checked' : '' }}>
                <label for="bolsa_plastico">Bolsa de plástico</label>
            </div>

            <div>
                <input type="hidden" name="liner" value="0">
                <input type="checkbox" id="liner" name="liner" value="1"
                    {{$liner ? 'checked' : ''}}>
                <label for="liner">Liner</label>
            </div>

            <div>
                <input type="hidden" name="esquineros" value="0">
                <input type="checkbox" id="esquineros" name="esquineros" value="1"
                    {{$esquineros ? 'checked' : ''}}>
                <label for="esquineros">Esquineros</label>
            </div>
        </div>
        <div class="form-group">
            <label for="otras_especificaciones_empaque">Otras especificaciones:</label>
            <textarea id="otras_especificaciones_empaque" name="otras_especificaciones_empaque">{{$otras_especificaciones_empaque}}</textarea>
        </div>

        <div class="form-group">
            <label for="datos_criticos">Datos críticos/especiales que el cliente solicite:</label>
            <textarea id="datos_criticos" name="datos_criticos">{{ $datos_criticos }}</textarea>
        </div>
    </div>
</fieldset>