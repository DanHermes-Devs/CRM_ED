<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->text('contactId')->nullable();
            $table->string('UGestion')->nullable();
            $table->dateTime('Fpreventa')->nullable();
            $table->string('campana')->nullable();
            $table->string('LoginOcm')->nullable();
            $table->string('LoginIntranet')->nullable();
            $table->string('NombreAgente')->nullable();
            $table->string('Supervisor')->nullable();
            $table->string('Codificacion')->nullable();
            $table->string('Nombre')->nullable();
            $table->string('ApePaterno')->nullable();
            $table->string('ApeMaterno')->nullable();
            $table->date('fNacimiento')->nullable();
            $table->integer('Edad')->nullable();
            $table->string('Genero')->nullable();
            $table->string('RFC')->nullable();
            $table->string('Homoclave')->nullable();
            $table->string('CURP')->nullable();
            $table->text('TelFijo')->nullable();
            $table->text('TelCelular')->nullable();
            $table->text('TelEmergencias')->nullable();
            $table->string('Correo')->nullable();
            $table->string('Calle')->nullable();
            $table->text('NumExt')->nullable();
            $table->text('NumInt')->nullable();
            $table->string('Colonia')->nullable();
            $table->string('AlMun')->nullable();
            $table->string('Estado')->nullable();
            $table->text('CP')->nullable();
            $table->string('Marca')->nullable();
            $table->string('SubMarca')->nullable();
            $table->string('Modelo')->nullable();
            $table->string('nSerie')->nullable();
            $table->string('nMotor')->nullable();
            $table->string('nPlacas')->nullable();
            $table->string('Segmento')->nullable();
            $table->string('Legalizado')->nullable();
            $table->string('nCotizacion')->nullable();
            $table->date('FinVigencia')->nullable();
            $table->date('FfVigencia')->nullable();
            $table->date('tPoliza')->nullable();
            $table->string('Paquete')->nullable();
            $table->string('nPoliza')->nullable();
            $table->string('Aseguradora')->nullable();
            $table->string('fPago')->nullable();
            $table->string('FrePago')->nullable();
            $table->string('tTarjeta')->nullable();
            $table->text('nTarjeta')->nullable();
            $table->string('fvencimiento')->nullable();
            $table->text('PncTotal')->nullable();
            $table->string('NombreDeCliente')->nullable();
            $table->string('tVenta')->nullable();
            $table->string('MesBdd')->nullable();
            $table->string('AnioBdd')->nullable();
            $table->integer('noPago')->nullable();
            $table->date('FechaProximoPago')->nullable();
            $table->date('FechaPagoReal')->nullable();
            $table->text('PrimaNetaCobrada')->nullable();
            $table->string('AgenteCob')->nullable();
            $table->string('TipoPago')->nullable();
            $table->string('EstadoDePago')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('ventas');
    }
}
