<?php

namespace App\Exports;

use App\Models\Venta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
// WhitStyles
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class VentasExport implements FromCollection, WithCustomStartCell, WithHeadings, shouldAutoSize, WithStyles, WithChunkReading
{

    public function __construct($start_date, $end_date, $resultados)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->resultados = $resultados;
    }


    public function collection()
    {

        // Mostarmos en un dd los $resultados
        // dd($this->resultados);
        // Traemos todas las ventas
        $ventas = $this->resultados;

        // Creamos un array vacío
        $ventasArray = [];

        // Recorremos cada venta
        foreach ($ventas as $venta) {

            // Creamos un array vacío
            $ventaArray = [];

            // Agregamos los datos de la venta al array
            $ventaArray['ID'] = $venta->id;
            $ventaArray['UGestion'] = $venta->UGestion;
            $ventaArray['Fpreventa'] = $venta->Fpreventa;
            $ventaArray['campana'] = $venta->campana;
            $ventaArray['LoginOcm'] = $venta->LoginOcm;
            $ventaArray['LoginIntranet'] = $venta->LoginIntranet;
            $ventaArray['NombreAgente'] = $venta->NombreAgente;
            $ventaArray['Supervisor'] = $venta->Supervisor;
            $ventaArray['Codificacion'] = $venta->Codificacion;
            $ventaArray['Nombre'] = $venta->Nombre;
            $ventaArray['ApePaterno'] = $venta->ApePaterno;
            $ventaArray['ApeMaterno'] = $venta->ApeMaterno;
            $ventaArray['fNacimiento'] = $venta->fNacimiento;
            $ventaArray['Edad'] = $venta->Edad;
            $ventaArray['Genero'] = $venta->Genero;
            $ventaArray['RFC'] = $venta->RFC;
            $ventaArray['Homoclave'] = $venta->Homoclave;
            $ventaArray['CURP'] = $venta->CURP;
            $ventaArray['Calle'] = $venta->Calle;
            $ventaArray['NumExt'] = $venta->NumExt;
            $ventaArray['NumInt'] = $venta->NumInt;
            $ventaArray['Colonia'] = $venta->Colonia;
            $ventaArray['AlMun'] = $venta->AlMun;
            $ventaArray['Estado'] = $venta->Estado;
            $ventaArray['CP'] = $venta->CP;
            $ventaArray['Marca'] = $venta->Marca;
            $ventaArray['SubMarca'] = $venta->SubMarca;
            $ventaArray['Modelo'] = $venta->Modelo;
            $ventaArray['nSerie'] = $venta->nSerie;
            $ventaArray['nMotor'] = $venta->nMotor;
            $ventaArray['nPlacas'] = $venta->nPlacas;
            $ventaArray['Segmento'] = $venta->Segmento;
            $ventaArray['Legalizado'] = $venta->Legalizado;
            $ventaArray['nCotizacion'] = $venta->nCotizacion;
            $ventaArray['FinVigencia'] = $venta->FinVigencia;
            $ventaArray['FfVigencia'] = $venta->FfVigencia;
            $ventaArray['tPoliza'] = $venta->tPoliza;
            $ventaArray['Paquete'] = $venta->Paquete;
            $ventaArray['nPoliza'] = $venta->nPoliza;
            $ventaArray['Aseguradora'] = $venta->Aseguradora;
            $ventaArray['fPago'] = $venta->fPago;
            $ventaArray['FrePago'] = $venta->FrePago;
            $ventaArray['PncTotal'] = $venta->PncTotal;
            $ventaArray['NombreDeCliente'] = $venta->NombreDeCliente;
            $ventaArray['tVenta'] = $venta->tVenta;
            $ventaArray['MesBdd'] = $venta->MesBdd;
            $ventaArray['AnioBdd'] = $venta->AnioBdd;
            $ventaArray['noPago'] = $venta->noPago;
            $ventaArray['FechaProximoPago'] = $venta->FechaProximoPago;
            $ventaArray['FechaPagoReal'] = $venta->FechaPagoReal;
            $ventaArray['PrimaNetaCobrada'] = $venta->PrimaNetaCobrada;
            $ventaArray['AgenteCob'] = $venta->AgenteCob;
            $ventaArray['TipoPago'] = $venta->TipoPago;
            $ventaArray['EstadoDePago'] = $venta->EstadoDePago;
            $ventaArray['created_at'] = $venta->created_at;
            
            $ventasArray[] = $ventaArray;
        }

        // Retornamos el array
        return collect($ventasArray);
    }

    public function chunkSize(): int
    {
        return 500; // Número de registros por chunk, ajusta este valor según tus necesidades
    }

    public function headings(): array
    {
        return [
            'ID',
            'UGestion',
            'Fpreventa',
            'campana',
            'LoginOcm',
            'LoginIntranet',
            'NombreAgente',
            'Supervisor',
            'Codificacion',
            'Nombre',
            'ApePaterno',
            'ApeMaterno',
            'fNacimiento',
            'Edad',
            'Genero',
            'RFC',
            'Homoclave',
            'CURP',
            'Calle',
            'NumExt',
            'NumInt',
            'Colonia',
            'AlMun',
            'Estado',
            'CP',
            'Marca',
            'SubMarca',
            'Modelo',
            'nSerie',
            'nMotor',
            'nPlacas',
            'Segmento',
            'Legalizado',
            'nCotizacion',
            'FinVigencia',
            'FfVigencia',
            'tPoliza',
            'Paquete',
            'nPoliza',
            'Aseguradora',
            'fPago',
            'FrePago',
            'PncTotal',
            'NombreDeCliente',
            'tVenta',
            'MesBdd',
            'AnioBdd',
            'noPago',
            'FechaProximoPago',
            'FechaPagoReal',
            'PrimaNetaCobrada',
            'AgenteCob',
            'TipoPago',
            'EstadoDePago',
            'created_at'
        ];
    }

    public function title(): string
    {
        return 'Ventas';
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        // Cambiar el nombre de la hoja
        $sheet = $sheet->setTitle('Ventas');

        // Cambiar la fuentes de la hoja
        $sheet->getStyle('A1:BJ1')->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => 14,
                'bold' => true,
                // Color de letra blanco
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
            'fill' => [
                'fillType' => 'solid',
                // Color de fondo negro
                'startColor' => ['argb' => 'FF000000'],
            ],
        ]);

        $sheet->getStyle('A1:BJ1' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ]
        ]);
    }
}
