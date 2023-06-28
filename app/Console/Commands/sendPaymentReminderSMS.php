<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class sendPaymentReminderSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendPaymentReminderSMS';

    protected $url = "http://b2c.marcatel.com.mx/MarcatelSMSWCF/ServicioInsertarSMS.svc/mex/";
    protected $user = "RENOVACIONES_QUALITAS";
    protected $password = "#T42AC30LzVu";

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
        // Buscar en la base de datos los recibos cuyo estado de pago sea "PENDIENTE",
        // que fueron creados hace 3 días o menos, y cuyo campo 'fecha_proximo_pago' no sea nulo.
        $receipts = Receipt::where('estado_pago', 'PENDIENTE')
            ->with('venta')
            ->whereDate('fecha_proximo_pago', Carbon::now()->addDays(3)->startOfDay())
            ->get();

        foreach ($receipts as $receipt) {
            $fecha_formateada = Carbon::parse($receipt->fecha_proximo_pago)->format('d-m-Y');

            $smsText = "{$receipt->venta->Aseguradora}: Te recordamos que el pago de tu poliza #{$receipt->venta->nPoliza} se debe realizar el día {$fecha_formateada} si quieres pagarlo hoy llama al 5593445265. Conduce con precaución";

            $fecha_hoy = Carbon::now()->format('Y-m-d');
            Log::channel('sendPaymentReminderSMS')->info($smsText . ' Fecha de inserción en OCM: ' . $fecha_hoy);

            // <tem:Telefonos>'.$receipt->venta->TelCelular.'</tem:Telefonos>
            $xml_post_string = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
            <soapenv:Header/>
            <soapenv:Body>
                <tem:EnviaSMS>
                    <tem:Usuario>'.$this->user.'</tem:Usuario>
                    <tem:Password>'.$this->password.'</tem:Password>
                    <tem:Telefonos>'.$receipt->venta->TelCelular.'</tem:Telefonos>
                    <tem:Mensaje>'.$smsText.'</tem:Mensaje>
                    <tem:codigoPais>52</tem:codigoPais>
                    <tem:SMSDosVias>0</tem:SMSDosVias>
                    <tem:Unicode>0</tem:Unicode>
                    <tem:MensajeLargo>1</tem:MensajeLargo>
                    <tem:ModoNotificacion>0</tem:ModoNotificacion>
                    <tem:Prioridad>1</tem:Prioridad>
                    <tem:NotificarRespuestas>0</tem:NotificarRespuestas>
                    <tem:FrecuenciaMinutos>0</tem:FrecuenciaMinutos>
                    <tem:AntiSpam>0</tem:AntiSpam>
                    <tem:NoTransaccion>0</tem:NoTransaccion>
                    <tem:ValidaListaNegra>0</tem:ValidaListaNegra>
                    <tem:ValidaZonaHoraria>0</tem:ValidaZonaHoraria>
                </tem:EnviaSMS>
            </soapenv:Body>
            </soapenv:Envelope>';

            $headers = [
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: http://tempuri.org/IInsertaSMS/EnviaSMS",
                "Content-length: ".strlen($xml_post_string),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->user.":".$this->password);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $response1 = str_replace("<soap:Body>", "", $response);
            $response2 = str_replace("</soap:Body>", "", $response1);

            // Parseamos la respuesta
            $parser = simplexml_load_string($response2);
        }
    }
}
