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
        $conteo = $this->conteoLeadsInicial();
        $llamadas = $this->llamadasPorCampana();
        $ventas = $this->VentasPorCampana();
        $lYvAYc = $this->llamadasYventasAgenteYcampana();
        $tipificacion = $this->ResultadosContacto();
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
        switch ($request->campana) {
            case 'UNI': // MOTOR DE UNIVERSIDAD INSURGENTES
                $tableFb = 'skill_fb_uimotor_data';
                $tableGo = 'skill_uimotor_data';
                $skillDefFb = 'fb_uimotor';
                $skillDefGo = 'uimotor';
            break;
            case 'QUA': // MOTOR DE QUALITAS
                $tableFb = 'skill_fb_qualitasmotor_data';
                $tableGo = 'skill_onl_qualitasmotor_data';
                $skillDefFb = 'fb_qualitasmotor';
                $skillDefGo = 'ONL_QUALITASMotor';
            break;
            case 'AXA': // MOTOR DE AXA
                $tableFb = 'skill_fb_axamr_data';
                $tableGo = 'skill_onl_axamr_data';
                $skillDefFb = 'fb_axamr';
                $skillDefGo = 'onl_axamr';
            break;
            case 'PRA': // MOTOR DE PRACTICUM
                $tableFb = 'skill_fb_practicummotor_data';
                $tableGo = 'skill_uimotor_data';
                $skillDefFb = 'FB_PRACTICUMMotor';
            break;
            default:
                $tableFb = 'skill_fb_uimotor_data';
                $tableGo = 'skill_uimotor_data';
                $skillDefFb = 'fb_uimotor';
                $skillDefGo = 'uimotor';
                break;
        }
        if($request->campana != 'PRA'){
            $conteo = $this->conteoLeadsXcamp($tableFb,$tableGo,$skillDefFb,$skillDefGo,$request->fecha_inicio,$request->fecha_fin);
            $llamadas = $this->llamadasPorCampanaXcamp($tableFb,$tableGo,$skillDefFb,$skillDefGo,$request->fecha_inicio,$request->fecha_fin);
            $ventas = $this->VentasPorCampanaXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $request->fecha_inicio,$request->fecha_fin);
            $lYvAYc = $this->llamadasYventasAgenteYcampanaXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $request->fecha_inicio,$request->fecha_fin);
            $tipificacion = $this->ResultadosContactoXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $request->fecha_inicio,$request->fecha_fin);
        }else{
            $conteo = $this->clXcampPRA($tableFb,$skillDefFb,$request->fecha_inicio,$request->fecha_fin);
            $llamadas = $this->lpcXcampPRA($tableFb,$skillDefFb,$request->fecha_inicio,$request->fecha_fin);
            $ventas = $this->vpcXcampPRA($tableFb, $skillDefFb,$request->fecha_inicio,$request->fecha_fin);
            $lYvAYc = $this->lvaycXcampPRA($tableFb, $skillDefFb, $request->fecha_inicio,$request->fecha_fin);
            $tipificacion = $this->rcXcampPRA($tableFb, $skillDefFb, $request->fecha_inicio,$request->fecha_fin);
        }
        $values = $request;
        $total = 0;
        foreach ($tipificacion as $item) { $total += $item['Total']; }
        return view('crm.index', compact('conteo','llamadas','ventas','lYvAYc','tipificacion','values','total'));
    }

