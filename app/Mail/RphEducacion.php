<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RphEducacion extends Mailable
{
    use Queueable, SerializesModels;

    public $processedData;

    public function __construct($processedData)
    {
        $this->processedData = $processedData;

    }

    public function build()
    {
        $fechaActual = Carbon::now();
        $horaActual = $currentDateTime->format('H:i');
        return $this->markdown('emails.rph_educacion')
                    ->subject('RPH | Universidad Insurgentes: '.$horaActual );
    }
}
