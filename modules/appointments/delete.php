<?php

require_once '../../settings/conexion.php';

$id = $_GET['id'];

$conexion->query("DELETE FROM turnos WHERE id_turno = $id");

header("Location: index.php");
exit;
?>