//======================================================================
// PRINCIPAL FUNCTIONS WITHOUT PARAMS FROM FACEBOOK AND GOOGLE
//======================================================================
    // Function to retrieve the intial lead count
    //CHECK WITH THE NEW QUERY
    private function conteoLeadsInicial()
    {
        $conteoLeads = "SELECT
                            SUM(CASE skilldef WHEN 'fb_uimotor' THEN 1 ELSE 0 END) as leadsFb,
                            SUM(CASE skilldef WHEN 'uimotor' THEN 1 ELSE 0 END) as leadsGoogle
                        FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                                    FROM ocmdb.skill_fb_uimotor_data d
                                    INNER JOIN ocmdb.skill_fb_uimotor_dataexten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                                    AND de.id_lead <> ''
                                UNION
                                SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                            FROM ocmdb.skill_uimotor_data d
                            INNER JOIN ocmdb.skill_uimotor_dataexten de ON d.id = de.id
                            INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                        WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1 AND de.id_lead <> '') AS Leads";
        return $resultConte =  $this->followQuery($conteoLeads);
    }

    // function to get the call count
    //CHECK WITH THE NEW QUERY
    private function llamadasPorCampana()
    {
        $llamadasporCampana = "SELECT skilldata,COUNT(distinct(numbercall)) as TOTAL
                               FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                               FROM ocmdb.ocm_log_calls lc
                               INNER JOIN (
                                    SELECT d.number1
                                    FROM ocmdb.skill_fb_uimotor_data d INNER JOIN ocmdb.skill_fb_uimotor_dataexten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                                    AND de.id_lead <> ''
                                    UNION
                                    SELECT  d.number1 FROM ocmdb.skill_uimotor_data d
                                    INNER JOIN ocmdb.skill_uimotor_dataexten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1 AND de.id_lead <> ''
                               ) As d
                               ON lc.numbercall = d.number1 AND skilldata  IN ('fb_uimotor','uimotor')
                               AND fecha BETWEEN CURDATE() AND CURDATE() + 1   AND attempt = 1
                               ORDER BY fecha DESC) l
                               WHERE row_numb = 1
                               AND agent <> ''
                               GROUP BY skilldata";


        return $resultCalls =  $this->followQuery($llamadasporCampana);
    }

    // Function to bring insurgentes university quotes
    //CHECK WITH THE NEW QUERY
    private function VentasPorCampana()
    {
        $ventasPorCampana = "SELECT  tipos.tipoLlamadas, COALESCE(log.Total, 0) AS Total
                            FROM ( SELECT 'VentasFb' AS tipoLlamadas UNION ALL SELECT 'VentasGoogle' ) AS tipos
                            LEFT JOIN (
                                SELECT
                                    CASE WHEN skilldata = 'FB_UIMotor' THEN 'VentasFb' ELSE 'VentasGoogle' END AS tipoLlamadas,
                                    COUNT(DISTINCT(numbercall)) AS Total
                                FROM ocmdb.ocm_log_calls
                                WHERE skilldata IN ('fb_uimotor', 'UIMotor')
                                AND fecha BETWEEN CURDATE() AND CURDATE() +1
                                AND resultdesc = 'COTIZACION'
                                GROUP BY skilldata
                            ) AS log
                            ON tipos.tipoLlamadas = log.tipoLlamadas;";
        return $resultSell = $this->followQuery($ventasPorCampana);
    }

    // Function to bring results from agents
    //CHECK WITH THE NEW QUERY
    private function llamadasYventasAgenteYcampana()
    {

        $ventasAgentes ="SELECT lc.agent, a.nombre,
        COUNT( lc.resultdesc ) AS totalLlamadas, cpg.primerContacto,(CASE WHEN va.total > 0 THEN va.total ELSE 0 END) As ventas,
        ROUND(( ((CASE WHEN va.total > 0 THEN va.total ELSE 0 END)/cpg.primerContacto) * 100  ), 1) AS Ratio
        FROM ocmdb.ocm_log_calls lc
        INNER JOIN ocmdb.ocm_agent a
            ON lc.agent = a.user
        INNER JOIN (
            SELECT l.agent,COUNT(distinct(numbercall)) as primerContacto
            FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                                FROM ocmdb.ocm_log_calls
                                WHERE skilldata  IN ('FB_UIMotor','UIMotor')
                        AND fecha BETWEEN CURDATE() AND CURDATE() + 1
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
            WHERE skilldata  IN ('FB_UIMotor','UIMotor')
            AND resultdesc = 'COTIZACION'
            AND fecha BETWEEN CURDATE() AND CURDATE() + 1
            GROUP BY agent) As va
        ON lc.agent = va.agent
        WHERE lc.skilldata  IN ('FB_UIMotor','UIMotor')
            AND lc.fecha BETWEEN  CURDATE() AND CURDATE() + 1
            AND lc.agent <> ''
            AND lc.timecall > 5
            GROUP BY lc.agent";

        return $resultAgents = $this->followQuery($ventasAgentes);
    }

    // Function to bring results in calls
    private function ResultadosContacto()
    {
        $resultadosContacto = "SELECT resultadoUC,COUNT(*) As Total FROM
                    ((SELECT  d.dateinsert,lc.numbercall,lc.skill, lc.fecha fechaUC,lc.agent agenteUC,lc.resultdesc resultadoUC,d.number1 telefono
                    FROM ocmdb.ocm_log_calls lc
                    INNER JOIN ocmdb.skill_uimotor_data d ON lc.numbercall = d.number1
                    INNER JOIN ocmdb.skill_uimotor_dataexten de ON d.id = de.id
                    WHERE lc.idlog_calls IN (SELECT MAX(lc.idlog_calls)
                FROM ocmdb.ocm_log_calls lc
                INNER JOIN ocmdb.skill_uimotor_data d ON lc.numbercall = d.number1
                INNER JOIN ocmdb.skill_uimotor_dataexten de ON d.id = de.id
                WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1 AND lc.skilldata IN ('FB_UIMotor','UIMotor')
                AND de.id_lead <> '' GROUP BY lc.idreg )
                AND d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                ORDER BY d.dateinsert DESC)
                UNION
                    (SELECT  d.dateinsert,lc.numbercall,lc.skill, lc.fecha fechaUC,lc.agent agenteUC,lc.resultdesc resultadoUC,d.number1 telefono
                    FROM ocmdb.ocm_log_calls lc
                    INNER JOIN ocmdb.skill_fb_uimotor_data d ON lc.idreg = d.id
                    INNER JOIN ocmdb.skill_fb_uimotor_dataexten de ON d.id = de.id
                    WHERE lc.idlog_calls IN (SELECT  MAX(lc.idlog_calls)
                    FROM ocmdb.ocm_log_calls lc
                    INNER JOIN ocmdb.skill_fb_uimotor_data d ON lc.numbercall = d.number1
                    INNER JOIN ocmdb.skill_fb_uimotor_dataexten de ON d.id = de.id
                                WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                                    AND lc.skilldata = 'fb_uimotor'
                                    AND de.id_lead <> ''
                                GROUP BY lc.idreg )
                AND d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                ORDER BY d.dateinsert DESC)) l
        GROUP BY resultadoUC ORDER BY Total DESC";
        return $resultContact = $this->followQuery($resultadosContacto);
    }

