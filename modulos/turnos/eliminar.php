<?php
// INCLUIMOS EL ARCHIVO DE CONEXION SQL 
require_once '../../config/conexion.php';

// TOMAMOS EL PARAMETRO ID QUE LLEGA POR URL, SE USA PARA SABER QUE TURNO ELIMINAR
$id = $_GET['id'];

// CONSULTA PARA ELIMINAR
$conexion->query("DELETE FROM turnos WHERE id_turno = $id");

//NOS REDIRIGE AL LISTADO 
header("Location: listado.php");
exit;
?>