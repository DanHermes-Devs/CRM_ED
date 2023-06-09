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
            $table->string('hora_entrada')->nullable();
            $table->string('hora_salida')->nullable();
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
            $table->dropForeign('users_id_superior_foreign');
            $table->dropForeign('users_id_campana_foreign');

            $table->dropColumn('id_superior');
            $table->dropColumn('id_campana');
            $table->dropColumn('sexo');
            $table->dropColumn('fecha_nacimiento');
            $table->dropColumn('rfc');
            $table->dropColumn('curp');
            $table->dropColumn('estado_civil');
            $table->dropColumn('no_imss');
            $table->dropColumn('cr_infonavit');
            $table->dropColumn('cr_fonacot');
            $table->dropColumn('tipo_sangre');
            $table->dropColumn('ba_nomina');
            $table->dropColumn('cta_clabe');
            $table->dropColumn('alergias');
            $table->dropColumn('padecimientos');
            $table->dropColumn('tel_casa');
            $table->dropColumn('tel_celular');
            $table->dropColumn('persona_emergencia');
            $table->dropColumn('tel_emergencia');
            $table->dropColumn('esquema_laboral');
            $table->dropColumn('proyecto_asignado');
            $table->dropColumn('turno');
            $table->dropColumn('hora_entrada');
            $table->dropColumn('hora_salida');
            $table->dropColumn('fecha_ingreso');
            $table->dropColumn('fecha_baja');
            $table->dropColumn('motivo_baja');
            $table->dropColumn('observaciones');
        });
    }
}
