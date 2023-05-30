<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'agent_id',
        'agente',
        'fecha_hora_login',
        'fecha_hora_logout',
        'tipo_asistencia',
        'skilldata',
        'observaciones',
    ];

    // Relacion con user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
