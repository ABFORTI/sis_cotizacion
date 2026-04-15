<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CosteoRequisicion;
use App\Models\CosteoCorridaPiloto;
use App\Models\ProcesosCosteo;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CosteoRequisicionController extends Controller
{
    public function create($cotizacionId)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'especificacionEmpaque',
            'requisicionCotizacion',
            'costeoRequisicion'
        ])->findOrFail($cotizacionId);

        return view('costeo.create', compact('cotizacion'));
    }

    public function store(Request $request, $cotizacionId)
    {
        try {
            [$cotizacion, $esCorridaPiloto] = $this->persistCosteo($request, $cotizacionId);

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
            $esCorridaPiloto = $request->has('btn_corrida_piloto') && $request->btn_corrida_piloto == 'corrida_piloto';

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

    public function storeAndDownloadPdf(Request $request, $cotizacionId)
    {
        try {
            [$cotizacion, $esCorridaPiloto] = $this->persistCosteo($request, $cotizacionId);

            if ($esCorridaPiloto) {
                throw ValidationException::withMessages([
                    'boton-guardar-costeo' => 'La descarga de PDF de costeo no aplica para corrida piloto.'
                ]);
            }

            $pdfData = $this->validatePdfPayload($request, $cotizacion);
            $fileName = 'Resumen_Costeo_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', (string) $cotizacion->no_proyecto) . '.pdf';

            return $this->buildPdfDownloadResponse($pdfData, $fileName);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'No fue posible generar el PDF.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el costeo y generar el PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function persistCosteo(Request $request, $cotizacionId): array
    {
        $cotizacion = Cotizacion::findOrFail($cotizacionId);
        $numberOrZero = static function ($value) {
            return ($value === null || $value === '' || !is_numeric($value)) ? 0 : $value;
        };

        $textOrEmpty = static function ($value) {
            return trim((string) ($value ?? ''));
        };

        DB::beginTransaction();

        try {
            $request->validate([
                'insertos' => 'nullable|numeric|min:0',
                'divisor_ancho' => 'nullable|numeric|min:1',
                'divisor_avance' => 'nullable|numeric|min:1',
            ]);
            $esCorridaPiloto = $request->has('btn_corrida_piloto') && $request->btn_corrida_piloto == 'corrida_piloto';

            $tablaPesos = [
                'ABS' => 1.02,
                'PS' => 1.08,
                'PET' => 1.35,
                'HDPE' => 1.02,
                'PP' => 0.93,
                'PET ESD' => 1.35,
                'PET-POLIPROPILENO' => 1.3,
                'PET-GRADO-ALIMENTICIO' => 1.35,
                'Otros' => 1.35
            ];
            $material = $request->material;
            $peso_especifico = is_numeric($request->peso_especifico)
                ? (float) $request->peso_especifico
                : ($tablaPesos[$material] ?? null);


            // Intercambiar valores solo si el usuario hizo clic en el botón
            if ($request->input('intercambio_medidas_avance') == '1') {
                $ancho1 = $request->acomodo_avance_cantidad_1;
                $avance1 = $request->acomodo_ancho_cantidad_1;
            } else {
                $ancho1 = $request->acomodo_ancho_cantidad_1;
                $avance1 = $request->acomodo_avance_cantidad_1;
            }

            $datos = [
                'usuario_id' => Auth::id(),
                'calibre_costeo' => $request->calibre_costeo,
                'insertos' => $request->insertos,
                'divisor_ancho' => $request->input('divisor_ancho', 1),
                'divisor_avance' => $request->input('divisor_avance', 1),
                'acomodo_ancho_medida_cantidad' => $avance1,
                'acomodo_ancho_medida_total' => $request->acomodo_ancho_total_1,
                'acomodo_ancho_orillas_mm' => $request->acomodo_ancho_medida_2,
                'acomodo_ancho_orillas_cantidad' => $request->acomodo_ancho_cantidad_2,
                'acomodo_ancho_orillas_total' => $request->acomodo_ancho_total_2,
                'acomodo_ancho_medianiles_mm' => $request->acomodo_ancho_medida_3,
                'acomodo_ancho_medianiles_cantidad' => $request->acomodo_ancho_cantidad_3,
                'acomodo_ancho_medianiles_total' => $request->acomodo_ancho_total_3,
                'acomodo_avance_medida_cantidad' => $ancho1,
                'acomodo_avance_medida_total' => $request->acomodo_avance_total_1,
                    'acomodo_avance_orillas_mm' => $request->acomodo_avance_medida_2,
                    'acomodo_avance_orillas_cantidad' => $request->acomodo_avance_cantidad_2,
                    'acomodo_avance_orillas_total' => $request->acomodo_avance_total_2,
                    'acomodo_avance_medianiles_mm' => $request->acomodo_avance_medida_3,
                    'acomodo_avance_medianiles_cantidad' => $request->acomodo_avance_cantidad_3,
                    'acomodo_avance_medianiles_total' => $request->acomodo_avance_total_3,
                    'molde_ancho' => $request->molde_ancho,
                    'molde_avance' => $request->molde_avance,
                    'hoja_ancho' => $request->hoja_ancho,
                    'aux_hoja_ancho' => $request->aux_hoja_ancho,
                    'hoja_avance' => $request->hoja_avance,
                    'aux_hoja_avance' => $request->aux_hoja_avance,
                    'placa_de_enfriamiento' => $request->placa_de_enfriamiento,
                    // Sobrescribe el valor recibido y usa el calculado
                    'peso_especifico' => $peso_especifico,
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
                    'costo_kilo' => $request->input('mp_base.costo_kilo', $request->costo_kilo),
                    'TC' => $request->input('mp_base.TC', $request->TC),
                    'costo_flete' => $request->input('mp_base.costo_flete', $request->costo_flete),
                    'precio_kg' => $request->input('mp_base.precio_kg', $request->precio_kg),
                    'costo_lamina' => $request->input('mp_base.costo_lamina', $request->costo_lamina),
                    'TC_lamina' => $request->input('mp_base.TC_lamina', $request->TC_lamina),
                    'costo_flete_lamina' => $request->input('mp_base.costo_flete_lamina', $request->costo_flete_lamina),
                    'precio_lamina' => $request->input('mp_base.precio_lamina', $request->precio_lamina),
                    'sugerencia_costos_mp' => $request->sugerencia_costos_mp,
                    'hojas_del_pedido' => $request->hojas_del_pedido,
                    'nombre_maquina_termoformado' => $request->nombre_maquina_termoformado,
                    'no_personas_termoformado' => $request->no_personas_termoformado,
                    'bajadas_por_minuto_termoformado' => $request->bajadas_por_minuto_termoformado,
                    'total_hojas_turno_termoformado' => $request->total_hojas_turno_termoformado,
                    'total_dias_turnos_termoformado' => $request->total_dias_turnos_termoformado,
                    'costo_termoformado' => $request->costo_termoformado,
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
                    
                    'resumen_costo_flete_total' => $request->resumen_costo_flete_total,
                    'resumen_piezas_flete' => $request->resumen_piezas_flete,
                    'resumen_costo_unit_flete' => $request->resumen_costo_unit_flete,
                    
                    'resumen_costo_pedimento' => $request->resumen_costo_pedimento,
                    'resumen_piezas_pedimento' => $request->resumen_piezas_pedimento,
                    'resumen_costo_unit_pedimento' => $request->resumen_costo_unit_pedimento,
                    
                    // Inocuidad
                    'resumen_costo_inocuidad' => $request->resumen_costo_inocuidad,
                    'resumen_piezas_inocuidad' => $request->resumen_piezas_inocuidad,
                    'resumen_costo_unit_inocuidad' => $request->resumen_costo_unit_inocuidad,
                    
                    // Polipropileno
                    'resumen_costo_polipropileno' => $request->resumen_costo_polipropileno,
                    'resumen_piezas_polipropileno' => $request->resumen_piezas_polipropileno,
                    'resumen_costo_unit_polipropileno' => $request->resumen_costo_unit_polipropileno,
                    
                    // Estaticidad
                    'resumen_costo_estaticidad' => $request->resumen_costo_estaticidad,
                    'resumen_piezas_estaticidad' => $request->resumen_piezas_estaticidad,
                    'resumen_costo_unit_estaticidad' => $request->resumen_costo_unit_estaticidad,
                    
                    // Maquila
                    'resumen_costo_maquila' => $request->resumen_costo_maquila,
                    'resumen_piezas_maquila' => $request->resumen_piezas_maquila,
                    'resumen_costo_unit_maquila' => $request->resumen_costo_unit_maquila,
                    
                    // Etiqueta
                    'resumen_costo_etiqueta' => $request->resumen_costo_etiqueta,
                    'resumen_piezas_etiqueta' => $request->resumen_piezas_etiqueta,
                    'resumen_costo_unit_etiqueta' => $request->resumen_costo_unit_etiqueta,
                    
                    // Totales costos fila Adicionales
                    'resumen_total_costo_adicionales' => $request->resumen_total_costo_adicionales,
                    'resumen_total_piezas_adicionales' => $request->resumen_total_piezas_adicionales,
                    'resumen_total_costo_unit_adicionales' => $request->resumen_total_costo_unit_adicionales,
                    // Totales del resumen
                    'resumen_margen_administrativo' => $request->resumen_margen_administrativo,
                    'resumen_total_costo_unit' => $request->resumen_total_costo_unit,
                    
                    // Nuevos campos
                    'costo_total' => $request->costo_total,
                    
                    'entrega_prototipo' => $request->entrega_prototipo,
                    'tiempo_herramientas' => $request->tiempo_herramientas,
                    'tiempo_pt' => $request->tiempo_pt,
                    'comentarios' => $request->comentarios
            ];
            if ($esCorridaPiloto) {
                $costeoRequisicion = CosteoCorridaPiloto::updateOrCreate(
                    ['cotizaciones' => $cotizacionId],
                    $datos
                );
            } else {
                $costeoRequisicion = CosteoRequisicion::updateOrCreate(
                    ['cotizaciones' => $cotizacionId],
                    $datos
                );
            }

            if ($request->has('mp') && is_array($request->mp)) {
                $costeoRequisicion->materiaPrimaProces()->delete();
                foreach ($request->mp as $index => $datosMP) {
                    $costeoRequisicion->materiaPrimaProces()->create([
                        'costo_kilo' => $datosMP['costo_kilo'] ?? null,
                        'TC' => $datosMP['TC'] ?? null,
                        'costo_flete' => $datosMP['costo_flete'] ?? null,
                        'precio_kg' => $datosMP['precio_kg'] ?? null,
                        'costo_lamina' => $datosMP['costo_lamina'] ?? null,
                        'TC_lamina' => $datosMP['TC_lamina'] ?? null,
                        'costo_flete_lamina' => $datosMP['costo_flete_lamina'] ?? null,
                        'precio_lamina' => $datosMP['precio_lamina'] ?? null,
                        'orden' => $index,
                    ]);
                }
            }

            if ($request->has('procesos_adicionales') && is_array($request->procesos_adicionales)) {
                $costeoRequisicion->procesosAdicionales()->delete();

                foreach ($request->procesos_adicionales as $index => $datosProceso) {
                    $procesoVacio =
                        $textOrEmpty($datosProceso['concepto'] ?? '') === '' &&
                        $textOrEmpty($datosProceso['descripcion'] ?? '') === '' &&
                        ($datosProceso['no_personas'] ?? '') === '' &&
                        ($datosProceso['bajadas_por_minuto'] ?? '') === '' &&
                        ($datosProceso['total_hojas_turno'] ?? '') === '' &&
                        ($datosProceso['total_piezas_turno'] ?? '') === '' &&
                        ($datosProceso['total_dias_turnos'] ?? '') === '' &&
                        ($datosProceso['costo'] ?? '') === '';

                    if ($procesoVacio) {
                        continue;
                    }

                    $costeoRequisicion->procesosAdicionales()->create([
                        'concepto' => $textOrEmpty($datosProceso['concepto'] ?? ''),
                        'descripcion' => $textOrEmpty($datosProceso['descripcion'] ?? ''),
                        'no_personas' => $numberOrZero($datosProceso['no_personas'] ?? null),
                        'bajadas_por_minuto' => $numberOrZero($datosProceso['bajadas_por_minuto'] ?? null),
                        'total_hojas_turno' => $numberOrZero($datosProceso['total_hojas_turno'] ?? null),
                        'total_piezas_turno' => $numberOrZero($datosProceso['total_piezas_turno'] ?? null),
                        'total_dias_turnos' => $numberOrZero($datosProceso['total_dias_turnos'] ?? null),
                        'costo' => $numberOrZero($datosProceso['costo'] ?? null),
                        'orden' => $index,
                    ]);
                }
            }

            DB::commit();

            return [$cotizacion, $esCorridaPiloto];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validatePdfPayload(Request $request, Cotizacion $cotizacion): array
    {
        $rawPayload = $request->input('pdf_resumen_payload');

        if (!is_string($rawPayload) || trim($rawPayload) === '') {
            throw ValidationException::withMessages([
                'pdf_resumen_payload' => 'No se recibió el resumen final para generar el PDF.'
            ]);
        }

        try {
            $payload = json_decode($rawPayload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw ValidationException::withMessages([
                'pdf_resumen_payload' => 'El resumen final no tiene un formato JSON válido.'
            ]);
        }

        $validated = validator($payload, [
            'meta' => 'required|array',
            'meta.titulo' => 'nullable|string|max:120',
            'meta.fecha' => 'nullable|string|max:30',
            'meta.folio' => 'nullable|string|max:100',
            'meta.proyecto' => 'nullable|string|max:255',
            'rows' => 'required|array|min:1',
            'rows.*.concepto' => 'required|string|max:120',
            'rows.*.costo_total' => 'nullable|numeric',
            'rows.*.piezas' => 'nullable|numeric',
            'rows.*.costo_unitario' => 'nullable|numeric',
            'summary' => 'required|array',
            'summary.costo_unitario' => 'nullable|numeric',
            'summary.margen_administrativo' => 'nullable|numeric',
            'summary.total_final' => 'nullable|numeric',
        ])->validate();

        $rows = array_map(function (array $row): array {
            return [
                'concepto' => $row['concepto'],
                'costo_total' => round((float) ($row['costo_total'] ?? 0), 2),
                'piezas' => round((float) ($row['piezas'] ?? 0), 2),
                'costo_unitario' => round((float) ($row['costo_unitario'] ?? 0), 2),
            ];
        }, $validated['rows']);

        return [
            'meta' => [
                'titulo' => $validated['meta']['titulo'] ?? 'Resumen de Costeo',
                'fecha' => $validated['meta']['fecha'] ?? now()->format('d/m/Y'),
                'folio' => $validated['meta']['folio'] ?? $cotizacion->no_proyecto,
                'proyecto' => $validated['meta']['proyecto'] ?? optional($cotizacion)->nombre_del_proyecto,
            ],
            'rows' => $rows,
            'summary' => [
                'costo_unitario' => round((float) ($validated['summary']['costo_unitario'] ?? 0), 2),
                'margen_administrativo' => round((float) ($validated['summary']['margen_administrativo'] ?? 0), 2),
                'total_final' => round((float) ($validated['summary']['total_final'] ?? 0), 2),
            ],
        ];
    }

    private function buildPdfDownloadResponse(array $pdfData, string $fileName)
    {
        $html = view('pdf.costeo', $pdfData)->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function show($id)
    {
        $costeo = CosteoRequisicion::with(['procesosCosteo', 'cotizacion', 'procesosAdicionales'])
            ->findOrFail($id);

        return view('costeo.show', compact('costeo'));
    }

    public function edit($id)
    {
        $costeo = CosteoRequisicion::with(['procesosCosteo', 'cotizacion', 'procesosAdicionales'])
            ->findOrFail($id);

        return view('costeo.edit', compact('costeo'));
    }

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

}