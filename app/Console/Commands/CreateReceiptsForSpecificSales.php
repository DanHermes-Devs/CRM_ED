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
            '150173805',
            '150173804',
            '4312200003438',
            '150173795',
            '4012200122607',
            '4012200122759',
            '4012200122755',
            '150172611',
            '4012200124103',
            '4012200123984',
            '150173876',
            '4012200123571',
            '4012200123292',
            '150173823',
            '150173773',
            '150173432',
            '150173951',
            '4012200125265',
            '150173918',
            '4012200124899',
            '4012200124739',
            '150173730',
            '4012200126265',
            '4012200126245',
            '4012200126108',
            '150173970',
            '150173970',
            '4312200003529',
            '4012200125820',
            '4312200003517',
            '150173342',
            '4012200127234',
            '4012200127150',
            '150174033',
            '4312200003571',
            '150174029',
            '4312200003559',
            '4012200124307',
            '150174103',
            '4012200128029',
            '150174093',
            '150174089',
            '150174083',
            '4012200127798',
            '150173938',
            '150173488',
            '4012200128345',
            '150173507',
            '150173405',
            '4012200128394',
            '4012200128388',
            '150174165',
            '150174156',
            '4312200003638',
            '150173724',
            '150174199',
            '150174196',
            '150174173',
            '4012200130604',
            '4012200131014',
            '150174275',
            '4012200133800',
            '4012200133774',
            '150174412',
            '150174623',
            '4012200134828',
            '4312200003937',
            '4312200003993',
            '4012200144486',
            '4012200144250',
            '150175024',
            '4312200004144',
            '4012200148830',
            '4012200148768',
            '4012200149059',
            '4012200150685',
            '4012200150697',
            '4012200150001',
            '4012000134960',
            '4311900014404',
            '4012000132239',
            '4011600346685',
            '4012000130538',
            '4011900220129',
            '4011800179739',
            '4012100159499',
            '4011900206637',
            '4012000127000',
            '4011600326922',
            '4011600324886',
            '4051900017445',
            '4051900017530',
            '4011900222502',
            '4010800184771',
            '4012000129417',
            '4311900014928',
            '4012000107283',
            '4012100165533',

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
