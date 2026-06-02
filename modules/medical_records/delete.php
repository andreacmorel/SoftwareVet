<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de historia clinica no válido.");
}

$id = $_GET['id'];

$sqlDelete = "DELETE FROM historia_clinica WHERE id_historia_clinica = '$id'";
$resultadoDelete = mysqli_query($conexion, $sqlDelete);

if (!$resultadoDelete) {
    die("Error al eliminar historia clinica: " . mysqli_error($conexion));
}

header("Location: index.php");
exit;
?>