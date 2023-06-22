<?php

namespace App\Http\Controllers\API\Educacion;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Education;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Education::query();

        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $query->whereBetween('fp_venta', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled(['client_name'])) {
            $query->where('client_name', [$request->client_name]);
        }

        if ($request->filled(['date_cobrada'])) {
            $query->where('date_cobrada', [$request->date_cobrada]);
        }

        if ($request->filled(['confirmed_account'])) {
            $query->where('confirmed_account', [$request->confirmed_account]);
        }
         // Búsquedas exactas
         $camposExactos = [
            'contact_id' => 'contact_id',
            'client_name' => 'client_name',
            'client_landline' => 'client_landline',
            'client_celphone' => 'client_celphone',
            'codification' => 'codification',
        ];

        foreach ($camposExactos as $campoDb => $campoReq) {

            if ($request->filled($campoReq)) {
                    $query->where($campoDb, $request->$campoReq);
            }
        }

        // Búsqueda por tipo de venta
        if ($request->filled('codification')) {
            $query->where('codification', $request->codification);
        }

        $resultados = $query->get();
        // Filtros por perfil de usuario
        $rol = $request->rol;

        if ($rol == 'Agente de Ventas' ) {
            $resultados = $resultados->where('agente_intra', $usuario);
        } elseif ($rol == 'Supervisor' || $rol == 'Coordinador') {
            // No aplicar filtros adicionales para supervisores y coordinadores
        } else {
            // No aplicar filtros adicionales para administradores
        }

        // Recuperamos todos los usuarios con rol supervisor y lo mandamos a la vista
        $supervisores = User::role('Supervisor')->get();

        // Recuperamos todos los usuario con rol Agente de Ventas y lo mandmos a la vista
        $agentes = User::role('Agente de Ventas')->get();

        $query->join('users', 'users.id', '=', 'education.agent_intra')
                ->select('education.*', DB::raw('CONCAT(users.apellido_paterno, " ", users.apellido_materno, " ", users.name) AS agent_fullname'));

        $resultados = $query->get();

        //RESPUESTA PARA PINTAR LAS QUERYS
        if (request()->ajax()) {
            return DataTables()
                ->of($resultados)
                ->addColumn('action', 'crm.modulos.educacion.uin.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.modulos.educacion.uin.index', compact('agentes','supervisores','resultados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //identificar si el usuario se encuentra en ZEUS
        $usuario = User::where('usuario', $request->agent_OCM)->first();
        // busco la cuenta por contact_id
        $cuentaRegistrada = Education::where('contact_id', $request->contact_id)->first();

        // Se verifica que la cuenta este registrada y que la codificación sea igual, si es manda un error al log
        if($cuentaRegistrada && ($cuentaRegistrada->codification == $request->codification )){
            return response()->json([
                "code" => 200,
                "message" => "La cuenta ya esta registrada con la misma codificación",
            ]);
        // Si la cuenta esta registrada pero la codificacion es diferente se manda a actualizar la información
        }else if($cuentaRegistrada && ($cuentaRegistrada->codification != $request->codification )){
            // se verifica si la codificación es COBRADA
            if($request->codification == 'COBRADA'){
                $birth_certifcate  ='SI';
                $curp_certificate  ='SI';
                $ine_certifcate  ='SI';
                $inscripcion_certificate = 'SI';
                $domicilio_certifcate  ='SI';
                $estudio_certifcate  ='SI';
                $cotizacion_certifcate  ='SI';
                $pago_certifcate  ='SI';
                $date_cobranza = Carbon::now()->toDateTimeString();
                // se ocupa el usuario_ocm para saber quien fue el que cerro la venta
                $usuarioCierreVenta = $request->agent_OCM;
            }else{
                $birth_certifcate  ='NO';
                $curp_certificate  ='NO';
                $ine_certifcate  ='NO';
                $inscripcion_certificate = 'NO';
                $domicilio_certifcate  ='NO';
                $estudio_certifcate  ='NO';
                $cotizacion_certifcate  ='NO';
                $pago_certifcate  ='NO';
                $date_cobranza = NULL;
                $usuarioCierreVenta = $request->agent_OCM;
            }

            $info = [
                'birth_certifcate' => $birth_certifcate,
                'curp_certificate' => $curp_certificate,
                'usuario_ocm' => $usuarioCierreVenta,
                'ine_certifcate' => $ine_certifcate,
                'inscripcion_certificate' => $inscripcion_certificate,
                'domicilio_certifcate' => $domicilio_certifcate,
                'estudio_certifcate' => $estudio_certifcate,
                'cotizacion_certifcate' => $cotizacion_certifcate,
                'pago_certifcate' => $pago_certifcate,
                'campana' => $request->campana,
                'codification' => $request->codification,
                'client_name' => $request->client_name,
                'client_landline' => $request->client_landline,
                'client_celphone' => $request->client_celphone,
                'client_modality' => $request->client_modality,
                'client_program' => $request->client_program,
                'client_specialty' => $request->client_specialty,
                'client_street' => $request->client_street,
                'client_number' => $request->client_number,
                'client_delegation' => $request->client_delegation,
                'client_state' => $request->client_state,
                'client_sex' => $request->client_sex,
                'client_birth' => $request->client_birth,
                'client_plantel' => $request->client_plante,
                'client_matricula' => $request->client_matricula,
                'client_plantel' => $request->client_plantel,
                'client_matricula' => $request->client_matricula,
                'date_cobranza' => $date_cobranza
            ];
            // no se puede modificarf la fecha de cotización pero si los otros campos
            Education::where('contact_id', $request->contact_id)
            ->update($info);
            // $coti = Education::findOrFail($id)->first();
            return response()->json([
                "code" => 200,
                "message" => "Registro actualizado correctamente",
                "data" => $info
            ]);
        }else{
            // Se verifica si existe el usuario en ZEUS
            if($usuario){
                $education = new Education;
                $education->contact_id = $request->contact_id;
                $education->usuario_ocm = $request->agent_OCM;
                $education->usuario_crm = $request->agent_OCM;
                $education->nombre_universidad = 'UIN';
                $education->fp_venta = Carbon::now();
                $education->campana = $request->campana;
                $education->agent_OCM = $request->agent_OCM;
                $education->agent_intra = $usuario->id;
                $education->supervisor = $usuario->id_superior;
                $education->codification = $request->codification;
                $education->client_name = $request->client_name;
                $education->client_landline = $request->client_landline;
                $education->client_celphone = $request->client_celphone;
                $education->client_modality = $request->client_modality;
                $education->client_program = $request->client_program;
                $education->client_specialty = $request->client_specialty;
                $education->client_street = $request->client_street;
                $education->client_number = $request->client_number;
                $education->client_delegation = $request->client_delegation;
                $education->client_state = $request->client_state;
                $education->client_sex = $request->client_sex;
                $education->client_birth = $request->client_birth;
                $education->schedule_date = $request->schedule_date;
                $education->client_plantel = $request->client_plantel;
                $education->client_matricula = $request->client_matricula;
                //$education->fill($request->all());
                if($request->codification == 'COBRADA'){
                    $education->date_cobranza = Carbon::now()->toDateTimeString();
                }
                $education->save();

                return response()->json([
                    "code" => 200,
                    "message" => "Registro guardado correctamente",
                    "data" => $education
                ]);
            }else{
                return response()->json([
                    "code" => 200,
                    "message" => "No se encontró tu usuario en ZEUS, Por favor, solicita el alta de tu usuario a tu supervisor.",
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $coti = Education::where("id", $id)->first();
        return view('crm.modulos.educacion.uin.show', compact('coti'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $info = $request->all();
        if(!isset($request->confirmed_account) || $request->confirmed_account === 'NO'){
            $info = $request->except('date_confirmada');
        }
        //dd($info);
        // POR SEGURIDAD SE GENERA DE CAMPO POR CAMPO
        $edu = Education::where('id', $id)->first();
        $edu->fill($info);
        $edu->save();
        $coti = Education::findOrFail($id);

        // PARA MANDAR MENSAJE DE QUE SE GUARDO CORRECTAMENTE
        return redirect()->route('educacion-uin.edit', $id)
         ->with('success', 'Registro editado con éxito')
         ->with('coti', $coti);
    }


    public function edit(Request $request, $id)
    {
        $coti = Education::findOrFail($id);
        $date_now = Carbon::now()->toDateTimeString();
        return view('crm.modulos.educacion.uin.edit', compact('coti','date_now'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function destroy(Education $education)
    {
        //
    }
}
