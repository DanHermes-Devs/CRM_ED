<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function index()
    {        
        $conteo = $this->conteoLeads();
        $llamadas = $this->llamadasPorCampana();
        $ventas = $this->VentasPorCampana();
        $agentecampana = $this->llamadasPorCampana();
        $lYvAYc = $this->llamadasYventasAgenteYcampana();
        $tipificacion = $this->ResultadosContacto();

        return view('crm.index', compact('conteo','llamadas','ventas','agentecampana','lYvAYc','tipificacion'));
    }
    // Esta funcion realiza el conteo de leads por facebook y google separados
    private function conteoLeads()
    {
        $query = "SELECT SUM(CASE skilldef WHEN 'fb_uimotor' THEN 1 ELSE 0 END) as leadsFb, SUM(CASE skilldef WHEN 'uimotor' THEN 1 ELSE 0 END) as leadsGoogle
        FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef 
            FROM ocmdb.skill_fb_uimotor_data d
            INNER JOIN ocmdb.skill_fb_uimotor_dataexten de ON d.id = de.id
            INNER JOIN ocmdb.ocm_skill_loads l ON d.idload = l.idload
            WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
            AND de.id_lead <> '' UNION
            SELECT d.id,d.dateinsert,de.id_lead,l.skilldef  
            FROM ocmdb.skill_uimotor_data d
            INNER JOIN ocmdb.skill_uimotor_dataexten de
                ON d.id = de.id
            INNER JOIN ocmdb.ocm_skill_loads l
                ON d.idload = l.idload
            WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1 AND de.id_lead <> '') AS Leads";

        $result = mysqli_query($this->connection, $query);

        if ($result) {
            $conteo = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $conteo[] = $row;
            }

            return $conteo;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }
    // Esta función realiza el conteo de llamadas realizadas en google y facebook
    private function llamadasPorCampana()
    {
        $query = "SELECT skilldata,COUNT(distinct(idreg)) as TOTAL FROM (
            SELECT *,ROW_NUMBER() OVER(PARTITION BY numbercall ORDER BY fecha) AS row_numb
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
            ON lc.numbercall = d.number1
            AND skilldata  IN ('fb_uimotor','uimotor') 
            AND fecha BETWEEN CURDATE() AND CURDATE() + 1 
            AND attempt = 1
            ORDER BY fecha DESC) l
        WHERE row_numb = 1
        GROUP BY skilldata";
        
        $result = mysqli_query($this->connection, $query);
        if ($result) {
            $llamadas = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $llamadas[] = $row;
            }
            return $llamadas;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }

    private function VentasPorCampana()
    {
        $query = "SELECT CASE 
        WHEN skilldata = 'FB_UIMotor'  THEN 'VentasFb'  ELSE 'VentasGoogle'  END AS tipoLlamadas, 
        COUNT(DISTINCT(numbercall)) As Total FROM ocmdb.ocm_log_calls WHERE skilldata  IN ('fb_uimotor','UIMotor') 
        AND fecha BETWEEN CURDATE() AND CURDATE() + 1 AND resultdesc = 'COTIZACION' GROUP BY skilldata";
        $result = mysqli_query($this->connection, $query);
        if ($result) {
            $ventas = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $ventas[] = $row;
            }
            return $ventas;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }

    private function llamadasYventasAgenteYcampana()
    {
        $query = "SELECT lc.agent, a.nombre,
        CASE WHEN lc.skilldata = 'FB_UIMotor' THEN 'LlamadasFb' ELSE 'LlamadasGoogle' END AS tipoLlamadas,
        SUM(CASE WHEN lc.resultdesc <> 'COTIZACION' THEN 1 ELSE 0 END) AS llamadasNoVenta,
        SUM(CASE WHEN lc.resultdesc = 'COTIZACION' AND lc.skilldata = 'FB_UIMotor' THEN 1 ELSE 0 END) AS llamadasVentaFb,
        SUM(CASE WHEN lc.resultdesc = 'COTIZACION' AND lc.skilldata <> 'FB_UIMotor' THEN 1 ELSE 0 END) AS llamadasVentaGo,
        (SUM(CASE WHEN lc.resultdesc = 'COTIZACION' THEN 1 ELSE 0 END) + SUM(CASE WHEN lc.resultdesc <> 'COTIZACION' THEN 1 ELSE 0 END)) As Total
    FROM ocmdb.ocm_log_calls lc INNER JOIN ocmdb.ocm_agent a ON lc.agent = a.user
    WHERE lc.skilldata  IN ('FB_UIMotor','UIMotor') AND lc.fecha BETWEEN CURDATE() AND CURDATE() + 1
    AND lc.agent <> '' GROUP BY lc.agent";
        $result = mysqli_query($this->connection, $query);
        if ($result) {
            $agenteCampana = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $agenteCampana[] = $row;
            }
            return $agenteCampana;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }

    public function ResultadosContacto(){
        $query = "SELECT resultadoUC,COUNT(*) As Total
        FROM
            ((SELECT  d.dateinsert,
                 lc.numbercall,lc.skill,
                 lc.fecha fechaUC,lc.agent agenteUC,lc.resultdesc resultadoUC,d.number1 telefono
              FROM ocmdb.ocm_log_calls lc
              INNER JOIN ocmdb.skill_uimotor_data d
                 ON lc.numbercall = d.number1
              INNER JOIN ocmdb.skill_uimotor_dataexten de
                 ON d.id = de.id
              WHERE lc.idlog_calls IN (SELECT MAX(lc.idlog_calls)
                                 FROM ocmdb.ocm_log_calls lc
                                 INNER JOIN ocmdb.skill_uimotor_data d
                                 ON lc.numbercall = d.number1
                                 INNER JOIN ocmdb.skill_uimotor_dataexten de
                                 ON d.id = de.id
                                 WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                                    AND lc.skilldata = 'uimotor'
                                      AND de.id_lead <> ''
                                 GROUP BY lc.idreg )
                 AND d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                  ORDER BY d.dateinsert DESC)
             UNION
             (SELECT  d.dateinsert,
                 lc.numbercall,lc.skill,
                 lc.fecha fechaUC,lc.agent agenteUC,lc.resultdesc resultadoUC,d.number1 telefono
              FROM ocmdb.ocm_log_calls lc
              INNER JOIN ocmdb.skill_fb_uimotor_data d
                 ON lc.idreg = d.id
              INNER JOIN ocmdb.skill_fb_uimotor_dataexten de
                 ON d.id = de.id
              WHERE lc.idlog_calls IN (SELECT  MAX(lc.idlog_calls)
                                 FROM ocmdb.ocm_log_calls lc
                                 INNER JOIN ocmdb.skill_fb_uimotor_data d
                                 ON lc.numbercall = d.number1
                                 INNER JOIN ocmdb.skill_fb_uimotor_dataexten de
                                 ON d.id = de.id
                                 WHERE d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                                    AND lc.skilldata = 'fb_uimotor' 
                                      AND de.id_lead <> ''
                                 GROUP BY lc.idreg )
                 AND d.dateinsert BETWEEN CURDATE() AND CURDATE() + 1
                  ORDER BY d.dateinsert DESC)) l
        GROUP BY resultadoUC ORDER BY Total DESC";
        $result = mysqli_query($this->connection, $query);
        if ($result) {
            $agenteCampana = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $agenteCampana[] = $row;
            }
            return $agenteCampana;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }
}
