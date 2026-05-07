<?php
require_once '../../settings/conexion.php';

$id = (int)$_GET['id'];

$conexion->query("
    UPDATE perfil
    SET estado = 0
    WHERE id_perfil = $id
");

header("Location: index.php");
exit;
?>