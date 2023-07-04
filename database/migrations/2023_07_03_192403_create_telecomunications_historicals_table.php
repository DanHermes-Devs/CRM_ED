<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelecomunicationsHistoricalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telecomunications_historicals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idVenta')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('loginOcm')->nullable();
            $table->string('loginIntranet')->nullable();
            $table->string('estado')->nullable();
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('telecomunications_historicals');
    }
}
