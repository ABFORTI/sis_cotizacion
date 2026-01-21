<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_resumen_de_costos', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('cotizacion_id')->nullable()->index();
            $table->unsignedBigInteger('costeo_requisicion_id')->nullable()->index();

            // Resumen - procesos
            $table->decimal('resumen_costo_procesos', 12, 4)->nullable();
            $table->integer('resumen_piezas_procesos')->nullable();
            $table->decimal('resumen_costo_unit_procesos', 12, 4)->nullable();
            $table->decimal('resumen_margen_procesos', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_procesos', 12, 4)->nullable();

            // Empaque
            $table->decimal('resumen_costo_empaque', 12, 4)->nullable();
            $table->integer('resumen_piezas_empaque')->nullable();
            $table->decimal('resumen_costo_unit_empaque', 12, 4)->nullable();
            $table->decimal('resumen_margen_empaque', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_empaque', 12, 4)->nullable();

            // Flete
            $table->decimal('resumen_costo_flete_total', 12, 4)->nullable();
            $table->integer('resumen_piezas_flete')->nullable();
            $table->decimal('resumen_costo_unit_flete', 12, 4)->nullable();
            $table->decimal('resumen_margen_flete', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_flete', 12, 4)->nullable();

            // Pedimento
            $table->decimal('resumen_costo_pedimento', 12, 4)->nullable();
            $table->integer('resumen_piezas_pedimento')->nullable();
            $table->decimal('resumen_costo_unit_pedimento', 12, 4)->nullable();
            $table->decimal('resumen_margen_pedimento', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_pedimento', 12, 4)->nullable();

            // Inocuidad
            $table->decimal('resumen_costo_inocuidad', 12, 4)->nullable();
            $table->integer('resumen_piezas_inocuidad')->nullable();
            $table->decimal('resumen_costo_unit_inocuidad', 12, 4)->nullable();
            $table->decimal('resumen_margen_inocuidad', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_inocuidad', 12, 4)->nullable();

            // Polipropileno
            $table->decimal('resumen_costo_polipropileno', 12, 4)->nullable();
            $table->integer('resumen_piezas_polipropileno')->nullable();
            $table->decimal('resumen_costo_unit_polipropileno', 12, 4)->nullable();
            $table->decimal('resumen_margen_polipropileno', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_polipropileno', 12, 4)->nullable();

            // Estaticidad
            $table->decimal('resumen_costo_estaticidad', 12, 4)->nullable();
            $table->integer('resumen_piezas_estaticidad')->nullable();
            $table->decimal('resumen_costo_unit_estaticidad', 12, 4)->nullable();
            $table->decimal('resumen_margen_estaticidad', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_estaticidad', 12, 4)->nullable();

            // Maquila
            $table->decimal('resumen_costo_maquila', 12, 4)->nullable();
            $table->integer('resumen_piezas_maquila')->nullable();
            $table->decimal('resumen_costo_unit_maquila', 12, 4)->nullable();
            $table->decimal('resumen_margen_maquila', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_maquila', 12, 4)->nullable();

            // Etiqueta
            $table->decimal('resumen_costo_etiqueta', 12, 4)->nullable();
            $table->integer('resumen_piezas_etiqueta')->nullable();
            $table->decimal('resumen_costo_unit_etiqueta', 12, 4)->nullable();
            $table->decimal('resumen_margen_etiqueta', 8, 4)->nullable();
            $table->decimal('resumen_precio_venta_etiqueta', 12, 4)->nullable();

            // Totales del resumen
            $table->decimal('resumen_margen_administrativo_aux', 12, 4)->nullable();
            $table->decimal('resumen_margen_administrativo', 12, 4)->nullable();
            $table->decimal('resumen_total_costo_unit', 12, 4)->nullable();

            $table->decimal('resumen_total_comision', 12, 4)->nullable();
            $table->decimal('resumen_total_comision_final', 12, 4)->nullable();
            $table->decimal('resumen_total_precio_venta_aux', 12, 4)->nullable();
            $table->decimal('resumen_total_precio_venta', 8, 4)->nullable();

            //imagenes
            $table->string('path_imagen')->nullable();

            // Variables para costo final
            $table->integer('lote_compra')->nullable();
            $table->decimal('coeficiente_merma', 8, 4)->nullable();
            $table->decimal('costo_total', 12, 2)->nullable();
            $table->decimal('precio_venta_final', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_resumen_de_costos');
    }
};