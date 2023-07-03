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
            $table->timestamps('fPreventa')->nullable();
            $table->timestamps('fUltimaGestion')->nullable();
            $table->string('uGestion')->nullable();
            $table->unsignedBigInteger('contactId')->nullable();
            $table->unsignedBigInteger('cuenta')->nullable();
            $table->string('campana')->nullable();
            $table->string('loginOcm')->nullable();
            $table->string('loginIntranet')->nullable();
            $table->string('mombreAgente')->nullable();
            $table->string('supervisor')->nullable();
            //Datos del cliente
            $table->string('tipoVenta')->nullable();
            $table->string('nombre')->nullable();
            $table->string('apellidoPaterno')->nullable();
            $table->string('apellidoMaterno')->nullable();
            $table->string('correo')->nullable();
            $table->string('celular')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->string('rfc')->nullable();
            $table->string('claveElector')->nullable();
            $table->string('claveDistribuidor')->nullable();
            $table->string('plazoForzoso')->nullable();
            $table->string('tipoLinea')->nullable();
            $table->string('numeroPortar')->nullable();
            $table->string('tipoSegmento')->nullable();
            $table->string('precio')->nullable();
            $table->string('tipoPaquete')->nullable();
            $table->string('paquete')->nullable();
            $table->string('adicionales')->nullable();
            $table->string('extensionesTv')->nullable();
            $table->string('extensionesTel')->nullable();
            $table->string('lineaAdicional')->nullable();
            $table->string('extGraba')->nullable();
            $table->string('tipoSegmentoMovil')->nullable();
            $table->string('paqueteMovil')->nullable();
            $table->string('portabilidad')->nullable();
            $table->string('imei')->nullable();
            $table->string('costo')->nullable();
            $table->string('lineaMovilAdicional')->nullable();
            $table->string('pedido')->nullable();
            $table->string('calle')->nullable();
            $table->string('numInterior')->nullable();
            $table->string('municipio')->nullable();
            $table->string('entreCalle1')->nullable();
            $table->string('numExterior')->nullable();
            $table->string('colonia')->nullable();
            $table->string('estado')->nullable();
            $table->string('entreCalle2')->nullable();
            $table->string('giro')->nullable();
            $table->date('fechaNacRepLegal')->nullable();
            $table->string('representante')->nullable();
            $table->string('rfcRepresentante')->nullable();
            $table->string('tipoTarjeta')->nullable();
            $table->string('numTarjeta')->nullable();
            $table->string('vencimiento')->nullable();
            $table->string('cvv')->nullable();
            $table->string('documentos')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('generar')->nullable();
            $table->string('id_lead')->nullable();
            $table->string('telefonoFijo')->nullable();
            $table->string('btnWsp')->nullable();
            $table->string('chkPremium')->nullable();
            $table->string('chkHbo')->nullable();
            $table->string('chkGolden')->nullable();
            $table->string('chkFox')->nullable();
            $table->string('chkInternacional')->nullable();
            $table->string('chkIzziHd')->nullable();
            $table->string('chkNoggin')->nullable();
            $table->string('chkHotPack')->nullable();
            $table->string('chkAfizzionados')->nullable();
            $table->string('chkParamount')->nullable();
            $table->string('chkDog')->nullable();
            $table->string('chkBlim')->nullable();
            $table->string('chkAcorn')->nullable();
            $table->string('chkStarz')->nullable();
            $table->string('chkDisney')->nullable();
            $table->string('chkNetEst')->nullable();
            $table->string('chkNetPre')->nullable();
            $table->string('numeroPortarMovil')->nullable();
            $table->string('cp')->nullable();
            $table->string('referencia')->nullable();
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
