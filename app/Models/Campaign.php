<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Campaign
 *
 * @property $id
 * @property $nombre_campana
 * @property $descripcion_campana
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property User[] $users
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Campaign extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_campana',
        'descripcion_campana',
        'status',
        'empresa',
        'tipo_proyecto',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'id_campana', 'id');
    }

    // RELACION DE UNO A MUCHOS CON LA TABLA GROUPS
    public function groups(){
        return $this->hasMany(Group::class);
    }
}
