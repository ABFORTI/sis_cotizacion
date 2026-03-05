<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\ArchivoAdjunto;

class CotizacionController extends Controller
{
    /**
     * Validate if a project number already exists.
     * Validaciones AJAX
     */

    /**
     * Eliminar un archivo adjunto de una cotización.
     */
    public function eliminarArchivo(Request $request, $cotizacionId)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $archivo = ArchivoAdjunto::where('cotizacion_id', $cotizacionId)
            ->where('path', $request->path)
            ->first();

        if (!$archivo) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo no encontrado en la base de datos.'
            ], 404);
        }

        // Eliminar del almacenamiento físico
        Storage::disk('public')->delete($archivo->path);

        // Eliminar el registro de la base de datos
        $archivo->delete();

        return response()->json(['success' => true]);
    }
public function ocultarParaCosteos(\App\Models\Cotizacion $cotizacion)
{
    // Actualizar de forma atómica
    $cotizacion->update(['oculta_para_costeos' => true]);

    // Redirigir de vuelta para mantener filtros y paginación
    return redirect()->back()->with('success', 'Cotización ocultada correctamente para Costeos.');
}


    public function index(Request $request)
{
    // Admin
    if (auth()->user()->role === 'admin') {
        return redirect()->route('administrador.admin.index');
    }

    $query = Cotizacion::with([
        'enviadoPorVentas',
        'enviadoPorCosteos',
        'requisicionCotizacion'
    ]);

    // Ventas: solo sus cotizaciones
    if (auth()->user()->role === 'ventas') {
        $query->where('user_id', auth()->id());
    }

    // Costeos: solo enviadas y NO ocultas
    if (auth()->user()->role === 'costeos') {
        $query->where('enviado_a_costeos', 1)
              ->where('oculta_para_costeos', false); // 🔥 AQUÍ ESTÁ LA MAGIA
    }

    // Búsqueda
    if ($search = $request->search) {
        $query->where(function ($q) use ($search) {
            $q->where('cliente', 'like', "%{$search}%")
              ->orWhere('no_proyecto', 'like', "%{$search}%")
              ->orWhere('nombre_del_proyecto', 'like', "%{$search}%");
        });
    }

    // Filtros por estado
    if ($estado = $request->estado_filter) {
        if (auth()->user()->role === 'ventas') {
            if ($estado === 'pendiente') {
                $query->where('enviado_a_costeos', false);
            } elseif ($estado === 'enviada') {
                $query->where('enviado_a_costeos', true)
                      ->where('enviado_a_ventas', false);
            } elseif ($estado === 'devuelta') {
                $query->where('enviado_a_ventas', true);
            }
        } elseif (auth()->user()->role === 'costeos') {
            if ($estado === 'pendiente') {
                $query->where('enviado_a_costeos', false);
            } elseif ($estado === 'recibida') {
                $query->where('enviado_a_ventas', false);
            } elseif ($estado === 'terminada') {
                $query->where('enviado_a_ventas', true);
            }
        }
    }

    $cotizaciones = $query
        ->orderByDesc('id')
        ->paginate(5)
        ->withQueryString();

    return view('cotizaciones.index', compact('cotizaciones'));
}



    public function verMatrizRiesgos($id)
{
    $cotizacion = Cotizacion::findOrFail($id);
    return view('cotizaciones.matriz-riesgos', compact('cotizacion'));
}
public function actualizarEstado(Request $request, Cotizacion $cotizacion)
{
    $request->validate([
        'estado' => 'required|in:aceptada,rechazada,pendiente',
    ]);

    $cotizacion->estado = $request->estado;
    $cotizacion->save();

    $mensaje = match($request->estado) {
        'aceptada' => '✅ Proyecto ACEPTADO correctamente.',
        'rechazada' => '❌ Proyecto RECHAZADO correctamente.',
        'pendiente' => '🔄 Proyecto marcado como PENDIENTE.',
    };

    return redirect()->route('cotizaciones.matrizRiesgos', $cotizacion->id)
                     ->with('success', $mensaje);
}

