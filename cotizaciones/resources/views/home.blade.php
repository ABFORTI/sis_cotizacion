@extends('layouts.app')

@section('title', 'Dashboard Cotizaciones')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap');

    .dash-root * { font-family: 'DM Sans', sans-serif; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim { animation: fadeUp 0.45s ease both; }

    .dash-header {
        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 50%, #b91c1c 100%);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    .dash-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .dash-header::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    .metric-card {
        position: relative;
        overflow: hidden;
        transition: transform 0.28s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s ease;
    }
    .metric-card:hover { transform: translateY(-4px); }

    .metric-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 1rem 1rem 0 0;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.22,1,0.36,1);
    }
    .metric-card:hover::after { transform: scaleX(1); }

    .metric-card--pending::after  { background: #94a3b8; }
    .metric-card--accepted::after { background: #22c55e; }
    .metric-card--rejected::after { background: #ef4444; }
    .metric-card--total::after    { background: #991b1b; }

    .metric-value {
        font-family: 'DM Mono', monospace;
        font-feature-settings: 'tnum';
        transition: transform 0.2s ease;
    }
    .metric-card:hover .metric-value { transform: scale(1.05); }

    .metric-icon {
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
    }
    .metric-card:hover .metric-icon { transform: scale(1.15) rotate(-5deg); }

    .filter-card {
        background: #fafafa;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
    }

    .chart-card {
        border-radius: 1.25rem;
        transition: box-shadow 0.25s ease;
    }
    .chart-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.07); }

    .client-row {
        border-radius: 0.6rem;
        transition: background 0.15s ease, padding 0.15s ease;
        cursor: default;
    }
    .client-row:hover { background: #fef2f2; padding-inline: 0.75rem; }

    .rank-badge {
        width: 1.6rem; height: 1.6rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        font-size: 0.7rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .pill {
        display: inline-flex; align-items: center;
        padding: 0.2rem 0.65rem;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.04em;
    }
</style>

<div class="dash-root max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="dash-header p-8 mb-8 anim" style="animation-delay:0ms">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="text-red-300 tracking-tight leading-tight">
                    INNOVET DASHBOARD
                </span>
                <h1 class="text-3xl font-semibold tracking-tight text-balance text-white sm:text-4xl">
                    SISTEMA DE COTIZACIONES
                </h1>
                <p class="text-sm/6 italic text-red-100 hover:text-gray-100">
                    Rendimiento, estados y comportamiento de las cotizaciones.
                </p>
            </div>
            <div class="bg-white-200 border border-white/50 rounded-2xl px-7 py-7 text-center backdrop-blur-sm">
                <p class="text-red-250 text-xs uppercase tracking-widest mb-2 ">Total registrado</p>
                <p class="text-5xl font-700 text-white" style="font-family:'DM Mono',monospace">
                    {{ number_format($totales) }}
                </p>
                <p class="text-red-300 text-xs mt-1">cotizaciones</p>
            </div>
        </div>
    </div>

    @php
    $metrics = [
        [
            'label'       => 'Pendientes',
            'value'       => $pendientes,
            'variant'     => 'pending',
            'value_color' => 'text-slate-700',
            'icon_bg'     => 'bg-slate-100',
            'icon_color'  => 'text-slate-500',
            'border'      => 'border-slate-200',
            'shadow'      => 'hover:shadow-slate-100',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
        ],
        [
            'label'       => 'Aceptadas',
            'value'       => $aceptadas,
            'variant'     => 'accepted',
            'value_color' => 'text-emerald-700',
            'icon_bg'     => 'bg-emerald-50',
            'icon_color'  => 'text-emerald-500',
            'border'      => 'border-emerald-100',
            'shadow'      => 'hover:shadow-emerald-50',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
        ],
        [
            'label'       => 'Rechazadas',
            'value'       => $rechazadas,
            'variant'     => 'rejected',
            'value_color' => 'text-red-700',
            'icon_bg'     => 'bg-red-50',
            'icon_color'  => 'text-red-500',
            'border'      => 'border-red-100',
            'shadow'      => 'hover:shadow-red-50',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
        ],
        [
            'label'       => 'Total',
            'value'       => $totales,
            'variant'     => 'total',
            'value_color' => 'text-red-800',
            'icon_bg'     => 'bg-red-50',
            'icon_color'  => 'text-red-700',
            'border'      => 'border-red-100',
            'shadow'      => 'hover:shadow-red-50',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>',
        ],
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @foreach($metrics as $i => $m)
        <div class="metric-card metric-card--{{ $m['variant'] }}
                    bg-white border {{ $m['border'] }} rounded-2xl p-6
                    hover:shadow-lg {{ $m['shadow'] }} cursor-default anim"
             style="animation-delay: {{ 80 + $i * 70 }}ms;">

            <div class="flex items-start justify-between mb-4">
                <p class="text-xs font-600 uppercase tracking-widest text-slate-400">
                    {{ $m['label'] }}
                </p>
                <div class="metric-icon {{ $m['icon_bg'] }} {{ $m['icon_color'] }} rounded-xl p-2.5">
                    {!! $m['icon'] !!}
                </div>
            </div>

            <p class="metric-value text-4xl font-600 {{ $m['value_color'] }} leading-none">
                {{ number_format($m['value']) }}
            </p>

            @if($totales > 0)
            <div class="mt-4 flex items-center gap-2">
                <div class="flex-1 h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700
                                @if($m['variant'] === 'pending')  bg-slate-400
                                @elseif($m['variant'] === 'accepted') bg-emerald-500
                                @elseif($m['variant'] === 'rejected') bg-red-500
                                @else bg-red-800 @endif"
                         style="width: {{ $totales > 0 ? round(($m['value'] / $totales) * 100) : 0 }}%">
                    </div>
                </div>
                <span class="text-xs text-slate-400 tabular-nums w-8 text-right">
                    {{ $totales > 0 ? round(($m['value'] / $totales) * 100) : 0 }}%
                </span>
            </div>
            @endif

        </div>
        @endforeach
    </div>
    <div class="filter-card p-6 mb-8 anim" style="animation-delay:380ms">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
            </svg>
            <p class="text-xs font-600 uppercase tracking-widest text-slate-400">Filtrar resultados</p>
        </div>
        <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row sm:items-end gap-3">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-500 text-slate-600 mb-1.5">
                    Fecha de registro
                </label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full rounded-xl border border-slate-200 bg-white text-sm px-3.5 py-2.5
                    text-slate-700 shadow-sm
                    focus:outline-none focus:ring-2 focus:ring-red-700 focus:border-red-700
                    transition duration-150">
            </div>
            <div class="flex space-x-4 justify-center sm:justify-start">
                <button type="submit"
                    class="inline-flex items-center gap-2
                        px-6 py-3
                        bg-red-700 text-white
                        text-sm font-semibold
                        rounded-xl
                        shadow-md
                        hover:bg-red-800
                        active:scale-95
                        transition-all duration-200">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Aplicar
                </button>
                    <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2
                            px-6 py-3
                            bg-gray-100 border border-slate-200
                            text-slate-600 text-sm font-semibold
                            rounded-2xl
                            rounded-xl
                            shadow-md
                            hover:bg-slate-50 hover:border-slate-300
                            active:scale-95
                            transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Limpiar
                    </a>
            </div>
        </form>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
        <div class="chart-card lg:col-span-2 bg-white border border-slate-200 p-6 anim"
             style="animation-delay:420ms">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-600 text-slate-800">Distribución de estados</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Proporción del total</p>
                </div>
                <span class="pill bg-slate-100 text-slate-500">Donut</span>
            </div>
            <div class="h-72">
                <canvas id="cotizacionesChart"></canvas>
            </div>
        </div>
        <div class="chart-card lg:col-span-3 bg-white border border-slate-200 p-6 anim"
             style="animation-delay:480ms">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-600 text-slate-800">Cotizaciones por fecha</h2>
                </div>
                <span class="pill bg-red-50 text-red-600">Tendencia</span>
            </div>
            <div class="h-72">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-6 anim"
         style="animation-delay:540ms">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-sm font-600 text-slate-800">Top clientes por volumen</h2>
                <p class="text-xs text-slate-400 mt-0.5">Los 5 con mayor número de cotizaciones</p>
            </div>
            <span class="pill bg-red-50 text-red-700">Top 5</span>
        </div>

        @php $rank = 1; @endphp
        <ul class="space-y-1">
            @forelse($top_clientes as $cliente => $cantidad)
            @php
                $rankColors = [
                    1 => ['bg' => 'bg-red-700',   'text' => 'text-white'],
                    2 => ['bg' => 'bg-red-100',    'text' => 'text-red-700'],
                    3 => ['bg' => 'bg-slate-100',  'text' => 'text-slate-600'],
                    4 => ['bg' => 'bg-slate-50',   'text' => 'text-slate-500'],
                    5 => ['bg' => 'bg-slate-50',   'text' => 'text-slate-400'],
                ];
                $rc = $rankColors[$rank] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-400'];
            @endphp
            <li class="client-row flex items-center gap-4 py-3 px-2">

                <div class="rank-badge {{ $rc['bg'] }} {{ $rc['text'] }}">
                    {{ $rank }}
                </div>

                <span class="flex-1 text-sm text-slate-700 font-500 truncate">
                    {{ $cliente }}
                </span>

                <div class="flex items-center gap-3">
                    @if($totales > 0)
                    <div class="w-24 h-1.5 bg-slate-100 rounded-full hidden sm:block overflow-hidden">
                        <div class="h-full bg-red-700 rounded-full"
                             style="width: {{ round(($cantidad / $totales) * 100) }}%">
                        </div>
                    </div>
                    @endif
                    <span class="text-sm font-600 text-red-700 tabular-nums w-6 text-right">
                        {{ $cantidad }}
                    </span>
                </div>

            </li>
            @php $rank++; @endphp
            @empty
            <li class="py-6 text-sm text-slate-400 text-center">
                No hay datos disponibles.
            </li>
            @endforelse
        </ul>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    Chart.defaults.font.family = "'DM Sans', sans-serif";

    const ctxDonut = document.getElementById("cotizacionesChart");
    if (ctxDonut) {
        new Chart(ctxDonut, {
            type: "doughnut",
            data: {
                labels: ["Pendientes", "Aceptadas", "Rechazadas"],
                datasets: [{
                    data: [{{ $pendientes ?? 0 }}, {{ $aceptadas ?? 0 }}, {{ $rechazadas ?? 0 }}],
                    backgroundColor: ["#94a3b8", "#22c55e", "#ef4444"],
                    borderWidth: 0,
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "72%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 18,
                            font: { size: 12, weight: '500' },
                            color: '#475569',
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.label + ': ' + ctx.formattedValue;
                            }
                        }
                    }
                }
            }
        });
    }

    const ctxLine = document.getElementById("timelineChart");
    if (ctxLine) {
        const labels = {!! json_encode($labels_fechas) !!};
        const data   = {!! json_encode($data_fechas) !!};

        new Chart(ctxLine, {
            type: "line",
            data: {
                labels,
                datasets: [{
                    label: "Cotizaciones",
                    data,
                    backgroundColor: 'rgba(153, 27, 27, 0.08)',
                    borderColor: '#991b1b',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#991b1b',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#94a3b8', font: { size: 11 } },
                        grid: { color: 'rgba(226,232,240,0.8)', drawBorder: false },
                        border: { display: false },
                    },
                    x: {
                        ticks: { color: '#94a3b8', font: { size: 11 }, maxRotation: 0 },
                        grid: { display: false },
                        border: { display: false },
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f1f5f9',
                        bodyColor: '#cbd5e1',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.formattedValue + ' cotizaciones';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush