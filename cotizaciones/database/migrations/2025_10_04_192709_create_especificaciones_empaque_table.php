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
        Schema::create('especificaciones_empaque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->boolean('cajas_corrugado')->default(false);
            $table->boolean('bolsa_plastico')->default(false);
            $table->boolean('liner')->default(false);
            $table->boolean('esquineros')->default(false);
            $table->text('otras_especificaciones_empaque')->nullable();
            $table->text('datos_criticos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especificaciones_empaque');
    }
};
