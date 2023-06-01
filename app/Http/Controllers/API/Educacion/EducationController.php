<?php

namespace App\Http\Controllers\API\Educacion;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

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



        return view('crm.modulos.educacion.uin.index');
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
        $education = new Education;
        $education->contact_id = $request->contact_id;
        $education->usuario_ocm = $request->agent_OCM;
        $education->usuario_crm = $request->agent_OCM;
        $education->nombre_universidad = 'UIN';
        $education->fp_venta = Carbon::now();
        $education->campana = $request->campana;
        $education->agent_OCM = $request->agent_OCM;
        //$education->agent_intra = $request->agent_intra;
        //$education->supervisor = $request->supervisor;
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function show(Education $education)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Education $education)
    {
        //
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
