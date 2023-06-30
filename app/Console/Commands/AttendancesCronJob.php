<?php

namespace App\Console\Commands;

use DateTime;
use DatePeriod;
use DateInterval;
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
        // Define las credenciales y la base de datos de la conexión
        $databaseName = 'ocmdb';
        $hostName = '172.93.111.251';
        $userName = 'root';
        $passCode = '55R%@$2KqC68';

        // Establece la conexión con la base de datos
        $connection = mysqli_connect($hostName, $userName, $passCode, $databaseName);

        // Comprueba si la conexión se estableció correctamente
        if (!$connection) {
            die("Error al conectarse a la base de datos: " . mysqli_connect_error());
        }

        // Define el rango de fechas para el que se procesarán los datos
        $fechaInicio = new DateTime('2023-06-01');
        $fechaFin = new DateTime('2023-06-23');

        // Define un intervalo de un día y crea un periodo que incluye cada día en el rango de fechas
        $intervalo = new DateInterval('P1D');
        $periodo = new DatePeriod($fechaInicio, $intervalo, $fechaFin);

        // Obtiene todos los usuarios que tienen una hora de entrada y salida definida
        $usuarios = User::whereNotNull('hora_entrada')->whereNotNull('hora_salida')->get();

        // Itera sobre cada día en el rango de fechas
        foreach ($periodo as $fecha) {
            $fecha_formato = $fecha->format('Y-m-d');

            // Obtiene el número de día de la semana (0 = Domingo, 1 = Lunes, ..., 6 = Sábado)
            $dia_semana = $fecha->format('w');

            // Itera sobre cada usuario
            foreach ($usuarios as $usuario) {
                // Si el día es domingo, se crea un registro de asistencia con 'Sin Datos' y se salta al siguiente usuario
                if ($dia_semana == 0) {
                    $sinDatos = new Attendance();
                    $sinDatos->agente = $usuario->usuario;
                    $sinDatos->fecha_login = $fecha_formato;
                    $sinDatos->agent_id = $usuario->id;
                    $sinDatos->tipo_asistencia = 'D';
                    $sinDatos->save();
                    continue;
                }

                // Prepara una consulta para obtener todos los inicios y cierres de sesión del usuario en el día actual
                $query = "
                    SELECT agent, fecha AS timestamp, estado
                    FROM ocm_log_agentstatus
                    WHERE DATE(fecha) = '$fecha_formato'
                    AND agent = '{$usuario->usuario}'
                    AND (estado = 'LOGIN' OR estado = 'LOGOUT')
                    ORDER BY fecha;
                ";

                // Ejecuta la consulta
                $result = mysqli_query($connection, $query);

                // Comprueba si la consulta se ejecutó correctamente
                if ($result) {
                    // Recupera la primera fila de la consulta
                    $row = mysqli_fetch_assoc($result);

                    // Si el usuario no ha iniciado sesión, marca una falta
                    if ($row == null) {
                        $falta = new Attendance();
                        $falta->agente = $usuario->usuario;
                        $falta->fecha_login = $fecha_formato;
                        $falta->agent_id = $usuario->id;
                        $falta->tipo_asistencia = 'F';
                        $falta->save();
                    } else {
                        $firstEntry = true; // Variable para almacenar si se trata de la primera entrada del día
                        $asistencia = new Attendance();
                        $asistencia->agente = $usuario->usuario;
                        $asistencia->agent_id = $usuario->id;

                        do {
                            $estado = $row['estado'];
                            $timestamp = $row['timestamp'];

                            $fecha = date('Y-m-d', strtotime($timestamp));
                            $hora = date('H:i:s', strtotime($timestamp));

                            if ($estado == 'LOGIN' && $firstEntry) {
                                $asistencia->fecha_login = $fecha;
                                $asistencia->hora_login = $hora;
                                $hora_entrada_usuario = date('H:i', strtotime($usuario->hora_entrada));
                                $diferencia_minutos = (strtotime($hora) - strtotime($hora_entrada_usuario)) / 60;

                                if ($diferencia_minutos >= 0 && $diferencia_minutos <= 14) {
                                    $asistencia->tipo_asistencia = 'A';
                                } elseif ($diferencia_minutos >= 15) {
                                    $asistencia->tipo_asistencia = 'R';
                                } else {
                                    $asistencia->tipo_asistencia = "A+";
                                }
                                $firstEntry = false;
                            } else if ($estado == 'LOGOUT') {
                                $asistencia->fecha_logout = $fecha;
                                $asistencia->hora_logout = $hora;
                            }
                        } while ($row = mysqli_fetch_assoc($result));

                        $asistencia->save();
                    }
                } else {
                    echo "Error al ejecutar la consulta: " . mysqli_error($connection);
                }
            }
        }
    }
}
