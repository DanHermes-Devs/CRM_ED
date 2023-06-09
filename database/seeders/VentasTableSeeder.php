<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VentasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ventas')->delete();
        
        \DB::table('ventas')->insert(array (
            0 => 
            array (
                'id' => 3,
                'contactId' => 1,
                'UGestion' => 'RENOVADA',
                'Fpreventa' => '2023-03-15',
                'campana' => NULL,
                'LoginOcm' => '2',
                'LoginIntranet' => '2',
                'NombreAgente' => NULL,
                'Supervisor' => NULL,
                'Codificacion' => 'PROMESA DE PAGO',
                'Nombre' => 'Dan',
                'ApePaterno' => 'Reyes',
                'ApeMaterno' => 'Osnaya',
                'fNacimiento' => NULL,
                'Edad' => NULL,
                'Genero' => NULL,
                'RFC' => '0909090909',
                'Homoclave' => NULL,
                'CURP' => NULL,
                'TelFijo' => NULL,
                'TelCelular' => 4545454545,
                'TelEmergencias' => NULL,
                'Correo' => NULL,
                'Calle' => NULL,
                'NumExt' => NULL,
                'NumInt' => NULL,
                'Colonia' => NULL,
                'AlMun' => NULL,
                'Estado' => NULL,
                'CP' => NULL,
                'Marca' => NULL,
                'SubMarca' => NULL,
                'Modelo' => NULL,
                'nSerie' => NULL,
                'nMotor' => NULL,
                'nPlacas' => NULL,
                'Segmento' => NULL,
                'Legalizado' => NULL,
                'nCotizacion' => NULL,
                'FinVigencia' => NULL,
                'FfVigencia' => NULL,
                'tPoliza' => NULL,
                'Paquete' => NULL,
                'nPoliza' => NULL,
                'Aseguradora' => NULL,
                'fPago' => NULL,
                'FrePago' => NULL,
                'tTarjeta' => NULL,
                'nTarjeta' => NULL,
                'fvencimiento' => NULL,
                'PncTotal' => NULL,
                'NombreDeCliente' => 'Dan',
                'tVenta' => 'POSIBLE DUPLICIDAD',
                'MesBdd' => NULL,
                'AnioBdd' => NULL,
                'noPago' => NULL,
                'FechaProximoPago' => NULL,
                'FechaPagoReal' => NULL,
                'PrimaNetaCobrada' => NULL,
                'AgenteCob' => NULL,
                'TipoPago' => NULL,
                'EstadoDePago' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2023-03-15 22:51:20',
                'updated_at' => '2023-03-15 22:59:25',
            ),
            1 => 
            array (
                'id' => 4,
                'contactId' => 1,
                'UGestion' => 'RENOVADA',
                'Fpreventa' => '2023-01-10',
                'campana' => NULL,
                'LoginOcm' => '2',
                'LoginIntranet' => '2',
                'NombreAgente' => NULL,
                'Supervisor' => NULL,
                'Codificacion' => 'PROMESA DE PAGO',
                'Nombre' => 'Dan',
                'ApePaterno' => 'Reyes',
                'ApeMaterno' => 'Osnaya',
                'fNacimiento' => NULL,
                'Edad' => NULL,
                'Genero' => NULL,
                'RFC' => '0909090909',
                'Homoclave' => NULL,
                'CURP' => NULL,
                'TelFijo' => NULL,
                'TelCelular' => 4545454545,
                'TelEmergencias' => NULL,
                'Correo' => NULL,
                'Calle' => NULL,
                'NumExt' => NULL,
                'NumInt' => NULL,
                'Colonia' => NULL,
                'AlMun' => NULL,
                'Estado' => NULL,
                'CP' => NULL,
                'Marca' => NULL,
                'SubMarca' => NULL,
                'Modelo' => NULL,
                'nSerie' => NULL,
                'nMotor' => NULL,
                'nPlacas' => NULL,
                'Segmento' => NULL,
                'Legalizado' => NULL,
                'nCotizacion' => NULL,
                'FinVigencia' => NULL,
                'FfVigencia' => NULL,
                'tPoliza' => NULL,
                'Paquete' => NULL,
                'nPoliza' => NULL,
                'Aseguradora' => NULL,
                'fPago' => NULL,
                'FrePago' => NULL,
                'tTarjeta' => NULL,
                'nTarjeta' => NULL,
                'fvencimiento' => NULL,
                'PncTotal' => NULL,
                'NombreDeCliente' => 'Dan Hermes Reyes Osnaya',
                'tVenta' => 'POSIBLE DUPLICIDAD',
                'MesBdd' => NULL,
                'AnioBdd' => NULL,
                'noPago' => NULL,
                'FechaProximoPago' => NULL,
                'FechaPagoReal' => NULL,
                'PrimaNetaCobrada' => NULL,
                'AgenteCob' => NULL,
                'TipoPago' => NULL,
                'EstadoDePago' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2023-01-10 22:51:20',
                'updated_at' => '2023-03-15 22:59:25',
            ),
            2 => 
            array (
                'id' => 5,
                'contactId' => 1,
                'UGestion' => 'RENOVADA',
                'Fpreventa' => '2023-01-10',
                'campana' => NULL,
                'LoginOcm' => '2',
                'LoginIntranet' => '2',
                'NombreAgente' => NULL,
                'Supervisor' => NULL,
                'Codificacion' => 'PROMESA DE PAGO',
                'Nombre' => 'Dan',
                'ApePaterno' => 'Reyes',
                'ApeMaterno' => 'Osnaya',
                'fNacimiento' => NULL,
                'Edad' => NULL,
                'Genero' => NULL,
                'RFC' => '0909090909',
                'Homoclave' => NULL,
                'CURP' => NULL,
                'TelFijo' => NULL,
                'TelCelular' => 4545454545,
                'TelEmergencias' => NULL,
                'Correo' => NULL,
                'Calle' => NULL,
                'NumExt' => NULL,
                'NumInt' => NULL,
                'Colonia' => NULL,
                'AlMun' => NULL,
                'Estado' => NULL,
                'CP' => NULL,
                'Marca' => NULL,
                'SubMarca' => NULL,
                'Modelo' => NULL,
                'nSerie' => NULL,
                'nMotor' => NULL,
                'nPlacas' => NULL,
                'Segmento' => NULL,
                'Legalizado' => NULL,
                'nCotizacion' => NULL,
                'FinVigencia' => NULL,
                'FfVigencia' => NULL,
                'tPoliza' => NULL,
                'Paquete' => NULL,
                'nPoliza' => '12',
                'Aseguradora' => NULL,
                'fPago' => NULL,
                'FrePago' => NULL,
                'tTarjeta' => NULL,
                'nTarjeta' => NULL,
                'fvencimiento' => NULL,
                'PncTotal' => NULL,
                'NombreDeCliente' => 'Dan Hermes Reyes Osnaya',
                'tVenta' => 'POSIBLE DUPLICIDAD',
                'MesBdd' => NULL,
                'AnioBdd' => NULL,
                'noPago' => NULL,
                'FechaProximoPago' => NULL,
                'FechaPagoReal' => NULL,
                'PrimaNetaCobrada' => NULL,
                'AgenteCob' => NULL,
                'TipoPago' => NULL,
                'EstadoDePago' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2023-01-10 22:51:20',
                'updated_at' => '2023-03-15 22:59:25',
            ),
        ));
        
        
    }
}