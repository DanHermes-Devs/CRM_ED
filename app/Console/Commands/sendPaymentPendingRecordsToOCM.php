<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sendPaymentPendingRecordsToOCM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendPaymentPendingRecordsToOCM';

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
        $url_ocm = 'http://172.93.111.251:8070/OCMAPI/AddReg';
        $skilldata = 'OUT_COBRANZAMotor';
        $idload = '133';

        $receipts = Receipt::where('estado_pago', 'PENDIENTE')
           ->whereDate('fecha_proximo_pago', Carbon::now()->addDays(3)->startOfDay())
           ->whereNull('ventas.OCMSent')
           ->whereNull('ventas.ocmdaytosend')
           ->leftJoin('ventas', 'receipts.venta_id', '=', 'ventas.id')
           ->get();

        // Recorremos cada uno de los recibos obtenidos
        foreach ($receipts as $receipt) {
            // Verificamos si el registro de venta asociado a este recibo ya ha sido enviado a OCM
            if (!$receipt->venta->OCMSent) {
                // Preparamos los datos para enviar a la API
                $data = $this->prepareData($receipt, $skilldata, $idload);

                // Enviamos los datos a la API
                $response = $this->sendData($url_ocm, $data);

                // Verificamos si la respuesta de la API es exitosa
                if ($response->successful()) {
                    // Marcamos el registro de venta como enviado a OCM
                    $receipt->venta->OCMSent = true;

                    // Guardamos la fecha del ultimo envio
                    $receipt->ocmdaytosend = Carbon::now();

                    $receipt->save();
                }
            }
        }
    }
}
