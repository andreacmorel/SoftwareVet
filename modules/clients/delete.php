<?php
// Conexión a la base de datos
require_once '../../settings/conexion.php';

// Validación de acceso según sesión/perfil
require_once '../../php/validateRoute.php';

// Verifica que llegue un ID por la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cliente no válido.");
}

// Guarda el ID del cliente recibido por GET
$id = $_GET['id'];

// Busca la persona asociada a ese cliente
$sqlBuscar = "SELECT id_persona FROM cliente WHERE id_cliente = '$id'";
$resBuscar = mysqli_query($conexion, $sqlBuscar);

// Verifica si el cliente existe
if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
    die("Cliente no encontrado.");
}

// Obtiene los datos del cliente encontrado
$data = mysqli_fetch_assoc($resBuscar);

// Guarda el ID de la persona asociada al cliente
$id_persona = $data['id_persona'];

// Cuenta cuántas mascotas tiene asociadas ese cliente
$sqlMascotas = "SELECT COUNT(*) AS total FROM mascota WHERE id_cliente = '$id'";
$resMascotas = mysqli_query($conexion, $sqlMascotas);
$mascotas = mysqli_fetch_assoc($resMascotas);

// Si el cliente tiene mascotas, no permite eliminarlo
if ($mascotas['total'] > 0) {
    header("Location: index.php?error=mascotas");
    exit;
}

// Baja lógica del domicilio asociado al cliente
mysqli_query($conexion, "UPDATE domicilio SET activo = 0 WHERE id_cliente = '$id'");

// Baja lógica del registro de la tabla cliente
if (!mysqli_query($conexion, "UPDATE cliente SET activo = 0 WHERE id_cliente = '$id'")) {
    die("Error al dar de baja cliente: " . mysqli_error($conexion));
}

// Baja lógica de la persona asociada al cliente
if (!mysqli_query($conexion, "UPDATE persona SET activo = 0 WHERE id_persona = '$id_persona'")) {
    die("Error al dar de baja persona: " . mysqli_error($conexion));
}

// Redirecciona al listado con mensaje de eliminado correctamente
header("Location: index.php?deleted=1");
exit;
?>