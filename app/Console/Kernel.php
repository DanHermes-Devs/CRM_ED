<?php

namespace App\Console;

use App\Models\CronJobConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\InsertDataToEndpoint;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        InsertDataToEndpoint::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $configs = CronJobConfig::all();
        $insertData = new InsertDataToEndpoint();

        foreach ($configs as $config) {
            $schedule->command("insert:data-to-endpoint {$config->skilldata} {$config->idload} {$config->motor_a} {$config->motor_b} {$config->motor_c}")
                    ->{$config->frequency}()
                    ->after(function () use ($config, $insertData) { // Añadimos $insertData aquí
                        if ($config->skilldata == 'OUT_COBRANZAMotor') {
                            $insertData->sendPaymentReminderSMS();
                            $insertData->sendPaymentPendingRecordsToOCM($config->url, $config->skilldata, $config->idload, $config->motor_a, $config->motor_b, $config->motor_c);
                        }
                    });
        }
    }



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