//======================================================================
// PRINCIPAL FUNCTIONS WITH PARAMS FROM FACEBOOK AND GOOGLE
//======================================================================
    // Function to retrieve the intial lead count with params
    // CHECK WITH DANY
    private function conteoLeadsXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        $conteoLeads = "SELECT
                            SUM(CASE skilldef WHEN '".$skillDefFb."' THEN 1 ELSE 0 END) as leadsFb,
                            SUM(CASE skilldef WHEN '".$skillDefGo."' THEN 1 ELSE 0 END) as leadsGoogle
                        FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                        FROM ocmdb.".$tableFb." d
                        INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                        INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                        WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                        AND de.id_lead <> '' UNION
                            SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                            FROM ocmdb.".$tableGo." d
                            INNER JOIN ocmdb.".$tableGo."exten de ON d.id = de.id
                            INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                        WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd." AND de.id_lead <> '') AS Leads";
                        // dd($conteoLeads);
        return $resultConte =  $this->followQuery($conteoLeads);

    }

    // FUNCTION TO OBTAIN THE CALL'S COUNT WITH PARAMS
    //CHECK WITH DANY
    private function llamadasPorCampanaXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        $llamadasporCampana = "SELECT skilldata,COUNT(distinct(numbercall)) as TOTAL
                               FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                               FROM ocmdb.ocm_log_calls lc
                               INNER JOIN (
                                    SELECT d.number1
                                    FROM ocmdb.".$tableFb." d INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                                    AND de.id_lead <> ''
                                    UNION
                                    SELECT  d.number1 FROM ocmdb.".$tableGo." d
                                    INNER JOIN ocmdb.".$tableGo."exten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd." AND de.id_lead <> ''
                               ) As d
                               ON lc.numbercall = d.number1 AND skilldata  IN ('".$skillDefFb."','".$skillDefGo."')
                               AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."   AND attempt = 1
                               ORDER BY fecha DESC) l
                               WHERE row_numb = 1
                               AND agent <> ''
                               GROUP BY skilldata";
        //  dd($llamadasporCampana);
        return $resultCalls =  $this->followQuery($llamadasporCampana);
    }

    // FUNCTION TO BING QUOTES WITH PARAMS
    //CHECK WITH DANY
    private function VentasPorCampanaXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        // $quali= ($skillDefFb = 'fb_qualitasmotor')? "AND resultdesc = ":"";
        switch($tableFb){
            case 'skill_fb_uimotor_data':
                $tipodeventa = 'COTIZACION';
            break;
            case 'skill_fb_qualitasmotor_data':
                $tipodeventa = 'VENTA';
            break;
            case 'skill_fb_axamr_data':
                $tipodeventa = 'PREVENTA';
            break;
            default:
                $tipodeventa = 'COTIZACION';
            break;
        }
        $ventasPorCampana = "SELECT  tipos.tipoLlamadas, COALESCE(log.Total, 0) AS Total
                            FROM ( SELECT 'VentasFb' AS tipoLlamadas UNION ALL SELECT 'VentasGoogle' ) AS tipos
                            LEFT JOIN (
                                SELECT
                                    CASE WHEN skilldata = '".$skillDefFb."' THEN 'VentasFb' ELSE 'VentasGoogle' END AS tipoLlamadas,
                                    COUNT(DISTINCT(numbercall)) AS Total
                                FROM ocmdb.ocm_log_calls
                                WHERE skilldata IN ('".$skillDefFb."','".$skillDefGo."')
                                AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
                                AND resultdesc = '".$tipodeventa."'
                                GROUP BY skilldata
                            ) AS log
                            ON tipos.tipoLlamadas = log.tipoLlamadas;";
        return $resultCalls =  $this->followQuery($ventasPorCampana);
    }

    // FUNCTION TO BRING RESULT FOR AGENTS
    //CHECK WITH DANY
    private function llamadasYventasAgenteYcampanaXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        switch($tableFb){
            case 'skill_fb_uimotor_data':
                $tipodeventa = 'COTIZACION';
            break;
            case 'skill_fb_qualitasmotor_data':
                $tipodeventa = 'VENTA';
            break;
            case 'skill_fb_axamr_data':
                $tipodeventa = 'PREVENTA';
            break;
            default:
                $tipodeventa = 'COTIZACION';
            break;
        }

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
                                WHERE skilldata  IN ('".$skillDefFb."', '".$skillDefGo."')
                        AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
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
            WHERE skilldata  IN ('".$skillDefFb."', '".$skillDefGo."')
            AND resultdesc = '".$tipodeventa."'
            AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
            GROUP BY agent) As va
        ON lc.agent = va.agent
        WHERE lc.skilldata  IN ('".$skillDefFb."', '".$skillDefGo."')
            AND lc.fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
            AND lc.agent <> ''
            AND lc.timecall > 5
            GROUP BY lc.agent";


        return $resultAgent =  $this->followQuery($resultadosAgents);
    }

    // FUNCTION TO BRING RESULTS IN CALLS
    //CHECK WITH DANY
    private function ResultadosContactoXcamp($tableFb, $tableGo, $skillDefFb, $skillDefGo, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        //resultadoUC
        $resultadosContacto = "SELECT l.resultdesc AS resultadoUC,COUNT(distinct(numbercall)) as Total
                                    FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                                    FROM ocmdb.ocm_log_calls lc
                                    INNER JOIN (
                                    SELECT d.number1
                                    FROM ocmdb.".$tableFb." d INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                                    UNION
                                    SELECT  d.number1 FROM ocmdb.".$tableGo." d
                                    INNER JOIN ocmdb.".$tableGo."exten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                ) As d
                 ON lc.numbercall = d.number1 AND skilldata  IN ('".$skillDefFb."','".$skillDefGo."')
        AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
        ORDER BY fecha DESC) l
        WHERE row_numb = 1
        AND agent <> ''
        GROUP BY l.resultdesc
        ORDER BY Total DESC";
        // dd($resultadosContacto);
        return $resultContact = $this->followQuery($resultadosContacto);
    }

