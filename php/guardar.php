<?php
require '../config/conexion.php';

if (!isset($_POST['token']) || !isset($_POST['password'])) {
    die("Faltan datos");
}

$token = $_POST['token'];
$nuevaClave = password_hash($_POST['password'], PASSWORD_DEFAULT);

$update = "UPDATE usuario 
           SET clave='$nuevaClave', reset_token=NULL 
           WHERE reset_token='$token'";

if (mysqli_query($conexion, $update)) {
    echo "Contraseña actualizada correctamente<br>"; 
    echo "<a href='index.php'>Volver al login</a>";
} else {
    echo "Error: " . mysqli_error($conexion);
}
?>