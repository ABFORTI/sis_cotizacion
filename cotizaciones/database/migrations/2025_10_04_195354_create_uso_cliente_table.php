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
        Schema::create('uso_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->boolean('manipulacion_interna_info')->default(false);
            $table->boolean('proceso_interno_manual_info')->default(false);
            $table->boolean('proceso_interno_robotizado_info')->default(false);
            $table->boolean('envio_unica_cliente_info')->default(false);
            $table->boolean('envio_cliente_retornable_info')->default(false);
            $table->boolean('exhibicion_info')->default(false);
            $table->boolean('exhibicion_sello_info')->default(false);
            $table->boolean('componente_int_automotriz_info')->default(false);
            $table->boolean('componente_ext_automotriz_info')->default(false);
            $table->boolean('uso_cliente_otro_checkbox')->default(false);
            $table->string('uso_cliente_otro')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uso_cliente');
    }
};
