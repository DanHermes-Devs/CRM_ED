<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'usuario_ocm',
        'usuario_crm',
        'nombre_universidad',
    ];

    // Relacion con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