public function actualizarMitigacion(Request $request, Cotizacion $cotizacion)
{
    $validated = $request->validate([
        'riesgos' => 'nullable|array',
        'riesgos.*.riesgo' => 'required|string',
        'riesgos.*.severidad' => 'required|string',
        'riesgos.*.probabilidad' => 'required|string',
    ]);

    // Eliminar riesgos existentes
    $cotizacion->matrizRiesgos()->delete();

    // Si no hay riesgos, simplemente retornar
    if (empty($validated['riesgos'])) {
        return redirect()->route('cotizaciones.matrizRiesgos', $cotizacion->id)
                         ->with('success', '💾 Matriz de riesgos actualizada correctamente. (Sin riesgos registrados)');
    }

    // Mapeo de valores de severidad
    $severidadValores = [
        'Mínima' => 1,
        'Moderada' => 2,
        'Media' => 3,
        'Alta' => 4,
        'Inaceptable' => 5,
    ];

    // Mapeo de valores de probabilidad
    $probabilidadValores = [
        'Improbable' => 1,
        'Poco probable' => 2,
        'Probable' => 3,
        'Moderada' => 4,
        'Constante' => 5,
    ];

    // Crear nuevos riesgos
    foreach ($validated['riesgos'] as $riesgoData) {
        // Calcular valores numéricos
        $severidadValor = $severidadValores[$riesgoData['severidad']] ?? 0;
        $probabilidadValor = $probabilidadValores[$riesgoData['probabilidad']] ?? 0;
        $nivelRiesgoValor = $severidadValor * $probabilidadValor;

        // Determinar nivel de riesgo
        if ($nivelRiesgoValor <= 4) {
            $nivelRiesgo = 'Riesgo aceptable';
        } elseif ($nivelRiesgoValor <= 9) {
            $nivelRiesgo = 'Riesgo tolerable';
        } elseif ($nivelRiesgoValor <= 14) {
            $nivelRiesgo = 'Riesgo alto';
        } else {
            $nivelRiesgo = 'Riesgo extremo';
        }

        $cotizacion->matrizRiesgos()->create([
            'riesgo' => $riesgoData['riesgo'],
            'severidad' => $riesgoData['severidad'],
            'severidad_valor' => $severidadValor,
            'probabilidad' => $riesgoData['probabilidad'],
            'probabilidad_valor' => $probabilidadValor,
            'nivel_riesgo' => $nivelRiesgo,
            'nivel_riesgo_valor' => $nivelRiesgoValor,
        ]);
    }

    return redirect()->route('cotizaciones.matrizRiesgos', $cotizacion->id)
                     ->with('success', '💾 Matriz de riesgos actualizada correctamente.');
}

