<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telecomunication extends Model
{
    use HasFactory;

    protected $table = 'telecomunications';

    protected $fillable = [
        'fPreventa',
        'fUltimaGestion',
        'uGestion',
        'contact_id',
        'cuenta',
        'campana',
        'ultimaCampana',
        'loginOcm',
        'loginIntranet',
        'nombreAgente',
        'supervisor',
        'tipoVenta',
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'correo',
        'celular',
        'telefonoFijo',
        'fechaNacimiento',
        'rfc',
        'tipoIdentificacion',
        'claveIdentificacion',
        'calle',
        'numInterior',
        'municipio',
        'entreCalle1',
        'numExterior',
        'colonia',
        'estado',
        'entreCalle2',
        'cp',
        'referencia',
        'idSipre',
        'dirCompleta',
        'lat',
        'lng',
        'plazoForzoso',
        'tipoLinea',
        'tipoSegmento',
        'numeroPortar',
        'tipoPaquete',
        'paquete',
        'precio',
        'chkHdPlus',
        'chkHbo',
        'chkInternacional',
        'chkComboPlus',
        'chkHotPack',
        'chkAfizzionados',
        'chkParamount',
        'chkUniPlus',
        'ckhStarPlus',
        'chkDisney',
        'chkVixPlus',
        'chkGolden',
        'chkNetBase',
        'ckhNetEst',
        'chkNetPrem',
        'extensionesTv',
        'extensionesTel',
        'lineaAdicional',
        'tipoSegmentoMovil',
        'paqueteMovil',
        'imei',
        'contratoAdicionalMovil',
        'pedido',
        'costo',
        'portabilidad',
        'numeroPortarMovil',
        'representante',
        'rfcRepresentante',
        'fechaNacRepLegal',
        'formaPago',
        'tipoTarjeta',
        'numTarjeta',
        'vencimiento',
        'cvv',
        'observaciones',
        'documentos',
        'datosGenerados',
        'btnWsp',
        'estadoIzzi',
        'fechaReventa',
        'estadoSiebel',
        'estadoMovil',
        'orden',
        'estadoOrden',
        'tramitador',
        'confirmador',
    ];

    // Relacion uno a muchos izziMovi_id
    // public function izziMovi_id()
    // {
    //     return $this->hasMany('App\Models\IzziMovi', 'id');
    // }
}
