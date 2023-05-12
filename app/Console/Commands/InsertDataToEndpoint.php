<?php

namespace App\Console\Commands;

use App\Models\Venta;
use Carbon\Carbon;
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
    protected $signature = 'insert:data-to-endpoint {skilldata? : Skilldata value}';
    protected $description = 'Insert data to the endpoint using a POST request';

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
        $url = 'http://apiproject.test/api/AddReg';
        // $url = 'http://172.93.111.251:8070/OCMAPI/AddReg';

        // Obtén el valor de skilldata de la opción
        $skilldata = $this->argument('skilldata');

        // Registra el valor de skilldata
        Log::info('Valor de skilldata:', ['skilldata' => $skilldata]);

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
                $data = $this->prepareData($record, $skilldata);

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

        // Revisa las ventas para reciclaje
        $this->checkForRecycling();

    }

    // Esta función privada llamada prepareData toma dos argumentos: un registro y un valor de skilldata
    private function prepareData($record, $skilldata)
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

            // 'idload' se establece como 95, esto parece un valor constante y puede necesitar ser ajustado dependiendo de tu lógica de negocio
            'idload' => 95,

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
    private function checkForRecycling()
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
                if ($record->campana === 'B') {
                    $record->campana = 'C';
                } else {
                    $record->campana = 'B';
                }
                // Finalmente, se guarda el registro con los cambios realizados.
                $record->save();
            }
        }
    }

}
