<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$erroresCampos = [];

$perfiles = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE estado = 1
    ORDER BY nombre_perfil
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $confirmar_clave = $_POST['confirmar_clave'] ?? '';
    $id_perfil = (int)($_POST['id_perfil'] ?? 0);

    if (empty($usuario)) {

        $erroresCampos['usuario'] = "El usuario es obligatorio.";

    } elseif (strlen($usuario) < 3) {

        $erroresCampos['usuario'] = "Debe tener al menos 3 caracteres.";

    } elseif (strlen($usuario) > 30) {

        $erroresCampos['usuario'] = "No puede superar los 30 caracteres.";
    }   
    
    elseif (!preg_match('/^[a-zA-Z0-9._]+$/', $usuario)) {

    $erroresCampos['usuario'] = "Solo se permiten letras, números, punto y guion bajo.";
    }

    if (empty($email)) {

        $erroresCampos['email'] = "El email es obligatorio.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erroresCampos['email'] = "Ingrese un email válido.";
    }

    if (empty($clave)) {

        $erroresCampos['clave'] = "La contraseña es obligatoria.";

    } elseif (strlen($clave) < 8) {

    $erroresCampos['clave'] = "Debe tener al menos 8 caracteres.";

    } elseif (
    !preg_match('/[A-Za-z]/', $clave) ||
    !preg_match('/[0-9]/', $clave)
    ) {

    $erroresCampos['clave'] = "Debe contener al menos una letra y un número.";
    }

    if (empty($confirmar_clave)) {

        $erroresCampos['confirmar_clave'] = "Debe confirmar la contraseña.";

    } elseif ($clave !== $confirmar_clave) {

        $erroresCampos['confirmar_clave'] = "Las contraseñas no coinciden.";
    }

    if (empty($id_perfil)) {

        $erroresCampos['id_perfil'] = "Seleccione un perfil.";
    }

    if (empty($erroresCampos)) {

        $usuarioSeguro = $conexion->real_escape_string($usuario);
        $emailSeguro = $conexion->real_escape_string($email);

        $validarUsuario = $conexion->query("
            SELECT id_usuario
            FROM usuario
            WHERE usuario = '$usuarioSeguro'
            LIMIT 1
        ");

        if ($validarUsuario->num_rows > 0) {

            $erroresCampos['usuario'] = "El nombre de usuario ya está registrado.";

        } else {

            $validarEmail = $conexion->query("
                SELECT id_usuario
                FROM usuario
                WHERE email = '$emailSeguro'
                LIMIT 1
            ");

            if ($validarEmail->num_rows > 0) {

                $erroresCampos['email'] = "El email ya está registrado.";
            }
        }
    }

    if (empty($erroresCampos)) {

        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

        $insert = $conexion->query("
            INSERT INTO usuario 
            (usuario, clave, email, estado, id_perfil)
            VALUES 
            ('$usuarioSeguro', '$clave_hash', '$emailSeguro', 1, $id_perfil)
        ");

        if ($insert) {

            header("Location: index.php?success=1");
            exit;

        } else {

            $erroresCampos['general'] = "Error al registrar usuario.";
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

        .titulo-pagina{
            font-weight:800;
            color:#1f2937;
        }

        .titulo-pagina i{
            color:#52266E;
        }

        .subtitulo-pagina{
            color:#9ca3af;
            font-size:14px;
            margin-top:-8px;
            margin-bottom:25px;
        }

        .card-form{
            border:none;
            border-radius:15px;
            box-shadow:0 4px 18px rgba(0,0,0,.06);
            overflow:hidden;
        }

        .card-header-form{
            background:#fbf7ff;
            border-bottom:1px solid #eee1f6;
            padding:18px 22px;
        }

        .card-header-form h5{
            color:#52266E;
            font-weight:800;
            margin:0;
        }

        .card-body{
            padding:25px;
        }

        label{
            color:#52266E;
            font-size:12px;
            font-weight:800;
            text-transform:uppercase;
        }

        .form-control{
            border-radius:8px;
            border:1px solid #d8c2e8;
            font-size:14px;
        }

        .form-control:focus{
            border-color:#52266E;
            box-shadow:0 0 0 3px rgba(82,38,110,.12);
        }

        .form-control.is-invalid{
            border-color:#dc2626 !important;
            box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
        }

        .invalid-feedback{
            display:block;
            font-size:13px;
            font-weight:600;
        }

        .section-title{
            color:#52266E;
            font-weight:800;
            font-size:15px;
            margin-bottom:18px;
        }

        .btn-purple{
            background:#52266E;
            color:white;
            border-radius:8px;
            font-weight:700;
            padding:8px 22px;
        }

        .btn-purple:hover{
            background:#3f1d55;
            color:white;
        }

        .btn-cancelar{
            background:#e5e7eb;
            color:#374151;
            border-radius:8px;
            font-weight:700;
            padding:8px 22px;
        }

        .btn-cancelar:hover{
            background:#d1d5db;
            color:#111827;
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

            <?php if(isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?php echo $erroresCampos['general']; ?>
                </div>
            <?php } ?>

            <form method="POST" novalidate>

                <h5 class="section-title">
                    <i class="fas fa-user mr-2"></i>
                    Información de acceso
                </h5>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Usuario<span style="color:#dc2626;">*</span></label>

                        <input type="text"name="usuario"
                            class="form-control <?php echo isset($erroresCampos['usuario']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">

                        <?php if(isset($erroresCampos['usuario'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['usuario']; ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Correo <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="email"
                            name="email"
                            class="form-control <?php echo isset($erroresCampos['email']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        >

                        <?php if(isset($erroresCampos['email'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['email']; ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Contraseña<span style="color:#dc2626;">*</span></label>

                        <input 
                            type="password"
                            name="clave"
                            class="form-control <?php echo isset($erroresCampos['clave']) ? 'is-invalid' : ''; ?>"
                        >
                    <div style="font-size:12px; color:#9ca3af; margin-top:5px;">
                         Debe contener al menos 8 caracteres, una letra y un número.
                    </div>
                        <?php if(isset($erroresCampos['clave'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['clave']; ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Confirmar contraseña<span style="color:#dc2626;">*</span></label>

                        <input 
                            type="password"
                            name="confirmar_clave"
                            class="form-control <?php echo isset($erroresCampos['confirmar_clave']) ? 'is-invalid' : ''; ?>"
                        >

                        <?php if(isset($erroresCampos['confirmar_clave'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['confirmar_clave']; ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <hr>

                <h5 class="section-title">
                    <i class="fas fa-user-tag mr-2"></i>
                    Perfil del usuario
                </h5>

                <div class="form-group">

                    <label>Perfil<span style="color:#dc2626;">*</span></label>

                    <select 
                        name="id_perfil"
                        class="form-control <?php echo isset($erroresCampos['id_perfil']) ? 'is-invalid' : ''; ?>"
                    >

                        <option value="">Seleccione un perfil</option>

                        <?php while ($perfil = $perfiles->fetch_object()) { ?>

                            <option 
                                value="<?= $perfil->id_perfil ?>"
                                <?= (($_POST['id_perfil'] ?? '') == $perfil->id_perfil) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($perfil->nombre_perfil) ?>
                            </option>

                        <?php } ?>

                    </select>

                    <?php if(isset($erroresCampos['id_perfil'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $erroresCampos['id_perfil']; ?>
                        </div>
                    <?php } ?>

                </div>

                <hr>

                <div class="d-flex justify-content-between">

                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-purple">
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