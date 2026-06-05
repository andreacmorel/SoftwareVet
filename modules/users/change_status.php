<?php
// Incluye la conexión a la base de datos.
require_once '../../settings/conexion.php';
// Valida que el usuario tenga permisos para acceder a esta ruta.
require_once '../../php/validateRoute.php';

// Obtiene el ID del usuario recibido por la URL.
$id = (int)($_GET['id'] ?? 0);

// Verifica que el ID sea válido.
if ($id <= 0) {
    // Si el ID no es válido, vuelve al listado.
    header("Location: index.php");
    exit;
}
// Consulta el estado actual del usuario.
$usuario = $conexion->query("SELECT estado FROM usuarioWHERE id_usuario = $id");

if (!$usuario || $usuario->num_rows == 0) {
    header("Location: index.php");
    exit;
}

// Obtiene los datos del usuario encontrado.
$data = $usuario->fetch_assoc();

// Determina el nuevo estado.
// Si está activo (1) pasa a inactivo (0).
// Si está inactivo (0) pasa a activo (1).
$nuevoEstado = ($data['estado'] == 1) ? 0 : 1;

// Actualiza el estado del usuario en la base de datos.
$conexion->query("UPDATE usuario SET estado = $nuevoEstadoWHERE id_usuario = $id");
// Verifica si el nuevo estado es activo.
if ($nuevoEstado == 1) {
    // Redirige indicando que el usuario fue activado
    header("Location: index.php?activated=1");

} else {
    // Redirige indicando que el usuario fue desactivado.
    header("Location: index.php?deactivated=1");

}
// Finaliza la ejecución del script.
exit;