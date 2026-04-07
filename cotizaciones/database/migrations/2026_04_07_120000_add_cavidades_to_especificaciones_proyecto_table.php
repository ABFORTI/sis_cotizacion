<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('especificaciones_proyecto', function (Blueprint $table) {
            $table->text('cavidades')->nullable()->after('calibre');
        });

        if (Schema::hasTable('resumen') && Schema::hasColumn('resumen', 'cavidades')) {
            $resumenes = DB::table('resumen')
                ->select('cotizacion_id', 'cavidades')
                ->whereNotNull('cavidades')
                ->where('cavidades', '!=', '')
                ->get();

            foreach ($resumenes as $resumen) {
                DB::table('especificaciones_proyecto')
                    ->where('cotizacion_id', $resumen->cotizacion_id)
                    ->whereNull('cavidades')
                    ->update(['cavidades' => $resumen->cavidades]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('especificaciones_proyecto', function (Blueprint $table) {
            $table->dropColumn('cavidades');
        });
    }
};