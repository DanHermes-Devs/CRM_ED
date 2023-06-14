<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education';

    protected $fillable = [
        'contact_id', // IDENTIFICADOR DE LA BASE DE DATOS
        'campana', // MOTOR DE MARCACION
        'agent_OCM', // USUARIO OCM QUE INGRESO LA PREVENTA
        'fp_venta', // FECHA DE ADD A CRM
        'agent_intra', // USUARIO DE LA INTRANET AL QUE PERTENECE LA PREVENTA
        'agent_name', // NOMBRE DEL AGENTE QUE INGRESO LA PREVENTA
        'supervisor', // SUPERVISOR A CARGO DEL AGENTE
        'codification', //INDICA LA GESTIÓN
        'client_name', // NOMBNRE DE QUIEN LLAMA O SE LE LLAMA
        'client_landline', // ALMACENA EL TELEFONO FIJO DEL CLIENTE
        'client_celphone', // ALMACENA EL TELEFONO MOVIL DEL CLIENTE
        'client_mail', // ALMACENA EL E-MAIL DEL CLIENTE
        'client_modality', // TIPO DE MODALIDAD DE ESTUDIO
        'client_program', // ESTUDIO DESEADO
        'client_specialty', // ESPECIALIDAD DESEADA
        'client_street', // CALLE DEL CLIENTE
        'client_number', // NUMERO DE CASA DEL CLIENTE
        'client_delegation', // DELEGACIÓN  DEL CLIENTE
        'client_state', // ESTADO DEL CLIENTE
        'client_sex', // SEXO DEL CIENTE
        'client_birth', // FECHA DE NACIMIENTO DEL CLIENTE
        'status', // ESTATUS DE LA COTIZACIÓN
        'documents_portal', // SI / NO ESTAN CARGADOS LOS DOCUMENTOS EN UIN
        'account_UIN', // NÚMERO DE CUENTA DE UIN ASIGNADO
        'schedule_date', //FECHA DE COTIZACION O DE AGENDA
        'birth_certifcate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'curp_certificate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'ine_certifcate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'inscripcion_certificate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'domicilio_certifcate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'estudio_certifcate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'cotizacion_certifcate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'pago_certifcate', // DOCUMENTO PARA VALIDAR SI ES ONLINE O MIXTO
        'confirmed_account', // SI ESTA CONFIRMADA LA CUENTA
        'client_plantel', // SE AÑADE EL PLANTE SI ES PRECENCIAL
        'client_matricula' // LA MATRICULA SI ES QUE SE TIENE
    ];

    // Relacion con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
