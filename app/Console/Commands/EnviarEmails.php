<?php

namespace App\Console\Commands;

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
        $testContent = "Este es un correo de prueba enviado desde Laravel.";

        Mail::raw($testContent, function ($message) {
            $message->from('no-reply@exponentedigital.mx', 'Exponente Digital - Incremental Sales');
            $message->to(['dreyes@exponentedigital.mx', 'danhermes2019@outlook.com']);
            $message->subject('Correo de Prueba');
        });
    }
}
