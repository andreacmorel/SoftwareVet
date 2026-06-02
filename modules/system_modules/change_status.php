<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$res = $conexion->query("
    SELECT estado 
    FROM modulo 
    WHERE id_modulo = $id
");

if (!$res || $res->num_rows == 0) {
    header("Location: index.php");
    exit;
}

$data = $res->fetch_assoc();
$nuevoEstado = ($data['estado'] == 1) ? 0 : 1;

$conexion->query("
    UPDATE modulo
    SET estado = $nuevoEstado
    WHERE id_modulo = $id
");

if ($nuevoEstado == 1) {
    header("Location: index.php?activated=1");
} else {
    header("Location: index.php?deactivated=1");
}

exit;
?>