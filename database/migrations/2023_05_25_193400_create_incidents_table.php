<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->comment('ID del agente');
            $table->string('agente')->comment('Nombre del agente');
            $table->string('login_ocm')->comment('Login OCM');
            $table->string('tipo_incidencia')->comment('Tipo de incidencia');
            $table->string('fecha_desde')->comment('Fecha desde');
            $table->string('fecha_hasta')->comment('Fecha hasta');

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
        Schema::dropIfExists('incidents');
    }
}
