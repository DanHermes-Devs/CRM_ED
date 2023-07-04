<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelecomunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telecomunications', function (Blueprint $table) {

            $table->id();
            //Datos de gestión
            $table->text('fPreventa')->nullable();
            $table->text('fUltimaGestion')->nullable();
            $table->text('uGestion')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('cuenta')->nullable();
            $table->text('campana')->nullable();
            $table->text('ultimaCampana')->nullable();
            $table->text('loginOcm')->nullable();
            $table->text('loginIntranet')->nullable();
            $table->text('nombreAgente')->nullable();
            $table->text('supervisor')->nullable();
            //Datos del cliente
            $table->text('tipoVenta')->nullable();
            $table->text('nombre')->nullable();
            $table->text('apellidoPaterno')->nullable();
            $table->text('apellidoMaterno')->nullable();
            $table->text('correo')->nullable();
            $table->text('celular')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->text('rfc')->nullable();
            $table->text('claveElector')->nullable();
            $table->text('claveDistribuidor')->nullable();
            $table->text('plazoForzoso')->nullable();
            $table->text('tipoLinea')->nullable();
            $table->text('numeroPortar')->nullable();
            $table->text('tipoSegmento')->nullable();
            $table->text('precio')->nullable();
            $table->text('tipoPaquete')->nullable();
            $table->text('paquete')->nullable();
            //$table->text('adicionales')->nullable();
            $table->text('extensionesTv')->nullable();
            $table->text('extensionesTel')->nullable();
            $table->text('lineaAdicional')->nullable();
            $table->text('extGraba')->nullable();
            $table->text('tipoSegmentoMovil')->nullable();
            $table->text('paqueteMovil')->nullable();
            $table->text('portabilidad')->nullable();
            $table->text('imei')->nullable();
            $table->text('costo')->nullable();
            $table->text('lineaMovilAdicional')->nullable();
            $table->text('pedido')->nullable();
            $table->text('calle')->nullable();
            $table->text('numInterior')->nullable();
            $table->text('municipio')->nullable();
            $table->text('entreCalle1')->nullable();
            $table->text('numExterior')->nullable();
            $table->text('colonia')->nullable();
            $table->text('estado')->nullable();
            $table->text('entreCalle2')->nullable();
            $table->text('giro')->nullable();
            $table->date('fechaNacRepLegal')->nullable();
            $table->text('representante')->nullable();
            $table->text('rfcRepresentante')->nullable();
            $table->text('tipoTarjeta')->nullable();
            $table->text('numTarjeta')->nullable();
            $table->text('vencimiento')->nullable();
            $table->text('cvv')->nullable();
            $table->text('documentos')->nullable();
            $table->string('observaciones')->nullable();
            $table->text('generar')->nullable();
            $table->text('id_lead')->nullable();
            $table->text('telefonoFijo')->nullable();
            $table->text('btnWsp')->nullable();
            $table->text('chkPremium')->nullable();
            $table->text('chkHbo')->nullable();
            $table->text('chkGolden')->nullable();
            $table->text('chkFox')->nullable();
            $table->text('chkInternacional')->nullable();
            $table->text('chkIzziHd')->nullable();
            $table->text('chkNoggin')->nullable();
            $table->text('chkHotPack')->nullable();
            $table->text('chkAfizzionados')->nullable();
            $table->text('chkParamount')->nullable();
            $table->text('chkDog')->nullable();
            $table->text('chkBlim')->nullable();
            $table->text('chkAcorn')->nullable();
            $table->text('chkStarz')->nullable();
            $table->text('chkDisney')->nullable();
            $table->text('chkNetEst')->nullable();
            $table->text('chkNetPre')->nullable();
            $table->text('numeroPortarMovil')->nullable();
            $table->text('cp')->nullable();
            //$table->text('referencia')->nullable();
            $table->text('estadoIzzi')->nullable();
            $table->text('fechaReventa')->nullable();

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
        Schema::dropIfExists('telecomunications');
    }
}
