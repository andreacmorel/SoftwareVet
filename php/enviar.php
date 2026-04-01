<?php
require '../config/conexion.php';

$email = $_POST['email'];

$sql = "SELECT * FROM usuario WHERE email='$email'";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$user = mysqli_fetch_assoc($resultado);

if ($user) {
    $token = uniqid();

    $update = "UPDATE usuario SET reset_token='$token' WHERE email='$email'";
    mysqli_query($conexion, $update);

    $link = "http://localhost/SoftwareVet/php/reset.php?token=$token";

    echo "Link de recuperación:<br>";
    echo "<a href='$link'>$link</a>";
} else {
    echo "El email no existe<br>";
    echo "<br><a href='olvide.php'>Intentar nuevamente</a>";
}
?>