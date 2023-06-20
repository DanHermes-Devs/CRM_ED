<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Campaign;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Project;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->flash();
        $fecha_1 = request()->get('fecha_pago_1');
        $fecha_2 = request()->get('fecha_pago_2');
        $agenteId = request()->get('agente');
        $campanaId = request()->get('campana');
        $supervisorId = request()->get('supervisor');

        $fecha_inicio = Carbon::parse($fecha_1)->format('Y-m-d');
        $fecha_fin = Carbon::parse($fecha_2)->format('Y-m-d');

        $asistencias = Attendance::whereBetween('fecha_login', [$fecha_inicio, $fecha_fin])->get();

        // Agrupa las asistencias por fecha y usuario
        $asistenciasPorFecha = $asistencias->groupBy(['fecha']);

        $fechas = CarbonPeriod::create($fecha_inicio, $fecha_fin);

        // Convierte el resultado en una colección (puede que necesites hacer esto para utilizar métodos de colección más adelante)
        $fechas = collect($fechas);

        // Mostramos todos los usuarios que no tengan rol Administrador, Coordinador, Supervisor y Director, los demas si deben mostrarse en la tabla
        $usuarios = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['Administrador', 'Coordinador', 'Supervisor', 'Director']);
        })
        ->where('estatus', '=', 1)
        ->whereNotNull('hora_entrada')
        ->whereNotNull('hora_salida')
        ->whereHas('attendances')
        ->when($campanaId, function ($query, $campanaId) {
            $query->whereHas('group', function ($query) use ($campanaId) {
                $query->where('campaign_id', $campanaId);
            });
        })
        ->when($supervisorId, function ($query) use ($supervisorId) {
            $query->where('id_superior', $supervisorId);
        })
        ->when($agenteId, function ($query, $agenteId) {
            $query->where('id', $agenteId);
        })
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
        ->paginate(100);

        // Retornamos las campañas
        $campanas = Campaign::where('status', '=', 1)->get();

        // Retornamos a los supervisores
        $supervisores = User::role('Supervisor')->get();

        // Retornamos a los agentes con rol Agente de Ventas
        $agentes = User::role('Agente de Ventas')->where('estatus', '=', 1)->get();

        return view('crm.modulo_usuarios.asistencias.asistencias', compact('campanas', 'supervisores', 'usuarios', 'asistencias', 'asistenciasPorFecha', 'fechas', 'agentes'));
    }

    public function getSupervisores($campaign_id)
    {
        // Encontramos todos los grupos de la campaña
        $grupos = Group::where('campaign_id', $campaign_id)->get();

        $supervisores = [];

        // Iteramos sobre cada grupo
        foreach ($grupos as $grupo) {
            // Ahora obtenemos los supervisores del grupo utilizando la nueva relación
            $supervisores_grupo = $grupo->users()->role('Supervisor')->get();
            $supervisores = array_merge($supervisores, $supervisores_grupo->toArray());
        }

        return response()->json($supervisores);
    }

    public function getAgentes($supervisor_id)
    {
        // Primero obtenemos el supervisor
        $supervisor = User::find($supervisor_id);

        if(!$supervisor) {
            return response()->json([]);
        }

        // Luego obtenemos los grupos de este supervisor
        $grupos = $supervisor->groups;

        $agentes = [];

        // Para cada grupo, obtenemos los agentes y los agregamos al arreglo de agentes
        foreach($grupos as $grupo) {
            $agentes_grupo = User::role(['Agente de Ventas', 'Agente Renovaciones', 'Agente de Cobranza'])
                            ->where('group_id', $grupo->id)->get();
            $agentes = array_merge($agentes, $agentes_grupo->toArray());
        }

        return response()->json($agentes);
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
}
