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

        foreach ($configs as $config) {
            // $schedule->command("insert:data-to-endpoint {$config->skilldata}")
            //         ->{$config->frequency}(); // Ajusta la frecuencia según la columna frequency

            $schedule->command("insert:data-to-endpoint {$config->skilldata} {$config->idload}")
                     ->{$config->frequency}();
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
