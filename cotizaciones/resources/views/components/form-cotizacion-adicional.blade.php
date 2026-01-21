@props(['cotizacion' => null])

@php
$aux = optional($cotizacion?->cotizacionAdicional);

$ppap = oldValue('ppap', $aux);
$corrida_piloto = oldValue('corrida_piloto', $aux);
$herramentales = oldValue('herramentales', $aux);
$almacenaje = oldValue('almacenaje', $aux);
$prototipo = oldValue('prototipo', $aux); //cambiar a int
$prototipo_descripcion = oldValue('prototipo_descripcion', $aux); //agregar campo en base de datos
$pedimento_virtual = oldValue('pedimento_virtual', $aux);
$otros_checkbox = oldValue('otros_checkbox', $aux);
$otro1 = oldValue('otro1', $aux);
$otro2 = oldValue('otro2', $aux);
$altura_maxima_estiba = oldValue('altura_maxima_estiba', $aux);
$peso_maximo_caja = oldValue('peso_maximo_caja', $aux);
$peso_componente = oldValue('peso_componente', $aux);
$componentes_por_charola = oldValue('componentes_por_charola', $aux);
$mostrar_pestana = oldValue('mostrar_pestana', $aux);
$pestana = oldValue('pestana', $aux, 'NA');
$informacion_adicional_otro_checkbox = oldValue('informacion_adicional_otro_checkbox', $aux);
$informacion_adicional_otro = oldValue('informacion_adicional_otro', $aux);
$ppap_descripcion = oldValue('ppap_descripcion', $aux);//agregar campo en base de datos
$corrida_piloto_descripcion = oldValue('corrida_piloto_descripcion', $aux);//agregar campo en base de datos

@endphp

