<?php

namespace App\Console\Commands;

use App\Mail\PolizasEnviadasMailable;
use App\Mail\VolcadoOCM;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $testContent = [
        //     [
        //         'nPoliza' => '1234567890',
        //         'nueva_poliza' => '0987654321',
        //         'skilldata' => 'FB_UIManual',
        //         'contactId' => '1234567890',
        //         'ocmdaytosend' => '2021-08-31',
        //     ]
        // ];

        // Mail::to(['dreyes@exponentedigital.mx'])->send(new PolizasEnviadasMailable($testContent));
    }
}
