@extends('layouts.app')

@section('title', 'Matriz de Riesgos')

@section('content')

<!--
    SOLUCIÓN DEFINITIVA PARA TAILWIND JIT (Purge):
    Este div oculto "engaña" a Tailwind para que "vea" las clases generadas dinámicamente
    en PHP y no las elimine (purgen) del archivo CSS final.
-->
<div class="hidden">
    <!-- Todas las combinaciones de colores usadas en la matriz principal (DEBE QUEDAR EXPLICITO) -->
    <span class="bg-green-400 text-gray-900"></span>
    <span class="bg-yellow-500 text-gray-900"></span>
    <span class="bg-orange-500 text-gray-900"></span>
    <span class="bg-red-600 text-white"></span>
    <!-- Clases usadas en otros bloques (Status, etc.) -->
    <span class="bg-green-500 text-white"></span>
    <span class="bg-red-600 text-white"></span>
</div>


<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">

    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">📊 MATRIZ DE RIESGOS</h1>
    
    <!-- INFORMACIÓN DEL PROYECTO -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg shadow-lg border border-blue-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
            <div class="flex items-center">
                <span class="font-bold text-gray-700 mr-2">📁 Proyecto:</span>
                <span class="text-gray-800">{{ $cotizacion->no_proyecto }}</span>
            </div>
            <div class="flex items-center">
                <span class="font-bold text-gray-700 mr-2">👤 Cliente:</span>
                <span class="text-gray-800">{{ $cotizacion->cliente ?? 'No especificado' }}</span>
            </div>
            <div class="flex items-center">
                <span class="font-bold text-gray-700 mr-2">📅 Fecha:</span>
                <span class="text-gray-800">{{ $cotizacion->fecha }}</span>
            </div>
            @if($cotizacion->nombre_del_proyecto)
                <div class="flex items-center">
                    <span class="font-bold text-gray-700 mr-2">🏷️ Nombre del Proyecto:</span>
                    <span class="text-gray-800">{{ $cotizacion->nombre_del_proyecto }}</span>
                </div>
            @endif
        </div>
    </div>

    @php
        // --- DEFINICIÓN CENTRAL DE COLORES (Usada en la leyenda y otros elementos fijos) ---
        $niveles = [
            'Riesgo aceptable' => 'bg-green-400 text-gray-900',      // Verde (1-4)
            'Riesgo tolerable' => 'bg-yellow-500 text-gray-900',     // Amarillo (5-9)
            'Riesgo alto'      => 'bg-orange-500 text-gray-900',     // Naranja (10-14)
            'Riesgo extremo'   => 'bg-red-600 text-white',           // Rojo (15-25)
        ];

        $consequences = [
            ['text' => 'Mínima', 'value' => 1],
            ['text' => 'Moderada', 'value' => 2],
            ['text' => 'Media', 'value' => 3],
            ['text' => 'Alta', 'value' => 4],
            ['text' => 'Inaceptable', 'value' => 5],
        ];

        $probability_headers = ['Improbable', 'Poco probable', 'Probable', 'Moderada', 'Constante'];

        // Valores de la matriz para el renderizado
        $matrixValues = [
            [1, 2, 3, 4, 5],    // Mínima
            [2, 4, 6, 8, 10],   // Moderada
            [3, 6, 9, 12, 15],  // Media
            [4, 8, 12, 16, 20], // Alta
            [5, 10, 15, 20, 25] // Inaceptable
        ];
        
        // --- DATOS REALES DE LA BASE DE DATOS ---
        // Obtener el estado real de la cotización desde la base de datos
        $estado_actual = $cotizacion->estado ?? 'pendiente';

        // Determina el texto y las clases del estado basado en la BD
        $estado_info = match($estado_actual) {
            'aceptada' => [
                'text' => 'PROYECTO ACEPTADO', 
                'class' => 'bg-green-500 text-white'
            ],
            'rechazada' => [
                'text' => 'PROYECTO RECHAZADO', 
                'class' => 'bg-red-600 text-white'
            ],
            default => [
                'text' => 'PROYECTO PENDIENTE', 
                'class' => 'bg-yellow-500 text-gray-900'
            ],
        };
        
    @endphp

    <!-- PRIMERA SECCIÓN: MATRIZ Y LEYENDA (Flexbox) -->
    <div class="flex flex-col lg:flex-row gap-6 mb-8">

        <!-- MATRIZ PRINCIPAL (Tabla más grande a la izquierda) -->
        <div class="overflow-x-auto flex-grow rounded-lg shadow-xl">
            <table class="w-full text-center border border-gray-400 text-sm">
                
                <!-- TÍTULO MATRIZ -->
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th colspan="7" class="py-2 text-lg font-extrabold border border-gray-400">MATRIZ DE RIESGOS</th>
                    </tr>
                </thead>

                <!-- ENCABEZADOS DE PROBABILIDAD Y CONSECUENCIA -->
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th colspan="2" rowspan="2" class="border px-2 py-2">Consecuencia</th>
                        <th colspan="5" class="border px-2 py-2">Probabilidad</th>
                    </tr>
                    <tr>
                        @foreach($probability_headers as $text)
                            <th class="border px-2 py-2">{{ $text }}</th>
                        @endforeach
                    </tr>
                </thead>
                
                <!-- CUERPO DE LA MATRIZ CON VALORES Y COLORES (ASIGNACIÓN CON ESTILOS INLINE) -->
                <tbody>
                    @foreach($consequences as $i => $cons)
                        <tr>
                            <!-- Consecuencia (Nombre) -->
                            <td class="border w-1/6 font-semibold px-3 py-2 bg-gray-300 text-gray-800 text-left">{{ $cons['text'] }}</td>
                            <!-- Consecuencia (Valor Numérico) -->
                            <td class="border w-1/12 font-bold px-3 py-2 bg-gray-400 text-gray-800">{{ $cons['value'] }}</td>
                            
                            <!-- Valores de Riesgo (Colored Cells) -->
                            @foreach($matrixValues[$i] as $value)
                                @php
                                    // ASIGNACIÓN DE COLORES Y CLASES PARA TAILWIND Y ESTILOS INLINE
                                    $tailwindClass = '';
                                    $hexColor = '#f3f4f6'; // gray-100
                                    $textColor = '#1f2937'; // gray-800/900

                                    if ($value <= 4) {
                                        $tailwindClass = 'bg-green-400 text-gray-900';
                                        $hexColor = '#48BB78'; // Green 400
                                    } elseif ($value <= 9) {
                                        $tailwindClass = 'bg-yellow-500 text-gray-900';
                                        $hexColor = '#ECC94B'; // Yellow 500
                                    } elseif ($value <= 14) {
                                        $tailwindClass = 'bg-orange-500 text-gray-900';
                                        $hexColor = '#ED8936'; // Orange 500
                                    } else { // 15 to 25
                                        $tailwindClass = 'bg-red-600 text-white';
                                        $hexColor = '#E53E3E'; // Red 600
                                        $textColor = 'white';
                                    }
                                    // Forzar el color con estilo inline (mayor prioridad)
                                    $styleAttr = "style='background-color: $hexColor; color: $textColor;'";
                                @endphp
                                <td class="border font-bold py-3 {{ $tailwindClass }}" {!! $styleAttr !!}>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- LEYENDA DE NIVELES (Mini tabla compacta) -->
        <div class="lg:w-80 flex-shrink-0 self-start">
            <div class="bg-white rounded-lg shadow-xl border border-gray-300 overflow-hidden">
            <div class="bg-gray-600 text-white text-center py-1">
                <h4 class="font-bold text-sm">🎯 LEYENDA DE RIESGOS</h4>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-green-400 text-gray-900 font-bold rounded text-center min-w-[60px] text-xs">1 - 4</div>
                    <div class="font-medium text-gray-800 text-xs">Riesgo aceptable</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-yellow-500 text-gray-900 font-bold rounded text-center min-w-[60px] text-xs">5 - 9</div>
                    <div class="font-medium text-gray-800 text-xs">Riesgo tolerable</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-orange-500 text-gray-900 font-bold rounded text-center min-w-[60px] text-xs">10 - 14</div>
                    <div class="font-medium text-gray-800 text-xs">Riesgo alto</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-red-600 text-white font-bold rounded text-center min-w-[60px] text-xs">15 - 25</div>
                    <div class="font-medium text-gray-800 text-xs">Riesgo extremo</div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- SEGUNDA SECCIÓN: STATUS Y MITIGACIÓN -->
    <!-- TABLA DE RIESGOS DETALLADA -->
    <div class="mb-8">
        <form action="{{ route('cotizaciones.actualizar-mitigacion', $cotizacion->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gray-700 text-white text-center py-3">
                    <h3 class="text-xl font-bold">📋 IDENTIFICACIÓN Y EVALUACIÓN DE RIESGOS</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-600 text-white">
                            <tr>
                                <th class="border border-gray-400 px-4 py-2 w-1/4">Riesgo</th>
                                <th class="border border-gray-400 px-4 py-2 w-1/4">Severidad</th>
                                <th class="border border-gray-400 px-4 py-2 w-1/4">Probabilidad</th>
                                <th class="border border-gray-400 px-4 py-2 w-1/4">Nivel de Riesgo</th>
                                <th class="border border-gray-400 px-2 py-2 w-10">
                                    <button type="button" onclick="agregarFila()" class="text-green-400 hover:text-green-300 text-xl" title="Agregar nueva fila">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabla-riesgos">
                            @php
                                $riesgosExistentes = $cotizacion->matrizRiesgos ?? collect([]);
                                $riesgosDisponibles = [
                                    'Arañas en contorno',
                                    'Arañas en cavidades',
                                    'Adelgazamiento de paredes',
                                    'Adelgazamiento de fondo de cavidades',
                                    'Poca funcionalidad de broche',
                                    'Blanqueado',
                                    'Opaco',
                                    'Desfase de pestañas',
                                    'Redondeo',
                                    'Contracción de material',
                                    'Riesgo de funcionalidad de estiba',
                                    'Entregas fuera de tiempo establecido'
                                ];
                            @endphp
                            
                            @foreach($riesgosExistentes as $index => $riesgo)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-2 py-2">
                                    <select name="riesgos[{{ $index }}][riesgo]" class="w-full border-gray-300 rounded px-2 py-1 text-sm" required>
                                        @foreach($riesgosDisponibles as $riesgoOpt)
                                            <option value="{{ $riesgoOpt }}" {{ $riesgo->riesgo == $riesgoOpt ? 'selected' : '' }}>
                                                {{ $riesgoOpt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <select name="riesgos[{{ $index }}][severidad]" class="w-full border-gray-300 rounded px-2 py-1 text-sm severidad-select" onchange="calcularNivelRiesgo(this)" required>
                                        <option value="Mínima" {{ $riesgo->severidad == 'Mínima' ? 'selected' : '' }}>Mínima</option>
                                        <option value="Moderada" {{ $riesgo->severidad == 'Moderada' ? 'selected' : '' }}>Moderada</option>
                                        <option value="Media" {{ $riesgo->severidad == 'Media' ? 'selected' : '' }}>Media</option>
                                        <option value="Alta" {{ $riesgo->severidad == 'Alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="Inaceptable" {{ $riesgo->severidad == 'Inaceptable' ? 'selected' : '' }}>Inaceptable</option>
                                    </select>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <select name="riesgos[{{ $index }}][probabilidad]" class="w-full border-gray-300 rounded px-2 py-1 text-sm probabilidad-select" onchange="calcularNivelRiesgo(this)" required>
                                        <option value="Improbable" {{ $riesgo->probabilidad == 'Improbable' ? 'selected' : '' }}>Improbable</option>
                                        <option value="Poco probable" {{ $riesgo->probabilidad == 'Poco probable' ? 'selected' : '' }}>Poco probable</option>
                                        <option value="Probable" {{ $riesgo->probabilidad == 'Probable' ? 'selected' : '' }}>Probable</option>
                                        <option value="Moderada" {{ $riesgo->probabilidad == 'Moderada' ? 'selected' : '' }}>Moderada</option>
                                        <option value="Constante" {{ $riesgo->probabilidad == 'Constante' ? 'selected' : '' }}>Constante</option>
                                    </select>
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <span class="nivel-riesgo px-3 py-1 rounded font-bold text-xs inline-block w-full" 
                                          data-nivel="{{ $riesgo->nivel_riesgo }}">
                                        {{ $riesgo->nivel_riesgo }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <button type="button" onclick="eliminarFila(this)" class="text-red-600 hover:text-red-800 text-lg" title="Eliminar fila">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 bg-gray-50 text-center">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300 font-bold shadow-lg">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Matriz de Riesgos
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- COLUMNA 1: STATUS DEL PROYECTO Y BOTONES -->
        <div class="bg-white p-4 rounded-lg shadow-xl border border-gray-200">
            
            <!-- INDICADOR DE ESTADO DEL PROYECTO -->
            <div class="mb-6 text-center">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Estado del Proyecto</h3>
                <span class="inline-block px-6 py-3 rounded-lg text-lg font-bold {{ $estado_info['class'] }}">
                    {{ $estado_info['text'] }}
                </span>
            </div>

            <!-- Botones usando la ruta PATCH de Laravel -->
            <div class="flex flex-col gap-3">
                
                {{-- Botón Aceptar Proyecto --}}
                {{-- La acción se envía al controlador para actualizar el estado a 'aceptada' --}}
                <form action="{{ route('cotizaciones.actualizar-estado', $cotizacion->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="estado" value="aceptada">
                    
                    <button type="submit" 
                            class="w-full bg-green-600 text-white px-4 py-3 rounded-xl shadow-md hover:bg-green-700 transition duration-300 transform hover:scale-[1.01]">
                        ✅ Aceptar Proyecto
                    </button>
                </form>

                {{-- Botón Rechazar Proyecto --}}
                {{-- La acción se envía al controlador para actualizar el estado a 'rechazada' --}}
                <form action="{{ route('cotizaciones.actualizar-estado', $cotizacion->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="estado" value="rechazada">
                    
                    <button type="submit" 
                            class="w-full bg-red-600 text-white px-4 py-3 rounded-xl shadow-md hover:bg-red-700 transition duration-300 transform hover:scale-[1.01]">
                        ❌ Rechazar Proyecto
                    </button>
                </form>
            </div>
            
            {{-- Botón Restablecer a Pendiente --}}
            <div class="mt-2 text-center">
                 <form action="{{ route('cotizaciones.actualizar-estado', $cotizacion->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="estado" value="pendiente">
                    <button type="submit" 
                            class="w-full text-sm text-gray-500 hover:text-gray-700 transition duration-300">
                        (Restablecer estado)
                    </button>
                </form>
            </div>

        </div>

        <!-- COLUMNA 2: PLAN DE MITIGACIÓN GENERAL -->
        <div class="bg-white p-4 rounded-lg shadow-xl border border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-800 text-center border-b pb-2">Plan de mitigación de riesgos</h3>
            
            <form action="{{ route('cotizaciones.actualizar-mitigacion-general', $cotizacion->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                
                <!-- Campo para el título/estado del plan -->
                <div class="mb-4">
                    <label for="plan_mitigacion_titulo" class="block text-sm font-medium text-gray-700 mb-2">Estado del Plan:</label>
                    <input type="text" 
                           id="plan_mitigacion_titulo" 
                           name="plan_mitigacion_titulo"
                           value="{{ $cotizacion->plan_mitigacion_titulo ?? 'No necesario' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center font-bold text-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Estado del plan de mitigación">
                </div>
                
                <!-- Campo para la descripción -->
                <div class="mb-4">
                    <label for="plan_mitigacion_descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción:</label>
                    <textarea id="plan_mitigacion_descripcion" 
                              name="plan_mitigacion_descripcion"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 italic text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                              placeholder="Descripción del plan de mitigación">{{ $cotizacion->plan_mitigacion_descripcion ?? 'En caso de que el nivel de riesgo sea rojo, se generará un plan de mitigación' }}</textarea>
                </div>
                
                <!-- Botón para guardar -->
                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-medium">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Plan de Mitigación
                    </button>
                </div>
            </form>
        </div>
        
    </div>
    </div>
</div>

<script>
    let filaIndex = {{ count($riesgosExistentes) }};
    
    const riesgosDisponibles = @json($riesgosDisponibles);
    
    const severidadValores = {
        'Mínima': 1,
        'Moderada': 2,
        'Media': 3,
        'Alta': 4,
        'Inaceptable': 5
    };
    
    const probabilidadValores = {
        'Improbable': 1,
        'Poco probable': 2,
        'Probable': 3,
        'Moderada': 4,
        'Constante': 5
    };
    
    function agregarFila() {
        const tbody = document.getElementById('tabla-riesgos');
        const nuevaFila = document.createElement('tr');
        nuevaFila.className = 'hover:bg-gray-50';
        
        let optionsRiesgo = '';
        riesgosDisponibles.forEach(riesgo => {
            optionsRiesgo += `<option value="${riesgo}">${riesgo}</option>`;
        });
        
        nuevaFila.innerHTML = `
            <td class="border border-gray-300 px-2 py-2">
                <select name="riesgos[${filaIndex}][riesgo]" class="w-full border-gray-300 rounded px-2 py-1 text-sm" required>
                    ${optionsRiesgo}
                </select>
            </td>
            <td class="border border-gray-300 px-2 py-2">
                <select name="riesgos[${filaIndex}][severidad]" class="w-full border-gray-300 rounded px-2 py-1 text-sm severidad-select" onchange="calcularNivelRiesgo(this)" required>
                    <option value="Mínima">Mínima</option>
                    <option value="Moderada">Moderada</option>
                    <option value="Media" selected>Media</option>
                    <option value="Alta">Alta</option>
                    <option value="Inaceptable">Inaceptable</option>
                </select>
            </td>
            <td class="border border-gray-300 px-2 py-2">
                <select name="riesgos[${filaIndex}][probabilidad]" class="w-full border-gray-300 rounded px-2 py-1 text-sm probabilidad-select" onchange="calcularNivelRiesgo(this)" required>
                    <option value="Improbable">Improbable</option>
                    <option value="Poco probable">Poco probable</option>
                    <option value="Probable" selected>Probable</option>
                    <option value="Moderada">Moderada</option>
                    <option value="Constante">Constante</option>
                </select>
            </td>
            <td class="border border-gray-300 px-2 py-2 text-center">
                <span class="nivel-riesgo px-3 py-1 rounded font-bold text-xs inline-block w-full bg-yellow-500 text-gray-900" 
                      data-nivel="Riesgo tolerable">
                    Riesgo tolerable
                </span>
            </td>
            <td class="border border-gray-300 px-2 py-2 text-center">
                <button type="button" onclick="eliminarFila(this)" class="text-red-600 hover:text-red-800 text-lg" title="Eliminar fila">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(nuevaFila);
        filaIndex++;
    }
    
    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
    }
    
    function calcularNivelRiesgo(selectElement) {
        const fila = selectElement.closest('tr');
        const severidadSelect = fila.querySelector('.severidad-select');
        const probabilidadSelect = fila.querySelector('.probabilidad-select');
        const nivelSpan = fila.querySelector('.nivel-riesgo');
        
        const severidad = severidadSelect.value;
        const probabilidad = probabilidadSelect.value;
        
        const valorSeveridad = severidadValores[severidad] || 0;
        const valorProbabilidad = probabilidadValores[probabilidad] || 0;
        const valorRiesgo = valorSeveridad * valorProbabilidad;
        
        let nivelRiesgo = '';
        let colorClass = '';
        
        if (valorRiesgo <= 4) {
            nivelRiesgo = 'Riesgo aceptable';
            colorClass = 'bg-green-400 text-gray-900';
        } else if (valorRiesgo <= 9) {
            nivelRiesgo = 'Riesgo tolerable';
            colorClass = 'bg-yellow-500 text-gray-900';
        } else if (valorRiesgo <= 14) {
            nivelRiesgo = 'Riesgo alto';
            colorClass = 'bg-orange-500 text-gray-900';
        } else {
            nivelRiesgo = 'Riesgo extremo';
            colorClass = 'bg-red-600 text-white';
        }
        
        nivelSpan.textContent = nivelRiesgo;
        nivelSpan.className = `nivel-riesgo px-3 py-1 rounded font-bold text-xs inline-block w-full ${colorClass}`;
        nivelSpan.setAttribute('data-nivel', nivelRiesgo);
    }
    
    // Aplicar colores a los niveles de riesgo existentes al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nivel-riesgo').forEach(span => {
            const nivel = span.getAttribute('data-nivel');
            let colorClass = '';
            
            switch(nivel) {
                case 'Riesgo aceptable':
                    colorClass = 'bg-green-400 text-gray-900';
                    break;
                case 'Riesgo tolerable':
                    colorClass = 'bg-yellow-500 text-gray-900';
                    break;
                case 'Riesgo alto':
                    colorClass = 'bg-orange-500 text-gray-900';
                    break;
                case 'Riesgo extremo':
                    colorClass = 'bg-red-600 text-white';
                    break;
            }
            
            span.className = `nivel-riesgo px-3 py-1 rounded font-bold text-xs inline-block w-full ${colorClass}`;
        });

        // Mostrar modal de éxito si hay mensaje de sesión
        @if(session('success'))
            showSuccessMessage("{{ session('success') }}");
        @endif

        // Mostrar modal de error si hay mensaje de error
        @if(session('error'))
            showErrorMessage("{{ session('error') }}");
        @endif
    });
</script>

@endsection