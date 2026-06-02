<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}



$usuario = $conexion->query("
    SELECT estado
    FROM usuario
    WHERE id_usuario = $id
");

if (!$usuario || $usuario->num_rows == 0) {
    header("Location: index.php");
    exit;
}

$data = $usuario->fetch_assoc();

$nuevoEstado = ($data['estado'] == 1) ? 0 : 1;



$conexion->query("
    UPDATE usuario
    SET estado = $nuevoEstado
    WHERE id_usuario = $id
");



if ($nuevoEstado == 1) {

    header("Location: index.php?activated=1");

} else {

    header("Location: index.php?deactivated=1");

}

exit;