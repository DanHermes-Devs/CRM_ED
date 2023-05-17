<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobConfig extends Model
{
    use HasFactory;

    protected $table = 'cron_job_configs';

    protected $fillable = [
        'name_cronJob',
        'skilldata',
        'aseguradora',
        'idload_skilldata',
        'frequency',
        'motor_b',
        'idload_motor_b',
        'motor_c',
        'idload_motor_c'
    ];

    // Relación inversa de uno a muchos con Insurance
    public function insurance()
    {
        return $this->belongsTo(Insurance::class, 'aseguradora', 'nombre_aseguradora');
    }
}
