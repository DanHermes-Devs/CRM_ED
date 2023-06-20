<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
                    // Marcamos el campo ocmdaytosend_cobranza como la fecha actual
                    $receipt->venta->ocmdaytosend_cobranza = Carbon::now()->toDateTimeString();

                    // Marcamos como true el campo OCMSent_obranza del registro de venta asociado a este recibo
                    $receipt->venta->OCMSent_obranza = true;

                    // Guardamos los cambios
                    $receipt->venta->save();

                    $fecha_hoy = Carbon::now()->format('Y-m-d');

                    Log::channel('sendPaymentPendingRecordsToOCM')->info("Success (ID Lead): " . $response['idlead'] . ' Skilldata: ' . $skilldata . ' Contact ID: ' . $receipt->venta->contactId . ' Fecha de inserción en OCM: ' . $fecha_hoy);
                }
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
                'noPoliza' => $record->nPoliza,
                'marca' => $record->Marca,
                'submarca' => $record->SubMarca,
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
                'aseguradoraVendida' => $record->aseguradora_vendida
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
