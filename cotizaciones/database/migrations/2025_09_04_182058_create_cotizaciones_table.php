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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();                    
            $table->date('fecha');
            $table->string('no_proyecto');
            $table->string('cliente')->nullable();
            $table->string('contacto')->nullable();
            $table->string('puesto')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('lugar_entrega')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('nombre_del_proyecto')->nullable();
            $table->string('tipo_de_empaque')->nullable();
            //$table->date('fecha_de_efectividad')->nullable(); ya no se va a ocupar ya que la fecha de efectividad no se almacena en db
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
