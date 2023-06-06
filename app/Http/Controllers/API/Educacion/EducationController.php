<?php

namespace App\Http\Controllers\API\Educacion;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Filtramos por agente
        $usuario = User::find($request->user);

        // Búsqueda por tipo de venta
        if ($request->filled('codification')) {
            $query->where('codification', $request->codification);
        }

        $resultados = $query->get();
        // Filtros por perfil de usuario
        $rol = $request->rol;

        if ($rol == 'Agente de Ventas') {
            $resultados = $resultados->where('codification', 'COTIZACION')
                ->where('codification', 'ALUMNO')
                ->where('agent_OCM', 'Agente2');
        } elseif ($rol == 'Supervisor' || $rol == 'Coordinador') {
            // No aplicar filtros adicionales para supervisores y coordinadores
        } else {
            // No aplicar filtros adicionales para administradores
        }

        // Recuperamos todos los usuarios con rol supervisor y lo mandamos a la vista
        $supervisores = User::role('Supervisor')->get();

        // Recuperamos todos los usuario con rol Agente de Ventas y lo mandmos a la vista
        $agentes = User::role('Agente de Ventas')->get();

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
        //PARA ALMACENAR LA INFORMACION
        $usuario = User::where('usuario', $request->agent_OCM)->first();

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
            //$education->fill($request->all());
            $education->save();

            return response()->json([
                "code" => 200,
                "message" => "Venta Guardada Correctamente",
                "data" => $education
            ]);
        }else{
            return response()->json([
                "code" => 200,
                "message" => "En usuario no se encuentra en la INTRA",
            ]);
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
        //dd($id);
        $info = $request->except(['_token', '_method']);
        Education::where('id', $id)
            ->update($info);
        $coti = Education::findOrFail($id)->first();
        return view('crm.modulos.educacion.uin.edit', compact('coti'));
    }


    public function edit(Request $request, $id)
    {

        $coti = Education::findOrFail($id)->first();
        return view('crm.modulos.educacion.uin.edit', compact('coti'));
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
