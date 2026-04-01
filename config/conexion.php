<?php 

$host = "localhost";
$user = "root";
$pass = "alfajor";
$db = "VETsys";

$db = "VETsys";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

?>