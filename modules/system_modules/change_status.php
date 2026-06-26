<?php
// Incluye la conexión a la base de datos.
require_once '../../settings/conexion.php';

// Valida que el usuario tenga permisos para acceder a este módulo.
require_once '../../app/validateRoute.php';

// Obtiene el ID del módulo enviado por la URL.
$id = (int)($_GET['id'] ?? 0);

// Verifica que el ID sea válido.
if ($id <= 0) {

    // Si el ID no es válido, vuelve al listado.
    header("Location: index.php");
    exit;
}

// Consulta el estado actual del módulo.
$res = $conexion->query("
    SELECT estado 
    FROM modulo 
    WHERE id_modulo = $id
");

// Verifica que el módulo exista.
if (!$res || $res->num_rows == 0) {

    // Si no existe, vuelve al listado.
    header("Location: index.php");
    exit;
}

// Obtiene los datos del módulo.
$data = $res->fetch_assoc();

// Determina el nuevo estado del módulo.
// Si está activo (1), pasa a inactivo (0).
// Si está inactivo (0), pasa a activo (1).
$nuevoEstado = ($data['estado'] == 1) ? 0 : 1;

// Actualiza el estado del módulo en la base de datos.
$conexion->query("
    UPDATE modulo
    SET estado = $nuevoEstado
    WHERE id_modulo = $id
");

// Si el módulo quedó activo.
if ($nuevoEstado == 1) {

    // Redirige al listado indicando que fue activado.
    header("Location: index.php?activated=1");

} else {

    // Si el módulo quedó inactivo, redirige indicando que fue desactivado.
    header("Location: index.php?deactivated=1");
}

// Finaliza la ejecución del script.
exit;

?>