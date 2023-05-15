<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Venta;
use GuzzleHttp\Client;
use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use SoapClient;

class InsertDataToEndpoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'insert:data-to-endpoint {skilldata? : Skilldata value}';
    protected $signature = 'insert:data-to-endpoint {skilldata? : Skilldata value} {idload? : Idload value} {motor_a? : Motor A value} {motor_b? : Motor B value} {motor_c? : Motor C value}';
    protected $description = 'Insert data to the endpoint using a POST request';

    protected $url = "http://b2c.marcatel.com.mx/MarcatelSMSWCF/ServicioInsertarSMS.svc/mex/";
    protected $user = "RENOVACIONES_QUALITAS";
    protected $password = "#T42AC30LzVu";

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
        // $url = 'http://apiproject.test/api/AddReg';
        $url = 'http://172.93.111.251:8070/OCMAPI/AddReg';

        // Obtén el valor de skilldata de la opción
        $skilldata = $this->argument('skilldata');
        $idload = $this->argument('idload');
        $motor_a = $this->argument('motor_a');
        $motor_b = $this->argument('motor_b');
        $motor_c = $this->argument('motor_c');

        // Obtenemos todas las ventas
        $records = Venta::all();

        // Si no hay registros, finaliza la ejecución
        if ($records->isEmpty()) {
            $this->info('No records to send.');
            return;
        }

        // Para cada registro en la colección de registros
        foreach ($records as $record) {
            // Inicializa una variable para almacenar el número de días a contar hacia atrás
            $daysBack = 0;

            // Si el proveedor del registro es 'MAPFRE', ajusta daysBack a 22
            if ($record->Proveedor === 'MAPFRE') {
                $daysBack = 22;
            }
            // Si el proveedor del registro es 'QUALITAS' o 'AXA', ajusta daysBack a 15
            elseif ($record->Proveedor === 'QUALITAS' || $record->Proveedor === 'AXA') {
                $daysBack = 15;
            }

            // Calcula la fecha para enviar a OCM, restando daysBack del FinVigencia del registro
            $dateForOCM = $record->FinVigencia->subDays($daysBack);

            // Si la fecha calculada es hoy y el registro aún no ha sido enviado a OCM
            if ($dateForOCM->isToday() && !$record->OCMSent) {
                // Prepara los datos para enviar a la API
                $data = $this->prepareData($record, $skilldata, $idload);

                // Envía los datos a la API
                $response = $this->sendData($url, $data);

                // Si la respuesta de la API es exitosa
                if ($response->successful()) {
                    // Marca el registro como enviado a OCM
                    $record->OCMSent = true;

                    // Guarda el cambio en la base de datos
                    $record->save();
                }
            }
        }

        // // Revisa las ventas para reciclaje
        $this->checkForRecycling();

    }

    // Esta función privada llamada prepareData toma dos argumentos: un registro y un valor de skilldata
    private function prepareData($record, $skilldata, $idload)
    {
        // Retorna un array asociativo con la siguiente estructura:
        return [
            // 'phone1' toma el valor de la propiedad TelFijo del registro
            'phone1' => $record->TelFijo,

            // 'phone2' toma el valor de la propiedad TelCelular del registro
            'phone2' => $record->TelCelular,

            // 'phone3', 'phone4' y 'phone5' se dejan vacíos por el momento
            'phone3' => '',
            'phone4' => '',
            'phone5' => '',

            // 'skilldata' toma el valor del argumento skilldata que se pasó a la función
            'skilldata' => $skilldata,

            // 'idload' se establece como idload, esto parece un valor constante y puede necesitar ser ajustado dependiendo de tu lógica de negocio
            'idload' => $idload,

            // 'datenextcall' toma la fecha y hora actual, formateada en el formato ISO 8601
            'datenextcall' => Carbon::now()->toIso8601String(),

            // 'extendata' es un array que contiene la propiedad 'nombre', que toma el valor de la propiedad Nombre del registro
            'extendata' => [
                'nombre' => $record->Nombre,
            ],
        ];
    }


    // Esta función privada llamada sendData toma dos argumentos: una URL y un conjunto de datos.
    private function sendData($url, $data)
    {
        // Aquí usamos el facade Http de Laravel para enviar una solicitud POST a la URL proporcionada.
        // En el encabezado de la solicitud, se incluyen los campos 'Accept' y 'ApiToken'.
        // 'Accept' le dice al servidor que la respuesta debe ser en formato JSON.
        // 'ApiToken' es un campo personalizado que probablemente se use para la autenticación en el servidor.
        // Los datos que se envían con la solicitud POST son el segundo argumento que se pasó a esta función.
        return Http::withHeaders([
            'Accept' => 'application/json',
            'ApiToken' => 'Expon1753',
        ])->post($url, $data);
    }

    // Esta función privada llamada checkForRecycling no toma argumentos. (Validar si es necesario consumir algun endpoint)
    private function checkForRecycling($motor_a, $motor_b, $motor_c)
    {
        // Primero, se obtienen todos los registros de la tabla 'Venta' donde 'OCMSent' es verdadero
        // y 'FinVigencia' es menor que la fecha actual menos 60 días.
        $records = Venta::where('OCMSent', true)
            ->where('FinVigencia', '<', Carbon::now()->subDays(60))
            ->get();

        // Luego, para cada uno de estos registros...
        foreach ($records as $record) {
            // Si 'UltimaGestion' no es igual a 'PROMESA DE PAGO' y 'UltimaGestion' no es igual a 'RENOVACIÓN'...
            if ($record->UltimaGestion !== 'PROMESA DE PAGO' && $record->UltimaGestion !== 'RENOVACIÓN') {
                // Si 'Motor' es igual a 'B', se cambia a 'C'. De lo contrario, se cambia a 'B'.
                if ($record->campana === $motor_b) {
                    $record->campana = $motor_c;
                } else {
                    $record->campana = $motor_b;
                }
                // Finalmente, se guarda el registro con los cambios realizados.
                $record->save();
            }
        }
    }

    public function sendPaymentReminderSMS()
    {
        // Buscar en la base de datos los recibos cuyo estado de pago sea "PENDIENTE",
        // que fueron creados hace 3 días o menos, y cuyo campo 'fecha_proximo_pago' no sea nulo.
        $receipts = Receipt::where('estado_pago', 'PENDIENTE')
            ->with('venta')
            ->where('created_at', '<=', Carbon::now()->addDays(3))
            ->get()
            ->reject(function ($receipt) {
                return is_null($receipt->fecha_proximo_pago);
            });


        foreach ($receipts as $receipt) {
            $fecha_formateada = Carbon::parse($receipt->fecha_proximo_pago)->format('d-m-Y');

            $smsText = "{$receipt->venta->Aseguradora}: Te recordamos que el pago de tu poliza #{$receipt->venta->nPoliza} se debe realizar el día {$fecha_formateada} si quieres pagarlo hoy llama al 5593445265. Conduce con precaución";

            $xml_post_string = '
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                <soapenv:Header/>
                <soapenv:Body>
                    <tem:EnviaSMS>
                        <tem:Usuario>'.$this->user.'</tem:Usuario>
                        <tem:Password>'.$this->password.'</tem:Password>
                        <tem:Telefonos>5518840878</tem:Telefonos>
                        <tem:Mensaje>'.$smsText.'</tem:Mensaje>
                        <tem:codigoPais>52</tem:codigoPais>
                        <tem:SMSDosVias>0</tem:SMSDosVias>
                        <tem:Unicode>0</tem:Unicode>
                        <tem:MensajeLargo>1</tem:MensajeLargo>
                        <tem:ModoNotificacion>0</tem:ModoNotificacion>
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
            return $parser;
        }
    }

    public function sendPaymentPendingRecordsToOCM($url, $skilldata, $idload)
    {
        $receipts = Receipt::where('estado_pago', 'PENDIENTE')
            ->where('fecha_proximo_pago', '<=', Carbon::now()->addDays(3))
            ->get();

        foreach ($receipts as $receipt) {
            if (!$receipt->OCMSent) {
                $data = $this->prepareData($receipt, $skilldata, $idload);

                $response = $this->sendData($url, $data);

                if ($response->successful()) {
                    $receipt->OCMSent = true;
                    $receipt->save();
                }
            }
        }
    }
}
