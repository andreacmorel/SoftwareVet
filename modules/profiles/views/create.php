<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Alta Perfil</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style_system.css" rel="stylesheet">
    <link href="../../css/createperf.css" rel="stylesheet">
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
                <a href="/SoftwareVet/modules/profiles/index.php">
                    Perfiles
                </a>
            </li>

            <li class="breadcrumb-item active">
                Nuevo perfil
            </li>

        </ol>
    </div>


<div class="container-fluid">

    <!-- Encabezado con título e ícono de la vista -->

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-tag mr-2"></i>
        Registro de Perfil
    </h1>

    <div class="subtitulo-pagina">
        Crea un perfil para asignar permisos a los usuarios.
    </div>


    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
                Nuevo Perfil
            </h5>
        </div>


        <div class="card-body">
            <!-- novalidate desactiva la validación nativa del navegador; la maneja el servidor -->
            <form method="POST" novalidate>
                <div class="form-group mb-4">
                    <label>Nombre del perfil
                        <span style="color:#dc2626;">*</span>
                        <!-- Asterisco indica campo obligatorio -->
                    </label>

                    <!--
                        is-invalid se agrega dinámicamente si el campo tiene error.
                        htmlspecialchars repinta el valor ingresado evitando XSS en caso de reenvío.
                    -->

                    <input type="text" name="nombre_perfil"
                        class="form-control <?php echo isset($erroresCampos['nombre_perfil']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['nombre_perfil'] ?? ''); ?>">


                    <!-- Muestra el mensaje de error debajo del input si existe -->

                    <?php if(isset($erroresCampos['nombre_perfil'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo htmlspecialchars($erroresCampos['nombre_perfil']); ?>
                        </div>
                    <?php } ?>

                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" name="btnGuardar" value="1"class="btn btn-purple">
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