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

        // Enviar cada registro al endpoint
        foreach ($records as $record) {
            $data = [
                'phone1' => $record->TelFijo,
                'phone2' => $record->TelCelular,
                'phone3' => '',
                'phone4' => '',
                'phone5' => '',
                'skilldata' => $skilldata,
                // 'idload' => $record->contactId,
                'idload' => 95,
                'datenextcall' => Carbon::now()->toIso8601String(),
                'extendata' => [
                    'nombre' => $record->Nombre,
                ],
            ];

            // Mandamos el apitoken en el header
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'ApiToken' => 'Expon1753',
            ])->post($url, $data);

            if ($response->successful()) {
                // Si la respuesta es exitosa, mostramos el message que nos manda el endpoint
                $this->info($response->body());
            } else {
                // Mostramos el mensaje de error que nos manda el endpoint
                $this->error($response->body());
            }
        }
    }
}
