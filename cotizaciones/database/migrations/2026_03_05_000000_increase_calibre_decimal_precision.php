<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite stores NUMERIC without precision enforcement,
            // so 4-decimal values already work. No schema change needed.
            return;
        }

        Schema::table('especificaciones_proyecto', function (Blueprint $table) {
            $table->decimal('calibre', 8, 4)->nullable()->change();
        });

        Schema::table('costeo_requisiciones', function (Blueprint $table) {
            $table->decimal('calibre_costeo', 8, 4)->nullable()->change();
        });

        Schema::table('costeo_corrida_piloto', function (Blueprint $table) {
            $table->decimal('calibre_costeo', 8, 4)->nullable()->change();
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        Schema::table('especificaciones_proyecto', function (Blueprint $table) {
            $table->decimal('calibre', 8, 2)->nullable()->change();
        });

        Schema::table('costeo_requisiciones', function (Blueprint $table) {
            $table->decimal('calibre_costeo', 8, 2)->nullable()->change();
        });

        Schema::table('costeo_corrida_piloto', function (Blueprint $table) {
            $table->decimal('calibre_costeo', 8, 2)->nullable()->change();
        });
    }
};
