<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateCobranzaDateConfirmadaFieldsToEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('education', function (Blueprint $table) {
            //
            $table->string('date_cobranza')->nullable();
            $table->string('date_confirmada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('education', function (Blueprint $table) {
            //
            $table->dropColumn('date_cobranza');
            $table->dropColumn('date_confirmada');
        });
    }
}
