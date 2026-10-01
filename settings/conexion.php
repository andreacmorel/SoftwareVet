<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$puerto = $_ENV['DB_PORT'];
$db = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

$conexion = mysqli_connect(
    $host,
    $user,
    $pass,
    $db,
    $puerto
);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");

?>