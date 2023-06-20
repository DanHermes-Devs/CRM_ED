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
            '6337'
         ];

        foreach ($specificSalesIds as $saleId) {
            $venta = Venta::with('agent')->where('id', $saleId)->first();
            // Mostramos el ID del agente para verificar que se esté ejecutando el comando correctamente

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
                $frecuenciaPago = strtoupper(trim($venta->FrePago));

                // Verificar si la frecuencia de pago es válida y quitamos espacios en blanco adelante y atrás
                if (!array_key_exists($frecuenciaPago, $frecuenciaPagos)) {
                    $this->error("Frecuencia de pago '{$frecuenciaPago}' inválida para la venta con ContactID {$saleId}");
                    continue;
                }
                
                $numRecibos = $frecuenciaPagos[$frecuenciaPago];
                $finVigencia = Carbon::parse($venta->FinVigencia);

                $primerReciboAnualAsignado = false;

                for ($i = 1; $i <= $numRecibos; $i++) {
                    $mesesPorRecibo = 12 / $numRecibos; // Cantidad de meses por recibo
                    $fechaProximoPago = $finVigencia->copy()->addMonthsNoOverflow($mesesPorRecibo * ($i - 1));

                    $fechaProximoPago = $i == 1 ? $finVigencia : $fechaProximoPago;

                    // Hago que el primer recibo se le asigne el agente que hizo la venta

                    $receipt = new Receipt([
                        'venta_id' => $venta->id,
                        'num_pago' => $i,
                        'fre_pago' => $venta->FrePago,
                        'fecha_proximo_pago' => $i > 1 ? $fechaProximoPago : $finVigencia,
                        'fecha_pago_real' => $venta->Fpreventa,
                        'prima_neta_cobrada' => $venta->PncTotal,
                        'agente_cob_id' => $i == 1 ? $venta->agent->id ?? null : null,
                        'tipo_pago' => $i == $numRecibos ? 'LIQUIDADO' : 'PAGO PARCIAL',
                        'estado_pago' => $i == 1 && $frecuenciaPago != 'ANUAL' && !$primerReciboAnualAsignado ? 'PAGADO' : 'PENDIENTE',
                        'contactId' => $venta->contactId,
                    ]);

                    // Después de asignar el estado de pago, para marcar el primer recibo anual como asignado
                    if ($i == 1 && $frecuenciaPago == 'ANUAL') {
                        $primerReciboAnualAsignado = true;
                    }

                    $receipt->save();
                }
            }

        }

        $this->info('Se han creado los recibos para las ventas específicas.');
    }
}
