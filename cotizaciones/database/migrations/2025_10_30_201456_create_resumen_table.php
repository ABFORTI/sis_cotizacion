<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 🔹 TABLA PADRE
        Schema::create('resumen', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('resumen_id');
            $table->unsignedBigInteger('cotizacion_id');

            $table->string('poka_yoke')->nullable();
            $table->string('acomodo_pieza')->nullable();
            $table->string('contenedor_cliente')->nullable();
            $table->string('medidas_contenedor')->nullable();
            $table->string('estiba_contenedor')->nullable();
            $table->string('cliente_proporciona')->nullable();

            $table->timestamps();

            $table->foreign('cotizacion_id')
                  ->references('id')
                  ->on('cotizaciones')
                  ->onDelete('cascade');
        });

        // 🔹 TABLA HIJA (ARCHIVOS)
        Schema::create('resumen_archivos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('resumen_id');

            $table->string('nombre_original');
            $table->string('path');

            $table->timestamps();

            $table->foreign('resumen_id')
                  ->references('resumen_id')
                  ->on('resumen')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resumen_archivos');
        Schema::dropIfExists('resumen');
    }
};
