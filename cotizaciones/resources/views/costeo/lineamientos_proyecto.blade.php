@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
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
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-red-600 mb-4">Lineamientos del Proyecto</h2>

                    <div class="space-y-4">
                        <div class="p-4 rounded">
                            <p>Precios en USD. No incluyen I.V.A. Se considera fabricación, facturación y entrega en una sola exhibición.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Los precios pueden ajustarse en respuesta a cambios en aranceles, impuestos o restricciones fiscales y comerciales establecidos por la autoridad.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>La vigencia de la presente cotización es de 12 meses y/o incrementos en MP superior al 5%.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Condiciones de pago son por anticipado.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Tiempo de desarrollo de herramentales y muestras para liberación ( ) semanas.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Tiempo de entrega de producto terminado: <strong>{{ $cotizacion->costeoRequisicion ? ceil((is_numeric($cotizacion->costeoRequisicion->tiempo_pt ?? 0) ? $cotizacion->costeoRequisicion->tiempo_pt : 0) / 5) : 'N/C' }}</strong> semanas (todos los tiempos se confirman con disponibilidad de maquinaria).</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>El producto se entrega en: <strong>{{ $cotizacion->lugar_entrega }}</strong></p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Considerar una variación ±10% en la entrega de producto terminado, sobre lote de producción (MOQ cotizado).</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Especificación de empaque: se confirma después de la 1ª. producción.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>Cualquier condición distinta al escenario cotizado implica una revisión de costos.</p>
                        </div>

                        <div class="p-4 rounded">
                            <p>La responsabilidad respecto de la mercancía producida por INNOVET, es única y exclusivamente por defectos de fabricación. La inspección de la pieza deformada o fuera de calor, causa deformaciones e invalida garantías. Es responsabilidad del CLIENTE aquellos desperfectos que sufra el producto por mal uso, transportación, almacenamiento o análogas derivadas de la actividad del CLIENTE.</p>
                        </div>
                    </div>
                </div>

                <!-- Sección Atentamente -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-red-600 mb-4">Atentamente</h3>
            <p class="mb-2 text-left">
                {{-- AÑADIMOS UN ID --}}
                <input id="nombre_contacto_input" name="nombre_contacto" value="{{ Auth::user()->name }}" class="w-full bg-transparent border-none p-0 m-0 focus:ring-0 focus:outline-none">
            </p>
            <p class="text-left">
                {{-- AÑADIMOS UN ID --}}
                <input id="puesto_contacto_input" name="puesto_contacto" value="Puesto" class="w-full bg-transparent border-none p-0 m-0 focus:ring-0 focus:outline-none">
            </p>
        </div>

        <!-- Pie de página -->
        <div class=" mt-8 pt-4 border-t border-gray-300 text-center text-sm text-gray-600">
            <p>Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
            <p class="mt-2">ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Función para generar el documento
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
                // ¡IMPORTANTE! Asegúrate que esta sea tu ruta de PDF
                baseUrl = "{{ route('cotizacion.lineamientos.pdf', ['id' => $cotizacion->id]) }}"; 
            } else if (tipo === 'excel') {
                // Esta es la ruta que ya tenías en tu botón <a>
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
_blank
        });

        document.getElementById('btn-generar-excel').addEventListener('click', function() {
            generarDocumento('excel');
        });

    });
</script>
@endsection