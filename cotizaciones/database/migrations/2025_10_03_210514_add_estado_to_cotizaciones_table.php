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
        Schema::table('cotizaciones', function (Blueprint $table) {

            if (!Schema::hasColumn('cotizaciones', 'estado')) {
                $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])
                    ->default('pendiente')
                    ->after('id');
            }

            if (!Schema::hasColumn('cotizaciones', 'enviado_a_costeos')) {
                $table->boolean('enviado_a_costeos')
                    ->default(false)
                    ->after('estado');
            }

            if (!Schema::hasColumn('cotizaciones', 'enviado_a_ventas')) {
                $table->boolean('enviado_a_ventas')
                    ->default(false)
                    ->after('enviado_a_costeos');
            }

            if (!Schema::hasColumn('cotizaciones', 'enviado_por_ventas')) {
                $table->foreignId('enviado_por_ventas')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete() // mejor práctica moderna
                    ->after('enviado_a_ventas');
            }

            if (!Schema::hasColumn('cotizaciones', 'fecha_envio_ventas')) {
                $table->timestamp('fecha_envio_ventas')
                    ->nullable()
                    ->after('enviado_por_ventas');
            }

            if (!Schema::hasColumn('cotizaciones', 'enviado_por_costeos')) {
                $table->foreignId('enviado_por_costeos')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete()
                    ->after('fecha_envio_ventas');
            }

            if (!Schema::hasColumn('cotizaciones', 'fecha_envio_costeos')) {
                $table->timestamp('fecha_envio_costeos')
                    ->nullable()
                    ->after('enviado_por_costeos');
            }

            if (!Schema::hasColumn('cotizaciones', 'plan_mitigacion_titulo')) {
                $table->string('plan_mitigacion_titulo')
                    ->nullable()
                    ->default('No necesario')
                    ->after('fecha_envio_costeos');
            }

            if (!Schema::hasColumn('cotizaciones', 'plan_mitigacion_descripcion')) {
                $table->text('plan_mitigacion_descripcion')
                    ->nullable()
                    ->after('plan_mitigacion_titulo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {

            if (Schema::hasColumn('cotizaciones', 'enviado_por_ventas')) {
                $table->dropForeign(['enviado_por_ventas']);
            }

            if (Schema::hasColumn('cotizaciones', 'enviado_por_costeos')) {
                $table->dropForeign(['enviado_por_costeos']);
            }
        });


        Schema::table('cotizaciones', function (Blueprint $table) {

            $columns = [
                'estado',
                'enviado_a_costeos',
                'enviado_a_ventas',
                'enviado_por_ventas',
                'fecha_envio_ventas',
                'enviado_por_costeos',
                'fecha_envio_costeos',
                'plan_mitigacion_titulo',
                'plan_mitigacion_descripcion'
            ];

            // elimina solo si existen (más robusto)
            foreach ($columns as $column) {
                if (Schema::hasColumn('cotizaciones', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};