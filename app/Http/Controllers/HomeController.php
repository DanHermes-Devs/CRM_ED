<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use mysqli;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $databaseName = 'ocmdb';
        $hostName = '172.93.111.251';
        $userName = 'root';
        $passCode = '55R%@$2KqC68';

        $connection = mysqli_connect($hostName, $userName, $passCode, $databaseName);

        $query = "SELECT SUM(CASE skilldef WHEN 'fb_uimotor' THEN 1 ELSE 0 END) as leadsFb, SUM(CASE skilldef WHEN 'uimotor' THEN 1 ELSE 0 END) as leadsGoogle
        FROM (SELECT d.id,d.dateinsert,de.id_lead,l.skilldef 
            FROM ocmdb.skill_fb_uimotor_data d
            INNER JOIN ocmdb.skill_fb_uimotor_dataexten de
                ON d.id = de.id
            INNER JOIN ocmdb.ocm_skill_loads l
                ON d.idload = l.idload
            WHERE d.dateinsert BETWEEN '2023-05-19 00:000:00' AND '2023-05-19 23:59:59'
            AND de.id_lead <> ''
            UNION
            SELECT d.id,d.dateinsert,de.id_lead,l.skilldef  
            FROM ocmdb.skill_uimotor_data d
            INNER JOIN ocmdb.skill_uimotor_dataexten de
                ON d.id = de.id
            INNER JOIN ocmdb.ocm_skill_loads l
                ON d.idload = l.idload
            WHERE d.dateinsert BETWEEN '2023-05-19 00:000:00' AND '2023-05-19 23:59:59'AND de.id_lead <> '') AS Leads";
        $result = mysqli_query($connection, $query);

        if ($result) {
            // Procesar los resultados de la consulta
            while ($row = mysqli_fetch_assoc($result)) {
                // Acceder a los datos de cada fila
                dd($row);
            }
        } else {
            // Manejo del error de consulta
            echo "Error al ejecutar la consulta: " . mysqli_error($mysqli);
        }

        if (!$connection) {
            die("Error al conectarse a la base de datos: " . mysqli_connect_error());
        }else{
            echo "Conexión exitosa a la base de datos";
        }

        // Aquí puedes realizar las consultas y operaciones necesarias utilizando la conexión

        mysqli_close($connection);

        return view('crm.index');
    }
}
