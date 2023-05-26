<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario',
        'loginCRM',
        'name',
        'email',
        'password',
        'apellido_paterno',
        'apellido_materno',
        'estatus',
        'fecha_ultimo_login',
        'id_superior',
        'id_campana',
        'sexo',
        'fecha_nacimiento',
        'rfc',
        'curp',
        'estado_civil',
        'no_imss',
        'cr_infonavit',
        'cr_fonacot',
        'tipo_sangre',
        'ba_nomina',
        'cta_clabe',
        'alergias',
        'padecimientos',
        'tel_casa',
        'tel_celular',
        'persona_emergencia',
        'tel_emergencia',
        'esquema_laboral',
        'proyecto_asignado',
        'turno',
        'horario',
        'fecha_ingreso',
        'fecha_baja',
        'motivo_baja',
        'observaciones',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relacion con grupos
    public function groups()
    {
        return $this->hasMany(Group::class, 'id_user');
    }

    // Relacion con receipts
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'agente_cob_id');
    }

    // Relacion con incidents
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'id_usuario');
    }
}
