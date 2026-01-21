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
        Schema::create('especificaciones_proyecto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->string('frecuencia_compra')->nullable();
            $table->integer('lote_compra')->nullable();
            $table->decimal('pieza_largo', 8, 2)->nullable();
            $table->decimal('pieza_ancho', 8, 2)->nullable();
            $table->decimal('pieza_alto', 8, 2)->nullable();
            $table->string('material')->nullable();
            $table->decimal('calibre', 8, 2)->nullable();
            $table->string('color')->nullable();
            $table->boolean('franja_color_si')->default(false);
            $table->string('franja_color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especificaciones_proyecto');
    }
};
