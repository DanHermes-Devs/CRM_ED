<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\PolizasEnviadasMailable;

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
        // $url_ocm = 'http://apiproject.test/api/AddReg';
        $url_ocm = 'http://172.93.111.251:8070/OCMAPI/AddReg';

        // Obtén el valor de skilldata de la opción
        $skilldata = $this->argument('skilldata');
        $idload = $this->argument('idload_skilldata');
        $aseguradora = $this->argument('aseguradora');
        $motor_b = $this->argument('motor_b');
        $motor_c = $this->argument('motor_c');

        // Obtenemos todas las ventas que correspondan a la aseguradora
        $records = Venta::where('Aseguradora', $aseguradora)
                        ->where(function($query) {
                            $query->whereNull('UGestion')
                                  ->orWhere('UGestion', '');
                        })
                        ->get();

        // Si no hay registros, finaliza la ejecución
        if ($records->isEmpty()) {
            $this->info('No records to send.');
            return;
        }

        $processedRecordsLog = [];

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
                    if($response['result'] == 'error'){
                        Log::channel('insertDataToEndPoint')->info("Error: " . $response['description']);
                    }else{
                        // Asignamos el skilldata a la campana de la venta
                        $record->campana = $skilldata;

                        // Marca el registro como enviado a OCM
                        $record->OCMSent = true;

                        // Guardamos la fecha del ultimo envio
                        $record->ocmdaytosend = Carbon::now();

                        // Guarda el cambio en la base de datos
                        $record->save();

                        $fecha_hoy = Carbon::now()->format('Y-m-d');

                        $processedRecordsLog[] = [
                            'nPoliza' => $record->nPoliza,
                            'nueva_poliza' => $record->nueva_poliza,
                            'skilldata' => $skilldata,
                            'ocmdaytosend' => $fecha_hoy
                        ];

                        Log::channel('insertDataToEndPoint')->info("Success (ID Lead): " . $response['idlead'] . ' Skilldata: ' . $skilldata . ' Contact ID: ' . $record->contactId . ' Fecha de inserción en OCM: ' . $fecha_hoy);
                    }
                }
            }
        }

        // Si hay registros procesados, envíalos por correo
        if (!empty($processedRecordsLog)) {
            Mail::to(['dreyes@exponentedigital.mx'])
                ->send(new PolizasEnviadasMailable($processedRecordsLog));
        }
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
                'aseguradoraVigente' => $record->Aseguradora,
                'mesbd' => $record->MesBdd,
                'aniobd' => $record->AnioBdd,
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
}
