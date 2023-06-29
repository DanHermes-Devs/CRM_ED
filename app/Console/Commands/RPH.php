<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Education;
use App\Mail\RphEducacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class RPH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:rph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Conexion a la BD
        $databaseName = 'ocmdb';
        $hostName = '172.93.111.251';
        $userName = 'root';
        $passCode = '55R%@$2KqC68';

        $this->connection = mysqli_connect($hostName, $userName, $passCode, $databaseName);

        if (!$this->connection) {
            die("Error al conectarse a la base de datos: " . mysqli_connect_error());
        }

         // Conexion a la BD CRM
         $databaseNameCrm = 'crm';
         $hostNameCrm = '45.58.126.11';
         $userNameCrm = 'apiuser';
         $passCodeCrm = 'ln&S1qm8M2!7';

         $this->connectionCrm = mysqli_connect($hostNameCrm, $userNameCrm, $passCodeCrm, $databaseNameCrm);

         if (!$this->connectionCrm) {
             die("Error al conectarse a la base de datos: " . mysqli_connect_error());
         }

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $datosReporte = $this->obtenerOrigenDatos('uin');
        $leads = $this->calculoLead($datosReporte);
        $llamadas = $this->calculoLlamadas($datosReporte);
        $preventas = $this->calculoPreventas($datosReporte);
        $ventas = $this->calculoVentas($datosReporte);

        $processedData = [
            'leads'=>$leads[0],
            'llamadas'=>$llamadas[0],
            'preventas'=>$preventas[0],
            'ventas'=>$ventas[0],
            'keys'=>array_keys($leads[0])
        ];

        //dd($processedData);
        if (!empty($processedData)) {
            Mail::to(['scamano@exponentedigital.mx', 'operaciones@exponentedigital.mx', 'comercial@exponentedigital.mx', 'tecnologia@exponentedigital.mx', 'calidad@exponentedigital.mx', 'sperez@exponentedigital.mx', 'dreyes@exponentedigital.mx'])
                ->send(new RphEducacion($processedData));
        }




    }
    //Función para obtener número de leads por hora
    private function calculoLead($datosProyecto)
    {

        //Aquí obtendré los leads de cada proyecto
        $fechaActual = Carbon::today()->format('Y-m-d');
        $fechaInicio = $fechaActual.' 00:00:00';
        $fechaFin = $fechaActual.' 23:59:59';
        $horaInicial = $datosProyecto['horaInicio'];
        $horaFinal = $datosProyecto['horaFin'];
        $conteoLeads = "SELECT IFNULL(SUM(CASE WHEN dateinsert BETWEEN '".$fechaActual." 00:00:00' AND '".$fechaActual." ".($horaInicial-1).":59:59' THEN 1 ELSE 0 END),0) 'FH'";
        while ($horaInicial <= $horaFinal) {
            $conteoLeads.= ",IFNULL(SUM(CASE WHEN dateinsert BETWEEN '".$fechaActual." ".$horaInicial.":00:00' AND '".$fechaActual." ".$horaInicial.":59:59' THEN 1 ELSE 0 END),0) '".$horaInicial."'";
            $horaInicial  = $horaInicial + 1;
        }

        $conteoLeads.= " FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                        FROM ocmdb.".$datosProyecto['skillDataFb']." d
                        INNER JOIN ocmdb.".$datosProyecto['skillDataFb']."exten de ON d.id = de.id
                        INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                        WHERE d.dateinsert BETWEEN '".$fechaInicio."' AND '".$fechaFin."'
                        AND de.id_lead <> '' ";
        if(isset($datosProyecto['skillDefGo'])){
            $conteoLeads .="
                        UNION
                        SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                        FROM ocmdb.".$datosProyecto['skillDataGo']." d
                        INNER JOIN ocmdb.".$datosProyecto['skillDataGo']."exten de ON d.id = de.id
                        INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                        WHERE d.dateinsert BETWEEN '".$fechaInicio."' AND '".$fechaFin."'
                        AND de.id_lead <> ''";
        }
        $conteoLeads .=") AS Leads";
        return  $this->followQuery($conteoLeads);


    }
    //Función para calcular las llamadas por hora
    private function calculoLlamadas($datosProyecto)
    {

        //Aquí obtendré los leads de cada proyecto
        $fechaActual = Carbon::today()->format('Y-m-d');
        $fechaInicio = $fechaActual.' 00:00:00';
        $fechaFin = $fechaActual.' 23:59:59';
        $horaInicial = $datosProyecto['horaInicio'];
        $horaFinal = $datosProyecto['horaFin'];


        $llamadasporCampana = "SELECT IFNULL(SUM(CASE WHEN fecha BETWEEN '".$fechaActual." 00:00:00' AND '".$fechaActual." ".($horaInicial-1).":59:59' THEN 1 ELSE 0 END),0) 'FH'";
        while ($horaInicial <= $horaFinal) {
            $llamadasporCampana.= ",IFNULL(SUM(CASE WHEN fecha BETWEEN '".$fechaActual." ".$horaInicial.":00:00' AND '".$fechaActual." ".$horaInicial.":59:59' THEN 1 ELSE 0 END),0) '".$horaInicial."'";
            $horaInicial  = $horaInicial + 1;
        }
        $llamadasporCampana.= "FROM (SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                               FROM ocmdb.ocm_log_calls lc
                               INNER JOIN (
                                SELECT d.number1
                                FROM ocmdb.".$datosProyecto['skillDataFb']." d
                                INNER JOIN ocmdb.".$datosProyecto['skillDataFb']."exten de ON d.id = de.id

                                WHERE d.dateinsert BETWEEN '".$fechaInicio."' AND '".$fechaFin."'
                                AND de.id_lead <> '' ";
        if(isset($datosProyecto['skillDefGo'])){
        $llamadasporCampana .= "UNION
                                    SELECT  d.number1 FROM ocmdb.".$datosProyecto['skillDataGo']." d
                                    INNER JOIN ocmdb.".$datosProyecto['skillDataGo']."exten de ON d.id = de.id
                                    WHERE d.dateinsert BETWEEN '".$fechaInicio."' AND '".$fechaFin."' AND de.id_lead <> ''";
        }
        $llamadasporCampana .= ") As d
                                ON lc.numbercall = d.number1
                                AND skilldata  IN ('".$datosProyecto['skillDefFb']."'";
        if(isset($datosProyecto['skillDefGo'])){
        $llamadasporCampana .=",'".$datosProyecto['skillDefGo']."'";
        }
        $llamadasporCampana .=") AND fecha BETWEEN '".$fechaInicio."' AND '".$fechaFin."'   AND attempt = 1
                                   ORDER BY fecha DESC) l
                                   WHERE row_numb = 1
                                   AND agent <> ''";

        return $this->followQuery($llamadasporCampana);
    }
    //Función para obtener número de leads por hora
    private function calculoPreventas($datosProyecto)
    {

        //Aquí obtendré los leads de cada proyecto
        $fechaActual = Carbon::today()->format('Y-m-d');
        $fechaInicio = $fechaActual.' 00:00:00';
        $fechaFin = $fechaActual.' 23:59:59';
        $horaInicial = $datosProyecto['horaInicio'];
        $horaFinal = $datosProyecto['horaFin'];
        $calificacionPreventa = $datosProyecto['calificacionPreventa'];

        $conteoPreventas = "SELECT IFNULL(SUM(CASE WHEN fecha BETWEEN '".$fechaActual." 00:00:00' AND '".$fechaActual." ".($horaInicial-1).":59:59' THEN 1 ELSE 0 END),0) 'FH'";
        while ($horaInicial <= $horaFinal) {
            $conteoPreventas.= ",IFNULL(SUM(CASE WHEN fecha BETWEEN '".$fechaActual." ".$horaInicial.":00:00' AND '".$fechaActual." ".$horaInicial.":59:59' THEN 1 ELSE 0 END),0) '".$horaInicial."'";
            $horaInicial  = $horaInicial + 1;
        }
        $conteoPreventas.= "FROM (SELECT DISTINCT(numbercall) AS numbercall, fecha,resultdesc
                                FROM ocmdb.ocm_log_calls
                                WHERE skilldata IN ('".$datosProyecto['skillDefFb']."','".$datosProyecto['skillDefGo']."')
                                AND fecha BETWEEN '".$fechaInicio."' AND '".$fechaFin."'
                                AND resultdesc = '".$calificacionPreventa."'
                                GROUP BY numbercall
                            ) AS l";

        return  $this->followQuery($conteoPreventas);


    }
    //Función para obtener número de leads por hora
    private function calculoVentas($datosProyecto)
    {

        //Aquí obtendré los leads de cada proyecto
        $fechaActual = Carbon::today()->format('Y-m-d');
        $fechaInicio = $fechaActual.' 00:00:00';
        $fechaFin = $fechaActual.' 23:59:59';
        $horaInicial = $datosProyecto['horaInicio'];
        $horaFinal = $datosProyecto['horaFin'];

        $conteoVentas = "SELECT IFNULL(SUM(CASE WHEN date_cobranza BETWEEN '".$fechaActual." 00:00:00' AND '".$fechaActual." ".($horaInicial-1).":59:59' THEN 1 ELSE 0 END),0) 'FH'";
        while ($horaInicial <= $horaFinal) {
            $conteoVentas.= ",IFNULL(SUM(CASE WHEN date_cobranza BETWEEN '".$fechaActual." ".$horaInicial.":00:00' AND '".$fechaActual." ".$horaInicial.":59:59' THEN 1 ELSE 0 END),0) '".$horaInicial."'";
            $horaInicial  = $horaInicial + 1;
        }

        $conteoVentas.= " FROM crm.".$datosProyecto['tablaVentasCrm']."
                        WHERE codification = 'COBRADA'
                        AND date_cobranza BETWEEN '".$fechaInicio."' AND '".$fechaFin."'";

        return  $this->followQueryCrm($conteoVentas);


    }
    //Parámetros de proyecto, se puede reemplazar por una tabla en db
    private function obtenerOrigenDatos($proyecto)
    {
        switch ($proyecto) {
            case 'qualitas':
                $datosProyecto = [
                    'skillDataFb' => 'skill_fb_qualitasmotor_data',
                    'skillDataGo' => 'skill_onl_qualitasmotor_data',
                    'skillDefFb' => 'fb_qualitasmotor',
                    'skillDefGo' => 'ONL_QUALITASMotor',
                    'calificacionPreventa' => 'PREVENTA',
                    'tablaVentasCrm' => 'ventas',
                    'horaInicio'=>'8',
                    'horaFin'=>'22'
                ];
            break;
            case 'uin':
                $datosProyecto = [
                    'skillDataFb' => 'skill_fb_uimotor_data',
                    'skillDataGo' => 'skill_uimotor_data',
                    'skillDefFb' => 'fb_uimotor',
                    'skillDefGo' => 'uimotor',
                    'calificacionPreventa' => 'COTIZACION',
                    'tablaVentasCrm' => 'education',
                    'horaInicio'=>'8',
                    'horaFin'=>'22'
                ];
            break;
            case 'practicum':
                $datosProyecto = [
                    'skillDataFb' => 'skill_fb_practicummotor_data',
                    'skillDataGo' => '',
                    'skillDefFb' => 'FB_PRACTICUMMotor',
                    'skillDefGo' => '',
                    'calificacionPreventa' => '',
                    'tablaVentasCrm' => 'education',
                    'horaInicio'=>'8',
                    'horaFin'=>'22'
                ];
            break;
            case 'axa':
                $datosProyecto = [
                    'skillDataFb' => 'skill_fb_axamr_data',
                    'skillDataGo' => 'skill_onl_axamr_data',
                    'skillDefFb' => 'fb_axamr',
                    'skillDefGo' => 'onl_axamr',
                    'calificacionPreventa' => 'PREVENTA',
                    'tablaVentasCrm' => 'ventas',
                    'horaInicio'=>'8',
                    'horaFin'=>'22'
                ];
            default:
                $datosProyecto = [
                    'skillDataFb' => '',
                    'skillDataGo' => '',
                    'skillDefFb' => '',
                    'skillDefGo' => '',
                    'calificacionVenta' => '',
                    'tablaVentasCrm' => '',
                    'horaInicio'=>'',
                    'horaFin'=>''
                ];
        }

        return $datosProyecto;
    }
    //Función para ejecutar las consultas
    private function followQuery($query)
    {
        $result = mysqli_query($this->connection, $query);
        if ($result) {
            $arrayResult = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $arrayResult[] = $row;
            }

            return $arrayResult;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }

    private function followQueryCrm($query)
    {
        $result = mysqli_query($this->connectionCrm, $query);
        if ($result) {
            $arrayResult = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $arrayResult[] = $row;
            }

            return $arrayResult;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connectionCrm);
        }
    }
}
