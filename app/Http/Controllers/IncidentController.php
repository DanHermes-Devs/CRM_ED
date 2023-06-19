<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Incident;
use Carbon\Carbon;
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
        $user = Incident::selectRaw('*, DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_date, DATE_FORMAT(fecha_desde, "%Y-%m-%d") as fecha_desde, DATE_FORMAT(fecha_hasta, "%Y-%m-%d") as fecha_hasta')
                ->where('agent_id', $id)
                ->get();

        if (request()->ajax()) {
            return DataTables()
                ->of($user)
                ->addColumn('action', function ($data) {
                    $button = '';
                    $valoresPermitidos = ['C', 'DL', 'DFL', 'PD', 'D', 'PSG', 'S', 'PP', 'PDEF'];
        
                    if (in_array($data->tipo_incidencia, $valoresPermitidos)) {
                        $button .= '<button type="button" name="edit"  id="' . $data->id . '" class="edit_btn btn btn-primary btn-sm" data-id="'. $data->id .'">Editar</button>';
                        $button .= '&nbsp;&nbsp;';
                    }
        
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);

        $fechas = [];

        for($date = $inicio; $date->lte($fin); $date->addDay()) {
            $fechas[] = $date->format('Y-m-d');
        }

        foreach($fechas as $fecha) {
            // Convertimos la fecha a formato Y-m-d hh:mm:ss
            $fecha = Carbon::parse($fecha)->format('Y-m-d H:i:s');
            $asistencias = Attendance::where('fecha_login', $fecha)->get();
            
            // Buscamos la incidencia correspondiente a la fecha
            $incidencia = Incident::where('fecha_desde', $fecha)
                                ->where('fecha_hasta', $fecha)
                                ->where('agent_id', $request->user_id)
                                ->first();

            // Si la incidencia existe, la actualizamos. Si no, creamos una nueva
            if ($incidencia) {
                $incidencia->agent_id = $request->user_id;
                $incidencia->agente = $request->usuario_name;
                if ($request->name) {
                    $incidencia->login_ocm = $request->usuario_name;
                }
                $incidencia->tipo_incidencia = $request->tipo_incidencia;
                $incidencia->user_modification = $request->user()->apellico_paterno . ' ' . $request->user()->apellido_materno . ' ' . $request->user()->name;
            } else {
                $incidencia = new Incident();
                $incidencia->agent_id = $request->user_id;
                $incidencia->agente = $request->usuario_name;
                // SI VIENE UN $REQUEST_NAME VACIO NO SE GUARDA EL NOMBRE DEL USUARIO QUE MODIFICO LA INCIDENCIA
                if ($request->name) {
                    $incidencia->login_ocm = $request->usuario_name;
                }else{
                    $incidencia->login_ocm = $incidencia->agente;
                }
                $incidencia->tipo_incidencia = $request->tipo_incidencia;
                $incidencia->user_modification = $request->user()->apellido_paterno . ' ' . $request->user()->apellido_materno . ' ' . $request->user()->name;
                $incidencia->fecha_desde = $fecha;
                $incidencia->fecha_hasta = $fecha;
            }
            $incidencia->save();

            // BUSCAMOS LA ASISTENCIA CORRESPONDIENTE A LA FECHA Y LA ACTUALIZAMOS SI NO EXISTE LA CREAMOS Y LA ACTUALIZAMOS
            $asistencia = Attendance::where('fecha_login', $fecha)
                                    ->where('agent_id', $request->user_id)
                                    ->first();
            if ($asistencia) {
                $asistencia->agent_id = $request->user_id;
                $asistencia->agente = $request->usuario_name;
                $asistencia->tipo_asistencia = $request->tipo_incidencia;
            } else {
                $asistencia = new Attendance();
                $asistencia->agent_id = $request->user_id;
                $asistencia->agente = $request->usuario_name;
                $asistencia->tipo_asistencia = $request->tipo_incidencia;
                $asistencia->fecha_login = $fecha;
            }

            $asistencia->save();

        }

        return response()->json([
            'code' => 200,
            'message' => 'Incidencias actualizadas o creadas correctamente'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function edit(Incident $incident, $id)
    {
        $incidencia = Incident::selectRaw('*, DATE_FORMAT(fecha_desde, "%Y-%m-%d") as fecha_desde, DATE_FORMAT(fecha_hasta, "%Y-%m-%d") as fecha_hasta')
                                ->where('id', $id)
                                ->first();

        return response()->json([
            'code' => 200,
            'incidencia' => $incidencia
        ]);
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
}
