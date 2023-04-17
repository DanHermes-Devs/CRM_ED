<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'receipts';

    protected $fillable = [
        'venta_id',
        'num_pago',
        'fre_pago',
        'fecha_proximo_pago',
        'fecha_pago_real',
        'prima_neta_cobrada',
        'agente_cob_id',
        'tipo_pago',
        'estado_pago'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function agente_cob()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
