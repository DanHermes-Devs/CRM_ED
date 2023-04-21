<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->text('id_project');
            $table->text('id_user');
            $table->unsignedBigInteger('id_supervisor')->nullable();
            $table->string('grupo');
            $table->text('descripcion');
            $table->integer('estatus');
            $table->softDeletes();

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
        Schema::dropIfExists('groups');
    }
}
