<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telecomunication extends Model
{
    use HasFactory;

    protected $table = 'telecomunications';

    protected $fillable = [
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
        'izziMovi_id',
    ];

    // Relacion uno a muchos izziMovi_id
    public function izziMovi_id()
    {
        return $this->hasMany('App\Models\IzziMovi', 'id');
    }
}
