<?php

// Verifica si no hay una sesión iniciada.
if (session_status() === PHP_SESSION_NONE) {

    // Inicia la sesión para poder acceder a las variables $_SESSION.
    session_start();
}

// Incluye la conexión a la base de datos.
require_once __DIR__ . '/../settings/conexion.php';

// Verifica si no existe un usuario logueado en la sesión.
if (!isset($_SESSION['id_usuario'])) {

    // Si no hay sesión activa, redirige al login.
    header("Location: /SoftwareVet/php/index.php");
    exit();
}

// Obtiene el perfil del usuario logueado.
$idPerfil = $_SESSION['id_perfil'];

// Obtiene la ruta actual del archivo que el usuario está intentando abrir.
$rutaActual = $_SERVER['PHP_SELF'];

// Consulta las rutas de los módulos permitidos para el perfil del usuario.
$query = "
    SELECT m.ruta
    FROM perfil_modulo pm
    INNER JOIN modulo m
    ON pm.id_modulo = m.id_modulo
    WHERE pm.id_perfil = '$idPerfil'
";

// Ejecuta la consulta en la base de datos.
$resultado = mysqli_query($conexion, $query);

// Variable que indica si el usuario tiene permiso para acceder.
$permitido = false;

// Recorre todas las rutas permitidas para el perfil.
while ($fila = mysqli_fetch_assoc($resultado)) {

    // Verifica si la ruta actual coincide con alguna ruta permitida.
    if (strpos($rutaActual, $fila['ruta']) !== false) {

        // Si coincide, se permite el acceso.
        $permitido = true;
        break;
    }
}

// Si no tiene permiso para acceder a la ruta actual.
if (!$permitido) {

    // Redirige al inicio mostrando un error de permiso.
    header("Location: /SoftwareVet/php/inicio.php?error=sin_permiso");
    exit();
}

?>