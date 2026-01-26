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
        Schema::create('cotizacion_adicional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->boolean('ppap')->default(false);
            $table->string('ppap_descripcion')->nullable();
            $table->boolean('corrida_piloto')->default(false);
            $table->string('corrida_piloto_descripcion')->nullable();
            $table->boolean('herramentales')->default(false);
            $table->boolean('almacenaje')->default(false);
            $table->boolean('prototipo')->default(false);
            $table->string('prototipo_descripcion')->nullable();
            $table->integer('pedimento_virtual')->nullable();
            $table->boolean('otros_checkbox')->default(false);
            $table->string('otro1')->nullable();
            $table->string('otro2')->nullable();
            $table->string('altura_maxima_estiba')->nullable();
            $table->decimal('peso_maximo_caja', 8, 2)->nullable();
            $table->decimal('peso_componente', 8, 2)->nullable();
            $table->integer('componentes_por_charola')->nullable();
            $table->boolean('mostrar_pestana')->default(false);
            $table->string('pestana')->nullable();
            $table->boolean('informacion_adicional_otro_checkbox')->default(false);
            $table->string('informacion_adicional_otro')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_adicional');
    }
};