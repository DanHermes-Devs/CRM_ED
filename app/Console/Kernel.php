<?php

namespace App\Console;

use App\Models\CronJobConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\AttendancesCronJob;
use App\Console\Commands\InsertDataToEndpoint;
use App\Console\Commands\sendPaymentReminderSMS;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        InsertDataToEndpoint::class,
        AttendancesCronJob::class,
        sendPaymentReminderSMS::class
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
            $schedule->command("insert:data-to-endpoint {$config->skilldata} {$config->idload_skilldata} {$config->aseguradora} {$config->motor_b} {$config->motor_c}")
                    ->{$config->frequency}()
                    ->withoutOverlapping()
                    ->after(function () use ($config, $insertData) { // Añadimos $insertData aquí
                        $url_ocm = 'http://172.93.111.251:8070/OCMAPI/AddReg';

                        if ($config->skilldata == 'OUT_COBRANZAMotor') {
                            $insertData->sendPaymentReminderSMS();
                            $insertData->sendPaymentPendingRecordsToOCM($url_ocm, $config->skilldata, $config->idload_skilldata);
                        }
                    });
        }

        $schedule->command('sendPaymentReminderSMS')->everyMinute();
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