<fieldset>
    <legend>Cotización Adicional e Información Adicional</legend>
    <div class="form-grid">

        <!-- Cotización Adicional -->
        <div class="form-group">
            <legend id="cotizacion_adicional_legend" class="sub-label">Cotización Adicional</legend>
            <div>
                <input type="hidden" name="ppap" value="0">
                <input type="checkbox" id="ppap" name="ppap" value="1"
                    data-toggle="ppap_descripcion" {{$ppap  ? 'checked' : '' }}>
                <label for="ppap">PPAP</label>
            </div>
            <div class="form-group {{ $ppap ? '' : 'is-hidden' }}" id="ppap_descripcion">
                <label>Descripción PPAP:</label>
                <input type="text" id="ppap_descripcion_input" name="ppap_descripcion"
                    value="{{ $ppap_descripcion }}"
                    placeholder="Describe el alcance o comentarios de PPAP" data-required="true">
            </div>
            <div>
                <input type="hidden" name="corrida_piloto" value="0">
                <input type="checkbox" id="corrida_piloto" name="corrida_piloto" value="1"
                    data-toggle="corrida_piloto_descripcion" {{$corrida_piloto  ? 'checked' : '' }}>
                <label for="corrida_piloto">Corrida piloto</label>
            </div>
            <div class="form-group {{ $corrida_piloto ? '' : 'is-hidden' }}" id="corrida_piloto_descripcion">
                <label>Descripción Corrida Piloto:</label>
                <input type="text" id="corrida_piloto_descripcion_input" name="corrida_piloto_descripcion"
                    value="{{ $corrida_piloto_descripcion }}"
                    placeholder="Describe el alcance o comentarios de Corrida Piloto" data-required="true">
            </div>
            <div>
                <input type="hidden" name="herramentales" value="0">
                <input type="checkbox" id="herramentales" name="herramentales" value="1"
                    {{ $herramentales  ? 'checked' : '' }}>
                <label for="herramentales">Herramentales</label>
            </div>
            <div>
                <input type="hidden" name="almacenaje" value="0">
                <input type="checkbox" id="almacenaje" name="almacenaje" value="1"
                    {{ $almacenaje  ? 'checked' : '' }}>
                <label for="almacenaje">Almacenaje</label>
            </div>
            <div>
                <input type="hidden" name="prototipo" value="0">
                <input type="checkbox" id="prototipo" name="prototipo" value="1"
                   data-toggle="prototipo_descripcion" {{ $prototipo  ? 'checked' : '' }}>
                <label for="prototipo">Prototipo</label>
            </div>
            <div class="form-group {{ $prototipo ? '' : 'is-hidden' }}" id="prototipo_descripcion">
                <label>Descripción Prototipo:</label>
                <input type="text" id="prototipo_descripcion_input" name="prototipo_descripcion"
                    value="{{ $prototipo_descripcion }}"
                    placeholder="Describe el alcance o comentarios de Prototipo" data-required="true">
            </div>
            <div class="form-group">
                <label>Pedimento Virtual:</label>
                <select id="pedimento_virtual" name="pedimento_virtual">
                    <option value="0" {{ $pedimento_virtual== '0' ? 'selected' : '' }}>"Sin pedimento virtual"</option>
                    <option value="1" {{ $pedimento_virtual== '1' ? 'selected' : '' }}>Herramental</option>
                    <option value="1" {{ $pedimento_virtual== '1' ? 'selected' : '' }}>Piezas</option>
                    <option value="2" {{ $pedimento_virtual== '2' ? 'selected' : '' }}>Ambas</option>
                </select>
            </div>
            <div>
                <label for="otros_checkbox">
                    Habilitar Otros
                    <input type="hidden" name="otros_checkbox" value="0">
                    <input type="checkbox" id="otros_checkbox" name="otros_checkbox" value="1" data-toggle="cotizacion_adicional_otros" {{ $otros_checkbox ? 'checked' : '' }}>
                </label>
            </div>
            <div class="form-group {{ $otros_checkbox ? '' : 'is-hidden' }}" id="cotizacion_adicional_otros">
                <label>Otro:</label>
                <input type="text" id="otro1" name="otro1"
                    value="{{ $otro1 }}"
                    placeholder="Otro 1">
                <label>Otro:</label>
                <input type="text" id="otro2" name="otro2"
                    value="{{ $otro2}}"
                    placeholder="Otro 2">
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="form-group">
            <legend id="informacion_adicional_legend" class="sub-label">Información Adicional</legend>
            <label>Altura máxima de estiba (cm):</label>
            <input type="number" id="altura_maxima_estiba" name="altura_maxima_estiba"
                placeholder="0.00 cm" step="0.10" value="{{ $altura_maxima_estiba}}">
            <label>Peso máximo por caja (g):</label>
            <input type="number" id="peso_maximo_caja" name="peso_maximo_caja"
                value="{{ $peso_maximo_caja }}"
                placeholder="Peso máximo por caja en gramos" step="0.10">
            <label>Peso del componente (g):</label>
            <input type="number" id="peso_componente" name="peso_componente"
                value="{{ $peso_componente}}"
                placeholder="Peso del componente" step="0.10">
            <label>Componentes por charola:</label>
            <input type="number" id="componentes_por_charola" name="componentes_por_charola"
                value="{{ $componentes_por_charola }}"
                placeholder="Componentes por charola">
            <div class="form-group">
                <label for="mostrar_pestana">
                    Habilitar Pestaña
                    <input type="hidden" name="mostrar_pestana" value="0">
                    <input type="checkbox" id="mostrar_pestana" name="mostrar_pestana" value="1"
                        {{ $mostrar_pestana ? 'checked' : '' }}
                        data-toggle="pestana_apartado">
                </label>
            </div>
            <div class="form-group {{ $mostrar_pestana ? '' : 'is-hidden' }}" id="pestana_apartado">
                <label>Pestaña:</label>
                <input type="text" id="pestana" name="pestana"
                    value="{{ $pestana ?? 'NA' }}"
                    placeholder="Pestaña">
            </div>
            <div class="form-group">
                <label for="informacion_adicional_otro_checkbox">
                    Habilitar Otro
                    <input type="hidden" name="informacion_adicional_otro_checkbox" value="0">
                    <input type="checkbox" id="informacion_adicional_otro_checkbox" name="informacion_adicional_otro_checkbox" value="1"
                        {{ $informacion_adicional_otro_checkbox ? 'checked' : '' }}
                        data-toggle="informacion_adicional_otro">
                </label>
            </div>
            <div class="form-group {{ $informacion_adicional_otro_checkbox ? '' : 'is-hidden' }}" id="informacion_adicional_otro">
                <label>Otro:</label>
                <input type="text" id="informacion_adicional_otro" name="informacion_adicional_otro"
                    value="{{ $informacion_adicional_otro }}"
                    placeholder="Otro">
            </div>
        </div>
    </div>
</fieldset>