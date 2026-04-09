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
        Schema::table('costeo_requisiciones', function (Blueprint $table) {
            $table->decimal('divisor_ancho', 12, 4)->nullable()->after('insertos');
            $table->decimal('divisor_avance', 12, 4)->nullable()->after('divisor_ancho');
        });

        Schema::table('costeo_corrida_piloto', function (Blueprint $table) {
            $table->decimal('divisor_ancho', 12, 4)->nullable()->after('insertos');
            $table->decimal('divisor_avance', 12, 4)->nullable()->after('divisor_ancho');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costeo_requisiciones', function (Blueprint $table) {
            $table->dropColumn(['divisor_ancho', 'divisor_avance']);
        });

        Schema::table('costeo_corrida_piloto', function (Blueprint $table) {
            $table->dropColumn(['divisor_ancho', 'divisor_avance']);
        });
    }
};