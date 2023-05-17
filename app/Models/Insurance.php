<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurances';

    protected $fillable = [
        'nombre_aseguradora',
        'status',
    ];

    // Relación uno a muchos con CronJobConfig
    public function cronJobs()
    {
        return $this->hasMany(CronJobConfig::class, 'aseguradora', 'nombre_aseguradora');
    }
}
