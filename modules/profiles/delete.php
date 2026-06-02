<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=id");
    exit;
}

$id = (int) $_GET['id'];

if ($id == 1) {
    header("Location: index.php?error=admin");
    exit;
}

$sqlUsuarios = "
    SELECT COUNT(*) AS total
    FROM usuario
    WHERE id_perfil = $id
    AND estado = 1
";

$resUsuarios = mysqli_query($conexion, $sqlUsuarios);
$usuarios = mysqli_fetch_assoc($resUsuarios);

if ($usuarios['total'] > 0) {
    header("Location: index.php?error=usuarios");
    exit;
}

$sql = "
    UPDATE perfil
    SET estado = 0
    WHERE id_perfil = $id
";

if (mysqli_query($conexion, $sql)) {
    header("Location: index.php?deleted=1");
    exit;
} else {
    header("Location: index.php?error=delete");
    exit;
}
?>