<?php

require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {

    $stmt = $conexion->prepare("
        UPDATE turnos
        SET activo = 0
        WHERE id_turno = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php?deleted=1");
exit;
?>