<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id');
            $table->integer('num_pago');
            $table->string('fre_pago');
            $table->date('fecha_proximo_pago')->nullable();
            $table->date('fecha_pago_real')->nullable();
            $table->decimal('prima_neta_cobrada', 10, 2)->nullable();
            $table->unsignedBigInteger('agente_cob_id')->nullable();
            $table->string('tipo_pago');
            $table->string('estado_pago');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('venta_id')->references('id')->on('ventas');
            $table->foreign('agente_cob_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
