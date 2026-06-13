<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conexion->prepare("
    UPDATE especie
    SET activo = 0
    WHERE id_especie = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: index.php?deleted=1");
exit;
?>