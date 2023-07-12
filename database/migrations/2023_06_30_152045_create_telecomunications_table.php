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
            $table->text('celular')->nullable()->comment('Celular del cliente');
            $table->text('telefonoFijo')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->text('rfc')->nullable();
            $table->text('tipoIdentificacion')->nullable();
            $table->text('claveIdentificacion')->nullable();
            // Detalles de Ubicacion
            $table->text('calle')->nullable();
            $table->text('numInterior')->nullable();
            $table->text('municipio')->nullable();
            $table->text('entreCalle1')->nullable();
            $table->text('numExterior')->nullable();
            $table->text('colonia')->nullable();
            $table->text('estado')->nullable();
            $table->text('entreCalle2')->nullable();
            $table->text('cp')->nullable();
            $table->text('referencia')->nullable();
            $table->text('idSipre')->nullable();
            $table->text('dirCompleta')->nullable();
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            // Detalles de Paquete
            $table->text('plazoForzoso')->nullable();
            $table->text('tipoLinea')->nullable();
            $table->text('tipoSegmento')->nullable();
            $table->text('numeroPortar')->nullable();
            $table->text('tipoPaquete')->nullable();
            $table->text('paquete')->nullable();
            $table->text('precio')->nullable();
            // Detalles de Adicionales
            $table->text('chkHdPlus')->nullable();
            $table->text('chkHbo')->nullable();
            $table->text('chkInternacional')->nullable();
            $table->text('chkComboPlus')->nullable();
            $table->text('chkHotPack')->nullable();
            $table->text('chkAfizzionados')->nullable();
            $table->text('chkParamount')->nullable();
            $table->text('chkUniPlus')->nullable();
            $table->text('ckhStarPlus')->nullable();
            $table->text('chkDisney')->nullable();
            $table->text('chkVixPlus')->nullable();
            $table->text('chkGolden')->nullable();
            $table->text('chkNetBase')->nullable();
            $table->text('ckhNetEst')->nullable();
            $table->text('chkNetPrem')->nullable();
            $table->text('extensionesTv')->nullable();
            $table->text('extensionesTel')->nullable();
            $table->text('lineaAdicional')->nullable();
            // Detalles de Izzi Movil
            $table->text('tipoSegmentoMovil')->nullable();
            $table->text('paqueteMovil')->nullable();
            $table->text('imei')->nullable();
            $table->text('contratoAdicionalMovil')->nullable();
            $table->text('pedido')->nullable();
            $table->text('costo')->nullable();
            $table->text('portabilidad')->nullable();
            $table->text('numeroPortarMovil')->nullable();
            // Detalles de Segmento
            $table->text('representante')->nullable();
            $table->text('rfcRepresentante')->nullable();
            $table->date('fechaNacRepLegal')->nullable();
            // Detalles de Pago
            $table->text('formaPago')->nullable();
            $table->text('tipoTarjeta')->nullable();
            $table->text('numTarjeta')->nullable();
            $table->text('vencimiento')->nullable();
            $table->text('cvv')->nullable();
            // Detalles de Observaciones
            $table->string('observaciones')->nullable();
            $table->text('documentos')->nullable();
            $table->text('datosGenerados')->nullable();
            $table->text('btnWsp')->nullable();
            $table->text('estadoIzzi')->nullable();
            $table->text('fechaReventa')->nullable();
            $table->text('estadoSiebel')->nullable();
            $table->text('estadoMovil')->nullable();
            $table->text('orden')->nullable();
            $table->text('estadoOrden')->nullable();
            $table->text('tramitador')->nullable();
            $table->text('confirmador')->nullable();

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
