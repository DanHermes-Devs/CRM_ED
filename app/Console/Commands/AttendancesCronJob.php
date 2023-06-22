<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Console\Command;

class AttendancesCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CronJob para actualizar las asistencias de los usuarios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $databaseName = 'ocmdb';
        $hostName = '172.93.111.251';
        $userName = 'root';
        $passCode = '55R%@$2KqC68';

        $connection = mysqli_connect($hostName, $userName, $passCode, $databaseName);

        if (!$connection) {
            die("Error al conectarse a la base de datos: " . mysqli_connect_error());
        }

        $fecha_actual = Carbon::now()->format('Y-m-d');

        $query = "SELECT agent, fecha AS timestamp, estado
            FROM ocm_log_agentstatus
            WHERE DATE(fecha) = '$fecha_actual'
            AND (estado = 'LOGIN' OR estado = 'LOGOUT')
            ORDER BY agent, fecha;
        ";

        $result = mysqli_query($connection, $query);

        $agentesConLogin = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $agent = $row['agent'];
                $timestamp = $row['timestamp'];
                $estado = $row['estado'];

                if ($estado == 'LOGIN') {
                    $agentesConLogin[$agent] = true;  // Agrega el agente a la lista
                }

                $user = User::where('usuario', $agent)
                ->whereNotNull('hora_entrada')
                ->whereNotNull('hora_salida')
                ->first();

                if ($user) {
                    $fecha = date('Y-m-d', strtotime($timestamp));
                    $hora = date('H:i:s', strtotime($timestamp));

                    if ($estado == 'LOGIN') {
                        $existingAttendance = Attendance::where('agente', $agent)
                        ->whereDate('fecha_login', $fecha)
                        ->first();

                        if (!$existingAttendance) {
                            $attendance = new Attendance();
                            $attendance->agente = $agent;
                            $attendance->fecha_login = $fecha;
                            $attendance->hora_login = $hora;
                            $attendance->agent_id = $user->id;

                            $hora_entrada_usuario = date('H:i', strtotime($user->hora_entrada));

                            $diferencia_minutos = (strtotime($hora) - strtotime($hora_entrada_usuario)) / 60;

                            if ($diferencia_minutos >= 0 && $diferencia_minutos <= 14) {
                                $attendance->tipo_asistencia = 'A';
                            } elseif ($diferencia_minutos >= 15) {
                                $attendance->tipo_asistencia = 'R';
                            } else {
                                $attendance->tipo_asistencia = "A+";
                            }

                            $attendance->save();
                        }
                    } elseif ($estado == 'LOGOUT') {
                        $existingAttendance = Attendance::where('agente', $agent)
                        ->whereDate('fecha_login', $fecha)
                        ->first();

                        if ($existingAttendance) {
                            $existingAttendance->fecha_logout = $fecha;
                            $existingAttendance->hora_logout = $hora;

                            if ($existingAttendance->tipo_asistencia === 'A' && $existingAttendance->hora_login === null) {
                                $existingAttendance->tipo_asistencia = 'F';
                            }

                            $existingAttendance->save();
                        }
                    }
                }
            }
            //  Buscamos los agentes que no hicieron login y los agregamos a la tabla de asistencias
            $usuariosSinLogin = User::whereDoesntHave('attendances', function ($query) use ($fecha_actual) {
                $query->whereDate('fecha_login', $fecha_actual);
            })->whereNotNull('hora_entrada')
            ->whereNotNull('hora_salida')
            ->whereNotIn('usuario', array_keys($agentesConLogin))  // Asegúrate de que el usuario no esté en la lista de agentes que hicieron "login"
            ->get();

            // Obtenemos el dia de la semana, 0 = Domingo, 1 = Lunes, ..., 6 = Sabado
            $dia_semana = date('w', strtotime($fecha_actual));

            if($dia_semana != 0 && Carbon::parse($fecha_actual)->lt(Carbon::today())) {
                foreach ($usuariosSinLogin as $usuario) {
                    $falta = new Attendance();
                    $falta->agente = $usuario->usuario;
                    $falta->fecha_login = $fecha_actual;
                    $falta->agent_id = $usuario->id;
                    $falta->tipo_asistencia = 'F';  // F para falta
                    $falta->save();
                }
            }else{
                // SI EL DIA DE LA SEMANA ES DOMINGO, NO SE AGREGAN FALTAS, SE AGREGA "Sin Datos" EN LA TABLA DE ASISTENCIAS
                foreach ($usuariosSinLogin as $usuario) {
                    $sinDatos = new Attendance();
                    $sinDatos->agente = $usuario->usuario;
                    $sinDatos->fecha_login = $fecha_actual;
                    $sinDatos->agent_id = $usuario->id;
                    $sinDatos->tipo_asistencia = 'Sin Datos';  // SD para Sin Datos
                    $sinDatos->save();
                }
            }
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($connection);
        }
    }
}
