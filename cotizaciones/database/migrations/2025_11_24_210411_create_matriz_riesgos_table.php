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
        Schema::create('matriz_riesgos', function (Blueprint $table) {
            $table->id();
            
            // Relación con cotizaciones
            $table->foreignId('cotizacion_id')
                ->constrained('cotizaciones')
                ->onDelete('cascade');
            
            // Información del Riesgo
            $table->enum('riesgo', ['Arañas en contorno', 'Arañas en cavidades', 'Adelgazamiento de paredes', 'Adelgazamiento de fondo de cavidades', 'Poca funcionalidad de broche', 'Blanqueado', 'Opaco', 'Desfase de pestañas', 'Redondeo', 'Contracción de material', 'Riesgo de funcionalidad de estiba', 'Entregas fuera de tiempo establecido'])
                ->comment('Descripción del riesgo identificado');
            
            // Severidad (Impacto)
            $table->enum('severidad', ['Mínima', 'Moderada', 'Media', 'Alta', 'Inaceptable'])
                ->comment('Nivel de impacto si el riesgo ocurre');
            
            $table->integer('severidad_valor')
                ->nullable()
                ->comment('Valor numérico de severidad (1-5)');
            
            // Probabilidad (Frecuencia)
            $table->enum('probabilidad', ['Improbable', 'Poco probable', 'Probable', 'Moderada', 'Constante'])
                ->comment('Probabilidad de que el riesgo ocurra');
            
            $table->integer('probabilidad_valor')
                ->nullable()
                ->comment('Valor numérico de probabilidad (1-5)');
            
            // Nivel de Riesgo (calculado)
            $table->enum('nivel_riesgo', ['Riesgo aceptable', 'Riesgo tolerable', 'Riesgo alto', 'Riesgo extremo'])
                ->comment('Nivel de riesgo: Riesgo aceptable (bajo), Riesgo tolerable (medio), Riesgo alto (alto), Riesgo extremo (muy alto)');
            
            $table->integer('nivel_riesgo_valor')
                ->nullable()
                ->comment('Valor calculado: Severidad x Probabilidad');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriz_riesgos');
    }
};
