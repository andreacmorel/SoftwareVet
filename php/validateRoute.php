<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../settings/conexion.php';

if (!isset($_SESSION['id_usuario'])) {

    header("Location: /SoftwareVet/php/index.php");
    exit();
}

$idPerfil = $_SESSION['id_perfil'];

$rutaActual = $_SERVER['PHP_SELF'];

$query = "
    SELECT m.ruta
    FROM perfil_modulo pm
    INNER JOIN modulo m
        ON pm.id_modulo = m.id_modulo
    WHERE pm.id_perfil = '$idPerfil'
";

$resultado = mysqli_query($conexion, $query);

$permitido = false;

while ($fila = mysqli_fetch_assoc($resultado)) {

    if (strpos($rutaActual, $fila['ruta']) !== false) {

        $permitido = true;
        break;
    }
}

if (!$permitido) {

   header("Location: /SoftwareVet/php/inicio.php?error=sin_permiso");
   exit();
}
?>