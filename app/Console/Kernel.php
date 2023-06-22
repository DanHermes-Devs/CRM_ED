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

        $schedule->command('insert:data-to-endpoint RENOVACIONES_A_MOTOR 136 MAPFRE')->dailyAt('5:59')->timezone('America/Mexico_City');
        $schedule->command('insert:data-to-endpoint REN_QUALITASMotor 137 QUALITAS')->dailyAt('5:59')->timezone('America/Mexico_City');
        $schedule->command('insert:data-to-endpoint REN_QUALITASMotor 137 AXA')->dailyAt('5:59')->timezone('America/Mexico_City');
        $schedule->command('command:sendPaymentReminderSMS')->dailyAt('5:59')->timezone('America/Mexico_City');
        $schedule->command('command:sendPaymentPendingRecordsToOCM')->dailyAt('5:59')->timezone('America/Mexico_City');
        $schedule->command('command:checkForRecycling')->dailyAt('5:59')->timezone('America/Mexico_City');
        $schedule->command('attendance:cronjob')->dailyAt('5:59');
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
