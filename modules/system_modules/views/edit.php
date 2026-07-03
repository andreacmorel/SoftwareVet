<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Modificar Módulo</title>
<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../../css/edit_modules.css" rel="stylesheet">
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
                    <i class="fas fa-save mr-1"></i> Guardar
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