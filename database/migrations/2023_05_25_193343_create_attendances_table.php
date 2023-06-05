<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->comment('ID del agente');
            $table->string('agente')->comment('Nombre del agente');
            $table->string('fecha_login')->comment('Fecha Login');
            $table->string('hora_login')->comment('Hora Login');
            $table->string('fecha_logout')->comment('Fecha de Logout');
            $table->string('hora_logout')->comment('Hora de Logout');
            $table->string('tipo_asistencia')->comment('Tipo de asistencia');
            $table->string('skilldata')->nullable()->comment('Skill Data');
            $table->string('observaciones')->nullable()->comment('Oservaciones');

            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
