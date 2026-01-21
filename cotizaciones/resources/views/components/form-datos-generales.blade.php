@props(['cotizacion' => null])

@php
$aux = optional($cotizacion);

$fecha = oldValue('fecha', $aux);
$no_proyecto = oldValue('no_proyecto', $aux);
$cliente = oldValue('cliente', $aux);
$contacto = oldValue('contacto', $aux);
$puesto = oldValue('puesto', $aux);
$domicilio = oldValue('domicilio', $aux);
$lugar_entrega = oldValue('lugar_entrega', $aux);
$telefono = oldValue('telefono', $aux);
$correo = oldValue('correo', $aux);
$nombre_del_proyecto = oldValue('nombre_del_proyecto', $aux);
$tipo_de_empaque = oldValue('tipo_de_empaque', $aux);

@endphp

<fieldset>
    <legend>Datos Generales</legend>
    <div class="form-grid">
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha"
                value="{{ $fecha ? date($fecha) : date('Y-m-d') }}"
                title="Seleccione la fecha de la requisición"
                placeholder="dd/mm/aaaa" required>
        </div>

        <div class="form-group">
            <label for="no_proyecto">No. Proyecto: <span class="text-red-600">*</span></label>
            <input type="text" id="no_proyecto" name="no_proyecto"
                value="{{ $no_proyecto }}"
                title="Ingrese el número de proyecto"
                placeholder="Ej. II_XXX_X_XX_X" required>
        </div>

        <div class="form-group">
            <label for="cliente">Cliente: <span class="text-red-600">*</span></label>
            <input type="text" id="cliente" name="cliente"
                value="{{ $cliente }}"
                title="Ingrese el nombre del cliente"
                placeholder="Nombre del cliente" required>
        </div>

        <div class="form-group">
            <label for="contacto">Contacto: <span class="text-red-600">*</span></label>
            <input type="text" id="contacto" name="contacto"
                value="{{ $contacto }}"
                title="Ingrese el nombre del contacto"
                placeholder="Persona de contacto" required>
        </div>

        <div class="form-group">
            <label for="puesto">Puesto:</label>
            <input type="text" id="puesto" name="puesto"
                value="{{ $puesto }}"
                title="Ingrese el puesto del contacto"
                placeholder="Puesto del contacto">
        </div>

        <div class="form-group">
            <label for="domicilio">Domicilio: <span class="text-red-600">*</span></label>
            <input type="text" id="domicilio" name="domicilio"
                value="{{ $domicilio }}"
                title="Ingrese el domicilio del cliente"
                placeholder="Domicilio del cliente" required>
        </div>

        <div class="form-group">
            <label for="lugar_entrega">Lugar de entrega: <span class="text-red-600">*</span></label>
            <input type="text" id="lugar_entrega" name="lugar_entrega"
                value="{{ $lugar_entrega }}"
                title="Ingrese el lugar de entrega"
                placeholder="Lugar de entrega" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono: <span class="text-orange-600">*</span></label>
            <input type="tel" id="telefono" name="telefono"
                value="{{ $telefono }}"
                title="Ingrese al menos un teléfono o correo electrónico"
                placeholder="Teléfono de contacto">
            <small class="text-gray-500 text-xs">* Obligatorio: Teléfono o Correo</small>
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico: <span class="text-orange-600">*</span></label>
            <input type="email" id="correo" name="correo"
                value="{{ $correo}}"
                title="Ingrese al menos un teléfono o correo electrónico"
                placeholder="Correo electrónico de contacto">
            <small class="text-gray-500 text-xs">* Obligatorio: Teléfono o Correo</small>
        </div>

        <div class="form-group">
            <label for="nombre_del_proyecto">Nombre del proyecto: <span class="text-red-600">*</span></label>
            <input type="text" id="nombre_del_proyecto" name="nombre_del_proyecto"
                value="{{ $nombre_del_proyecto }}"
                title="Ingrese el nombre del proyecto"
                placeholder="Nombre del proyecto" required>
        </div>

        <div class="form-group">
            <label for="tipo_de_empaque">Tipo de empaque: <span class="text-red-600">*</span></label>
            <select id="tipo_de_empaque" name="tipo_de_empaque" title="Seleccione el tipo de empaque" required>
                <option value="" {{ $tipo_de_empaque == '' ? 'selected' : '' }}>Selecciona una opción</option>
                <option value="Clamshell" {{ $tipo_de_empaque == 'Clamshell' ? 'selected' : '' }}>Clamshell</option>
                <option value="Charola" {{ $tipo_de_empaque == 'Charola' ? 'selected' : '' }}>Charola</option>
                <option value="Blister" {{ $tipo_de_empaque == 'Blister' ? 'selected' : '' }}>Blister</option>
                <option value="Tapa" {{ $tipo_de_empaque == 'Tapa' ? 'selected' : '' }}>Tapa</option>
                <option value="Charola Gran Formato" {{ $tipo_de_empaque == 'Charola Gran Formato' ? 'selected' : '' }}>Charola Gran Formato</option>
                <option value="Tarima Gran Formato" {{ $tipo_de_empaque == 'Tarima Gran Formato' ? 'selected' : '' }}>Tarima Gran Formato</option>
            </select>
        </div>
    </div>
</fieldset>

<script>
    // Validación personalizada: al menos teléfono o correo debe estar lleno
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const telefonoInput = document.getElementById('telefono');
        const correoInput = document.getElementById('correo');

        if (form && telefonoInput && correoInput) {
            form.addEventListener('submit', function(e) {
                const telefono = telefonoInput.value.trim();
                const correo = correoInput.value.trim();

                // Si ambos están vacíos, prevenir el envío
                if (!telefono && !correo) {
                    e.preventDefault();
                    
                    // Marcar los campos como inválidos
                    telefonoInput.setCustomValidity('Debe proporcionar al menos un teléfono o correo electrónico');
                    correoInput.setCustomValidity('Debe proporcionar al menos un teléfono o correo electrónico');
                    
                    // Mostrar el mensaje de validación
                    telefonoInput.reportValidity();
                    
                    // Agregar borde rojo a ambos campos
                    telefonoInput.classList.add('border-red-500');
                    correoInput.classList.add('border-red-500');
                    
                    return false;
                } else {
                    // Limpiar validaciones personalizadas si hay datos
                    telefonoInput.setCustomValidity('');
                    correoInput.setCustomValidity('');
                    telefonoInput.classList.remove('border-red-500');
                    correoInput.classList.remove('border-red-500');
                }
            });

            // Limpiar el mensaje de error cuando el usuario empiece a escribir
            telefonoInput.addEventListener('input', function() {
                if (telefonoInput.value.trim() || correoInput.value.trim()) {
                    telefonoInput.setCustomValidity('');
                    correoInput.setCustomValidity('');
                    telefonoInput.classList.remove('border-red-500');
                    correoInput.classList.remove('border-red-500');
                }
            });

            correoInput.addEventListener('input', function() {
                if (telefonoInput.value.trim() || correoInput.value.trim()) {
                    telefonoInput.setCustomValidity('');
                    correoInput.setCustomValidity('');
                    telefonoInput.classList.remove('border-red-500');
                    correoInput.classList.remove('border-red-500');
                }
            });
        }
    });
</script>