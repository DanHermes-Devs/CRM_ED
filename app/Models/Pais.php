<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pais extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paises';

    protected $fillable = [
        'pais',
        'descripcion',
        'estatus',
        'deleted_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    // RELACION DE UNO A MUCHOS CON LA TABLA GROUPS
    public function groups(){
        return $this->hasMany(Group::class);
    }
}
