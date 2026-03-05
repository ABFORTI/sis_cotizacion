<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas_resumen_de_costos', function (Blueprint $table) {
            $table->decimal('herramental_margen', 8, 4)->nullable()->after('precio_venta_final');
            $table->decimal('herramental_total_ventas', 12, 2)->nullable()->after('herramental_margen');
        });
    }

    public function down(): void
    {
        Schema::table('ventas_resumen_de_costos', function (Blueprint $table) {
            $table->dropColumn(['herramental_margen', 'herramental_total_ventas']);
        });
    }
};
