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
            $table->text('lineamiento_1')->nullable()->after('lugar_entrega');
            $table->text('lineamiento_2')->nullable()->after('lineamiento_1');
            $table->text('lineamiento_3')->nullable()->after('lineamiento_2');
            $table->text('lineamiento_4')->nullable()->after('lineamiento_3');
            $table->text('lineamiento_5')->nullable()->after('lineamiento_4');
            $table->text('lineamiento_6')->nullable()->after('lineamiento_5');
            $table->text('lineamiento_7')->nullable()->after('lineamiento_6');
            $table->text('lineamiento_8')->nullable()->after('lineamiento_7');
            $table->text('lineamiento_9')->nullable()->after('lineamiento_8');
            $table->text('lineamiento_10')->nullable()->after('lineamiento_9');
            $table->string('tiempo_herramentales')->nullable()->after('lineamiento_10');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn([
                'lineamiento_1',
                'lineamiento_2',
                'lineamiento_3',
                'lineamiento_4',
                'lineamiento_5',
                'lineamiento_6',
                'lineamiento_7',
                'lineamiento_8',
                'lineamiento_9',
                'lineamiento_10',
                'tiempo_herramentales',
            ]);
        });
    }
};
