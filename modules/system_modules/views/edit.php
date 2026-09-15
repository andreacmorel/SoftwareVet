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
<link href="../../css/style_system.css" rel="stylesheet">
<link href="../../css/edit_modules.css" rel="stylesheet">

</head>

<body>


<div class="vetsys-breadcrumb-container">
    <ol class="vetsys-breadcrumb">

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i>
                Inicio
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/modules/system_modules/index.php">
                Módulos
            </a>
        </li>

        <li class="breadcrumb-item active">
            Editar módulo
        </li>

    </ol>
</div>


<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-pen mr-2"></i>
        Editar Módulo
    </h1>

    <div class="subtitulo-pagina">
        Modifica datos del módulo seleccionado.
    </div>


    <div class="card card-form mb-4">


        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos del Módulo
            </h5>
        </div>

        <div class="card-body">

        <?php if (isset($erroresCampos['general'])) { ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-1"></i>
                <?= htmlspecialchars($erroresCampos['general']) ?>
            </div>
        <?php } ?>

        <form method="POST" novalidate>

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