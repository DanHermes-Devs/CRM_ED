<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security', function (Blueprint $table) {

            $table->id();
            $table->string('contact_id')->nullable();
            $table->string('fecha_venta')->nullable();
            $table->string('campana')->nullable();
            $table->string('login_ocm')->nullable();
            $table->string('login_intranet')->nullable();
            $table->string('nombre_agente')->nullable();
            $table->unsignedBigInteger('supervisor')->nullable();
            $table->string('codificacion')->nullable();
            $table->string('cuenta_adt')->nullable();
            $table->string('cliente_nombre')->nullable();
            $table->string('cliente_rfc')->nullable();
            $table->string('cliente_telefono')->nullable();
            $table->string('cliente_celular')->nullable();
            $table->string('cliente_correo')->nullable();
            $table->string('cliente_calle')->nullable();
            $table->string('cliente_numero')->nullable();
            $table->string('cliente_cp')->nullable();
            $table->string('client_colonia')->nullable();
            $table->string('cliente_estado')->nullable();
            $table->string('cliente_municipio')->nullable();
            $table->string('cliente_producto')->nullable();
            $table->string('cliente_tipo_producto')->nullable();
            $table->string('cliente_tipo_equipo')->nullable();
            $table->string('contrato_plazo')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('emergencia_nombre_uno')->nullable();
            $table->string('emergencia_tel_uno')->nullable();
            $table->string('emergencia_nombre_dos')->nullable();
            $table->string('emergencia_tel_dos')->nullable();
            $table->string('referencia_visual')->nullable();
            $table->string('estatus_venta')->nullable();
            $table->string('fecha_instalacion')->nullable();
            $table->string('estatus_instalacion')->nullable();
            $table->string('estatus_post_instalacion')->nullable();
            $table->string('usuario_tramitacion')->nullable();
            $table->string('nombre_tramitador')->nullable();
            $table->timestamps();

            $table->foreign('supervisor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security');
    }
}
