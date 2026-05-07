<?php
require_once '../../settings/conexion.php';

$id_usuario = (int)$_GET['id'];

$conexion->query("DELETE FROM usuario WHERE id_usuario = $id_usuario");

header("Location: index.php");
exit;