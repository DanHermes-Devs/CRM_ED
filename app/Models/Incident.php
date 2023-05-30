<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'agent_id',
        'agente',
        'login_ocm',
        'tipo_incidencia',
        'fecha_desde',
        'fecha_hasta',
    ];

    // Relacion con user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
