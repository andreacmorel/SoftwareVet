<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de mascota no válido.");
}

$id = $_GET['id'];

$sqlDelete = "UPDATE mascota SET activo = 0 WHERE id_mascota = '$id'";

$resultadoDelete = mysqli_query($conexion, $sqlDelete);

if (!$resultadoDelete) {
    die("Error al eliminar mascota: " . mysqli_error($conexion));
}

header("Location: index.php?delete=1");
exit;
?>