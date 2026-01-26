@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Lineamientos del Proyecto</h1>
                <p class="text-gray-600">Folio: {{ $cotizacion->no_proyecto }}</p>
                <p class="text-gray-600">Fecha: {{ $cotizacion->fecha }}</p>
            </div>

            <!-- Botones para generar PDF y Excel -->
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">

            <!-- Botón para generar Excel (Modificado de tu <a>) -->
            <button type="button" id="btn-generar-excel"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center w-full sm:w-auto">
                <i class="fas fa-file-excel mr-2"></i>
                Descargar Excel
            </button>

            <!-- Botón para generar PDF (Nuevo) -->
            <button type="button" id="btn-generar-pdf"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center w-full sm:w-auto">
                <!-- Icono de PDF -->
                <i class="fas fa-file-pdf mr-2"></i>
                Descargar PDF
            </button>

        </div>
                </div>

                <!-- Información del Cliente -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Información del Cliente</h3>
                    <p><strong>Cliente:</strong> {{ $cotizacion->cliente }}</p>
                    <p><strong>Proyecto:</strong> {{ $cotizacion->nombre_del_proyecto }}</p>
                    <p><strong>Email:</strong> {{ $cotizacion->correo }}</p>
                    <p><strong>Teléfono:</strong> {{ $cotizacion->telefono }}</p>
                </div>

                <!-- Lineamientos -->
                <form id="form-lineamientos" method="POST" action="{{ route('cotizacion.lineamientos.save', $cotizacion->id) }}" class="mb-6">
                    @csrf
                    @method('PUT')
                    
                    <h2 class="text-xl font-semibold text-red-600 mb-4">Lineamientos del Proyecto</h2>

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

                    <!-- Botón para guardar lineamientos -->
                    <div class="mt-6 mb-6">
                        <button type="button" id="btn-guardar-lineamientos" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            💾 Guardar Lineamientos
                        </button>
                    </div>

                    <!-- Sección Atentamente dentro del formulario -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-300">
                        <h3 class="text-lg font-semibold text-red-600 mb-4">Atentamente</h3>
                        <p class="mb-2 text-left">
                            <input id="nombre_contacto_input" name="nombre_contacto" value="{{ $cotizacion->nombre_contacto ?? Auth::user()->name }}" class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-red-600 focus:outline-none">
                        </p>
                        <p class="text-left">
                            <input id="puesto_contacto_input" name="puesto_contacto" value="{{ $cotizacion->puesto_contacto ?? 'Puesto' }}" class="w-full bg-white border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-red-600 focus:outline-none" placeholder="Ingrese su puesto">
                        </p>
                    </div>
                </form>

        <!-- Pie de página -->
        <div class=" mt-8 pt-4 border-t border-gray-300 text-center text-sm text-gray-600">
            <p>Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
            <p class="mt-2">ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ===== GUARDAR LINEAMIENTOS CON AJAX =====
        const btnGuardar = document.getElementById('btn-guardar-lineamientos');
        if (btnGuardar) {
            btnGuardar.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Crear FormData con todos los datos del formulario
                const form = document.getElementById('form-lineamientos');
                const formData = new FormData(form);
                
                console.log('Guardando lineamientos...');
                console.log('URL:', "{{ route('cotizacion.lineamientos.save', $cotizacion->id) }}");
                
                // Enviar con AJAX
                fetch("{{ route('cotizacion.lineamientos.save', $cotizacion->id) }}", {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Status:', response.status);
                    console.log('OK:', response.ok);
                    
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response:', text);
                            throw new Error(`Error ${response.status}: ${text}`);
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Success response:', data);
                    // Mostrar mensaje de éxito
                    showSuccessMessage('✅ Lineamientos guardados correctamente');
                    
                    // Opcional: actualizar la página después de 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    showErrorMessage('❌ Error al guardar los lineamientos: ' + error.message);
                });
            });
        } else {
            console.warn('Botón btn-guardar-lineamientos no encontrado');
        }

        // Función para mostrar mensaje de éxito
        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded shadow-lg z-50';
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => alertDiv.remove(), 3000);
        }

        // Función para mostrar mensaje de error
        function showErrorMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded shadow-lg z-50';
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => alertDiv.remove(), 3000);
        }
        
        // ===== GENERAR DOCUMENTOS (Excel/PDF) =====
        function generarDocumento(tipo) {
            // 1. Leer los valores actuales de los inputs
            var nombre = document.getElementById('nombre_contacto_input').value;
            var puesto = document.getElementById('puesto_contacto_input').value;

            // 2. Construir los parámetros de la URL
            var params = new URLSearchParams();
            params.append('nombre_contacto', nombre);
            params.append('puesto_contacto', puesto);
            
            var baseUrl = '';

            // 3. Obtener la URL base correcta
            if (tipo === 'pdf') {
                baseUrl = "{{ route('cotizacion.lineamientos.pdf', ['id' => $cotizacion->id]) }}"; 
            } else if (tipo === 'excel') {
                baseUrl = "{{ route('cotizacion.lineamientos.excel', ['id' => $cotizacion->id]) }}"; 
            }

            // 4. Combinar la URL base + los parámetros
            var fullUrl = baseUrl + '?' + params.toString();

            // 5. Abrir la URL en una nueva pestaña
            window.open(fullUrl, '_blank');
        }

        // Asignar los eventos a los botones
        document.getElementById('btn-generar-pdf').addEventListener('click', function() {
            generarDocumento('pdf');
        });

        document.getElementById('btn-generar-excel').addEventListener('click', function() {
            generarDocumento('excel');
        });

    });
</script>
@endsection