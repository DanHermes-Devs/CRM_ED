<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
        'id_project',
        'id_user',
        'grupo',
        'description',
        'status',
        'deleted_at'
    ];

    // Relacion con proyecto
    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project');
    }
}
