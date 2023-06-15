<?php

/**
*Como registro para las consultas se deben de consultar dos tablas para las consultas anidadas
*Estos motores se generan de dos tablas dependiendo cada una de las campañas
*Por lo que para las campañas activas se tendra que consultar cada una de las siguientes tablas
*Las campañas activas son:
*-------Universidad insurgentes-------
*Tablas a consultar ocmb.skill_fb_uimotor_dataextren leads de facebook
*Tablas a consultar ocmb.skill_uimotor_data leads de google
*-------Qualitas-------
*Tablas a consultar ocmb.skill_fb_qualitasmotor_dataexten leads de facebook
*Tablas a consultar ocmb.skill_onl_qualitasmotor_dataexten leads de google
*-------AXA-------
*Tablas a consultar ocmb.skill_fb_axamr_dataexten leads de facebook
*Tablas a consultar ocmb.skill_onl_axamr_data leads de google
*-------PRACTICUM-------
*Este es un caso especial debido a que solo hay campaña de Facebook activa
*Tablas a consultar ocmb.skill_fb_practicummotor_dataexten leads de facebook
*Tablas a consultar ocmb.skill_onl_practicumotor_dataexten leads de google
*/

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use mysqli;

class HomeController extends Controller
{
    private $connection;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // Conexion a la BD
        $databaseName = 'ocmdb';
        $hostName = '172.93.111.251';
        $userName = 'root';
        $passCode = '55R%@$2KqC68';

        $this->connection = mysqli_connect($hostName, $userName, $passCode, $databaseName);

