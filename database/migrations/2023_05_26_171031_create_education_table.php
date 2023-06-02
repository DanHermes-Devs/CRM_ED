<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->string('usuario_ocm');
            $table->string('usuario_crm');
            $table->string('nombre_universidad')->nullable();
            $table->string('fp_venta')->nullable();
            $table->string('campana')->nullable();
            $table->string('agent_OCM')->nullable();
            $table->unsignedBigInteger('agent_intra');
            $table->unsignedBigInteger('supervisor');
            $table->string('codification')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_landline')->nullable();
            $table->string('client_celphone')->nullable();
            $table->string('client_modality')->nullable();
            $table->string('client_program')->nullable();
            $table->string('client_specialty')->nullable();
            $table->string('client_street')->nullable();
            $table->string('client_number')->nullable();
            $table->string('client_delegation')->nullable();
            $table->string('client_state')->nullable();
            $table->string('client_sex')->nullable();
            $table->string('client_birth')->nullable();
            $table->string('status')->nullable();
            $table->string('documents_portal')->nullable();
            $table->string('account_UIN')->nullable();
            $table->timestamps();

            $table->foreign('agent_intra')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('education');
    }
}
