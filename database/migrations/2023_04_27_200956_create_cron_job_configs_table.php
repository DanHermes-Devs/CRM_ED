<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_job_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name_cronJob');
            $table->string('skilldata');
            $table->string('aseguradora');
            $table->string('idload_skilldata');
            $table->string('frequency');
            $table->string('motor_b');
            $table->string('idload_motor_b');
            $table->string('motor_c');
            $table->string('idload_motor_c');
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
        Schema::dropIfExists('cron_job_configs');
    }
}
