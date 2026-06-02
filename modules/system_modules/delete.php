<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$conexion->query("
    UPDATE modulo
    SET estado = 0
    WHERE id_modulo = $id
");

header("Location: index.php");
exit;
?>