<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use Carbon\Carbon;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class checkForRecycling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkForRecycling';
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
        $records = Venta::where('ocmdaytosend', '=', Carbon::now()->subDays(7)->startOfDay()->format('Y-m-d'))
            ->where('tVenta', 'RENOVACION')
            ->whereNotIn('UGestion', ['RENOVACION', 'PROMESA DE PAGO'])
            ->take(2)
            ->get();


        foreach ($records as $record) {
            $skilldata = '';
            $idload = '';

            if ($record->Aseguradora === 'MAPFRE') {
                if ($record->campana === 'RENOVACIONES_B_MOTOR') {
                    $skilldata = 'RENOVACIONES_C_MOTOR';
                    $idload = 144;
                } elseif ($record->campana === 'RENOVACIONES_C_MOTOR') {
                    // Si ya pertenece a MOTOR C, no hagas nada o realiza alguna otra acción
                } else {
                    $skilldata = 'RENOVACIONES_B_MOTOR';
                    $idload = 134;
                }
            } elseif ($record->Aseguradora === 'QUALITAS' || $record->Aseguradora === 'AXA') {
                if ($record->campana === 'RENOVACIONES_QUALITAS_B_MOTOR') {
                    $skilldata = 'RENOVACIONES_QUALITAS_C_MOTOR';
                    $idload = 139;
                } elseif ($record->campana === 'RENOVACIONES_QUALITAS_C_MOTOR') {
                    // Si ya pertenece a MOTOR C, no hagas nada o realiza alguna otra acción
                } else {
                    $skilldata = 'RENOVACIONES_QUALITAS_B_MOTOR';
                    $idload = 138;
                }
            }

            $record->campana = $skilldata;
            $record->save();

            $data = $this->prepareData($record, $skilldata, $idload);
            $response = $this->sendData($url_ocm, $data);

            if ($response->successful()) {
                if ($skilldata === 'RENOVACIONES_B_MOTOR' || $skilldata === 'RENOVACIONES_QUALITAS_B_MOTOR') {
                    $record->OCMSent = true;
                    $record->ocmdaytosend = Carbon::now();
                }elseif($skilldata === 'RENOVACIONES_C_MOTOR' || $skilldata === 'RENOVACIONES_QUALITAS_C_MOTOR'){
                    $record->OCMSent_motor_c = true;
                    $record->ocmdaytosend_motor_c = Carbon::now();
                }

                $record->save();
            }
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
}
