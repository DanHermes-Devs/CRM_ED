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
        $data = $this->consultaSQL();

        return view('crm.index', compact('data'));
    }

    private function consultaSQL()
    {
        $query = "SELECT SUM(CASE skilldef WHEN 'fb_uimotor' THEN 1 ELSE 0 END) as leadsFb, SUM(CASE skilldef WHEN 'uimotor' THEN 1 ELSE 0 END) as leadsGoogle
        FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef 
            FROM ocmdb.skill_fb_uimotor_data d
            INNER JOIN ocmdb.skill_fb_uimotor_dataexten de
                ON d.id = de.id
            INNER JOIN ocmdb.ocm_skill_loads l
                ON d.idload = l.idload
            WHERE d.dateinsert BETWEEN '2023-05-19 00:00:00' AND '2023-05-19 23:59:59'
            AND de.id_lead <> ''
            UNION
            SELECT d.id,d.dateinsert,de.id_lead,l.skilldef  
            FROM ocmdb.skill_uimotor_data d
            INNER JOIN ocmdb.skill_uimotor_dataexten de
                ON d.id = de.id
            INNER JOIN ocmdb.ocm_skill_loads l
                ON d.idload = l.idload
            WHERE d.dateinsert BETWEEN '2023-05-19 00:00:00' AND '2023-05-19 23:59:59'AND de.id_lead <> '') AS Leads";

        $result = mysqli_query($this->connection, $query);

        if ($result) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            return $data;
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($this->connection);
        }
    }
}
