<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechasToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('OCMSent_motor_c')->nullable();
            $table->string('OCMSent_obranza')->nullable();
            $table->string('ocmdaytosend_cobranza')->nullable();
            $table->string('ocmdaytosend_motor_c')->nullable();
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
            $table->dropColumn('OCMSent_motor_c');
            $table->dropColumn('OCMSent_obranza');
            $table->dropColumn('ocmdaytosend_cobranza');
            $table->dropColumn('ocmdaytosend_motor_c');
        });
    }
}
