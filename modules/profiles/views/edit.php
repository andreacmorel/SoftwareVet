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
<link href="../../css/style_system.css" rel="stylesheet">
<link href="../../css/editperf.css" rel="stylesheet">

</head>

<body>


<div class="vetsys-breadcrumb-container">
    <ol class="vetsys-breadcrumb">
        <li class="breadcrumb-item">
            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i>Inicio
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/modules/profiles/index.php">
                Perfiles
            </a>
        </li>

        <li class="breadcrumb-item active">
            Editar perfil
        </li>
    </ol>
</div>


<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-edit mr-2"></i>
        Editar Perfil
    </h1>

    <div class="subtitulo-pagina">
        Modifica el nombre del perfil seleccionado.
    </div>

    <div class="card card-form mb-4">
        <div class="card-header-form">

            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos del Perfil
            </h5>
        </div>

        <div class="card-body">
            <form method="POST" novalidate>
                <div class="form-group mb-4">
                    <label>Nombre del perfil
                        <span style="color:#dc2626;">*</span>
                    </label>

                    <input type="text"name="nombre_perfil"
                        class="form-control <?php echo isset($erroresCampos['nombre_perfil']) ? 'is-invalid' : ''; ?>"
                        value="<?= htmlspecialchars($perfilEditar->nombre_perfil) ?>">


                    <?php if (isset($erroresCampos['nombre_perfil'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erroresCampos['nombre_perfil']); ?>
                        </div>

                    <?php } ?>
                </div>
                
                <div class="d-flex justify-content-between">

                    <a href="index.php"class="btn btn-cancelar">

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

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>