public function actualizarMitigacionGeneral(Request $request, Cotizacion $cotizacion)
{
    $request->validate([
        'plan_mitigacion_titulo' => 'required|string|max:255',
        'plan_mitigacion_descripcion' => 'required|string|max:1000',
    ]);

    $cotizacion->update([
        'plan_mitigacion_titulo' => $request->plan_mitigacion_titulo,
        'plan_mitigacion_descripcion' => $request->plan_mitigacion_descripcion,
    ]);

    return redirect()->route('cotizaciones.matrizRiesgos', $cotizacion->id)
                     ->with('success', '💾 Plan de mitigación general actualizado correctamente.');
}

    /**
     * Display admin view of all cotizaciones with advanced filtering and full information.
     */
    public function adminIndex(Request $request)
    {
        $query = Cotizacion::with(['enviadoPorVentas', 'enviadoPorCosteos', 'requisicionCotizacion', 'user']);

        // 🔍 Búsqueda avanzada
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_del_proyecto', 'like', "%{$search}%")
                    ->orWhere('cliente', 'like', "%{$search}%")
                    ->orWhere('no_proyecto', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // 🔍 Filtro por rol del usuario
        if ($roleFilter = $request->input('role_filter')) {
            $query->whereHas('user', function ($userQuery) use ($roleFilter) {
                $userQuery->where('role', $roleFilter);
            });
        }

        // 🔍 Filtro por estado
        if ($statusFilter = $request->input('status_filter')) {
            switch ($statusFilter) {
                case 'pendiente':
                    $query->where('enviado_a_costeos', false);
                    break;
                case 'enviado_costeos':
                    $query->where('enviado_a_costeos', true)
                          ->where('enviado_a_ventas', false);
                    break;
                case 'devuelto_ventas':
                    $query->where('enviado_a_ventas', true);
                    break;
            }
        }

        $cotizaciones = $query->orderBy('id', 'desc')->paginate(5);

        // 📊 Estadísticas para el dashboard
        $estadisticas = [
            'total' => Cotizacion::count(),
            'pendientes' => Cotizacion::where('enviado_a_costeos', false)->count(),
            'en_costeos' => Cotizacion::where('enviado_a_costeos', true)
                                    ->where('enviado_a_ventas', false)->count(),
            'completadas' => Cotizacion::where('enviado_a_ventas', true)->count(),
        ];

        return view('administrador.admin-index', compact('cotizaciones', 'estadisticas'));
    }

    // Relacionar cotización como enviada a Costeos
    public function marcarEnviado(Cotizacion $cotizacion)
    {
        if (!$cotizacion->enviado_a_costeos) {
            $cotizacion->update([
                'enviado_a_costeos' => true,
                'enviado_por_ventas' => Auth::id(), // ID del usuario de ventas que envía
                'fecha_envio_ventas' => now() // Fecha y hora actual del envío desde ventas
            ]);

            return redirect()->route('cotizaciones.index')
                ->with('success', 'Cotización enviada a Costeos correctamente.');
        }

        return redirect()->route('cotizaciones.index')
            ->with('error', 'Esta cotización ya fue enviada a Costeos.');
    }

    // Relacionar cotización como enviada a Ventas
    public function enviarACosteos(Cotizacion $cotizacion)
    {
        $usuario = Auth::user();

        // Solo ventas y admin pueden enviar
        if (!in_array($usuario->role, ['ventas', 'admin'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        // Actualizar campos de envío
        $cotizacion->update([
            'enviado_a_costeos' => true,
            'enviado_por' => $usuario->id, // Guardar el ID del remitente
            'fecha_envio_a_costeos' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Cotización enviada al área de Costeos correctamente.');
    }

    public function enviarAVentas($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        if ($cotizacion->enviado_a_ventas) {
            return response()->json(['warning' => 'Esta cotización ya fue enviada a Ventas.']);
        }

        $cotizacion->update([
            'enviado_a_ventas' => true,
            'enviado_por_costeos' => Auth::id(), // ID del usuario de costeos que envía
            'fecha_envio_costeos' => now() // Fecha y hora actual del envío desde costeos
        ]);

        return response()->json(['success' => 'La cotización fue enviada correctamente a Ventas.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('cotizaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación básica
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'no_proyecto' => 'required|string|max:255',
            'correo' => 'nullable|email'
            //'fecha_de_efectividad' => 'required|date', se quita porque ya no se va a guardar en db
        ]);

        // 2. Crear cotización principal
        $cotizacion = Cotizacion::create(array_merge(
            $request->only([
                'fecha',
                'no_proyecto',
                'cliente',
                'contacto',
                'puesto',
                'domicilio',
                'lugar_entrega',
                'telefono',
                'correo',
                'nombre_del_proyecto',
                'tipo_de_empaque'
                //'fecha_de_efectividad' ya no se va a guardar en db
            ]),
            ['user_id' => auth()->id()] // ✅ Guarda el ID del usuario autenticado
        ));

        // 3. Crear especificaciones proyecto
        $cotizacion->especificacionProyecto()->create($request->only([
            'frecuencia_compra',
            'lote_compra',
            'pieza_largo',
            'pieza_ancho',
            'pieza_alto',
            'material',
            'material_otro',
            'calibre',
            'color',
            'franja_color_si',
            'franja_color'
        ]));

        // 4. Crear especificaciones empaque
        $cotizacion->especificacionEmpaque()->create($request->only([
            'cajas_corrugado',
            'bolsa_plastico',
            'liner',
            'esquineros',
            'otras_especificaciones_empaque',
            'datos_criticos'
        ]));

        // 5. Crear cotización adicional
        $cotizacion->cotizacionAdicional()->create($request->only([
            'ppap',
            'ppap_descripcion',
            'corrida_piloto',
            'corrida_piloto_descripcion',
            'herramentales',
            'almacenaje',
            'prototipo',
            'prototipo_descripcion',
            'pedimento_virtual',
            'otros_checkbox',
            'otro1',
            'otro2',
            'altura_maxima_estiba',
            'peso_maximo_caja',
            'peso_componente',
            'componentes_por_charola',
            'mostrar_pestana',
            'pestana',
            'informacion_adicional_otro_checkbox',
            'informacion_adicional_otro'
        ]));

        // 6.Requisicion de cotizacion
        $cotizacion->requisicionCotizacion()->create($request->only([
            'tipo_estiba',
            'numero_parte',
            'descripcion_parte',
            'tipo_material',
            'logo_cliente',
            'logo_innovet',
            'sin_grabado',
            'requisicion_otro',
            'otros',
            'tipo_flujo_carga',
            'pared',
            'movimiento',
            'sujecion',
            'temperaturas_expuestas',
            'temperaturas_expuestas_descripcion',
            'proceso_de_inocuidad'
        ]));

        // 7. Termoformado
        $cotizacion->termoformado()->create($request->only([
            'pieza_mejorar',
            'pieza_fisica_proteger',
            'plano_pieza_termoformada',
            'igs_componente',
            'igs_pieza_termoformada',
            'contenedor',
            'plano_pieza_pdf',
            'nc',
            'na',
            'termoformado_otro_checkbox',
            'termoformado_otro_info'
        ]));

        // 8. uso cliente
        $cotizacion->usoCliente()->create($request->only([
            'manipulacion_interna_info',
            'proceso_interno_manual_info',
            'proceso_interno_robotizado_info',
            'envio_unica_cliente_info',
            'envio_cliente_retornable_info',
            'exhibicion_info',
            'exhibicion_sello_info',
            'componente_int_automotriz_info',
            'componente_ext_automotriz_info',
            'uso_cliente_otro_checkbox',
            'uso_cliente_otro',
        ]));

        // 9. Caja cliente
        $cotizacion->cajaCliente()->create($request->only([
            'caja_largo',
            'caja_ancho',
            'caja_alto',
            'dedales'
        ]));

        // . Archivos
        if ($request->hasFile('archivos')) {
            $filePaths = [];
            foreach ($request->file('archivos') as $file) {
                $path = $file->store('cotizaciones', 'public');
                $filePaths[] = $path;
            }
            $cotizacion->archivosAdjuntos()->createMany(
                collect($filePaths)->map(fn($path) => ['path' => $path])->toArray()
            );
        }

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Requisición de cotización creada con éxito.');
    }


    /**
     * Display the specified resource.
     */
    public function showForm(Cotizacion $cotizacion)
{
    $usuario = auth()->user();

    // Buscar resumen asociado a la cotización
    $resumen = \App\Models\Resumen::where('cotizacion_id', $cotizacion->id)->first();

    if ($usuario->role === 'costeos') {
        return view('costeo.resumen_cotizacion', compact('cotizacion', 'resumen'));
    }

    return view('costeo.cotizacion_innovet', compact('cotizacion', 'resumen'));
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cotizacion $cotizacion, Request $request)
    {
        return view('cotizaciones.edit', compact('cotizacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        // 1. Actualizar cotización principal
        $cotizacion->update($request->only([
            'fecha',
            'no_proyecto',
            'cliente',
            'contacto',
            'puesto',
            'domicilio',
            'lugar_entrega',
            'telefono',
            'correo',
            'nombre_del_proyecto',
            'tipo_de_empaque'
            //'fecha_de_efectividad'// ya no se va a guardar en db
        ]));

        // 2. Actualizar hijos (si existen)
        $cotizacion->especificacionProyecto()
            ->updateOrCreate([], $request->only([
                'frecuencia_compra',
                'lote_compra',
                'pieza_largo',
                'pieza_ancho',
                'pieza_alto',
                'material',
                'material_otro',
                'calibre',
                'color',
                'franja_color_si',
                'franja_color'
            ]));

        $cotizacion->especificacionEmpaque()
            ->updateOrCreate([], $request->only([
                'cajas_corrugado',
                'bolsa_plastico',
                'liner',
                'esquineros',
                'otras_especificaciones_empaque',
                'datos_criticos'
            ]));

        $cotizacion->cotizacionAdicional()
            ->updateOrCreate([], $request->only([
                'ppap',
                'ppap_descripcion',
                'corrida_piloto',
                'corrida_piloto_descripcion',
                'herramentales',
                'almacenaje',
                'prototipo',
                'prototipo_descripcion',
                'pedimento_virtual',
                'otros_checkbox',
                'otro1',
                'otro2',
                'altura_maxima_estiba',
                'peso_maximo_caja',
                'peso_componente',
                'componentes_por_charola',
                'mostrar_pestana',
                'pestana',
                'informacion_adicional_otro_checkbox',
                'informacion_adicional_otro'
            ]));

        $cotizacion->requisicionCotizacion()
            ->updateOrCreate([], $request->only([
                'tipo_estiba',
                'numero_parte',
                'descripcion_parte',
                'tipo_material',
                'logo_cliente',
                'logo_innovet',
                'sin_grabado',
                'requisicion_otro',
                'otros',
                'tipo_flujo_carga',
                'pared',
                'movimiento',
                'sujecion',
                'temperaturas_expuestas',
                'temperaturas_expuestas_descripcion',
                'proceso_de_inocuidad'
            ]));

        $cotizacion->termoformado()
            ->updateOrCreate([], $request->only([
                'pieza_mejorar',
                'pieza_fisica_proteger',
                'plano_pieza_termoformada',
                'igs_componente',
                'igs_pieza_termoformada',
                'contenedor',
                'plano_pieza_pdf',
                'nc',
                'na',
                'termoformado_otro_checkbox',
                'termoformado_otro_info'
            ]));

        $cotizacion->usoCliente()
            ->updateOrCreate(
                [],
                $request->only([
                    'manipulacion_interna_info',
                    'proceso_interno_manual_info',
                    'proceso_interno_robotizado_info',
                    'envio_unica_cliente_info',
                    'envio_cliente_retornable_info',
                    'exhibicion_info',
                    'exhibicion_sello_info',
                    'componente_int_automotriz_info',
                    'componente_ext_automotriz_info',
                    'uso_cliente_otro_checkbox',
                    'uso_cliente_otro',
                ])
            );

        $cotizacion->cajaCliente()
            ->updateOrCreate([], $request->only([
                'caja_largo',
                'caja_ancho',
                'caja_alto',
                'dedales'
            ]));


        // 3. Manejo de archivos
        if ($request->hasFile('archivos')) {
            $filePaths = [];

            foreach ($request->file('archivos') as $file) {
                $path = $file->store('cotizaciones', 'public');
                $filePaths[] = $path;
            }

            // Crea múltiples registros relacionados
            $cotizacion->archivosAdjuntos()->createMany(
                collect($filePaths)->map(fn($path) => ['path' => $path])->toArray()
            );
        }

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización actualizada con éxito.');
    }


    /**
     * Mostrar la vista de lineamientos del proyecto.
     */
    public function mostrarLineamientos($id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion'
        ])->findOrFail($id);

        return view('costeo.cotizacion_innovet', compact('cotizacion'));
    }



    /**
     * Guardar los lineamientos de la cotización.
     */
    public function guardarLineamientos(Request $request, $id)
    {
        $request->validate([
            'lineamiento_1' => 'nullable|string',
            'lineamiento_2' => 'nullable|string',
            'lineamiento_3' => 'nullable|string',
            'lineamiento_4' => 'nullable|string',
            'lineamiento_5' => 'nullable|string',
            'lineamiento_6' => 'nullable|string',
            'lineamiento_7' => 'nullable|string',
            'lineamiento_8' => 'nullable|string',
            'lineamiento_9' => 'nullable|string',
            'lineamiento_10' => 'nullable|string',
            'tiempo_herramentales' => 'nullable|string',
            'puesto_contacto' => 'nullable|string',
        ]);

        $cotizacion = Cotizacion::findOrFail($id);
        
        $cotizacion->update([
            'lineamiento_1' => $request->lineamiento_1,
            'lineamiento_2' => $request->lineamiento_2,
            'lineamiento_3' => $request->lineamiento_3,
            'lineamiento_4' => $request->lineamiento_4,
            'lineamiento_5' => $request->lineamiento_5,
            'lineamiento_6' => $request->lineamiento_6,
            'lineamiento_7' => $request->lineamiento_7,
            'lineamiento_8' => $request->lineamiento_8,
            'lineamiento_9' => $request->lineamiento_9,
            'lineamiento_10' => $request->lineamiento_10,
            'tiempo_herramentales' => $request->tiempo_herramentales,
            'puesto_contacto' => $request->puesto_contacto,
        ]);

        return redirect()->route('cotizacion.lineamientos', $id)
            ->with('success', 'Lineamientos guardados correctamente.');
    }

    /**
     * Clonar una requisición de cotización existente.
     * Crea un nuevo registro copiando todos los datos, reseteando el flujo de trabajo.
     */
    public function clone(Cotizacion $cotizacion)
    {
        $usuario = Auth::user();

        if (!in_array($usuario->role, ['ventas', 'admin'])) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $cotizacion->load([
            'especificacionProyecto',
            'especificacionEmpaque',
            'cotizacionAdicional',
            'requisicionCotizacion',
            'termoformado',
            'usoCliente',
            'cajaCliente',
        ]);

        // Clonar cotización principal, reseteando campos de flujo y auditoría
        $nueva = $cotizacion->replicate();
        $nueva->fecha               = now()->format('Y-m-d');
        $nueva->user_id             = $usuario->id;
        $nueva->enviado_a_costeos   = false;
        $nueva->enviado_por_ventas  = null;
        $nueva->fecha_envio_ventas  = null;
        $nueva->enviado_a_ventas    = false;
        $nueva->enviado_por_costeos = null;
        $nueva->fecha_envio_costeos = null;
        $nueva->oculta_para_costeos = false;
        $nueva->estado              = 'pendiente';
        $nueva->plan_mitigacion_titulo      = null;
        $nueva->plan_mitigacion_descripcion = null;

        foreach (range(1, 10) as $i) {
            $nueva->{"lineamiento_{$i}"} = null;
        }

        $nueva->save();

        // Clonar relaciones hijo actualizando la FK
        foreach (['especificacionProyecto', 'especificacionEmpaque', 'cotizacionAdicional', 'requisicionCotizacion', 'termoformado', 'usoCliente', 'cajaCliente'] as $relacion) {
            if ($cotizacion->$relacion) {
                $hijo = $cotizacion->$relacion->replicate();
                $hijo->cotizacion_id = $nueva->id;
                $hijo->save();
            }
        }

        return redirect()->route('cotizaciones.edit', $nueva)
            ->with('success', 'Requisición clonada exitosamente. Modifica los valores necesarios y guarda.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada con éxito.');
    }
    
}
