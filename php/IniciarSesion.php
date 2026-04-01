<?php
session_start();

require_once '../config/conexion.php';

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

    $query = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $result = mysqli_query($conexion, $query);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($clave, $row['clave'])) {

            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['usuario'] = $row['usuario'];

            header("Location: inicio.php");
            exit();

        } else {
            header("Location: index.php?error=Usuario o contraseña incorrectos.");
            exit();
        }

    } else {
        header("Location: index.php?error=Usuario no encontrado.");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>