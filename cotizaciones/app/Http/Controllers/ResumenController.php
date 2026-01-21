<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resumen;
use App\Models\Cotizacion; // --- CAMBIO: Añadido ---
use Illuminate\Support\Facades\Storage; // --- CAMBIO: Añadido ---
use Illuminate\Support\Facades\Log; // Added for debugging logs
use App\Models\ResumenArchivo;


class ResumenController extends Controller
{
   public function updateField(Request $request)
{
    $request->validate([
        'cotizacion_id'        => 'required|integer',
        'poka_yoke'            => 'nullable|string',
        'acomodo_pieza'        => 'nullable|string',
        'contenedor_cliente'   => 'nullable|string',
        'medidas_contenedor'   => 'nullable|string',
        'estiba_contenedor'    => 'nullable|string',
        'cliente_proporciona'  => 'nullable|string',

        // 👇 múltiples archivos
        'archivo_adjunto.*'    => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,dwg,dxf,step|max:10240',
    ]);

    // 1️⃣ Buscar o crear resumen
    $resumen = Resumen::firstOrNew([
        'cotizacion_id' => $request->cotizacion_id
    ]);

    // 2️⃣ Actualizar campos
    $resumen->poka_yoke           = $request->poka_yoke;
    $resumen->acomodo_pieza       = $request->acomodo_pieza;
    $resumen->contenedor_cliente  = $request->contenedor_cliente;
    $resumen->medidas_contenedor  = $request->medidas_contenedor;
    $resumen->estiba_contenedor   = $request->estiba_contenedor;
    $resumen->cliente_proporciona = $request->cliente_proporciona;

    $resumen->save();

    // 3️⃣ Guardar múltiples archivos
    if ($request->hasFile('archivo_adjunto')) {

        foreach ($request->file('archivo_adjunto') as $file) {

            if ($file->isValid()) {

                $originalName = $file->getClientOriginalName();
                $path = $file->store('resumen_adjuntos', 'public');

                ResumenArchivo::create([
                    'resumen_id'      => $resumen->resumen_id,
                    'nombre_original' => $originalName,
                    'path'            => $path,
                ]);
            }
        }
    }

    return back()->with('success', 'Datos del resumen guardados correctamente ✅');
}
public function eliminarArchivo($id)
{
    $archivo = ResumenArchivo::findOrFail($id);

    if (Storage::disk('public')->exists($archivo->path)) {
        Storage::disk('public')->delete($archivo->path);
    }

    $archivo->delete();

    return response()->json(['success' => true]);
}

