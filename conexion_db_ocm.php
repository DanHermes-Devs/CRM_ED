<?php
$databaseName = 'ocmdb';
$hostName = '172.93.111.251';
$userName = 'root';
$passCode = '55R%@$2KqC68';

$connection = mysqli_connect($hostName, $userName, $passCode, $databaseName);

$query = "SELECT * FROM skill_fb_uimotor_data LIMIT 1";
$result = mysqli_query($connection, $query);

if ($result) {
    // Procesar los resultados de la consulta
    while ($row = mysqli_fetch_assoc($result)) {
        // Acceder a los datos de cada fila
        var_dump($row);
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
