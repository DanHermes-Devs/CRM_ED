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
        // Verifica si el ID ya existe en la base de datos
        $ventaExistente = Venta::find($row[0]);
        if ($ventaExistente) {
            // Si el ID ya existe, no importa el registro y devuelve null
            return null;
        }

        $venta = new Venta([
            'id' => $row[0],
            'contactId' => $row[1],
            'UGestion' => $row[2],
            'Fpreventa' => $this->parseDateTime($row[3], 'd/m/Y H:i'),
            'campana' => $row[4],
            'LoginOcm' => $row[5],
            'LoginIntranet' => $row[6],
            'NombreAgente' => $row[7],
            'Supervisor' => $row[8],
            'Codificacion' => $row[9],
            'Nombre' => $row[10],
            'ApePaterno' => $row[11],
            'ApeMaterno' => $row[12],
            'fNacimiento' => $this->parseDate($row[13], 'd/m/Y'),
            'Edad' => $row[14],
            'Genero' => $row[15],
            'RFC' => $row[16],
            'Homoclave' => $row[17],
            'CURP' => $row[18],
            'TelFijo' => $row[19],
            'TelCelular' => $row[20],
            'TelEmergencias' => $row[21],
            'Correo' => $row[22],
            'Calle' =>  $row[23],
            'NumExt' => $row[24],
            'NumInt' => $row[25],
            'Colonia' => $row[26],
            'AlMun' => $row[27],
            'Estado' => $row[28],
            'CP' => $row[29],
            'Marca' => $row[30],
            'SubMarca' => $row[31],
            'Modelo' => $row[32],
            'nSerie' => $row[33],
            'nMotor' => $row[34],
            'nPlacas' => $row[35],
            'Segmento' => $row[36],
            'Legalizado' => $row[37],
            'nCotizacion' => $row[38],
            'FinVigencia' => $this->parseDateTime($row[39], 'd/m/Y H:i'),
            'FfVigencia' => $row[40],
            'tPoliza' => $this->parseDateTime($row[41], 'd/m/Y H:i'),
            'Paquete' => $row[42],
            'nPoliza' => $row[43],
            'Aseguradora' => $row[44],
            'fPago' => $row[45],
            'FrePago' => $row[46],
            'tTarjeta' => $row[47],
            'nTarjeta' => $row[48],
            'fvencimiento' => $row[49],
            'PncTotal' => $row[50],
            'NombreDeCliente' => $row[51],
            'tVenta' => $row[52],
            'MesBdd' => $row[53],
            'AnioBdd' => $row[54],
            'noPago' => $row[55],
            'FechaProximoPago' => $this->parseDate($row[56] ?? null, 'd/m/Y'),
            'FechaPagoReal' => $this->parseDate($row[57] ?? null, 'd/m/Y'),
            'PrimaNetaCobrada' => $row[58],
            'AgenteCob' => $row[59],
            'TipoPago' => $row[60],
            'EstadoDePago' => $row[61],
            'created_at' => Carbon::now()
        ]);

        $venta->save();

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
                $frecuenciaPago = null;
            }

            if ($frecuenciaPago !== null) {
            
                $numRecibos = $frecuenciaPagos[$frecuenciaPago];

                for ($i = 1; $i <= $numRecibos; $i++) {
                    $finVigencia = Carbon::parse($venta->FinVigencia);
                    $fechaProximoPago = $finVigencia->addMonths($i);

                    $receipt = new Receipt([
                        'venta_id' => $venta->id,
                        'num_pago' => $i,
                        'fre_pago' => $venta->FrePago,
                        'fecha_proximo_pago' => $i > 1 ? $fechaProximoPago : null,
                        'fecha_pago_real' => $venta->Fpreventa,
                        'prima_neta_cobrada' => $venta->PrimaNetaCobrada,
                        'agente_cob_id' => null,
                        'tipo_pago' => $i == $numRecibos ? 'LIQUIDADO' : 'PAGO PARCIAL',
                        'estado_pago' => 'PENDIENTE'
                    ]);

                    $receipt->save();
                }
            }
        }

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
