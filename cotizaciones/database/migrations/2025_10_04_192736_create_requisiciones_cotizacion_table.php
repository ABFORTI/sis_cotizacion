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
        Schema::create('requisiciones_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->string('tipo_estiba')->nullable();

            $table->boolean('numero_parte')->default(false);
            $table->string('descripcion_parte')->nullable();
            $table->boolean('tipo_material')->default(false);
            $table->boolean('logo_cliente')->default(false);
            $table->boolean('logo_innovet')->default(false);
            $table->boolean('sin_grabado')->default(false);

            $table->boolean('requisicion_otro')->default(false);
            $table->string('otros')->nullable();
            $table->string('tipo_flujo_carga')->nullable();
            $table->string('pared')->nullable();
            $table->string('movimiento')->nullable();
            $table->string('sujecion')->nullable();
            $table->string('temperaturas_expuestas')->nullable();
            $table->string('temperaturas_expuestas_descripcion')->nullable();
            $table->boolean('proceso_de_inocuidad')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisiciones_cotizacion');
    }
};
