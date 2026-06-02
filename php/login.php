<?php
session_start();

require_once '../settings/conexion.php';


if (isset($_POST['usuario']) && isset($_POST['clave'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $usuario = validate($_POST['usuario']);
    $clave = validate($_POST['clave']);

    if (empty($usuario)) {
        header("Location: index.php?error=El campo usuario es obligatorio.");
        exit();
    }

    if (empty($clave)) {
        header("Location: index.php?error=El campo contraseña es obligatorio.");
        exit();
    }

   $query = "
    SELECT u.*, p.nombre_perfil
    FROM usuario u
    LEFT JOIN perfil p ON u.id_perfil = p.id_perfil
    WHERE u.usuario = '$usuario'
";

$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) == 1) {

    $row = mysqli_fetch_assoc($result);


    if (!password_verify($clave, $row['clave'])) {
        header("Location: index.php?error=Usuario o contraseña incorrectos.");
        exit();
    }

    if (!password_verify($clave, $row['clave'])) {
        header("Location: index.php?error=Usuario o contraseña incorrectos.");
        exit();
    }

    if ($row['estado'] == 0) {
        header("Location: index.php?error=Tu cuenta se encuentra inactiva.");
        exit();
    }

    $_SESSION['id_usuario'] = $row['id_usuario'];
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['id_perfil'] = $row['id_perfil'];
    $_SESSION['nombre_perfil'] = $row['nombre_perfil'];

    header("Location: inicio.php");
    exit();

} else {
    header("Location: index.php?error=Usuario o contraseña incorrectos.");
    exit();
}
}