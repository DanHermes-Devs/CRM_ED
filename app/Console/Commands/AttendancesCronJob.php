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
        $query = "SELECT agent, MIN(fecha) AS primer_login, MAX(fecha) AS primer_logout
        FROM ocm_log_agentstatus
        WHERE DATE(fecha) = '$fecha_actual'
        AND (estado = 'LOGIN' OR estado = 'LOGOUT')
        GROUP BY agent;";

        $result = mysqli_query($connection, $query);

        if ($result) {
            // Procesar los resultados de la consulta
            while ($row = mysqli_fetch_assoc($result)) {
                // Obtener los valores de cada registro
                $agent = $row['agent'];
                $primer_registro = $row['primer_login'];
                $primer_logout = $row['primer_logout'];

                // Obtener el usuario correspondiente al agente en la tabla users
                $user = User::where('usuario', $agent)->first();
                
                if ($user) {
                    // Separar la fecha y hora del primer registro
                    $fecha_login = date('Y-m-d', strtotime($primer_registro));
                    $hora_login = date('H:i:s', strtotime($primer_registro));

                    // Separar la fecha y hora del primer logout
                    $fecha_logout = date('Y-m-d', strtotime($primer_logout));
                    $hora_logout = date('H:i:s', strtotime($primer_logout));

                    // Verificar si ya existe un registro con la misma fecha actual y agente
                    $existingAttendance = Attendance::where('agente', $agent)
                    ->whereDate('fecha_login', $fecha_login)
                    ->first();

                    if(!$existingAttendance)
                    {
                        // Crear una instancia de Attendance
                        $attendance = new Attendance();
                        $attendance->agente = $agent;
                        $attendance->fecha_login = $fecha_login;
                        $attendance->hora_login = $hora_login;
                        $attendance->fecha_logout = $fecha_logout;
                        $attendance->hora_logout = $hora_logout;

                        // Verificar si el agente existe en la tabla 'users' y asignar el agente_id correspondiente
                        $attendance->agent_id = $user->id;

                        // Obtener la hora de entrada del usuario
                        $hora_entrada_usuario = date('H:i', strtotime($user->hora_entrada));
                        
                        // Calcular la diferencia en minutos entre la hora de entrada de la asistencia y la hora de entrada del usuario
                        $diferencia_minutos = (strtotime($hora_login) - strtotime($hora_entrada_usuario)) / 60;

                        // Validación 1: Asistencia correcta
                        if ($diferencia_minutos >= 0 && $diferencia_minutos <= 14) {
                            $attendance->tipo_asistencia = 'A';
                        }
                        // Validación 2: Retardo
                        elseif ($diferencia_minutos >= 15) {
                            $attendance->tipo_asistencia = 'R';
                        }
                        // Validación 3: No inicio de sesión
                        elseif ($primer_logout === null) {
                            $attendance->tipo_asistencia = 'F';
                        }else{
                            $attendance->tipo_asistencia = "Entro más temprano de lo normal";
                        }

                        $attendance->save();

                        // Mandamos mensaje a la consola del cron job
                        $this->info('Asistencia actualizada para el agente: ' . $agent);
                    }
                }                
            }
        } else {
            // Manejo del error de consulta
            echo "Error al ejecutar la consulta: " . mysqli_error($connection);
        }
    }
}
