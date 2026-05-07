<?php
require_once '../../settings/conexion.php';

$error = '';

$perfiles = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE estado = 1
    ORDER BY nombre_perfil
");

if (!empty($_POST['btnGuardar'])) {

    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar_clave'];
    $id_perfil = (int)$_POST['id_perfil'];

    $usuarioSeguro = $conexion->real_escape_string($usuario);
    $emailSeguro = $conexion->real_escape_string($email);

    if (empty($usuario) || empty($email) || empty($clave) || empty($confirmar_clave) || empty($id_perfil)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no tiene un formato válido.";
    } elseif ($clave !== $confirmar_clave) {
        $error = "Las contraseñas no coinciden.";
    } else {

        $validarUsuario = $conexion->query("
            SELECT id_usuario 
            FROM usuario 
            WHERE usuario = '$usuarioSeguro'
            LIMIT 1
        ");

        if ($validarUsuario->num_rows > 0) {
            $error = "El nombre de usuario ya está registrado.";
        } else {

            $validarEmail = $conexion->query("
                SELECT id_usuario 
                FROM usuario 
                WHERE email = '$emailSeguro'
                LIMIT 1
            ");

            if ($validarEmail->num_rows > 0) {
                $error = "El email ya está registrado.";
            } else {

                $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

                $conexion->query("
                    INSERT INTO usuario (usuario, clave, email, estado, id_perfil)
                    VALUES ('$usuarioSeguro', '$clave_hash', '$emailSeguro', 1, $id_perfil)
                ");

                header("Location: index.php");
                exit;
            }
        }
    }
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Alta Usuario</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .titulo-pagina {
            font-weight: 800;
            color: #1f2937;
        }

        .titulo-pagina i {
            color: #52266E;
        }

        .subtitulo-pagina {
            color: #9ca3af;
            font-size: 14px;
            margin-top: -8px;
            margin-bottom: 25px;
        }

        .card-form {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .card-header-form {
            background: #fbf7ff;
            border-bottom: 1px solid #eee1f6;
            padding: 18px 22px;
        }

        .card-header-form h5 {
            color: #52266E;
            font-weight: 800;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        label {
            color: #52266E;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d8c2e8;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #52266E;
            box-shadow: 0 0 0 3px rgba(82,38,110,.12);
        }

        .section-title {
            color: #52266E;
            font-weight: 800;
            font-size: 15px;
            margin-bottom: 18px;
        }

        .btn-purple {
            background: #52266E;
            color: white;
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 22px;
        }

        .btn-purple:hover {
            background: #3f1d55;
            color: white;
        }

        .btn-cancelar {
            background: #e5e7eb;
            color: #374151;
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 22px;
        }

        .btn-cancelar:hover {
            background: #d1d5db;
            color: #111827;
        }

        .alert-pro {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 10px;
            padding: 12px 15px;
            font-weight: 600;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-plus mr-2"></i>
        Registro de Usuario
    </h1>

    <div class="subtitulo-pagina">
        Cargá los datos de acceso y asigná un perfil al usuario.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-id-card mr-2"></i>
                Datos del Usuario
            </h5>
        </div>

        <div class="card-body">

            <?php if (!empty($error)) { ?>
                <div class="alert-pro">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>

            <form method="POST">

                <h5 class="section-title">
                    <i class="fas fa-user mr-2"></i>
                    Información de acceso
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Usuario</label>
                        <input type="text" name="usuario" class="form-control"
                               value="<?= isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : '' ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Contraseña</label>
                        <input type="password" name="clave" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="confirmar_clave" class="form-control" required>
                    </div>
                </div>

                <hr>

                <h5 class="section-title">
                    <i class="fas fa-user-tag mr-2"></i>
                    Perfil del usuario
                </h5>

                <div class="form-group">
                    <label>Perfil</label>
                    <select name="id_perfil" class="form-control" required>
                        <option value="">Seleccione un perfil</option>

                        <?php while ($perfil = $perfiles->fetch_object()) { ?>
                            <option value="<?= $perfil->id_perfil ?>"
                                <?= isset($_POST['id_perfil']) && $_POST['id_perfil'] == $perfil->id_perfil ? 'selected' : '' ?>>
                                <?= htmlspecialchars($perfil->nombre_perfil) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" name="btnGuardar" value="1" class="btn btn-purple">
                        <i class="fas fa-save mr-1"></i>
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>