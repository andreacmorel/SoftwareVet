<?php
require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';

$id_usuario = (int)$_GET['id'];

$conexion->query("DELETE FROM usuario WHERE id_usuario = $id_usuario");

header("Location: index.php?deleted=1");
exit;