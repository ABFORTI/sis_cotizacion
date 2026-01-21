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
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])
                ->default('pendiente')
                ->after('id');

            // 🔹 Campo: enviada a Costeos (desde Ventas)
            $table->boolean('enviado_a_costeos')
                ->default(false)
                ->after('estado');
            // 🔹 Campo: enviada a Ventas (desde Costeos)
            $table->boolean('enviado_a_ventas')
                ->default(false)
                ->after('enviado_a_costeos');

            $table->foreignId('enviado_por_ventas')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->after('enviado_a_ventas');

            $table->timestamp('fecha_envio_ventas')
                ->nullable()
                ->after('enviado_por_ventas');

            // 🔹 Campo: usuario de Costeos que envía de vuelta a Ventas
            $table->foreignId('enviado_por_costeos')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->after('fecha_envio_ventas');

            // 🔹 Campo: fecha cuando Costeos envía de vuelta a Ventas
            $table->timestamp('fecha_envio_costeos')
                ->nullable()
                ->after('enviado_por_costeos');

            // 🔹 Campos para Plan de Mitigación de Riesgos
            $table->string('plan_mitigacion_titulo')
                ->nullable()
                ->default('No necesario')
                ->after('fecha_envio_costeos');

            $table->text('plan_mitigacion_descripcion')
                ->nullable()
                ->default('En caso de que el nivel de riesgo sea rojo, se generará un plan de mitigación')
                ->after('plan_mitigacion_titulo');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn([
                'estado',
                'enviado_a_costeos',
                'enviado_a_ventas',
                'enviado_por_ventas',
                'fecha_envio_ventas',
                'enviado_por_costeos',
                'fecha_envio_costeos',
                'plan_mitigacion_titulo',
                'plan_mitigacion_descripcion'
            ]);
        });
    }
};
