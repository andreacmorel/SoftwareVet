<?php
// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Verifica que llegue un ID por GET y que no esté vacío
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de profesional no válido.");
}

// Guarda el ID del profesional recibido por la URL
$id = $_GET['id'];

// Busca la persona asociada al profesional
$sqlBuscar = "SELECT id_persona FROM profesional WHERE id_profesional = '$id'";
$resBuscar = mysqli_query($conexion, $sqlBuscar);

// Si no encuentra el profesional, corta la ejecución
if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
    die("Profesional no encontrado.");
}

// Obtiene los datos del profesional encontrado
$data = mysqli_fetch_assoc($resBuscar);

// Guarda el ID de la persona relacionada con ese profesional
$id_persona = $data['id_persona'];

// Verifica si el profesional tiene turnos asociados
$sqlTurnos = "SELECT COUNT(*) AS total FROM turnos WHERE id_profesional = '$id'";
$resTurnos = mysqli_query($conexion, $sqlTurnos);

// Si falla la consulta de turnos, muestra el error
if (!$resTurnos) {
    die("Error al verificar turnos: " . mysqli_error($conexion));
}

// Obtiene la cantidad de turnos encontrados
$turnos = mysqli_fetch_assoc($resTurnos);

// Si tiene turnos asociados, no permite eliminarlo
if ($turnos['total'] > 0) {
    header("Location: index.php?error=turnos");
    exit;
}

// Elimina primero el domicilio asociado al profesional
$sqlDomicilio = "DELETE FROM domicilio WHERE id_profesional = '$id'";
$resDomicilio = mysqli_query($conexion, $sqlDomicilio);

// Si falla la eliminación del domicilio, muestra el error
if (!$resDomicilio) {
    die("Error al eliminar domicilio: " . mysqli_error($conexion));
}

// Elimina el registro de la tabla profesional
$sqlProfesional = "DELETE FROM profesional WHERE id_profesional = '$id'";
$resProfesional = mysqli_query($conexion, $sqlProfesional);

// Si falla la eliminación del profesional, muestra el error
if (!$resProfesional) {
    die("Error al eliminar profesional: " . mysqli_error($conexion));
}

// Elimina la persona asociada a ese profesional
$sqlPersona = "DELETE FROM persona WHERE id_persona = '$id_persona'";
$resPersona = mysqli_query($conexion, $sqlPersona);

// Si falla la eliminación de la persona, muestra el error
if (!$resPersona) {
    die("Error al eliminar persona: " . mysqli_error($conexion));
}

// Si todo se eliminó correctamente, redirige al listado con mensaje de éxito
header("Location: index.php?deleted=1");
exit;
?>