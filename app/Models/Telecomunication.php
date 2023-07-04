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
        'fechaNacimiento',
        'rfc',
        'claveElector',
        'claveDistribuidor',
        'plazoForzoso',
        'tipoLinea',
        'numeroPortar',
        'tipoSegmento',
        'precio',
        'tipoPaquete',
        'paquete',
        //'adicionales',
        'extensionesTv',
        'extensionesTel',
        'lineaAdicional',
        'extGraba',
        'tipoSegmentoMovil',
        'paqueteMovil',
        'portabilidad',
        'imei',
        'costo',
        'lineaMovilAdicional',
        'pedido',
        'calle',
        'numInterior',
        'municipio',
        'entreCalle1',
        'numExterior',
        'colonia',
        'estado',
        'entreCalle2',
        'giro',
        'fechaNacRepLegal',
        'representante',
        'rfcRepresentante',
        'tipoTarjeta',
        'cuenta',
        'numTarjeta',
        'vencimiento',
        'cvv',
        'documentos',
        'observaciones',
        'generar',
        'id_lead',
        'telefonoFijo',
        'btnWsp',
        'chkPremium',
        'chkHbo',
        'chkGolden',
        'chkFox',
        'chkInternacional',
        'chkIzziHd',
        'chkNoggin',
        'chkHotPack',
        'chkAfizzionados',
        'chkParamount',
        'chkDog',
        'chkBlim',
        'chkAcorn',
        'chkStarz',
        'chkDisney',
        'chkNetEst',
        'chkNetPre',
        'numeroPortarMovil',
        'cp',
        'estadoIzzi',
        'fechaReventa'
        //'referencia',

    ];

    // Relacion uno a muchos izziMovi_id
    // public function izziMovi_id()
    // {
    //     return $this->hasMany('App\Models\IzziMovi', 'id');
    // }
}
