<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class InsertDataToEndpoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'insert:data-to-endpoint {skilldata? : Skilldata value}';
    protected $signature = 'insert:data-to-endpoint {skilldata? : Skilldata value} {idload_skilldata? : Idload_skilldata value} {aseguradora? : Aseguradora value} {motor_b? : Motor B value} {motor_c? : Motor C value}';
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
        $url_ocm = 'http://172.93.111.251:8070/OCMAPI/AddReg';

        // Obtén el valor de skilldata de la opción
        $skilldata = $this->argument('skilldata');
        $idload = $this->argument('idload_skilldata');
        $aseguradora = $this->argument('aseguradora');
        $motor_b = $this->argument('motor_b');
        $motor_c = $this->argument('motor_c');

        $this->info("Hola");
        // Obtenemos todas las ventas que correspondan a la aseguradora
        $records = Venta::where('Aseguradora', $aseguradora)->get();

        // Si no hay registros, finaliza la ejecución
        if ($records->isEmpty()) {
            $this->info('No records to send.');
            return;
        }

        // Para cada registro en la colección de registros
        foreach ($records as $record) {
            // Inicializa una variable para almacenar el número de días a contar hacia atrás
            $daysBack = 0;

            // Si el Aseguradora del registro es 'MAPFRE', ajusta daysBack a 22
            if ($record->Aseguradora === 'MAPFRE') {
                $daysBack = 22;
            }
            // Si el Aseguradora del registro es 'QUALITAS' o 'AXA', ajusta daysBack a 15
            elseif ($record->Aseguradora === 'QUALITAS' || $record->Aseguradora === 'AXA') {
                $daysBack = 15;
            }

            // Calcula la fecha a enviar a OCM, sumando los días al día actual
            $dateForOCM = Carbon::today()->addDays($daysBack);

            // Si la fecha calculada es hoy y el registro aún no ha sido enviado a OCM
            if ($dateForOCM->eq($record->FinVigencia) && !$record->OCMSent) {

                // Prepara los datos para enviar a la API
                $data = $this->prepareData($record, $skilldata, $idload);

                $response = $this->sendData($url_ocm, $data);

                // Si la respuesta de la API es exitosa
                if ($response->successful()) {
                    // Asignamos el skilldata a la campana de la venta
                    $record->campana = $skilldata;

                    // Marca el registro como enviado a OCM
                    $record->OCMSent = true;

                    // Guardamos la fecha del ultimo envio
                    $record->ocmdaytosend = Carbon::now();

                    // Guarda el cambio en la base de datos
                    $record->save();
                }
            }
        }

        $this->checkForRecycling($url_ocm);
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
                'apellidoPaterno' => $record->ApePaterno,
                'apellidoMaterno' => $record->ApeMaterno,
                'fechaNacimiento' => $record->fNacimiento,
                'genero' => $record->Genero,
                'correo' => $record->Correo,
                'calle' => $record->Calle,
                'noInterior' => $record->NumInt,
                'noExterior' => $record->NumExt,
                'cp' => $record->CP,
                'municipio' => $record->AlMun,
                'colonia' => $record->Colonia,
                'paquete' => $record->Paquete,
                'formaPago' => $record->fPago,
                'polizaAnterior' => $record->nPoliza,
                'marca' => $record->Marca,
                'submarca' => $record->Submarca,
                'modelo' => $record->Modelo,
                'numeroSerie' => $record->nSerie,
                'numeroMotor' => $record->nMotor,
                'numeroPlaca' => $record->nPlaca,
                'tipoSegmento' => $record->Segmento,
                'legalizado' => $record->Legalizado,
                'paquete' => $record->Paquete,
                'numeroPoliza' => $record->nPoliza,
                'frecuenciaPago' => $record->FrePago,
                'tipoTarjeta' => $record->tTarjeta,
                'numeroTarjeta' => $record->nTarjeta,
                'fechaVencimiento' => $record->fVencimiento,
                'primaCobrada' => $record->PrimaNetaCobrada,
                'primaTotal' => $record->PncTotal,
                'id_lead' => $record->contactId,
                'aseguradoraVigente' => $record->Aseguradora
            ],
        ];
    }

    // Esta función privada llamada sendData toma dos argumentos: una URL y un conjunto de datos.
    private function sendData($url_ocm, $data)
    {
        // Aquí usamos el facade Http de Laravel para enviar una solicitud POST a la URL proporcionada.
        // En el encabezado de la solicitud, se incluyen los campos 'Accept' y 'ApiToken'.
        // 'Accept' le dice al servidor que la respuesta debe ser en formato JSON.
        // 'ApiToken' es un campo personalizado que probablemente se use para la autenticación en el servidor.
        // Los datos que se envían con la solicitud POST son el segundo argumento que se pasó a esta función.
        if($url_ocm == null){
            return null;
        }else{
            return Http::withHeaders([
                'Accept' => 'application/json',
                'ApiToken' => 'Expon1753',
            ])->post($url_ocm, $data);
        }
    }

    // Esta función privada llamada checkForRecycling no toma argumentos. (Validar si es necesario consumir algun endpoint)
    private function checkForRecycling($url_ocm)
    {
        $records = Venta::where('ocmdaytosend', '<', Carbon::now()->subDays(8)->startOfDay())
            ->where('tVenta', 'RENOVACION')
            ->whereNotIn('UGestion', ['RENOVACION', 'PROMESA DE PAGO'])
            ->where('MesBdd', 'JUNIO')
            ->where('AnioBdd', '2023')
            ->orderBy('Aseguradora', 'desc')
            ->get();

        foreach ($records as $record) {
            $skilldata = '';
            $idload = '';
            // Mostramos un mensaje para ver que se está ejecutando el comando
            $this->info($record->campana);
            if ($record->Aseguradora === 'MAPFRE') {
                if ($record->campana === 'RENOVACIONES_B_MOTOR') {
                    $skilldata = 'RENOVACIONES_C_MOTOR';
                    $idload = 135;
                } else {
                    $skilldata = 'RENOVACIONES_B_MOTOR';
                    $idload = 134;
                }
            } elseif ($record->Aseguradora === 'QUALITAS' || $record->Aseguradora === 'AXA') {
                if ($record->campana === 'RENOVACIONES_QUALITAS_B_MOTOR') {
                    $skilldata = 'RENOVACIONES_QUALITAS_C_MOTOR';
                    $idload = 139;
                } else {
                    $skilldata = 'RENOVACIONES_QUALITAS_B_MOTOR';
                    $idload = 138;
                }
            }

            // Actualizar la venta
            $record->campana = $skilldata;
            $record->save();

            // Preparar los datos para enviar a la API
            $data = $this->prepareData($record, $skilldata, $idload);

            // Enviar los datos a la API
            $response = $this->sendData($url_ocm, $data);

            // Verificar si la respuesta de la API es exitosa
            if ($response->successful()) {
                $record->OCMSent = true;
                $record->ocmdaytosend = Carbon::now();
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
            ->where('fecha_proximo_pago', '<=', Carbon::now()->addDays(3))
            ->get()
            ->reject(function ($receipt) {
                return is_null($receipt->fecha_proximo_pago);
            });


        foreach ($receipts as $receipt) {
            $fecha_formateada = Carbon::parse($receipt->fecha_proximo_pago)->format('d-m-Y');

            $smsText = "{$receipt->venta->Aseguradora}: Te recordamos que el pago de tu poliza #{$receipt->venta->nPoliza} se debe realizar el día {$fecha_formateada} si quieres pagarlo hoy llama al 5593445265. Conduce con precaución";
            // <tem:Telefonos>'.$receipt->venta->TelCelular.'</tem:Telefonos>
            $xml_post_string = '
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                <soapenv:Header/>
                <soapenv:Body>
                    <tem:EnviaSMS>
                        <tem:Usuario>'.$this->user.'</tem:Usuario>
                        <tem:Password>'.$this->password.'</tem:Password>
                        
                        <tem:Mensaje>'.$smsText.'</tem:Mensaje>
                        <tem:codigoPais>52</tem:codigoPais>
                        <tem:Telefonos>5518840878</tem:Telefonos>
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
            return $parser;
        }
    }

    public function sendPaymentPendingRecordsToOCM($url_ocm, $skilldata, $idload)
    {
        // Obtenemos todos los recibos pendientes de pago que cumplen las condiciones
        $fechaLimite = Carbon::today();

        $receipts = Receipt::where('estado_pago', 'PENDIENTE')
            ->whereDate('fecha_proximo_pago', $fechaLimite->addDays(3)->toDateString())
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
