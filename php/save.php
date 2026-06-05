<?php

// Incluye la conexión a la base de datos.
require_once '../settings/conexion.php';

// Valida que el usuario tenga permisos para acceder a esta ruta.
require_once 'validateRoute.php';

// Verifica que se hayan recibido los datos necesarios desde el formulario.
if (!isset($_POST['token']) || !isset($_POST['password']) || !isset($_POST['password2'])) {

    // Si falta algún dato, finaliza la ejecución.
    die("Faltan datos");
}

// Obtiene el token y las contraseñas enviadas por el formulario.
$token = $_POST['token'];
$password = $_POST['password'];
$password2 = $_POST['password2'];

// Verifica que ambas contraseñas coincidan.
if ($password !== $password2) {

    // Si son diferentes, vuelve al formulario mostrando un mensaje de error.
    header("Location: reset_password.php?token=".$token."&mensaje=error");
    exit;
}

// Genera el hash seguro de la nueva contraseña.
$nuevaClave = password_hash($password, PASSWORD_DEFAULT);

// Actualiza la contraseña del usuario y elimina el token de recuperación.
$update = "UPDATE usuario SET clave='$nuevaClave', reset_token=NULL 
        WHERE reset_token='$token'";

// Ejecuta la actualización en la base de datos.
if (mysqli_query($conexion, $update)) {

    // Si la actualización fue exitosa, redirige al login.
    header("Location: index.php?mensaje=ok");
    exit;

} else {

    // Si ocurre un error en la base de datos, vuelve al formulario.
    header("Location: reset_password.php?token=".$token."&mensaje=db_error");
    exit;
}

?>