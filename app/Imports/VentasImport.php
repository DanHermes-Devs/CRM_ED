<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Venta;
use App\Models\Receipt;
use Illuminate\Support\Facades\Log;

// Agrega estas dos líneas al comienzo del archivo
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Exceptions\InvalidFormatException;
use Maatwebsite\Excel\Concerns\WithValidation;

class VentasImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $venta = new Venta([
            'contactId' => $row[0],
            'Nombre' => $row[1],
            'ApePaterno' => $row[2],
            'ApeMaterno' => $row[3],
            'fNacimiento' => $this->parseDate($row[4], 'd/m/Y'),
            'Genero' => $row[5],
            'RFC' => $row[6],
            'TelFijo' => $row[7],
            'TelCelular' => $row[8],
            'Correo' => $row[9],
            'Calle' =>  $row[10],
            'NumExt' => $row[11],
            'AlMun' => $row[12],
            'Estado' => $row[13],
            'CP' => $row[14],
            'Marca' => $row[15],
            'Modelo' => $row[16],
            'nSerie' => $row[17],
            'FinVigencia' => !empty($row[18]) ? Carbon::createFromFormat('d/m/Y', $row[18])->startOfDay()->toDateString() : null,
            'FfVigencia' => !empty($row[18]) ? Carbon::createFromFormat('d/m/Y', $row[18])->startOfDay()->addYear()->toDateString() : null,
            'Paquete' => $row[19],
            'nPoliza' => $row[20],
            'Aseguradora' => $row[21],
            'FrePago' => $row[22],
            'tVenta' => $row[23],
            'MesBdd' => $row[24],
            'AnioBdd' => $row[25],
            'created_at' => Carbon::now()
        ]);

        $venta->save();

        // Verificamos si ya existen recibos de pago para la venta
        // $recibos = Receipt::where('venta_id', $venta->id)->count();

        // // Si no hay recibos existentes, crea nuevos recibos
        // if ($recibos === 0) {
        //     // Creamos un arreglo con las frecuencias de pago
        //     $frecuenciaPagos = [
        //         'ANUAL' => 1,
        //         'SEMESTRAL' => 2,
        //         'TRIMESTRAL' => 4,
        //         'CUATRIMESTRAL' => 3,
        //         'MENSUAL' => 12
        //     ];

        //     // Convertimos frecuenciaPago en Mayusculas
        //     $frecuenciaPago = strtoupper($venta->FrePago);

        //     // Verificar si la frecuencia de pago es válida
        //     if (!array_key_exists($frecuenciaPago, $frecuenciaPagos)) {
        //         $frecuenciaPago = null;
        //     }

        //     if ($frecuenciaPago !== null) {
        //         $numRecibos = $frecuenciaPagos[$frecuenciaPago];
        //         $finVigencia = Carbon::parse($venta->FinVigencia);

        //         $primerReciboAnualAsignado = false;

        //         for ($i = 1; $i <= $numRecibos; $i++) {
        //             $mesesPorRecibo = 12 / $numRecibos; // Cantidad de meses por recibo
        //             $fechaProximoPago = $finVigencia->copy()->addMonthsNoOverflow($mesesPorRecibo * ($i - 1));

        //             $fechaProximoPago = $i == 1 ? $finVigencia : $fechaProximoPago;

        //             // Hago que el primer recibo se le asigne el agente que hizo la venta

        //             $receipt = new Receipt([
        //                 'venta_id' => $venta->id,
        //                 'num_pago' => $i,
        //                 'fre_pago' => $venta->FrePago,
        //                 'fecha_proximo_pago' => $i > 1 ? $fechaProximoPago : $finVigencia,
        //                 'fecha_pago_real' => $venta->Fpreventa,
        //                 'prima_neta_cobrada' => $venta->PncTotal,
        //                 'agente_cob_id' => $i == 1 ? $venta->agent->id ?? null : null,
        //                 'tipo_pago' => $i == $numRecibos ? 'LIQUIDADO' : 'PAGO PARCIAL',
        //                 'estado_pago' => $i == 1 && $frecuenciaPago != 'ANUAL' && !$primerReciboAnualAsignado ? 'PAGADO' : 'PENDIENTE',
        //                 'contactId' => $venta->contactId,
        //             ]);

        //             // Después de asignar el estado de pago, para marcar el primer recibo anual como asignado
        //             if ($i == 1 && $frecuenciaPago == 'ANUAL') {
        //                 $primerReciboAnualAsignado = true;
        //             }

        //             $receipt->save();
        //         }
        //     }
        // }

        // Devuelve la instancia de Venta
        return $venta;
    }

    /**
    * @return array
    */
    public function rules(): array
    {
        return [
            '0' => 'unique:ventas,id', // Asegura que el ID sea único en la tabla 'ventas'
        ];
    }

    private function parseDate($value, $format)
    {
        try {
            return Carbon::createFromFormat($format, $value)->format('Y-m-d');
        } catch (InvalidFormatException $e) {
            return null;
        }
    }

    private function parseDateTime($value, $format)
    {
        try {
            return Carbon::createFromFormat($format, $value)->format('Y-m-d H:i:s');
        } catch (InvalidFormatException $e) {
            return null;
        }
    }

}
