<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de profesional no válido.");
}

$id = $_GET['id'];

$sqlBuscar = "SELECT id_persona FROM profesional WHERE id_profesional = '$id'";
$resBuscar = mysqli_query($conexion, $sqlBuscar);

if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
    die("Profesional no encontrado.");
}

$data = mysqli_fetch_assoc($resBuscar);
$id_persona = $data['id_persona'];

$sqlTurnos = "SELECT COUNT(*) AS total FROM turnos WHERE id_profesional = '$id'";
$resTurnos = mysqli_query($conexion, $sqlTurnos);

if (!$resTurnos) {
    die("Error al verificar turnos: " . mysqli_error($conexion));
}

$turnos = mysqli_fetch_assoc($resTurnos);

if ($turnos['total'] > 0) {
    header("Location: index.php?error=turnos");
    exit;
}

$sqlDomicilio = "DELETE FROM domicilio WHERE id_profesional = '$id'";
$resDomicilio = mysqli_query($conexion, $sqlDomicilio);

if (!$resDomicilio) {
    die("Error al eliminar domicilio: " . mysqli_error($conexion));
}

$sqlProfesional = "DELETE FROM profesional WHERE id_profesional = '$id'";
$resProfesional = mysqli_query($conexion, $sqlProfesional);

if (!$resProfesional) {
    die("Error al eliminar profesional: " . mysqli_error($conexion));
}

$sqlPersona = "DELETE FROM persona WHERE id_persona = '$id_persona'";
$resPersona = mysqli_query($conexion, $sqlPersona);

if (!$resPersona) {
    die("Error al eliminar persona: " . mysqli_error($conexion));
}

header("Location: index.php?deleted=1");
exit;
?>