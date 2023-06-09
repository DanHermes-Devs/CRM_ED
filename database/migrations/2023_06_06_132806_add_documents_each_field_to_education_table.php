<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentsEachFieldToEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('education', function (Blueprint $table) {
            $table->string('birth_certifcate')->nullable();
            $table->string('curp_certificate')->nullable();
            $table->string('ine_certifcate')->nullable();
            $table->string('inscripcion_certificate')->nullable();
            $table->string('domicilio_certifcate')->nullable();
            $table->string('estudio_certifcate')->nullable();
            $table->string('cotizacion_certifcate')->nullable();
            $table->string('pago_certifcate')->nullable();
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
            $table->dropColumn('birth_certifcate');
            $table->dropColumn('curp_certificate');
            $table->dropColumn('ine_certifcate');
            $table->dropColumn('inscripcion_certificate');
            $table->dropColumn('domicilio_certifcate');
            $table->dropColumn('estudio_certifcate');
            $table->dropColumn('cotizacion_certifcate');
            $table->dropColumn('pago_certifcate');
        });
    }
}
