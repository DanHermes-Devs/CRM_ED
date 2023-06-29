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
        // $configs = CronJobConfig::all();
        // $insertData = new InsertDataToEndpoint();

        // foreach ($configs as $config) {
        //     $schedule->command("insert:data-to-endpoint {$config->skilldata} {$config->idload_skilldata} {$config->aseguradora} {$config->motor_b} {$config->motor_c}")
        //             ->{$config->frequency}()
        //             ->withoutOverlapping();
        // }

        $schedule->command('insert:data-to-endpoint RENOVACIONES_A_MOTOR 136 MAPFRE')->dailyAt('5:00')->timezone('America/Mexico_City'); // Motor a MAPFRE
        $schedule->command('insert:data-to-endpoint REN_QUALITASMotor 137 QUALITAS')->dailyAt('15:00')->timezone('America/Mexico_City'); // Motor a QUALITAS
        $schedule->command('insert:data-to-endpoint REN_QUALITASMotor 137 AXA')->dailyAt('15:00')->timezone('America/Mexico_City'); // Motor a AXA
        $schedule->command('command:salto_motor_b')->dailyAt('5:00')->timezone('America/Mexico_City'); // Salto a Motor B
        $schedule->command('command:checkForRecycling')->dailyAt('5:00')->timezone('America/Mexico_City'); // Salto a Motor C
        $schedule->command('command:sendPaymentReminderSMS')->dailyAt('9:00')->timezone('America/Mexico_City'); // Envio de SMS
        $schedule->command('command:sendPaymentPendingRecordsToOCM')->dailyAt('5:00')->timezone('America/Mexico_City'); // Envio de datos a cobranza
        $schedule->command('attendance:cronjob')->dailyAt('23:00')->timezone('America/Mexico_City');
        $schedule->command('command:rph')->hourly()->timezone('America/Mexico_City');
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
