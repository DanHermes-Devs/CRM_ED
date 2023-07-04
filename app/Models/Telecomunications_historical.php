<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telecomunications_historical extends Model
{
    use HasFactory;
    protected $table = 'telecomunications_historicals';

    protected $fillable = [
        'idVenta',
        'contact_id',
        'loginOcm',
        'loginIntranet',
        'estado',
        'descripcion',
    ];
}
