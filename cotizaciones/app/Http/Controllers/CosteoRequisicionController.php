<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CosteoRequisicion;
use App\Models\CosteoCorridaPiloto;
use App\Models\ProcesosCosteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CosteoRequisicionController extends Controller
{
    public function create($cotizacionId)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'requisicionCotizacion',
            'costeoRequisicion' // .procesosCosteo - COMENTADO POR PROCESOS DINAMICOS NO USADOS
        ])->findOrFail($cotizacionId);

        return view('costeo.create', compact('cotizacion'));
    }

    public function store(Request $request, $cotizacionId)
    {
        $cotizacion = Cotizacion::findOrFail($cotizacionId);

        DB::beginTransaction();

        try {
            // Validación básica
            $validated = $request->validate([
                'insertos' => 'nullable|numeric|min:0',
                // Agrega aquí más campos según necesites
            ]);

            // Detectar si es corrida piloto
            $esCorridaPiloto = $request->has('btn_corrida_piloto') && $request->btn_corrida_piloto == 'corrida_piloto';

            // Preparar datos comunes
            $datos = [
                'usuario_id' => Auth::id(),
                'calibre_costeo' => $request->calibre_costeo,
                'insertos' => $request->insertos,
                    // Acomodo Ancho
                    'acomodo_ancho_medida_cantidad' => $request->acomodo_ancho_cantidad_1,
                    'acomodo_ancho_medida_total' => $request->acomodo_ancho_total_1,
                    'acomodo_ancho_orillas_mm' => $request->acomodo_ancho_medida_2,
                    'acomodo_ancho_orillas_cantidad' => $request->acomodo_ancho_cantidad_2,
                    'acomodo_ancho_orillas_total' => $request->acomodo_ancho_total_2,
                    'acomodo_ancho_medianiles_mm' => $request->acomodo_ancho_medida_3,
                    'acomodo_ancho_medianiles_cantidad' => $request->acomodo_ancho_cantidad_3,
                    'acomodo_ancho_medianiles_total' => $request->acomodo_ancho_total_3,
                    // Acomodo Avance
                    'acomodo_avance_medida_cantidad' => $request->acomodo_avance_cantidad_1,
                    'acomodo_avance_medida_total' => $request->acomodo_avance_total_1,
                    'acomodo_avance_orillas_mm' => $request->acomodo_avance_medida_2,
                    'acomodo_avance_orillas_cantidad' => $request->acomodo_avance_cantidad_2,
                    'acomodo_avance_orillas_total' => $request->acomodo_avance_total_2,
                    'acomodo_avance_medianiles_mm' => $request->acomodo_avance_medida_3,
                    'acomodo_avance_medianiles_cantidad' => $request->acomodo_avance_cantidad_3,
                    'acomodo_avance_medianiles_total' => $request->acomodo_avance_total_3,

                    // Molde y Hoja
                    'molde_ancho' => $request->molde_ancho,
                    'molde_avance' => $request->molde_avance,
                    'hoja_ancho' => $request->hoja_ancho,
                    'aux_hoja_ancho' => $request->aux_hoja_ancho,
                    'hoja_avance' => $request->hoja_avance,
                    'aux_hoja_avance' => $request->aux_hoja_avance,
                    'placa_de_enfriamiento' => $request->placa_de_enfriamiento,

                    // Material Prima placa_de_enfriamiento 
                    'peso_especifico' => $request->peso_especifico,
                    'area_formado_hoja' => $request->area_formado_hoja,
                    'cantidad_hojas' => $request->cantidad_hojas,
                    'peso_pieza' => $request->peso_pieza,
                    'peso_neto_hoja' => $request->peso_neto_hoja,
                    'coeficiente_merma' => $request->coeficiente_merma,
                    'peso_merma' => $request->peso_merma,
                    'peso_bruto_hoja' => $request->peso_bruto_hoja,
                    'peso_neto' => $request->peso_neto,
                    'peso_total' => $request->peso_total,
                    'PRM' => $request->PRM,
                    'divisor_prm' => $request->divisor_prm,
                    'sumador_prm' => $request->sumador_prm,
                    'PZRM' => $request->PZRM,
                    // Costos MP
                    'costo_kilo' => $request->costo_kilo,
                    'TC' => $request->TC,
                    'costo_flete' => $request->costo_flete,
                    'precio_kg' => $request->precio_kg,
                    'costo_lamina' => $request->costo_lamina,
                    'TC_lamina' => $request->TC_lamina,
                    'costo_flete_lamina' => $request->costo_flete_lamina,
                    'precio_lamina' => $request->precio_lamina,
                    'sugerencia_costos_mp' => $request->sugerencia_costos_mp,
                    // Costos de Procesos
                    'hojas_del_pedido' => $request->hojas_del_pedido,

                    // Campos de Termoformado (directos en la tabla)
                    'nombre_maquina_termoformado' => $request->nombre_maquina_termoformado,
                    'no_personas_termoformado' => $request->no_personas_termoformado,
                    'bajadas_por_minuto_termoformado' => $request->bajadas_por_minuto_termoformado,
                    'total_hojas_turno_termoformado' => $request->total_hojas_turno_termoformado,
                    'total_dias_turnos_termoformado' => $request->total_dias_turnos_termoformado,
                    'costo_termoformado' => $request->costo_termoformado,

                    // Campos de Suaje (directos en la tabla)
                    'nombre_maquina_suaje' => $request->nombre_maquina_suaje,
                    'no_personas_suaje' => $request->no_personas_suaje,
                    'bajadas_por_minuto_suaje' => $request->bajadas_por_minuto_suaje,
                    'total_hojas_turno_suaje' => $request->total_hojas_turno_suaje,
                    'total_piezas_turno_suaje' => $request->total_piezas_turno_suaje,
                    'total_dias_turnos_suaje' => $request->total_dias_turnos_suaje,
                    'costo_suaje' => $request->costo_suaje,

                    // Costos Adicionales de Procesos
                    'costo_montaje' => $request->costo_montaje,
                    'costo_montaje2' => $request->costo_montaje2,
                    'costo_amortizacion_herramentales' => $request->costo_amortizacion_herramentales,
                    'costo_amortizacion_herramentales2' => $request->costo_amortizacion_herramentales2,
                    'costo_electricidad' => $request->costo_electricidad,
                    'costo_electricidad2' => $request->costo_electricidad2,
                    'amortizacion_maquinaria' => $request->amortizacion_maquinaria,
                    'amortizacion_maquinaria2' => $request->amortizacion_maquinaria2,
                    // Costos Fabricación
                    'costo_fabricacion' => $request->costo_fabricacion,
                    'costo_mp' => $request->costo_mp,
                    'costo_total_procesos' => $request->costo_total_procesos,
                    // seccion Empaque
                    'piezas_por_bolsa' => $request->piezas_por_bolsa,
                    'aux_piezas_por_bolsa' => $request->aux_piezas_por_bolsa,
                    'piezas_por_caja' => $request->piezas_por_caja,
                    'bolsas_por_tarima' => $request->bolsas_por_tarima,
                    'cajas_por_tarima' => $request->cajas_por_tarima,
                    'total_bolsas' => $request->total_bolsas,
                    'total_cajas' => $request->total_cajas,
                    'tarimas_totales_bolsas' => $request->tarimas_totales_bolsas,
                    'tarimas_totales_cajas' => $request->tarimas_totales_cajas,

                    // COSTOS EMPAQUE
                    'costo_corrugado' => $request->costo_corrugado,
                    'total_corrugado' => $request->total_corrugado,
                    'costo_bolsa' => $request->costo_bolsa,
                    'total_bolsa' => $request->total_bolsa,
                    'costo_tarima' => $request->costo_tarima,
                    'total_tarima' => $request->total_tarima,
                    'costo_empaque_total' => $request->costo_empaque_total,

                    // COSTOS ADICIONALES
                    'costo_inocuidad' => $request->costo_inocuidad,
                    'costo_pared' => $request->costo_pared,
                    'aplicacion_estaticida' => $request->aplicacion_estaticida,
                    'no_personas_estaticida' => $request->no_personas_estaticida,
                    'piezas_por_hora_estaticida' => $request->piezas_por_hora_estaticida,
                    'costo_estaticida_total' => $request->costo_estaticida_total,
                    'maquila' => $request->maquila,
                    'no_personas_maquila' => $request->no_personas_maquila,
                    'costo_maquila_total' => $request->costo_maquila_total,

                    // COSTOS HERRAMENTALES
                    'molde_ancho_copia' => $request->molde_ancho_copia,
                    'molde_avance_copia' => $request->molde_avance_copia,
                    'ajuste_ancho' => $request->ajuste_ancho,
                    'ajuste_avance' => $request->ajuste_avance,
                    'ajuste_alto' => $request->ajuste_alto,
                    'medida_bloque_ancho' => $request->medida_bloque_ancho,
                    'medida_bloque_avance' => $request->medida_bloque_avance,
                    'medida_bloque_alto' => $request->medida_bloque_alto,
                    'kilos' => $request->kilos,
                    'constante_empujador' => $request->constante_empujador,

                    // COSTOS HERRAMENTALES
                    'costo_aluminio' => $request->costo_aluminio,
                    'costo_molde' => $request->costo_molde,
                    'aux_empujador' => $request->aux_empujador,
                    'costo_empujador' => $request->costo_empujador,
                    'costo_suaje_base' => $request->costo_suaje_base,
                    'no_muestras' => $request->no_muestras,
                    'aux_muestras' => $request->aux_muestras,
                    'costo_muestras' => $request->costo_muestras,
                    'costo_placa_fijacion' => $request->costo_placa_fijacion,
                    'dividendo' => $request->dividendo,
                    'divisor' => $request->divisor,
                    'costo_madera_campana' => $request->costo_madera_campana,
                    'costo_prototipo' => $request->costo_prototipo,
                    'costo_tornilleria' => $request->costo_tornilleria,
                    'costo_pedimento_herramental' => $request->costo_pedimento_herramental,
                    'hrs_maquinada_molde' => $request->hrs_maquinada_molde,
                    'hrs_maquinada_empujador' => $request->hrs_maquinada_empujador,

                    // CALCULO TOTALES
                    'total_molde' => $request->total_molde,
                    'total_empujador' => $request->total_empujador,
                    'TOTAL_FINAL' => $request->TOTAL_FINAL,
                    'TOTAL_VENTAS' => $request->TOTAL_VENTAS,

                    //Procesos
                    'resumen_costo_procesos' => $request->resumen_costo_procesos,
                    'resumen_piezas_procesos' => $request->resumen_piezas_procesos,
                    'resumen_costo_unit_procesos' => $request->resumen_costo_unit_procesos,

                    // Empaque
                    'resumen_costo_empaque' => $request->resumen_costo_empaque,
                    'resumen_piezas_empaque' => $request->resumen_piezas_empaque,
                    'resumen_costo_unit_empaque' => $request->resumen_costo_unit_empaque,
                    //'resumen_margen_empaque' => $request->resumen_margen_empaque,
                    //'resumen_precio_venta_empaque' => $request->resumen_precio_venta_empaque,
                    // Flete
                    'resumen_costo_flete_total' => $request->resumen_costo_flete_total,
                    'resumen_piezas_flete' => $request->resumen_piezas_flete,
                    'resumen_costo_unit_flete' => $request->resumen_costo_unit_flete,
                    //'resumen_margen_flete' => $request->resumen_margen_flete,
                    //'resumen_precio_venta_flete' => $request->resumen_precio_venta_flete,
                    // Pedimento
                    'resumen_costo_pedimento' => $request->resumen_costo_pedimento,
                    'resumen_piezas_pedimento' => $request->resumen_piezas_pedimento,
                    'resumen_costo_unit_pedimento' => $request->resumen_costo_unit_pedimento,
                    //'resumen_margen_pedimento' => $request->resumen_margen_pedimento,
                    //'resumen_precio_venta_pedimento' => $request->resumen_precio_venta_pedimento,
                    // Inocuidad
                    'resumen_costo_inocuidad' => $request->resumen_costo_inocuidad,
                    'resumen_piezas_inocuidad' => $request->resumen_piezas_inocuidad,
                    'resumen_costo_unit_inocuidad' => $request->resumen_costo_unit_inocuidad,
                    //'resumen_margen_inocuidad' => $request->resumen_margen_inocuidad,
                    //'resumen_precio_venta_inocuidad' => $request->resumen_precio_venta_inocuidad,
                    // Polipropileno
                    'resumen_costo_polipropileno' => $request->resumen_costo_polipropileno,
                    'resumen_piezas_polipropileno' => $request->resumen_piezas_polipropileno,
                    'resumen_costo_unit_polipropileno' => $request->resumen_costo_unit_polipropileno,
                    //'resumen_margen_polipropileno' => $request->resumen_margen_polipropileno,
                    //'resumen_precio_venta_polipropileno' => $request->resumen_precio_venta_polipropileno,
                    // Estaticidad
                    'resumen_costo_estaticidad' => $request->resumen_costo_estaticidad,
                    'resumen_piezas_estaticidad' => $request->resumen_piezas_estaticidad,
                    'resumen_costo_unit_estaticidad' => $request->resumen_costo_unit_estaticidad,
                    //'resumen_margen_estaticidad' => $request->resumen_margen_estaticidad,
                    //'resumen_precio_venta_estaticidad' => $request->resumen_precio_venta_estaticidad,
                    // Maquila
                    'resumen_costo_maquila' => $request->resumen_costo_maquila,
                    'resumen_piezas_maquila' => $request->resumen_piezas_maquila,
                    'resumen_costo_unit_maquila' => $request->resumen_costo_unit_maquila,
                    //'resumen_margen_maquila' => $request->resumen_margen_maquila,
                    //'resumen_precio_venta_maquila' => $request->resumen_precio_venta_maquila,
                    // Etiqueta
                    'resumen_costo_etiqueta' => $request->resumen_costo_etiqueta,
                    'resumen_piezas_etiqueta' => $request->resumen_piezas_etiqueta,
                    'resumen_costo_unit_etiqueta' => $request->resumen_costo_unit_etiqueta,
                    //'resumen_margen_etiqueta' => $request->resumen_margen_etiqueta,
                    //'resumen_precio_venta_etiqueta' => $request->resumen_precio_venta_etiqueta,
                    // Totales costos fila Adicionales
                    'resumen_total_costo_adicionales' => $request->resumen_total_costo_adicionales,
                    'resumen_total_piezas_adicionales' => $request->resumen_total_piezas_adicionales,
                    'resumen_total_costo_unit_adicionales' => $request->resumen_total_costo_unit_adicionales,
                    // Totales del resumen
                    'resumen_margen_administrativo' => $request->resumen_margen_administrativo,
                    'resumen_total_costo_unit' => $request->resumen_total_costo_unit,
                    //'resumen_total_precio_venta' => $request->resumen_total_precio_venta,
                    //'comision_margen' => $request->comision_margen,
                    //'resumen_total_comision' => $request->resumen_total_comision,

                    // Nuevos campos
                    //'venta_total' => $request->venta_total,
                    'costo_total' => $request->costo_total,
                    //'margen_bruto' => $request->margen_bruto,
                    //'margen_bruto_porcentaje' => $request->margen_bruto_porcentaje,
                    'entrega_prototipo' => $request->entrega_prototipo,
                    'tiempo_herramientas' => $request->tiempo_herramientas,
                    'tiempo_pt' => $request->tiempo_pt,
                    'comentarios' => $request->comentarios
            ];

            // Decidir en qué tabla guardar según si es corrida piloto o no
            if ($esCorridaPiloto) {
                // Guardar en tabla de corrida piloto
                $costeoRequisicion = CosteoCorridaPiloto::updateOrCreate(
                    ['cotizaciones' => $cotizacionId],
                    $datos
                );
            } else {
                // Guardar en tabla normal de costeo
                $costeoRequisicion = CosteoRequisicion::updateOrCreate(
                    ['cotizaciones' => $cotizacionId],
                    $datos
                );
            }

            DB::commit();

            // Si es corrida piloto, devolver respuesta JSON para manejar con JavaScript
            if ($esCorridaPiloto) {
                return response()->json([
                    'success' => true,
                    'message' => 'Corrida Piloto guardada exitosamente.',
                    'pdfUrl' => route('cotizacion.resumen.costos.pdf', $cotizacionId)
                ]);
            }

            return redirect()->route('cotizaciones.index', $cotizacionId)
                ->with('success', 'Costeo guardado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Si es corrida piloto y hay error, devolver JSON
            if ($esCorridaPiloto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la corrida piloto: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al guardar el costeo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $costeo = CosteoRequisicion::with(['procesosCosteo', 'cotizacion'])
            ->findOrFail($id);

        return view('costeo.show', compact('costeo'));
    }

    public function edit($id)
    {
        $costeo = CosteoRequisicion::with(['procesosCosteo', 'cotizacion'])
            ->findOrFail($id);

        return view('costeo.edit', compact('costeo'));
    }

    public function update(Request $request, $id)
    {
        // Similar al store pero para actualizar
        return $this->store($request, $id);
    }

}