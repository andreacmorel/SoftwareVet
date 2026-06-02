<?php 
date_default_timezone_set('America/Argentina/Buenos_Aires');


$host = "localhost";
$user = "root";
$pass = "";
$db = "Vetsys";

$db = "Vetsys";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

?>