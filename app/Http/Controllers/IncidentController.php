<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // Me trae todas las incidencias del agente
        $incidents = Incident::where('agent_id', $id)->get();

        // Me trae las asistencias del agente
        $asistencias = Attendance::where('agent_id', $id)->get();

        // Mandamos la info del usuario
        $user = User::findOrFail($id);

        return response()->json([
            'code' => true,
            'usuario' => $user,
            'incidencias' => $incidents,
            'asistencias' => $asistencias
        ]);
    }

    public function incidenciasUsuario($id)
    {
        $user = Incident::selectRaw('*, DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_date')
                ->where('agent_id', $id)
                ->get();

        if (request()->ajax()) {
            return DataTables()
                ->of($user)
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $incidencia = new Incident();
        $incidencia->agent_id = $request->user_id;
        $incidencia->agente = $request->usuario_name;
        $incidencia->login_ocm = $request->usuario_name;
        $incidencia->tipo_incidencia = $request->tipo_incidencia;
        $incidencia->fecha_desde = $request->fecha_inicio;
        $incidencia->fecha_hasta = $request->fecha_fin;

        $incidencia->save();

        return response()->json([
            'code' => 200,
            'message' => 'Incidencia creada correctamente'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $incident)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function edit(Incident $incident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incident $incident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incident $incident)
    {
        //
    }
}
