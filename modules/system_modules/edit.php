<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$erroresCampos = [];

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$modulo = $conexion->query("
    SELECT id_modulo, nombre_modulo, ruta, icono
    FROM modulo
    WHERE id_modulo = $id
")->fetch_object();

if (!$modulo) {
    header("Location: index.php");
    exit;
}

if (!empty($_POST['btnModificar'])) {

    $nombre_modulo = trim($_POST['nombre_modulo'] ?? '');
    $ruta = trim($_POST['ruta'] ?? '');
    $icono = trim($_POST['icono'] ?? '');

    if (empty($nombre_modulo)) {
        $erroresCampos['nombre_modulo'] = "El nombre del módulo es obligatorio.";
    } elseif (strlen($nombre_modulo) < 3) {
        $erroresCampos['nombre_modulo'] = "Debe tener al menos 3 caracteres.";
    } elseif (strlen($nombre_modulo) > 50) {
        $erroresCampos['nombre_modulo'] = "No puede superar los 50 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ0-9\s]+$/', $nombre_modulo)) {
        $erroresCampos['nombre_modulo'] = "Solo se permiten letras, números y espacios.";
    }

    if (empty($ruta)) {
        $erroresCampos['ruta'] = "La ruta es obligatoria.";
    } elseif (strlen($ruta) > 255) {
        $erroresCampos['ruta'] = "La ruta no puede superar los 255 caracteres.";
    }

    if (!empty($icono) && strlen($icono) > 100) {
        $erroresCampos['icono'] = "El icono no puede superar los 100 caracteres.";
    }

    if (empty($erroresCampos)) {

        $nombreSeguro = $conexion->real_escape_string($nombre_modulo);
        $rutaSeguro = $conexion->real_escape_string($ruta);

        $validarNombre = $conexion->query("
            SELECT id_modulo
            FROM modulo
            WHERE nombre_modulo = '$nombreSeguro'
            AND id_modulo != $id
            AND estado = 1
            LIMIT 1
        ");

        if ($validarNombre && $validarNombre->num_rows > 0) {
            $erroresCampos['nombre_modulo'] = "Ya existe un módulo activo con ese nombre.";
        }

        $validarRuta = $conexion->query("
            SELECT id_modulo
            FROM modulo
            WHERE ruta = '$rutaSeguro'
            AND id_modulo != $id
            AND estado = 1
            LIMIT 1
        ");

        if ($validarRuta && $validarRuta->num_rows > 0) {
            $erroresCampos['ruta'] = "Ya existe un módulo activo con esa ruta.";
        }
    }

    if (empty($erroresCampos)) {

        $nombreSeguro = $conexion->real_escape_string($nombre_modulo);
        $rutaSeguro = $conexion->real_escape_string($ruta);
        $iconoSeguro = $conexion->real_escape_string($icono);

        $update = $conexion->query("
            UPDATE modulo
            SET nombre_modulo = '$nombreSeguro',
                ruta = '$rutaSeguro',
                icono = '$iconoSeguro'
            WHERE id_modulo = $id
        ");

        if ($update) {
            header("Location: index.php?updated=1");
            exit;
        } else {
            $erroresCampos['general'] = "Error al modificar módulo.";
        }
    }

    $modulo->nombre_modulo = $nombre_modulo;
    $modulo->ruta = $ruta;
    $modulo->icono = $icono;
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Modificar Módulo</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>
.page-title { font-weight:800; color:#1f2937; margin-bottom:2px; }
.page-title i { color:#52266E; }
.page-subtitle { color:#9ca3af; font-size:14px; }

.form-card {
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    margin-top:20px;
}

label {
    color:#52266E;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.form-control {
    border-radius:8px;
    border:1px solid #d8c2e8;
    font-size:14px;
}

.form-control:focus {
    border-color:#52266E;
    box-shadow:0 0 0 3px rgba(82,38,110,.12);
}

.form-control.is-invalid {
    border-color:#dc2626 !important;
    box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
}

.invalid-feedback {
    display:block;
    font-size:13px;
    font-weight:600;
}

.btn-purple {
    background:#52266E;
    color:white;
    border-radius:8px;
    font-weight:700;
    padding:8px 20px;
}

.btn-purple:hover {
    background:#3f1d55;
    color:white;
}

.btn-cancel {
    background:#e5e7eb;
    color:#374151;
    border-radius:8px;
    font-weight:700;
    padding:8px 20px;
}

.btn-cancel:hover {
    background:#d1d5db;
    color:#111827;
}

.section-title {
    color:#52266E;
    font-weight:800;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 page-title">
            <i class="fas fa-edit mr-2"></i> Modificar Módulo
        </h1>
        <div class="page-subtitle">Editar datos del módulo seleccionado</div>
    </div>

    <div class="form-card">

        <?php if (isset($erroresCampos['general'])) { ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-1"></i>
                <?= htmlspecialchars($erroresCampos['general']) ?>
            </div>
        <?php } ?>

        <form method="POST" novalidate>

            <h5 class="section-title">
                <i class="fas fa-cubes mr-2"></i> Datos del módulo
            </h5>

            <div class="form-group">
                <label>Nombre del módulo <span style="color:#dc2626;">*</span></label>

                <input 
                    type="text" 
                    name="nombre_modulo" 
                    class="form-control <?= isset($erroresCampos['nombre_modulo']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($modulo->nombre_modulo ?? '') ?>"
                >

                <?php if(isset($erroresCampos['nombre_modulo'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['nombre_modulo']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label>Ruta <span style="color:#dc2626;">*</span></label>

                <input 
                    type="text" 
                    name="ruta" 
                    class="form-control <?= isset($erroresCampos['ruta']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($modulo->ruta ?? '') ?>"
                >

                <?php if(isset($erroresCampos['ruta'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['ruta']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label>Icono</label>

                <input 
                    type="text" 
                    name="icono" 
                    class="form-control <?= isset($erroresCampos['icono']) ? 'is-invalid' : '' ?>"
                    placeholder="Ej: fas fa-paw"
                    value="<?= htmlspecialchars($modulo->icono ?? '') ?>"
                >

                <?php if(isset($erroresCampos['icono'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['icono']) ?>
                    </div>
                <?php } ?>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-cancel">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>

                <button type="submit" name="btnModificar" value="1" class="btn btn-purple">
                    <i class="fas fa-save mr-1"></i> Guardar cambios
                </button>
            </div>

        </form>

    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>