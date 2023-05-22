<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->unsignedBigInteger('id_supervisor')->nullable();
            $table->string('perfil')->nullable();
            $table->string('status')->nullable();
            $table->text('ruta_ine')->nullable();
            $table->text('ruta_acta_nacimiento')->nullable();
            $table->text('ruta_curp')->nullable();
            $table->text('ruta_constancia_fiscal')->nullable();
            $table->text('ruta_nss')->nullable();
            $table->text('ruta_comp_estudios')->nullable();
            $table->text('ruta_comp_domicilio')->nullable();
            $table->text('ruta_edo_bancario')->nullable();
            $table->text('ruta_aviso_ret_infonavit')->nullable();
            $table->text('ruta_aviso_ret_fonacot')->nullable();

            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_proyecto')->references('id')->on('projects');
            $table->foreign('id_supervisor')->references('id')->on('users');
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
        Schema::dropIfExists('personal_files');
    }
}
