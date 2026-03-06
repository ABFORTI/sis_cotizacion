<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archivos_adjuntos', function (Blueprint $table) {
            $table->string('nombre_original')->nullable()->after('path');
            $table->string('tipo_archivo')->nullable()->after('nombre_original');
            $table->unsignedBigInteger('tamaño')->nullable()->after('tipo_archivo');
        });
    }

    public function down(): void
    {
        Schema::table('archivos_adjuntos', function (Blueprint $table) {
            $table->dropColumn(['nombre_original', 'tipo_archivo', 'tamaño']);
        });
    }
};
