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
        'frequency',
    ];
}
