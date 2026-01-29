@extends('layouts.app')

@section('title', 'Dashboard Cotizaciones')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-20">

    <header class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-[#991B1B] leading-tight tracking-wide">
            Dashboard de Gestión de Cotizaciones
        </h1>
        <p class="text-lg text-gray-700 mt-2 max-w-2xl mx-auto border-b-2 border-red-200 pb-2">
            Visualización ejecutiva e integral del rendimiento y estado actual de las cotizaciones.
        </p>
    </header>

    <form method="GET" action="{{ route('home') }}"
        class="bg-white p-4 rounded-2xl shadow-xl shadow-red-100 mb-8 flex flex-col md:flex-row items-center justify-center gap-4 border border-gray-100">

        <div class="flex flex-col">
            <label for="fecha" class="text-base font-semibold text-gray-800 mb-1">Selecciona un día específico:</label>
            <input type="date" id="fecha" name="fecha" value="{{ request('fecha') }}"
                class="border-2 border-red-300 rounded-xl px-4 py-2 text-base text-gray-700 shadow-inner 
                       focus:outline-none focus:ring-4 focus:ring-red-400/50 transition duration-300 w-full">
        </div>

        <div class="flex gap-4 mt-2 md:mt-0">
            <button type="submit"
                class="bg-[#991B1B] text-white px-6 py-2 rounded-xl text-base font-bold shadow-lg shadow-[#991B1B]/40 
                       hover:bg-[#7f1515] hover:shadow-2xl hover:shadow-[#7f1515]/60 transition duration-300 transform hover:scale-105">
                Aplicar Filtro
            </button>
            <a href="{{ route('home') }}"
                class="bg-gray-200 text-gray-800 px-6 py-2 rounded-xl text-base font-bold shadow-md hover:bg-gray-300 
                       transition duration-300 transform hover:scale-105">
                Limpiar
            </a>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">

        @php
        // Definición de estilo para cada tarjeta
        $cardStyles = [
        'pendientes' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-500', 'text' => 'text-yellow-600', 'shadow' => 'shadow-yellow-300/40'],
        'aceptadas' => ['bg' => 'bg-green-50', 'border' => 'border-green-600', 'text' => 'text-green-700', 'shadow' => 'shadow-green-300/40'],
        'rechazadas' => ['bg' => 'bg-red-50', 'border' => 'border-red-600', 'text' => 'text-red-700', 'shadow' => 'shadow-red-300/40'],
        'totales' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-600', 'text' => 'text-blue-700', 'shadow' => 'shadow-blue-300/40'],
        ];
        $data = [
        'pendientes' => ['label' => 'PENDIENTES', 'count' => $pendientes, 'desc' => 'En espera de revisión o respuesta'],
        'aceptadas' => ['label' => 'ACEPTADAS', 'count' => $aceptadas, 'desc' => 'Aprobadas y en producción'],
        'rechazadas' => ['label' => 'RECHAZADAS', 'count' => $rechazadas, 'desc' => 'Oportunidades no concretadas'],
        'totales' => ['label' => 'TOTALES', 'count' => $totales, 'desc' => 'Volumen total de cotizaciones'],
        ];
        @endphp

        @foreach($data as $key => $item)
        @php $style = $cardStyles[$key]; @endphp
        <div class="p-4 rounded-xl border-b-4 {{ $style['border'] }} {{ $style['bg'] }} shadow-lg {{ $style['shadow'] }} 
                    hover:shadow-xl transform hover:scale-[1.02] transition duration-300 ease-in-out">
            <p class="text-xs font-bold tracking-widest text-gray-500 mb-1">{{ $item['label'] }}</p>
            <p class="mt-1 text-4xl font-extrabold {{ $style['text'] }}">{{ $item['count'] }}</p>
            <p class="text-sm text-gray-600 mt-2">{{ $item['desc'] }}</p>
        </div>
        @endforeach

    </div>

    <div class="bg-white p-8 md:p-12 rounded-2xl shadow-2xl shadow-gray-200 mb-16 border border-gray-100">
        <h2 class="text-3xl font-bold text-[#991B1B] mb-8 border-b-4 border-red-100 pb-3 text-center">
            Análisis Gráfico de Cotizaciones
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 justify-items-center">

            <div class="w-full max-w-md bg-gray-50 p-6 rounded-xl shadow-inner border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Distribución de Estados</h3>
                <div style="width: 100%; height: 350px;">
                    <canvas id="cotizacionesChart"></canvas>
                </div>
            </div>

            <div class="w-full max-w-lg bg-gray-50 p-6 rounded-xl shadow-inner border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Cotizaciones por Fecha de Registro</h3>
                <div style="width: 100%; height: 350px;">
                    <canvas id="timelineChart"></canvas>
                    <div class="text-sm text-gray-500 mt-2 text-center">
                        <em>Nota: La gráfica utiliza la columna "Fecha" para reflejar el momento de registro de cada cotización.</em>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
        <div class="bg-white shadow-2xl shadow-red-100 rounded-2xl p-8 border-t-8 border-blue-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-5 pb-3 border-b border-gray-200 flex items-center">
                <span class="mr-3 text-blue-500">🏆</span> Top 5 Clientes por Volumen
            </h2>
            <ol class="list-decimal pl-5 space-y-4 text-gray-700">
                @forelse($top_clientes as $cliente => $cantidad)
                <li class="p-3 border-l-4 border-blue-300 bg-blue-50 rounded-lg flex justify-between items-center hover:bg-blue-100 transition duration-300">
                    <span class="font-semibold text-gray-900">{{ $cliente }}</span>
                    <span class="text-xl font-extrabold text-blue-600">{{ $cantidad }}</span>
                </li>
                @empty
                <li class="text-gray-500 p-4 bg-gray-50 rounded-lg">No hay clientes con cotizaciones registradas.</li>
                @endforelse
            </ol>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ------------------------------------------------------------------
    // --- Gráfica 1: Distribución (Donut) ---
    // ------------------------------------------------------------------
    const ctxDonut = document.getElementById("cotizacionesChart");

    if (ctxDonut) {
        const dataDonut = {
            labels: ["Pendientes", "Aceptadas", "Rechazadas", "Totales"],
            datasets: [{
                data: [{{ $pendientes ?? 0 }}, {{ $aceptadas ?? 0 }}, {{ $rechazadas ?? 0 }}, {{ $totales ?? 0 }}],
                backgroundColor: ["#FACC15", "#22C55E", "#EF4444", "#3B82F6"],
                borderWidth: 2,
                hoverOffset: 12, // Mayor desplazamiento al pasar el ratón
            }]
        };

        const optionsDonut = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "75%", // Más grande el corte central
            plugins: {
                legend: { 
                    position: "bottom",
                    labels: { 
                        usePointStyle: true, 
                        padding: 20 
                    }
                },
                title: { 
                    display: true, 
                    text: "Distribución de Cotizaciones",
                    color: "#991B1B",
                    font: {
                        size: 20,
                        weight: "bold",
                    },
                    padding: { bottom: 20 },
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return '${context.label}: ${context.formattedValue}';
                        },
                    },
                },
            },
        };

        new Chart(ctxDonut, {
            type: "doughnut",
            data: dataDonut,
            options: optionsDonut,
        });
    }

    // ------------------------------------------------------------------
    // --- Gráfica 2: Línea de Tiempo (Línea y Puntos) ---
    // ------------------------------------------------------------------
    const ctxTimeline = document.getElementById("timelineChart");

    if (ctxTimeline) {
        const labels = {!! json_encode($labels_fechas) !!};
        const data = {!! json_encode($data_fechas) !!};

        const dataTimeline = {
            labels: labels,
            datasets: [{
                label: "Cotizaciones Registradas",
                data: data,
                backgroundColor: 'rgba(153, 27, 27, 0.4)', // Fondo de área suave
                borderColor: '#991B1B', 
                borderWidth: 3, // Línea más gruesa
                pointRadius: 6,
                pointBackgroundColor: '#7f1515',
                pointHoverRadius: 8,
                fill: 'origin', // Rellena el área bajo la línea
                tension: 0.3
            }]
        };

        const optionsTimeline = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Cotizaciones',
                        color: '#4B5563',
                        font: { size: 16 }
                    },
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.3)',
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Fecha de Registro (Columna "Fecha")',
                        color: '#4B5563',
                        font: { size: 16 }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: { display: false },
                title: { display: false },
            },
        };

        new Chart(ctxTimeline, {
            type: "line",
            data: dataTimeline,
            options: optionsTimeline,
        });
    }
});
</script>
@endpush