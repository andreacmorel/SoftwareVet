<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Modificar Perfil</title>
<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../../css/editperf.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-user-edit mr-2"></i> Modificar Perfil
        </h1>

        <div class="page-subtitle">
            Editá el nombre del perfil seleccionado
        </div>
    </div>
</div>

<div class="form-card">

<form method="POST" novalidate>

    <h5 class="section-title">
        <i class="fas fa-id-badge mr-2"></i> Datos del Perfil
    </h5>

    <div class="form-group mb-4">

        <label>
            Nombre del perfil
            <span style="color:#dc2626;">*</span>
        </label>

        <input 
            type="text" 
            name="nombre_perfil" 
            class="form-control <?php echo isset($erroresCampos['nombre_perfil']) ? 'is-invalid' : ''; ?>"
            value="<?= htmlspecialchars($perfilEditar->nombre_perfil) ?>"
        >

        <?php if (isset($erroresCampos['nombre_perfil'])) { ?>

            <div class="invalid-feedback">
                <?php echo htmlspecialchars($erroresCampos['nombre_perfil']); ?>
            </div>

        <?php } ?>

    </div>

    <div class="d-flex justify-content-between">

        <a href="index.php" class="btn btn-cancel">
            <i class="fas fa-times mr-1"></i>
            Cancelar
        </a>

        <button type="submit" name="btnModificar" value="1" class="btn btn-purple">
            <i class="fas fa-save mr-1"></i>
            Guardar
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

