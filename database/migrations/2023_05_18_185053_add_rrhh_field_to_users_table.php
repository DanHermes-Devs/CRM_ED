<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRrhhFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_superior')->nullable();
            $table->unsignedBigInteger('id_campana')->nullable();
            $table->string('sexo')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('no_imss')->nullable();
            $table->string('cr_infonavit')->nullable();
            $table->string('cr_fonacot')->nullable();
            $table->string('tipo_sangre')->nullable();
            $table->string('ba_nomina')->nullable();
            $table->string('cta_clabe')->nullable();
            $table->string('alergias')->nullable();
            $table->string('padecimientos')->nullable();
            $table->string('tel_casa')->nullable();
            $table->string('tel_celular')->nullable();
            $table->string('persona_emergencia')->nullable();
            $table->string('tel_emergencia')->nullable();
            $table->string('esquema_laboral')->nullable();
            $table->string('proyecto_asignado')->nullable();
            $table->string('turno')->nullable();
            $table->string('horario')->nullable();
            $table->string('fecha_ingreso')->nullable();
            $table->string('fecha_baja')->nullable();
            $table->string('motivo_baja')->nullable();
            $table->string('observaciones')->nullable();

            $table->foreign('id_superior')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_campana')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
