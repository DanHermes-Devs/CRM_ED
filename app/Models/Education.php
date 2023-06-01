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
    ];

    // Relacion con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


/*
SELECT * FROM ocmdb.ocm_log_agentstatus
WHERE agent = 'agente'
ORDER BY idCREATE TABLE skill_uimotor_dataexten (
  id int(11) NOT NULL DEFAULT 0,
  nombre varchar(50) DEFAULT NULL,
  apellidoPaterno varchar(50) DEFAULT NULL,
  apellidoMaterno varchar(50) DEFAULT NULL,
  fechaNacimiento datetime DEFAULT NULL,
  genero text DEFAULT NULL,
  correo varchar(100) DEFAULT NULL,
  posgradoDeseado varchar(30) DEFAULT NULL,
  motivo varchar(30) DEFAULT NULL,
  telefonoFijo varchar(10) DEFAULT NULL,
  telefonoCelular varchar(10) DEFAULT NULL,
  otraUniversidad varchar(100) DEFAULT NULL,
  modalidad varchar(30) DEFAULT NULL,
  costoPromedio varchar(50) DEFAULT NULL,
  formaPago varchar(50) DEFAULT NULL,
  trabajaActualmente varchar(5) DEFAULT NULL,
  horasEstudio varchar(5) DEFAULT NULL,
  programa varchar(100) DEFAULT NULL,
  observaciones text DEFAULT NULL,
  especialidad varchar(50) DEFAULT NULL,
  costoTotal double DEFAULT NULL,
  calle varchar(100) DEFAULT NULL,
  numeroExterior varchar(10) DEFAULT NULL,
  numeroInterior varchar(10) DEFAULT NULL,
  municipioDelegacion varchar(100) DEFAULT NULL,
  cp varchar(5) DEFAULT NULL,
  colonia varchar(100) DEFAULT NULL,
  tipoPago varchar(30) DEFAULT NULL,
  costoInscripcion double DEFAULT NULL,
  costoMensual double DEFAULT NULL,
  estado varchar(40) DEFAULT NULL,
  universidadVisitada varchar(100) DEFAULT NULL,
  id_lead text DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fx_UIMotor_id FOREIGN KEY (id) REFERENCES skill_uimotor_data (id) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 DESC
*/
