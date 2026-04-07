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
        Schema::create('proceso_adicionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costeo_requisicion_id')->constrained('costeo_requisiciones')->onDelete('cascade');
            $table->string('concepto')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('no_personas')->nullable();
            $table->decimal('bajadas_por_minuto', 10, 4)->nullable();
            $table->integer('total_hojas_turno')->nullable();
            $table->integer('total_piezas_turno')->nullable();
            $table->decimal('total_dias_turnos', 10, 4)->nullable();
            $table->decimal('costo', 12, 4)->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proceso_adicionals');
    }
};
