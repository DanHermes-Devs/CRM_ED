<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ventas';

    protected $fillable = [
        'contactId',
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
        'TelFijo',
        'TelCelular',
        'TelEmergencias',
        'Correo',
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
        'nueva_poliza',
        'Aseguradora',
        'fPago',
        'FrePago',
        'tTarjeta',
        'nTarjeta',
        'fvencimiento',
        'PncTotal',
        'NombreDeCliente',
        'tVenta',
        'MesBdd',
        'AnioBdd',
        'noPago',
        'FechaProximoPago',
        'FechaPagoReal',
        'PrimaNetaCobrada',
        'PrimaNetaAnual',
        'AgenteCob',
        'TipoPago',
        'EstadoDePago',
        'OCMSent',
        'ocmdaytosend'
    ];

    protected $dates = [
        'deleted_at',
        'FinVigencia'
    ];

    // Relacion con Receipt
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
