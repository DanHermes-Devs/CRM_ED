<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PolizasEnviadasMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $processedRecordsLog;

    public function __construct($processedRecordsLog)
    {
        $this->processedRecordsLog = $processedRecordsLog;
    }

    public function build()
    {
        return $this->markdown('emails.polizas_enviadas')
                    ->subject('Polizas Enviadas');
    }
}
