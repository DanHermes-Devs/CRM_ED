<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adt extends Model
{
    use HasFactory;

    protected $table = 'security';

    protected $fillable = [
        'contact_id',
        'fecha_venta',
        'campana',
        'login_ocm',
        'login_intranet',
        'nombre_agente',
        'supervisor',
        'codificacion',
        'cuenta_adt',
        'cliente_nombre',
        'cliente_rfc',
        'cliente_telefono',
        'cliente_celular',
        'cliente_correo',
        'cliente_calle',
        'cliente_numero',
        'cliente_cp',
        'client_colonia',
        'cliente_estado',
        'cliente_municipio',
        'cliente_producto',
        'cliente_tipo_producto',
        'cliente_tipo_equipo',
        'contrato_plazo',
        'forma_pago',
        'emergencia_nombre_uno',
        'emergencia_tel_uno',
        'emergencia_nombre_dos',
        'emergencia_tel_dos',
        'referencia_visual',
        'estatus_venta',
        'fecha_instalacion',
        'estatus_instalacion',
        'estatus_post_instalacion',
        'usuario_tramitacion',
        'nombre_tramitador',

    ];

    // Relacion con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
