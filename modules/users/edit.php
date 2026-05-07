<?php
require_once '../../settings/conexion.php';

$error = '';

$id_usuario = (int)$_GET['id'];

$usuarioActual = $conexion->query("
    SELECT *
    FROM usuario
    WHERE id_usuario = $id_usuario
")->fetch_object();

$perfiles = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE estado = 1
    ORDER BY nombre_perfil
");

if (!empty($_POST['btnActualizar'])) {

    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $email = $conexion->real_escape_string($_POST['email']);
    $id_perfil = (int)$_POST['id_perfil'];
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar_clave'];

    if (!empty($clave) || !empty($confirmar_clave)) {

        if ($clave !== $confirmar_clave) {
            $error = "Las contraseñas no coinciden.";
        } else {
            $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

            $conexion->query("
                UPDATE usuario
                SET usuario = '$usuario',
                    email = '$email',
                    clave = '$clave_hash',
                    id_perfil = $id_perfil
                WHERE id_usuario = $id_usuario
            ");

            header("Location: index.php");
            exit;
        }

    } else {

        $conexion->query("
            UPDATE usuario
            SET usuario = '$usuario',
                email = '$email',
                id_perfil = $id_perfil
            WHERE id_usuario = $id_usuario
        ");

        header("Location: index.php");
        exit;
    }
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Editar Usuario</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .btn-guardar {
            background: #52266E;
            color: white;
            border-radius: 8px;
        }

        .btn-guardar:hover {
            background: #3f1d55;
            color: white;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user-edit mr-2" style="color:#52266E;"></i>
        Editar Usuario
    </h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
            <?php } ?>

            <form method="POST">

                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control"
                           value="<?= htmlspecialchars($usuarioActual->usuario) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($usuarioActual->email) ?>" required>
                </div>

                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="clave" class="form-control">
                    <small class="text-muted">Dejar vacío si no desea cambiarla.</small>
                </div>

                <div class="form-group">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password" name="confirmar_clave" class="form-control">
                </div>

                <div class="form-group">
                    <label>Perfil</label>
                    <select name="id_perfil" class="form-control" required>

                        <?php while ($perfil = $perfiles->fetch_object()) { ?>
                            <option value="<?= $perfil->id_perfil ?>"
                                <?= $perfil->id_perfil == $usuarioActual->id_perfil ? 'selected' : '' ?>>
                                <?= htmlspecialchars($perfil->nombre_perfil) ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <button type="submit" name="btnActualizar" value="1" class="btn btn-guardar">
                    <i class="fas fa-save"></i> Actualizar
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>