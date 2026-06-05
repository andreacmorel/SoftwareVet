<?php
// Incluye la conexión a la base de datos y la validación de rutas permitidas
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

// Verifica que el parámetro 'id' exista y no esté vacío en la URL
// Si no viene, redirige al listado con un error de id inválido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=id");
    exit;
}

// Castea el id a entero para evitar inyección SQL
$id = (int) $_GET['id'];

// Protección especial: el perfil con id=1 es el administrador del sistema
// y no puede ser eliminado bajo ninguna circunstancia
if ($id == 1) {
    header("Location: index.php?error=admin");
    exit;
}

// Verifica si hay usuarios activos asignados a este perfil
// No se puede eliminar un perfil que todavía tiene usuarios en uso
$sqlUsuarios = "
    SELECT COUNT(*) AS total
    FROM usuario
    WHERE id_perfil = $id
    AND estado = 1
";

$resUsuarios = mysqli_query($conexion, $sqlUsuarios);
$usuarios = mysqli_fetch_assoc($resUsuarios);

// Si el contador es mayor a 0, hay usuarios activos vinculados al perfil
// Se redirige con error en lugar de eliminar
if ($usuarios['total'] > 0) {
    header("Location: index.php?error=usuarios");
    exit;
}

// Baja lógica: en lugar de borrar el registro de la BD,
// se marca el perfil como inactivo (estado = 0)
// Esto preserva la integridad histórica de los datos
$sql = "UPDATE perfil SET estado = 0 WHERE id_perfil = $id";

// Ejecuta la baja lógica y redirige según el resultado
if (mysqli_query($conexion, $sql)) {
    // Éxito: redirige al listado indicando que se eliminó correctamente
    header("Location: index.php?deleted=1");
    exit;
} else {
    // Error inesperado de base de datos al intentar actualizar
    header("Location: index.php?error=delete");
    exit;
}
?>