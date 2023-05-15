<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\Receipt;
use Carbon\Carbon;

class CreateReceiptsForSpecificSales extends Command
{
    protected $signature = 'receipts:create-for-specific-sales';
    protected $description = 'Create receipts for 98 specific sales';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Coloca aquí los IDs de los 98 registros específicos
        $specificSalesIds = [
            '4012100133931',
            '4011900163381',
            '4011800158522',
            '4011900192035',
            '4012000101163',
            '4012000105125',
            '4311900013769',
            '4311900013638',
            '4012100151549',
            '4012200122548',
            '4012200122498',
        ];

        foreach ($specificSalesIds as $saleId) {
            $venta = Venta::where('contactId', $saleId)->first(); 

            if (!$venta) {
                $this->error("Venta con ContactID {$saleId} no encontrada.");
                continue;
            }

            // Verificamos si ya existen recibos de pago para la venta
            $recibos = Receipt::where('venta_id', $venta->id)->count();

            // Si no hay recibos existentes, crea nuevos recibos
            if ($recibos === 0) {
                // Creamos un arreglo con las frecuencias de pago
                $frecuenciaPagos = [
                    'ANUAL' => 1,
                    'SEMESTRAL' => 2,
                    'TRIMESTRAL' => 4,
                    'CUATRIMESTRAL' => 3,
                    'MENSUAL' => 12
                ];

                // Convertimos frecuenciaPago en Mayusculas
                $frecuenciaPago = strtoupper($venta->FrePago);

                // Verificar si la frecuencia de pago es válida
                if (!array_key_exists($frecuenciaPago, $frecuenciaPagos)) {
                    $this->error("Frecuencia de pago '{$frecuenciaPago}' inválida para la venta con ContactID {$saleId}");
                    continue;
                }
                
                $numRecibos = $frecuenciaPagos[$frecuenciaPago];

                for ($i = 1; $i <= $numRecibos; $i++) {
                    $finVigencia = Carbon::parse($venta->FinVigencia);
                    $fechaProximoPago = $finVigencia->addMonths($i);

                    // dd($venta->contactId);

                    $receipt = new Receipt([
                        'venta_id' => $venta->id,
                        'num_pago' => $i,
                        'fre_pago' => $venta->FrePago,
                        'fecha_proximo_pago' => $i > 1 ? $fechaProximoPago : null,
                        'fecha_pago_real' => $venta->Fpreventa,
                        'prima_neta_cobrada' => $venta->PncTotal,
                        'agente_cob_id' => null,
                        'tipo_pago' => $i == $numRecibos ? 'LIQUIDADO' : 'PAGO PARCIAL',
                        'estado_pago' => 'PENDIENTE',
                        'contactId' => $venta->contactId,
                    ]);

                    $receipt->save();
                }
            }

        }

        $this->info('Se han creado los recibos para las ventas específicas.');
    }
}
