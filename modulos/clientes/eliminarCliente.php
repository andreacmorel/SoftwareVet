<?php
require_once '../../config/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cliente no válido.");
}

$id = $_GET['id'];

$sqlBuscar = "SELECT id_persona FROM cliente WHERE id_cliente = '$id'";
$resBuscar = mysqli_query($conexion, $sqlBuscar);

if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
    die("Cliente no encontrado.");
}

$data = mysqli_fetch_assoc($resBuscar);
$id_persona = $data['id_persona'];

$sqlMascotas = "SELECT COUNT(*) AS total FROM mascota WHERE id_cliente = '$id'";
$resMascotas = mysqli_query($conexion, $sqlMascotas);
$mascotas = mysqli_fetch_assoc($resMascotas);

if ($mascotas['total'] > 0) {
    header("Location: listadoCliente.php?error=mascotas");
    exit;
}

mysqli_query($conexion, "DELETE FROM domicilio WHERE id_cliente = '$id'");

if (!mysqli_query($conexion, "DELETE FROM cliente WHERE id_cliente = '$id'")) {
    die("Error al eliminar cliente: " . mysqli_error($conexion));
}

if (!mysqli_query($conexion, "DELETE FROM persona WHERE id_persona = '$id_persona'")) {
    die("Error al eliminar persona: " . mysqli_error($conexion));
}

header("Location: listadoCliente.php?eliminado=1");
exit;
?>