    /**
     * Mostrar Resumen de Costos en una página completa
     */
    public function showPage($id)
    {
        $cotizacion = Cotizacion::with('costeoRequisicion')->findOrFail($id);
        $costeoRequisicion = $cotizacion->costeoRequisicion ?? new \App\Models\CosteoRequisicion();

        // Si ya existe un resumen guardado, pásalo a la vista para mostrarlo
        $ventasResumen = $cotizacion->ventasResumen ?? $costeoRequisicion->ventasResumen ?? null;

        // Para simplicidad, renderizamos directamente la vista `resumen_tabla` con los datos
        return view('cotizaciones.resumen_tabla', compact('cotizacion', 'costeoRequisicion', 'ventasResumen'));
    }
    /**
     * Guardar o actualizar el resumen de costos en la tabla ventas_resumen_de_costos
     */
    public function storeVentasResumen(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'resumen_costo_procesos' => 'nullable|numeric',
            'resumen_piezas_procesos' => 'nullable|numeric',
            'resumen_costo_unit_procesos' => 'nullable|numeric',
            'resumen_margen_procesos' => 'nullable|numeric',
            'resumen_precio_venta_procesos' => 'nullable|numeric',

            'resumen_costo_empaque' => 'nullable|numeric',
            'resumen_piezas_empaque' => 'nullable|integer',
            'resumen_costo_unit_empaque' => 'nullable|numeric',
            'resumen_margen_empaque' => 'nullable|numeric',
            'resumen_precio_venta_empaque' => 'nullable|numeric',

            'resumen_costo_flete_total' => 'nullable|numeric',
            'resumen_piezas_flete' => 'nullable|integer',
            'resumen_costo_unit_flete' => 'nullable|numeric',
            'resumen_margen_flete' => 'nullable|numeric',
            'resumen_precio_venta_flete' => 'nullable|numeric',

            'resumen_costo_pedimento' => 'nullable|numeric',
            'resumen_piezas_pedimento' => 'nullable|integer',
            'resumen_costo_unit_pedimento' => 'nullable|numeric',
            'resumen_margen_pedimento' => 'nullable|numeric',
            'resumen_precio_venta_pedimento' => 'nullable|numeric',

            // Inocuidad
            'resumen_costo_inocuidad' => 'nullable|numeric',
            'resumen_piezas_inocuidad' => 'nullable|integer',
            'resumen_costo_unit_inocuidad' => 'nullable|numeric',
            'resumen_margen_inocuidad' => 'nullable|numeric',
            'resumen_precio_venta_inocuidad' => 'nullable|numeric',

            // Polipropileno
            'resumen_costo_polipropileno' => 'nullable|numeric',
            'resumen_piezas_polipropileno' => 'nullable|integer',
            'resumen_costo_unit_polipropileno' => 'nullable|numeric',
            'resumen_margen_polipropileno' => 'nullable|numeric',
            'resumen_precio_venta_polipropileno' => 'nullable|numeric',

            // Estaticidad
            'resumen_costo_estaticidad' => 'nullable|numeric',
            'resumen_piezas_estaticidad' => 'nullable|integer',
            'resumen_costo_unit_estaticidad' => 'nullable|numeric',
            'resumen_margen_estaticidad' => 'nullable|numeric',
            'resumen_precio_venta_estaticidad' => 'nullable|numeric',

            // Maquila
            'resumen_costo_maquila' => 'nullable|numeric',
            'resumen_piezas_maquila' => 'nullable|integer',
            'resumen_costo_unit_maquila' => 'nullable|numeric',
            'resumen_margen_maquila' => 'nullable|numeric',
            'resumen_precio_venta_maquila' => 'nullable|numeric',

            // Etiqueta
            'resumen_costo_etiqueta' => 'nullable|numeric',
            'resumen_piezas_etiqueta' => 'nullable|integer',
            'resumen_costo_unit_etiqueta' => 'nullable|numeric',
            'resumen_margen_etiqueta' => 'nullable|numeric',
            'resumen_precio_venta_etiqueta' => 'nullable|numeric',

            'resumen_margen_administrativo_aux' => 'nullable|numeric',
            'resumen_margen_administrativo' => 'nullable|numeric',
            'resumen_total_costo_unit' => 'nullable|numeric',

            'resumen_total_comision' => 'nullable|numeric',
            'resumen_total_comision_final' => 'nullable|numeric',
            'resumen_total_precio_venta_aux' => 'nullable|numeric',
            'resumen_total_precio_venta' => 'nullable|numeric',

            'lote_compra' => 'nullable|integer',
            'coeficiente_merma' => 'nullable|numeric',
            'costo_total' => 'nullable|numeric',
            'precio_venta_final' => 'nullable|numeric'
        ]);

        // Log inputs for debugging
        Log::info('storeVentasResumen called', ['id' => $id, 'user_id' => optional(auth()->user())->id, 'input' => $request->all()]);

        $cotizacion = Cotizacion::with('costeoRequisicion')->findOrFail($id);

        $datos = $request->only([
            'resumen_costo_procesos','resumen_piezas_procesos','resumen_costo_unit_procesos','resumen_margen_procesos','resumen_precio_venta_procesos',
            'resumen_costo_empaque','resumen_piezas_empaque','resumen_costo_unit_empaque','resumen_margen_empaque','resumen_precio_venta_empaque',
            'resumen_costo_flete_total','resumen_piezas_flete','resumen_costo_unit_flete','resumen_margen_flete','resumen_precio_venta_flete',
            'resumen_costo_pedimento','resumen_piezas_pedimento','resumen_costo_unit_pedimento','resumen_margen_pedimento','resumen_precio_venta_pedimento',
            'resumen_costo_inocuidad','resumen_piezas_inocuidad','resumen_costo_unit_inocuidad','resumen_margen_inocuidad','resumen_precio_venta_inocuidad',
            'resumen_costo_polipropileno','resumen_piezas_polipropileno','resumen_costo_unit_polipropileno','resumen_margen_polipropileno','resumen_precio_venta_polipropileno',
            'resumen_costo_estaticidad','resumen_piezas_estaticidad','resumen_costo_unit_estaticidad','resumen_margen_estaticidad','resumen_precio_venta_estaticidad',
            'resumen_costo_maquila','resumen_piezas_maquila','resumen_costo_unit_maquila','resumen_margen_maquila','resumen_precio_venta_maquila',
            'resumen_costo_etiqueta','resumen_piezas_etiqueta','resumen_costo_unit_etiqueta','resumen_margen_etiqueta','resumen_precio_venta_etiqueta',
            'resumen_margen_administrativo_aux','resumen_margen_administrativo','resumen_total_costo_unit',
            'resumen_total_comision','resumen_total_comision_final','resumen_total_precio_venta_aux','resumen_total_precio_venta',
            'lote_compra','coeficiente_merma','costo_total','precio_venta_final'
        ]);

        // relacionar costeo si existe
        $datos['cotizacion_id'] = $cotizacion->id;
        $datos['costeo_requisicion_id'] = optional($cotizacion->costeoRequisicion)->id;

        try {
            $registro = \App\Models\VentasResumenDeCostos::updateOrCreate(
                ['cotizacion_id' => $cotizacion->id],
                $datos
            );
            Log::info('ventas resumen saved', ['id' => $registro->id, 'datos' => $datos]);
        } catch (\Exception $e) {
            Log::error('ventas resumen save failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'datos' => $datos]);
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el resumen. Revisa los logs.']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'id' => $registro->id]);
        }

        return back()->with('success', 'Resumen de costos guardado correctamente.');
    }}