//======================================================================
// PRINCIPAL FUNCTIONS WITH PARAMS FROM FACEBOOK AND GOOGLE
//======================================================================
    // Function to retrieve the intial lead count with params
    private function clXcampPRA($tableFb, $skillDefFb, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        $conteoLeads = "SELECT
                        SUM(CASE skilldef WHEN '".$skillDefFb."' THEN 1 ELSE 0 END) as leadsFb
                        FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef
                        FROM ocmdb.".$tableFb." d
                        INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                        INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                        WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                        AND de.id_lead <> '' AND endresultdesc <> 'Time Rule Deny' AND endresultdesc <> 'Abandono' AND endresultdesc <> 'Dial Error' AND endresultdesc <> 'Fin') AS Leads";
                    //  dd($conteoLeads);
        return $resultConte =  $this->followQuery($conteoLeads);

    }

    // function to get the call count with params
    private function lpcXcampPRA($tableFb, $skillDefFb, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        $llamadasporCampana = "SELECT skilldata,COUNT(distinct(idreg)) as TOTAL
                               FROM ( SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
                               FROM ocmdb.ocm_log_calls lc
                               INNER JOIN (
                                    SELECT d.number1
                                    FROM ocmdb.".$tableFb." d INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                                    INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
                                    WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                                    AND de.id_lead <> '' AND endresultdesc <> 'Time Rule Deny' AND endresultdesc <> 'Abandono' AND endresultdesc <> 'Dial Error' AND endresultdesc <> 'Fin') As d
                               ON lc.numbercall = d.number1 AND skilldata  IN ('".$skillDefFb."')
                               AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."   AND attempt = 1
                               ORDER BY fecha DESC) l
                               WHERE row_numb = 1
                               GROUP BY skilldata";
        //  dd($llamadasporCampana);
        return $resultCalls =  $this->followQuery($llamadasporCampana);
    }

    //Function to bring  quotes with params
    private function vpcXcampPRA($tableFb, $skillDefFb, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        $ventasPorCampana = "SELECT CASE WHEN skilldata = '".$skillDefFb."'  THEN 'VentasFb'  END AS tipoLlamadas,
        COUNT(DISTINCT(numbercall)) As Total FROM ocmdb.ocm_log_calls WHERE skilldata  IN ('".$skillDefFb."')
        AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd." AND resultdesc = 'PREVENTA' GROUP BY skilldata";
        return $resultCalls =  $this->followQuery($ventasPorCampana);
    }

    // Function to bring results from agents
    private function lvaycXcampPRA($tableFb, $skillDefFb, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
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
                                WHERE skilldata  IN ('".$skillDefFb."')
                        AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
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
            WHERE skilldata  IN ('".$skillDefFb."')
            AND resultdesc = 'PREVENTA'
            AND fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
            GROUP BY agent) As va
        ON lc.agent = va.agent
        WHERE lc.skilldata  IN ('".$skillDefFb."')
            AND lc.fecha BETWEEN ".$fechaStart." AND ".$fechaEnd."
            AND lc.agent <> ''
            AND lc.timecall > 5
            GROUP BY lc.agent";

        return $resultAgent =  $this->followQuery($resultadosAgents);
    }

    // Function to bring results in calls
    private function rcXcampPRA($tableFb, $skillDefFb, $fechaStart, $fechaEnd)
    {
        $fechaStart = $this->formatDateStart($fechaStart);
        $fechaEnd = $this->formatDateEnd($fechaEnd);
        $resultadosContacto = "SELECT resultadoUC,COUNT(*) As Total FROM

                    (SELECT  d.dateinsert,lc.numbercall,lc.skill, lc.fecha fechaUC,lc.agent agenteUC,lc.resultdesc resultadoUC,d.number1 telefono
                     FROM ocmdb.ocm_log_calls lc
                     INNER JOIN ocmdb.".$tableFb." d ON lc.idreg = d.id
                     INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                     WHERE lc.idlog_calls IN (SELECT  MAX(lc.idlog_calls)
                     FROM ocmdb.ocm_log_calls lc
                     INNER JOIN ocmdb.".$tableFb." d ON lc.numbercall = d.number1
                     INNER JOIN ocmdb.".$tableFb."exten de ON d.id = de.id
                                 WHERE d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                                    AND lc.skilldata IN ('".$skillDefFb."')
                                      AND de.id_lead <> ''
                                 GROUP BY lc.idreg )
                 AND d.dateinsert BETWEEN ".$fechaStart." AND ".$fechaEnd."
                  ORDER BY d.dateinsert DESC) l
        GROUP BY resultadoUC ORDER BY Total DESC";
        // dd($resultadosContacto);
        return $resultContact = $this->followQuery($resultadosContacto);
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







}
