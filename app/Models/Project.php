<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_pais',
        'proyecto',
        'descripcion',
        'estatus',
        'deleted_at'
    ];

    // Relacion con pais
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais');
    }

    // Relacion con grupos
    public function groups()
    {
        return $this->hasMany(Group::class, 'id_project');
    }

    protected $hidden = [
        'deleted_at'
    ];
}
