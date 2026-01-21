<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Filtro por fecha (opcional)
        $query = Cotizacion::query();

        if ($request->filled('fecha')) {
            // Este filtro se aplica a la columna 'fecha' del formulario.
            $query->whereDate('fecha', $request->fecha);
        }

        // Estadísticas por estado (usa el query filtrado)
        $pendientes = (clone $query)->where('estado', 'pendiente')->count();
        $aceptadas  = (clone $query)->where('estado', 'aceptada')->count();
        $rechazadas = (clone $query)->where('estado', 'rechazada')->count();
        $totales    = (clone $query)->count();

        // --- Lógica para la nueva gráfica: Cotizaciones por la columna 'fecha' ---

        // La consulta de la gráfica debe reflejar el filtro del formulario.
        $cotizaciones_por_dia_query = Cotizacion::select(
            DB::raw('DATE(fecha) as fecha_cotizacion'), // CAMBIO CLAVE: Usamos 'fecha' en lugar de 'created_at'
            DB::raw('count(*) as count')
        )
            ->groupBy('fecha_cotizacion')
            ->orderBy('fecha_cotizacion', 'asc');

        if ($request->filled('fecha')) {
            // Si hay filtro, solo muestra el conteo para esa fecha
            $cotizaciones_por_dia_query->whereDate('fecha', $request->fecha);
        } else {
            // Si no hay filtro, limita el rango para que la gráfica no sea enorme
            // Muestra las cotizaciones de los últimos 30 días basadas en la columna 'fecha'.
            $cotizaciones_por_dia_query->where('fecha', '>=', now()->subDays(30));
        }

        $cotizaciones_por_dia = $cotizaciones_por_dia_query->get();

        $labels_fechas = $cotizaciones_por_dia->pluck('fecha_cotizacion');
        $data_fechas = $cotizaciones_por_dia->pluck('count');
        // -------------------------------------------------------------------------

        // Próximas entregas
        //$proximas_entregas = Cotizacion::whereNotNull('fecha_de_efectividad')
    //->whereDate('fecha_de_efectividad', '>=', Carbon::today()) // 🔹 Solo futuras o actuales
    //->orderBy('fecha_de_efectividad', 'asc')
    //->take(5)
    //->get();

        // Top clientes
        $top_clientes = Cotizacion::select('cliente')
            ->whereNotNull('cliente')
            ->get()
            ->groupBy('cliente')
            ->map(fn($items) => $items->count())
            ->sortDesc()
            ->take(5);

        return view('home', compact(
            'pendientes',
            'aceptadas',
            'rechazadas',
            'totales',
            //'proximas_entregas',
            'top_clientes',
            'labels_fechas',
            'data_fechas'
        ));
    }
}
