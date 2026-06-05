<?php

// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Define los estados permitidos para un turno
$estados_validos = ['pendiente', 'confirmado', 'en_atencion', 'completado', 'cancelado'];

// Obtiene el ID del turno enviado por POST y lo convierte a entero
$id_turno = (int)($_POST['id_turno'] ?? 0);

// Obtiene el nuevo estado enviado por POST
$estado = $_POST['estado'] ?? '';

// Verifica que el ID sea válido y que el estado exista dentro de los estados permitidos
if ($id_turno > 0 && in_array($estado, $estados_validos)) {

    // Prepara la consulta para actualizar el estado del turno
    $stmt = $conexion->prepare("
        UPDATE turnos 
        SET estado = ? 
        WHERE id_turno = ? 
        AND estado NOT IN ('completado', 'cancelado')
    ");

    // Vincula los parámetros a la consulta preparada
    // s = string (estado)
    // i = integer (id_turno)
    $stmt->bind_param("si", $estado, $id_turno);

    // Ejecuta la actualización
    $stmt->execute();

    // Cierra la consulta preparada
    $stmt->close();
}

// Redirige al listado de turnos con mensaje de éxito
header("Location: index.php?status=1");
exit;

?>