        if (!$this->connection) {
            die("Error al conectarse a la base de datos: " . mysqli_connect_error());
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


//======================================================================
// PRINCIPAL VIEW WITHOUT PARAMS
//======================================================================
    public function index()
    {
        // Se envia la información de las campañas que tenga definidas el usuario
        $id = Auth::user();
        $usuario = User::findOrFail($id->id);
        $campana = Campaign::where('id', $usuario->id_campana)->where('status', 1)->get();
        //Se envía la información de la campaña al día
        $campanas = $this->TraerCampana(NULL);
        $table_FB = isset($campanas['tableFb']) ? $campanas['tableFb'] : NULL;
        $table_GO = isset($campanas['tableGo']) ? $campanas['tableGo'] : NULL;
        $skill_Def_FB = isset($campanas['skillDefFb']) ? $campanas['skillDefFb'] : NULL;
        $skill_Def_GO = isset($campanas['skillDefGo']) ? $campanas['skillDefGo'] : NULL;
        $tipoVenta = isset($campanas['tipoVenta']) ? $campanas['tipoVenta'] : 'COTIZACION';
        $date_inicial = "CURDATE()";
        $date_final  = "CURDATE() + 1";
        $conteo = $this->conteoLeadsTrack4Leads($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final);
        $llamadas = $this->llamadasPrimerContactoTrack4Leads($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final);
        $ventas = $this->cotizacionesPreventasPCTrack4Leads($skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final,$tipoVenta);
        $lYvAYc = $this->LlamadasVentasPorAgenteDeOCM($skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final,$tipoVenta);
        $tipificacion = $this->ResultadosDeContactoEnLlamadas($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final);

        $values = (object)[
            'campana' => '',
            'fecha_inicio' => '',
            'fecha_fin' => ''
        ];
        $total = 0;
        foreach ($tipificacion as $item) { $total += $item['Total']; }
        // dd($total);
        return view('crm.index', compact('conteo','llamadas','ventas','lYvAYc','tipificacion','values','total'));
    }

//======================================================================
// PRINCIPAL VIEW WITH PARAMS
//======================================================================
    public function filter(Request $request)
    {

        $campanas = $this->TraerCampana($request->campana);
        $table_FB = isset($campanas['tableFb']) ? $campanas['tableFb'] : NULL;
        $table_GO = isset($campanas['tableGo']) ? $campanas['tableGo'] : NULL;
        $skill_Def_FB = isset($campanas['skillDefFb']) ? $campanas['skillDefFb'] : NULL;
        $skill_Def_GO = isset($campanas['skillDefGo']) ? $campanas['skillDefGo'] : NULL;
        $tipoVenta = isset($campanas['tipoVenta']) ? $campanas['tipoVenta'] : 'COTIZACION';
        $date_inicial = isset($request->fecha_inicio) ? $request->fecha_inicio : "CURDATE()";
        $date_final  = isset($request->fecha_fin) ? $request->fecha_fin : "CURDATE() + 1";

        // REFACTORY
        $conteo = $this->conteoLeadsTrack4Leads($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final);
        $llamadas = $this->llamadasPrimerContactoTrack4Leads($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final);
        $ventas = $this->cotizacionesPreventasPCTrack4Leads($skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final,$tipoVenta);
        $lYvAYc = $this->LlamadasVentasPorAgenteDeOCM($skill_Def_FB,$skill_Def_GO,$date_inicial,$date_final,$tipoVenta);
        $tipificacion = $this->ResultadosDeContactoEnLlamadas($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final);
        // END REFACTORY

        $values = $request;
        $total = 0;
        foreach ($tipificacion as $item) { $total += $item['Total']; }
        return view('crm.index', compact('conteo','llamadas','ventas','lYvAYc','tipificacion','values','total'));
    }

//======================================================================
// FUNCTIONS WITHOUT PARAMS FROM FACEBOOK AND GOOGLE TO ALL CAMPAINGS
//======================================================================

private function conteoLeadsTrack4Leads($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final)
{
    $date_inicial = (($date_inicial != "CURDATE()")? $this->formatDateStart($date_inicial) : $date_inicial);
    $date_final = (($date_final != "CURDATE() + 1")? $this->formatDateStart($date_final) : $date_final);

    $conteoLeads = "SELECT SUM(CASE skilldef WHEN '".$skill_Def_FB."' THEN 1 ELSE 0 END) as leadsFb ";
    if(isset($skill_Def_GO)){
        $conteoLeads .= ",SUM(CASE skilldef WHEN '".$skill_Def_GO."' THEN 1 ELSE 0 END) as leadsGoogle ";
    }
    $conteoLeads .= "FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                    FROM ocmdb.".$table_FB." d
                    INNER JOIN ocmdb.".$table_FB."exten de ON d.id = de.id
                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                    WHERE d.dateinsert BETWEEN ".$date_inicial." AND ".$date_final."
                    AND de.id_lead <> '' ";
    if(isset($skill_Def_GO)){
        $conteoLeads .="
                    UNION
                    SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                    FROM ocmdb.".$table_GO." d
                    INNER JOIN ocmdb.".$table_GO."exten de ON d.id = de.id
                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                    WHERE d.dateinsert BETWEEN ".$date_inicial." AND ".$date_final."
                    AND de.id_lead <> ''";
    }
    $conteoLeads .=") AS Leads";
    return  $this->followQuery($conteoLeads);



}

private function llamadasPrimerContactoTrack4Leads($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final)
{
    $date_inicial = (($date_inicial != "CURDATE()")? $this->formatDateStart($date_inicial) : $date_inicial);
    $date_final = (($date_final != "CURDATE() + 1")? $this->formatDateStart($date_final) : $date_final);

    $llamadasporCampana = "SELECT skilldata,COUNT(distinct(numbercall)) as TOTAL
                           FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                           FROM ocmdb.ocm_log_calls lc
                           INNER JOIN (
                            SELECT d.number1
                            FROM ocmdb.".$table_FB." d
                            INNER JOIN ocmdb.".$table_FB."exten de ON d.id = de.id
                            INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                            WHERE d.dateinsert BETWEEN ".$date_inicial." AND ".$date_final."
                            AND de.id_lead <> '' ";
    if(isset($skill_Def_GO)){
    $llamadasporCampana .= "UNION
                                SELECT  d.number1 FROM ocmdb.".$table_GO." d
                                INNER JOIN ocmdb.".$table_GO."exten de ON d.id = de.id
                                INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                WHERE d.dateinsert BETWEEN ".$date_inicial." AND ".$date_final." AND de.id_lead <> ''";
    }
    $llamadasporCampana .= ") As d
                            ON lc.numbercall = d.number1
                            AND skilldata  IN ('".$skill_Def_FB."'";
    if(isset($skill_Def_GO)){
    $llamadasporCampana .=",'".$skill_Def_GO."'";
    }
    $llamadasporCampana .=") AND fecha BETWEEN ".$date_inicial." AND ".$date_final."   AND attempt = 1
                               ORDER BY fecha DESC) l
                               WHERE row_numb = 1
                               AND agent <> ''
                               GROUP BY skilldata";

    return $this->followQuery($llamadasporCampana);
}

private function cotizacionesPreventasPCTrack4Leads($skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final,$tipodeventa)
{

    if(!isset($skill_Def_GO)){ $skill_Def_GO = ''; }
    $date_inicial = (($date_inicial != "CURDATE()")? $this->formatDateStart($date_inicial) : $date_inicial);
    $date_final = (($date_final != "CURDATE() + 1")? $this->formatDateStart($date_final) : $date_final);
    $ventasPorCampana = "SELECT  tipos.tipoLlamadas, COALESCE(log.Total, 0) AS Total
        FROM ( SELECT 'VentasFb' AS tipoLlamadas UNION ALL SELECT 'VentasGoogle' ) AS tipos
        LEFT JOIN (
            SELECT
                CASE WHEN skilldata = '".$skill_Def_FB."' THEN 'VentasFb' ELSE 'VentasGoogle' END AS tipoLlamadas,
                COUNT(DISTINCT(numbercall)) AS Total
            FROM ocmdb.ocm_log_calls
            WHERE skilldata IN ('".$skill_Def_FB."'
            ,'".$skill_Def_GO."'
            ) AND fecha BETWEEN ".$date_inicial." AND ".$date_final."
            AND resultdesc = '".$tipodeventa."'
            GROUP BY skilldata
        ) AS log
        ON tipos.tipoLlamadas = log.tipoLlamadas;";
    return $this->followQuery($ventasPorCampana);
}

private function LlamadasVentasPorAgenteDeOCM($skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final,$tipodeventa)
{
    if(isset($skill_Def_GO)){ $skill_Def_GO = ''; }
    $date_inicial = (($date_inicial != "CURDATE()")? $this->formatDateStart($date_inicial) : $date_inicial);
    $date_final = (($date_final != "CURDATE() + 1")? $this->formatDateStart($date_final) : $date_final);
    $resultadosAgents ="SELECT lc.agent, a.nombre,
    COUNT( lc.resultdesc ) AS totalLlamadas, cpg.primerContacto,(CASE WHEN va.total > 0 THEN va.total ELSE 0 END) As ventas,
    ROUND(( ((CASE WHEN va.total > 0 THEN va.total ELSE 0 END)/cpg.primerContacto) * 100  ), 1) AS Ratio
    FROM ocmdb.ocm_log_calls lc
    INNER JOIN ocmdb.ocm_agent a
        ON lc.agent = a.user
    INNER JOIN (
        SELECT l.agent,COUNT(distinct(numbercall)) as primerContacto
        FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                            FROM ocmdb.ocm_log_calls
                            WHERE skilldata  IN ('".$skill_Def_FB."', '".$skill_Def_GO."')
                    AND fecha BETWEEN ".$date_inicial." AND ".$date_final."
                    AND attempt = 1
                    AND timecall > 5
                ORDER BY fecha DESC) l
        WHERE row_numb = 1
            AND agent <> ''
        GROUP BY l.agent
    ) AS cpg
    ON lc.agent = cpg.agent
    LEFT JOIN (
        SELECT agent,count(distinct(numbercall)) total
        FROM ocmdb.ocm_log_calls
        WHERE skilldata  IN ('".$skill_Def_FB."', '".$skill_Def_GO."')
        AND resultdesc = '".$tipodeventa."'
        AND fecha BETWEEN ".$date_inicial." AND ".$date_final."
        GROUP BY agent) As va
    ON lc.agent = va.agent
    WHERE lc.skilldata  IN ('".$skill_Def_FB."', '".$skill_Def_GO."')
        AND lc.fecha BETWEEN ".$date_inicial." AND ".$date_final."
        AND lc.agent <> ''
        AND lc.timecall > 5
        GROUP BY lc.agent";


    return $resultAgent =  $this->followQuery($resultadosAgents);
}

private function ResultadosDeContactoEnLlamadas($table_FB,$table_GO,$skill_Def_FB,$skill_Def_GO,$date_inicial, $date_final)
{
    $date_inicial = (($date_inicial != "CURDATE()")? $this->formatDateStart($date_inicial) : $date_inicial);
    $date_final = (($date_final != "CURDATE() + 1")? $this->formatDateStart($date_final) : $date_final);
    $resultadosContacto = "SELECT l.resultdesc AS resultadoUC,COUNT(distinct(numbercall)) as Total
                            FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                            FROM ocmdb.ocm_log_calls lc
                            INNER JOIN (
                                SELECT d.number1
                                FROM ocmdb.".$table_FB." d INNER JOIN ocmdb.".$table_FB."exten de ON d.id = de.id
                                INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                WHERE d.dateinsert BETWEEN ".$date_inicial." AND ".$date_final." ";
                                if(isset($skill_Def_GO)){
                                    $resultadosContacto .= "UNION
                                    SELECT  d.number1 FROM ocmdb.".$table_GO." d
                                    INNER JOIN ocmdb.".$table_GO."exten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN ".$date_inicial." AND ".$date_final."";
                                }
                                $resultadosContacto .= ") As d
                                ON lc.numbercall = d.number1 AND skilldata  IN ('".$skill_Def_FB."'";
                                if(isset($skill_Def_GO)){
                                    $resultadosContacto .=",'".$skill_Def_GO."'";
                                }
                                    $resultadosContacto .= "
                                ) AND fecha BETWEEN ".$date_inicial." AND ".$date_final."
                                ORDER BY fecha DESC) l
                                WHERE row_numb = 1
                                AND agent <> ''
                                GROUP BY l.resultdesc
                                ORDER BY Total DESC";
    return $this->followQuery($resultadosContacto);
}

//======================================================================
// FUNCTION FORMATS AND MIN CODE
//======================================================================
    // SE CREA FUNCION PARA CORRER QUERYS DE MANERA GENERAL
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
    //SE CREA FUNCION PARA FORMATEAR FECHA DE INICIO Y-m-d H:i:s
    private function formatDateStart($date)
    {
        if($date){
            $date = "'".Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->format('Y-m-d H:i:s')."'";
        }else{
            $date = 'CURDATE()';
        }
        return $date;
    }
    //SE CREA FUNCION PARA FORMATEAR FECHA DE END Y-m-d H:i:s o
    private function formatDateEnd($date)
    {
        if($date){
            $date = "'".Carbon::createFromFormat('Y-m-d', $date)->endOfDay()->format('Y-m-d H:i:s')."'";
        }else{
            $date = 'CURDATE() + 1';
        }
        return $date;
    }
    //SE CREA FUNCION PARA TRAER LAS VARIABLES DE LOS MOTRES ASI COMO LAS TABLAS
    private function TraerCampana($campana)
    {
        $campanas = array(
            'UNI' => array(
                'tableFb' => 'skill_fb_uimotor_data',
                'tableGo' => 'skill_uimotor_data',
                'skillDefFb' => 'fb_uimotor',
                'skillDefGo' => 'uimotor',
                'tipoVenta' => 'COTIZACION'
            ),
            'QUA' => array(
                'tableFb' => 'skill_fb_qualitasmotor_data',
                'tableGo' => 'skill_onl_qualitasmotor_data',
                'skillDefFb' => 'fb_qualitasmotor',
                'skillDefGo' => 'ONL_QUALITASMotor',
                'tipoVenta' => 'VENTA'
            ),
            'AXA' => array(
                'tableFb' => 'skill_fb_axamr_data',
                'tableGo' => 'skill_onl_axamr_data',
                'skillDefFb' => 'fb_axamr',
                'skillDefGo' => 'onl_axamr',
                'tipoVenta' => 'PREVENTA'
            ),
            'PRA' => array(
                'tableFb' => 'skill_fb_practicummotor_data',
                'tableGo' => 'skill_uimotor_data',
                'skillDefFb' => 'FB_PRACTICUMMotor',
                'tipoVenta' => 'PREVENTA'
            )
        );

        if (isset($campanas[$campana])) {
            return $campanas[$campana];
        } else {
            return array(
                'tableFb' => 'skill_fb_uimotor_data',
                'tableGo' => 'skill_uimotor_data',
                'skillDefFb' => 'fb_uimotor',
                'skillDefGo' => 'uimotor',
                'tipoVenta' => 'COTIZACION'
            );
        }
    }







}
