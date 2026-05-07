<?php
require_once '../../settings/conexion.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$sql = "
    UPDATE usuario 
    SET estado = IF(estado = 1, 0, 1)
    WHERE id_usuario = $id
";

$conexion->query($sql);

header("Location: index.php");
exit;