<?php
require_once '../../settings/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de mascota no válido.");
}

$id = $_GET['id'];

$sqlDelete = "DELETE FROM mascota WHERE id_mascota = '$id'";
$resultadoDelete = mysqli_query($conexion, $sqlDelete);

if (!$resultadoDelete) {
    die("Error al eliminar mascota: " . mysqli_error($conexion));
}

header("Location: index.php");
exit;
?>