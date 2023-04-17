<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Venta;
use Maatwebsite\Excel\Concerns\ToModel;

class VentasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Venta([
            'id' => $row[0],
            'contactId' => $row[1],
            'UGestion' => $row[2],
            'Fpreventa' => Carbon::createFromFormat('d/m/Y H:i', $row[3])->format('Y-m-d H:i:s'),
            'campana' => $row[4],
            'LoginOcm' => $row[5],
            'LoginIntranet' => $row[6],
            'NombreAgente' => $row[7],
            'Supervisor' => $row[8],
            'Codificacion' => $row[9],
            'Nombre' => $row[10],
            'ApePaterno' => $row[11],
            'ApeMaterno' => $row[12],
            'fNacimiento' => Carbon::createFromFormat('d/m/Y', $row[13])->format('Y-m-d'),
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
            'FinVigencia' => Carbon::createFromFormat('d/m/Y H:i', $row[39])->format('Y-m-d H:i:s'),
            'FfVigencia' => $row[40],
            'tPoliza' => Carbon::createFromFormat('d/m/Y H:i', $row[41])->format('Y-m-d H:i:s'),
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
            'FechaProximoPago' => Carbon::createFromFormat('d/m/Y', $row[56])->format('Y-m-d'),
            'FechaPagoReal' => Carbon::createFromFormat('d/m/Y', $row[57])->format('Y-m-d'),
            'PrimaNetaCobrada' => $row[58],
            'AgenteCob' => $row[59],
            'TipoPago' => $row[60],
            'EstadoDePago' => $row[61],
            'created_at' => Carbon::now(),
        ]);
    }
}
