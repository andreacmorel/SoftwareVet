<?php

require_once '../../settings/conexion.php';

$estados_validos = ['pendiente', 'confirmado', 'en_atencion', 'completado', 'cancelado'];

$id_turno = (int)($_POST['id_turno'] ?? 0);
$estado = $_POST['estado'] ?? '';

if ($id_turno > 0 && in_array($estado, $estados_validos)) {

    $stmt = $conexion->prepare("
        UPDATE turnos 
        SET estado = ? 
        WHERE id_turno = ? 
        AND estado NOT IN ('completado', 'cancelado')
    ");

    $stmt->bind_param("si", $estado, $id_turno);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit;
?>