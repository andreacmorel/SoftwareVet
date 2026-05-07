<?php
require_once '../settings/conexion.php';

if (!isset($_POST['token']) || !isset($_POST['password']) || !isset($_POST['password2'])) {
    die("Faltan datos");
}

$token = $_POST['token'];
$password = $_POST['password'];
$password2 = $_POST['password2'];

if ($password !== $password2) {
    header("Location: reset_password.php?token=".$token."&mensaje=error");
    exit;
}

$nuevaClave = password_hash($password, PASSWORD_DEFAULT);

$update = "UPDATE usuario 
           SET clave='$nuevaClave', reset_token=NULL 
           WHERE reset_token='$token'";

if (mysqli_query($conexion, $update)) {

    header("Location: index.php?mensaje=ok");
    exit;

} else {

    header("Location: reset_password.php?token=".$token."&mensaje=db_error");
    exit;
}
?>