<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonalFile
 *
 * @property $id
 * @property $id_usuario
 * @property $id_proyecto
 * @property $id_supervisor
 * @property $perfil
 * @property $status
 * @property $ruta_ine
 * @property $ruta_acta_nacimiento
 * @property $ruta_curp
 * @property $ruta_constancia_fiscal
 * @property $ruta_nss
 * @property $ruta_comp_estudios
 * @property $ruta_comp_domicilio
 * @property $ruta_edo_bancario
 * @property $ruta_aviso_ret_infonavit
 * @property $ruta_aviso_ret_fonacot
 * @property $created_at
 * @property $updated_at
 *
 * @property Project $project
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PersonalFile extends Model
{
    
    static $rules = [
        'id_usuario' => 'required',
        'id_proyecto' => 'required',
        'id_supervisor' => 'required',
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_usuario',
        'id_proyecto',
        'id_supervisor',
        'perfil',
        'status',
        'ruta_ine',
        'ruta_acta_nacimiento',
        'ruta_curp',
        'ruta_constancia_fiscal',
        'ruta_nss',
        'ruta_comp_estudios',
        'ruta_comp_domicilio',
        'ruta_edo_bancario',
        'ruta_aviso_ret_infonavit',
        'ruta_aviso_ret_fonacot',
        'ruta_contrato',
        'ruta_responsiva',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'id_proyecto');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

}
