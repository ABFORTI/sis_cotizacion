<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('costeo_requisiciones', function (Blueprint $table) {
            $table->id();
            // RELACION CON TABLA COTIZACIONES
            $table->foreignId('cotizaciones')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');

            // ========== SECCIÓN: DISTRIBUCION DE HERRAMENTAL ==========
            $table->decimal('calibre_costeo', 8, 2)->nullable();
            $table->decimal('insertos', 8, 2)->nullable();

            // ========== SECCIÓN: ACOMODO ANCHO ========== placa_de_enfriamiento 
            $table->integer('acomodo_ancho_medida_cantidad')->nullable();
            $table->decimal('acomodo_ancho_medida_total', 10, 2)->nullable();
            $table->decimal('acomodo_ancho_orillas_mm', 10, 2)->nullable();
            $table->integer('acomodo_ancho_orillas_cantidad')->nullable();
            $table->decimal('acomodo_ancho_orillas_total', 10, 2)->nullable();
            $table->decimal('acomodo_ancho_medianiles_mm', 10, 2)->nullable();
            $table->integer('acomodo_ancho_medianiles_cantidad')->nullable();
            $table->decimal('acomodo_ancho_medianiles_total', 10, 2)->nullable();

            // ========== SECCIÓN: ACOMODO AVANCE ==========
            $table->integer('acomodo_avance_medida_cantidad')->nullable();
            $table->decimal('acomodo_avance_medida_total', 10, 2)->nullable();
            $table->decimal('acomodo_avance_orillas_mm', 10, 2)->nullable();
            $table->integer('acomodo_avance_orillas_cantidad')->nullable();
            $table->decimal('acomodo_avance_orillas_total', 10, 2)->nullable();
            $table->decimal('acomodo_avance_medianiles_mm', 10, 2)->nullable();
            $table->integer('acomodo_avance_medianiles_cantidad')->nullable();
            $table->decimal('acomodo_avance_medianiles_total', 10, 2)->nullable();

            // ========== SECCIÓN: MOLDE, HOJA Y PLACA DE ENFRIAMIENTO ==========
            $table->decimal('molde_ancho', 10, 2)->nullable();
            $table->decimal('molde_avance', 10, 2)->nullable();
            $table->decimal('hoja_ancho', 10, 2)->nullable();
            $table->decimal('aux_hoja_ancho', 10, 2)->nullable();
            $table->decimal('hoja_avance', 10, 2)->nullable();
            $table->decimal('aux_hoja_avance', 10, 2)->nullable();
            $table->integer('placa_de_enfriamiento')->nullable();

            // ========== SECCIÓN: MATERIAL PRIMA ==========
            $table->decimal('peso_especifico', 10, 4)->nullable();
            $table->decimal('area_formado_hoja', 12, 4)->nullable();
            $table->integer('cantidad_hojas')->nullable();
            $table->decimal('peso_pieza', 12, 4)->nullable();
            $table->decimal('peso_neto_hoja', 12, 4)->nullable();
            $table->decimal('coeficiente_merma', 8, 4)->nullable();
            $table->decimal('peso_merma', 12, 4)->nullable();
            $table->decimal('peso_bruto_hoja', 12, 4)->nullable();
            $table->decimal('peso_neto', 12, 4)->nullable();
            $table->decimal('peso_total', 12, 4)->nullable();
            $table->decimal('PRM', 12, 4)->nullable();
            $table->decimal('divisor_prm', 12, 4)->nullable();
            $table->decimal('sumador_prm', 12, 4)->nullable();
            $table->decimal('PZRM', 12, 4)->nullable();

            // COSTOS MP
            $table->decimal('costo_kilo', 12, 4)->nullable();
            $table->decimal('TC', 8, 4)->nullable();
            $table->decimal('costo_flete', 12, 4)->nullable();
            $table->decimal('precio_kg', 12, 4)->nullable();
            $table->decimal('costo_lamina', 12, 4)->nullable();
            $table->decimal('TC_lamina', 8, 4)->nullable();
            $table->decimal('costo_flete_lamina', 12, 4)->nullable();
            $table->decimal('precio_lamina', 12, 4)->nullable();
            $table->text('sugerencia_costos_mp')->nullable();

            // ========== SECCIÓN: COSTOS DE PROCESOS ==========
            $table->decimal('hojas_del_pedido', 12, 4)->nullable();
            // ========== SECCIÓN: COSTOS DE TERMOMFORMADO ==========
            $table->string('nombre_maquina_termoformado')->default('Máquina de Termoformado');
            $table->integer('no_personas_termoformado')->nullable();
            $table->decimal('bajadas_por_minuto_termoformado', 10, 2)->nullable();
            $table->integer('total_hojas_turno_termoformado')->nullable();
            $table->decimal('total_dias_turnos_termoformado', 10, 2)->nullable();
            $table->decimal('costo_termoformado', 12, 4)->nullable();
            // ========== SECCIÓN: COSTOS DE SUAJE ==========
            $table->string('nombre_maquina_suaje')->default('Máquina de Suaje');
            $table->integer('no_personas_suaje')->nullable();
            $table->decimal('bajadas_por_minuto_suaje', 10, 2)->nullable();
            $table->integer('total_hojas_turno_suaje')->nullable();
            $table->integer('total_piezas_turno_suaje')->nullable();
            $table->decimal('total_dias_turnos_suaje', 10, 2)->nullable();
            $table->decimal('costo_suaje', 12, 4)->nullable();

            // COSTOS DE PROCESOS SON LOS QUE VAN DEBAJO DE MAQUINAS
            $table->decimal('costo_montaje', 12, 4)->nullable();
            $table->decimal('costo_montaje2', 12, 4)->nullable();
            $table->decimal('costo_amortizacion_herramentales', 12, 4)->nullable();
            $table->decimal('costo_amortizacion_herramentales2', 12, 4)->nullable();
            $table->decimal('costo_electricidad', 12, 4)->nullable();
            $table->decimal('costo_electricidad2', 12, 4)->nullable();
            $table->decimal('amortizacion_maquinaria', 12, 4)->nullable();
            $table->decimal('amortizacion_maquinaria2', 12, 4)->nullable();

            // COSTOS FABRICACIÓN
            $table->decimal('costo_fabricacion', 12, 4)->nullable();
            $table->decimal('costo_mp', 12, 4)->nullable();
            $table->decimal('costo_total_procesos', 12, 4)->nullable();

            // ========== SECCIÓN: EMPAQUE ==========
            $table->integer('piezas_por_bolsa')->nullable();
            $table->integer('aux_piezas_por_bolsa')->nullable();
            $table->integer('piezas_por_caja')->nullable();
            $table->integer('bolsas_por_tarima')->nullable();
            $table->integer('cajas_por_tarima')->nullable();
            $table->integer('total_bolsas')->nullable();
            $table->integer('total_cajas')->nullable();
            $table->decimal('tarimas_totales_bolsas', 12, 2)->nullable();
            $table->decimal('tarimas_totales_cajas', 12, 2)->nullable();

            // COSTOS EMPAQUE
            $table->decimal('costo_corrugado', 12, 4)->nullable();
            $table->decimal('total_corrugado', 12, 4)->nullable();
            $table->decimal('costo_bolsa', 12, 4)->nullable();
            $table->decimal('total_bolsa', 12, 4)->nullable();
            $table->decimal('costo_tarima', 12, 4)->nullable();
            $table->decimal('total_tarima', 12, 4)->nullable();
            $table->decimal('costo_empaque_total', 12, 4)->nullable();

            // ========== SECCIÓN: COSTOS ADICIONALES ==========
            $table->decimal('costo_inocuidad', 12, 4)->nullable();
            $table->decimal('costo_pared', 12, 4)->nullable();
            $table->enum('aplicacion_estaticida', ['si', 'no'])->default('no');
            $table->decimal('no_personas_estaticida', 12, 4)->nullable();
            $table->decimal('piezas_por_hora_estaticida', 12, 4)->nullable();
            $table->decimal('costo_estaticida_total', 12, 4)->nullable();
            $table->enum('maquila', ['si', 'no'])->default('no');
            $table->decimal('no_personas_maquila', 12, 4)->nullable();
            $table->decimal('costo_maquila_total', 12, 4)->nullable();

            // ========== SECCIÓN: COSTOS HERRAMENTALES ==========
            $table->decimal('molde_ancho_copia', 12, 4)->nullable();
            $table->decimal('molde_avance_copia', 12, 4)->nullable();
            $table->decimal('ajuste_ancho', 12, 4)->nullable();
            $table->decimal('ajuste_avance', 12, 4)->nullable();
            $table->decimal('ajuste_alto', 12, 4)->nullable();
            $table->decimal('medida_bloque_ancho', 12, 4)->nullable();
            $table->decimal('medida_bloque_avance', 12, 4)->nullable();
            $table->decimal('medida_bloque_alto', 12, 4)->nullable();
            $table->decimal('kilos', 12, 4)->nullable();
            $table->decimal('constante_empujador', 12, 4)->nullable();

            /// ========== COSTEO HERRAMENTALES ==========
            $table->decimal('costo_aluminio', 12, 4)->nullable();
            $table->decimal('costo_molde', 12, 4)->nullable();
            $table->decimal('aux_empujador', 12, 4)->nullable();
            $table->decimal('costo_empujador', 12, 4)->nullable();
            $table->decimal('costo_suaje_base', 12, 4)->nullable();
            $table->decimal('no_muestras', 12, 4)->nullable();
            $table->decimal('aux_muestras', 12, 4)->nullable();
            $table->decimal('costo_muestras', 12, 4)->nullable();
            $table->decimal('costo_placa_fijacion', 12, 4)->nullable();
            $table->decimal('dividendo', 12, 4)->nullable();
            $table->decimal('divisor', 12, 4)->nullable();
            $table->decimal('costo_madera_campana', 12, 4)->nullable();
            $table->decimal('costo_prototipo', 12, 4)->nullable();
            $table->decimal('costo_tornilleria', 12, 4)->nullable();
            $table->decimal('costo_pedimento_herramental', 12, 4)->nullable();
            $table->integer('hrs_maquinada_molde')->nullable();
            $table->integer('hrs_maquinada_empujador')->nullable();

            /// ========== CALCULO DE TOTALES  ==========
            $table->decimal('total_molde', 12, 4)->nullable();
            $table->decimal('total_empujador', 12, 4)->nullable();
            $table->decimal('TOTAL_FINAL', 12, 4)->nullable();
            $table->decimal('TOTAL_VENTAS', 12, 4)->nullable();

            // ========== SECCIÓN: RESUMEN DE COSTOS ==========
            // Procesos
            $table->decimal('resumen_costo_procesos', 12, 4)->nullable();
            $table->decimal('resumen_piezas_procesos', 8, 2)->nullable();
            $table->decimal('resumen_costo_unit_procesos', 12, 4)->nullable();
            //$table->decimal('resumen_margen_procesos', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_procesos', 12, 4)->nullable();

            // Empaque
            $table->decimal('resumen_costo_empaque', 12, 4)->nullable();
            $table->integer('resumen_piezas_empaque')->nullable();
            $table->decimal('resumen_costo_unit_empaque', 12, 4)->nullable();
            //$table->decimal('resumen_margen_empaque', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_empaque', 12, 4)->nullable();

            // Flete
            $table->decimal('resumen_costo_flete_total', 12, 4)->nullable();
            $table->integer('resumen_piezas_flete')->nullable();
            $table->decimal('resumen_costo_unit_flete', 12, 4)->nullable();
            //$table->decimal('resumen_margen_flete', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_flete', 12, 4)->nullable();
            
            // Pedimento
            $table->decimal('resumen_costo_pedimento', 12, 4)->nullable();
            $table->integer('resumen_piezas_pedimento')->nullable();
            $table->decimal('resumen_costo_unit_pedimento', 12, 4)->nullable();
            //$table->decimal('resumen_margen_pedimento', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_pedimento', 12, 4)->nullable();
    
            // Inocuidad
            $table->decimal('resumen_costo_inocuidad', 12, 4)->nullable();
            $table->integer('resumen_piezas_inocuidad')->nullable();
            $table->decimal('resumen_costo_unit_inocuidad', 12, 4)->nullable();
            //$table->decimal('resumen_margen_inocuidad', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_inocuidad', 12, 4)->nullable();

            // Polipropileno
            $table->decimal('resumen_costo_polipropileno', 12, 4)->nullable();
            $table->integer('resumen_piezas_polipropileno')->nullable();
            $table->decimal('resumen_costo_unit_polipropileno', 12, 4)->nullable();
            //$table->decimal('resumen_margen_polipropileno', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_polipropileno', 12, 4)->nullable();

            // Estaticidad
            $table->decimal('resumen_costo_estaticidad', 12, 4)->nullable();
            $table->integer('resumen_piezas_estaticidad')->nullable();
            $table->decimal('resumen_costo_unit_estaticidad', 12, 4)->nullable();
            //$table->decimal('resumen_margen_estaticidad', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_estaticidad', 12, 4)->nullable();

            // Maquila
            $table->decimal('resumen_costo_maquila', 12, 4)->nullable();
            $table->integer('resumen_piezas_maquila')->nullable();
            $table->decimal('resumen_costo_unit_maquila', 12, 4)->nullable();
            //$table->decimal('resumen_margen_maquila', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_maquila', 12, 4)->nullable();
            
            // Etiqueta
            $table->decimal('resumen_costo_etiqueta', 12, 4)->nullable();
            $table->integer('resumen_piezas_etiqueta')->nullable();
            $table->decimal('resumen_costo_unit_etiqueta', 12, 4)->nullable();
            //$table->decimal('resumen_margen_etiqueta', 8, 4)->nullable();
            //$table->decimal('resumen_precio_venta_etiqueta', 12, 4)->nullable();

            // Totales costos fila Adicionales
            $table->decimal('resumen_total_costo_adicionales', 12, 4)->nullable();
            $table->integer('resumen_total_piezas_adicionales')->nullable();
            $table->decimal('resumen_total_costo_unit_adicionales', 12, 4)->nullable();

            // Totales del resumen
            $table->decimal('resumen_margen_administrativo', 12, 4)->nullable();
            $table->decimal('resumen_total_costo_unit', 12, 4)->nullable();
            //$table->decimal('comision_margen', 12, 4)->nullable();
            //$table->decimal('resumen_total_comision', 12, 4)->nullable();
            //$table->decimal('resumen_total_precio_venta', 12, 4)->nullable();
            
            //$table->decimal('venta_total', 12, 4)->nullable();
            $table->decimal('costo_total', 12, 4)->nullable();
            //$table->decimal('margen_bruto', 12, 4)->nullable();
            //$table->decimal('margen_bruto_porcentaje', 8, 4)->nullable();
            $table->string('entrega_prototipo')->nullable();
            $table->string('tiempo_herramientas')->nullable();
            $table->string('tiempo_pt')->nullable();
            $table->text('comentarios')->nullable();

            $table->timestamps();
        });

        // PROCESOS DINAMICOS - NO USADOS POR EL MOMENTO
        /*
        // Crear tabla para procesos
        Schema::create('procesos_costeo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costeo_requisiciones_id')->constrained('costeo_requisiciones')->onDelete('cascade');
            $table->string('concepto')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('costo', 12, 4)->nullable();
            $table->timestamps();
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PROCESOS DINAMICOS - NO USADOS POR EL MOMENTO
        // Schema::dropIfExists('procesos_costeo');
        Schema::dropIfExists('costeo_requisiciones');
    }
};