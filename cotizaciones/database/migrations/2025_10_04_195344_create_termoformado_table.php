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
        Schema::create('termoformado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->boolean('pieza_mejorar')->default(false);
            $table->boolean('pieza_fisica_proteger')->default(false);
            $table->boolean('plano_pieza_termoformada')->default(false);
            $table->boolean('igs_componente')->default(false);
            $table->boolean('igs_pieza_termoformada')->default(false);
            $table->boolean('contenedor')->default(false);
            $table->boolean('plano_pieza_pdf')->default(false);
            $table->boolean('nc')->default(false);
            $table->boolean('na')->default(false);
            $table->boolean('termoformado_otro_checkbox')->default(false);
            $table->string('termoformado_otro_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('termoformado');
    }
};
