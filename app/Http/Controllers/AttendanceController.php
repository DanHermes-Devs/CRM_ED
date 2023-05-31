<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campana = $request->get('campana');
        $supervisor = $request->get('supervisor');

        // Mostramos todos los usuarios que no tengan rol Administrador, Coordinador, Supervisor y Director, los demas si deben mostrarse
        $usuarios = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['Administrador', 'Coordinador', 'Supervisor', 'Director']);
        })
        ->with('attendances')
        ->withCount([
            'attendances as total_retardos' => function ($query) {
                $query->where('tipo_asistencia', 'R');
            },
            'attendances as total_asistencias' => function ($query) {
                $query->whereIn('tipo_asistencia', ['A', 'A+']);
            },
            'attendances as total_faltas' => function ($query) {
                $query->where('tipo_asistencia', 'F');
            },
            'attendances as total_entro_temprano' => function ($query) {
                $query->where('tipo_asistencia', 'A+');
            }
        ])
        ->get();

        // Retornamos las campañas
        $campanas = Campaign::all();

        // Retornamos a los supervisores
        $supervisores = User::role('Supervisor')->get();

        if (request()->ajax()) {
            return DataTables()
                ->of($usuarios)
                ->addColumn('action', 'crm.modulo_usuarios.asistencias.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.modulo_usuarios.asistencias.asistencias', compact('campanas', 'supervisores', 'usuarios'));
    }

    public function asistenciaUsuario($id)
    {
        $user = Attendance::where('agent_id', $id)->get();

        if (request()->ajax()) {
            return DataTables()
                ->of($user)
                ->addColumn('action', 'crm.modulo_usuarios.asistencias.actions_incidencias')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function actualizarAsistencia($id)
    {
        $asistencia = Attendance::findOrFail($id);
        $asistencia->tipo_asistencia = request()->tipo_asistencia;
        $asistencia->save();

        return response()->json([
            'code' => true,
            'message' => 'Se actualizo la asistencia correctamente'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
