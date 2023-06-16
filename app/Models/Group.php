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

    // RELACION DE UNO A MUCHOS CON LA TABLA USERS
    public function users(){
        return $this->hasMany(User::class);
    }
    
    // RELACION DE UNO A MUCHOS CON LA TABLA CAMPAIGNS
    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
    
    // RELACION DE UNO A MUCHOS CON LA TABLA PAISES
    public function pais(){
        return $this->belongsTo(Pais::class);
    }
}
