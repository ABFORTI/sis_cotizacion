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
        Schema::create('materia_prima_procesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costeo_requisicion_id')->constrained('costeo_requisiciones')->onDelete('cascade');

            // COSTOS MP - mismo formato que en costeo_requisiciones
            $table->decimal('costo_kilo', 12, 4)->nullable();
            $table->decimal('TC', 8, 4)->nullable();
            $table->decimal('costo_flete', 12, 4)->nullable();
            $table->decimal('precio_kg', 12, 4)->nullable();
            $table->decimal('costo_lamina', 12, 4)->nullable();
            $table->decimal('TC_lamina', 8, 4)->nullable();
            $table->decimal('costo_flete_lamina', 12, 4)->nullable();
            $table->decimal('precio_lamina', 12, 4)->nullable();

            // Orden para mantener secuencia
            $table->integer('orden')->default(0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_prima_procesos');
    }
};
