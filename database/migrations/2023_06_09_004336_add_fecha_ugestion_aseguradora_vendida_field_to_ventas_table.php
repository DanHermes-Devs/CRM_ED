<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaUgestionAseguradoraVendidaFieldToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->text('fecha_ultima_gestion')->nullable()->comment('Fecha de la ultima gestion');
            $table->text('aseguradora_vendida')->nullable()->comment('Aseguradora vendida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('fecha_ultima_gestion');
            $table->dropColumn('aseguradora_vendida');
        });
    }
}
