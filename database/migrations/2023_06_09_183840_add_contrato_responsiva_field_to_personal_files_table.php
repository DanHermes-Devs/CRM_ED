<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContratoResponsivaFieldToPersonalFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_files', function (Blueprint $table) {
            $table->text('ruta_contrato')->nullable();
            $table->text('ruta_responsiva')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_files', function (Blueprint $table) {
            $table->dropColumn('ruta_contrato');
            $table->dropColumn('ruta_responsiva');
        });
    }
}
