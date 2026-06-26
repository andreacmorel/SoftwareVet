<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Alta Usuario</title>
    <!-- Íconos Font Awesome -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Estilos del tema SB Admin 2 -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Título principal de la página */
        .titulo-pagina { font-weight:800; color:#1f2937; }
        .titulo-pagina i { color:#52266E; }

        /* Subtítulo descriptivo debajo del título */
        .subtitulo-pagina { color:#9ca3af; font-size:14px; margin-top:-8px; margin-bottom:25px; }

        /* Tarjeta contenedora del formulario */
        .card-form { border:none; border-radius:15px; box-shadow:0 4px 18px rgba(0,0,0,.06); overflow:hidden; }

        /* Encabezado de la tarjeta con fondo lila suave */
        .card-header-form { background:#fbf7ff; border-bottom:1px solid #eee1f6; padding:18px 22px; }
        .card-header-form h5 { color:#52266E; font-weight:800; margin:0; }

        .card-body { padding:25px; }

        /* Etiquetas de los campos en mayúsculas y color corporativo */
        label { color:#52266E; font-size:12px; font-weight:800; text-transform:uppercase; }

        /* Inputs con bordes redondeados en tono lila */
        .form-control { border-radius:8px; border:1px solid #d8c2e8; font-size:14px; }
        .form-control:focus { border-color:#52266E; box-shadow:0 0 0 3px rgba(82,38,110,.12); }

        /* Estado de error con borde rojo */
        .form-control.is-invalid { border-color:#dc2626 !important; box-shadow:0 0 0 3px rgba(220,38,38,.12) !important; }
        .invalid-feedback { display:block; font-size:13px; font-weight:600; }

        /* Títulos de sección dentro del formulario */
        .section-title { color:#52266E; font-weight:800; font-size:15px; margin-bottom:18px; }

        /* Botón principal (guardar) */
        .btn-purple { background:#52266E; color:white; border-radius:8px; font-weight:700; padding:8px 22px; }
        .btn-purple:hover { background:#3f1d55; color:white; }

        /* Botón secundario (cancelar) */
        .btn-cancelar { background:#e5e7eb; color:#374151; border-radius:8px; font-weight:700; padding:8px 22px; }
        .btn-cancelar:hover { background:#d1d5db; color:#111827; }
    </style>
</head>

<body>

<div class="container-fluid">

    <!-- Título e icono de la vista -->
    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-plus mr-2"></i>
        Registro de Usuario
    </h1>

    <!-- Descripción breve de la pantalla -->
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

            <!-- Error general (fallo de INSERT en la BD) -->
            <?php if(isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?php echo $erroresCampos['general']; ?>
                </div>
            <?php } ?>

            <!-- novalidate desactiva la validación nativa del navegador para usar la del servidor -->
            <form method="POST" novalidate>

                <!-- Sección: datos de acceso -->
                <h5 class="section-title">
                    <i class="fas fa-user mr-2"></i>
                    Información de acceso
                </h5>

                <div class="row">

                    <!-- Campo: nombre de usuario -->
                    <div class="form-group col-md-6">
                        <label>Usuario<span style="color:#dc2626;">*</span></label>

                        <!-- is-invalid se agrega dinámicamente si el campo tiene error -->
                        <input type="text" name="usuario"
                            class="form-control <?php echo isset($erroresCampos['usuario']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
                        <!-- htmlspecialchars evita XSS al repintar el valor en caso de error -->

                        <?php if(isset($erroresCampos['usuario'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['usuario']; ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Campo: correo electrónico -->
                    <div class="form-group col-md-6">
                        <label>Correo <span style="color:#dc2626;">*</span></label>

                        <input type="email" name="email"
                            class="form-control <?php echo isset($erroresCampos['email']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

                        <?php if(isset($erroresCampos['email'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['email']; ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <div class="row">

                    <!-- Campo: contraseña (no se repinta el valor por seguridad) -->
                    <div class="form-group col-md-6">
                        <label>Contraseña<span style="color:#dc2626;">*</span></label>

                        <input type="password" name="clave"
                            class="form-control <?php echo isset($erroresCampos['clave']) ? 'is-invalid' : ''; ?>">

                        <!-- Texto de ayuda visible debajo del input -->
                        <div style="font-size:12px; color:#9ca3af; margin-top:5px;">
                            Debe contener al menos 8 caracteres, una letra y un número.
                        </div>

                        <?php if(isset($erroresCampos['clave'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['clave']; ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Campo: confirmación de contraseña -->
                    <div class="form-group col-md-6">
                        <label>Confirmar contraseña<span style="color:#dc2626;">*</span></label>

                        <input type="password" name="confirmar_clave"
                            class="form-control <?php echo isset($erroresCampos['confirmar_clave']) ? 'is-invalid' : ''; ?>">

                        <?php if(isset($erroresCampos['confirmar_clave'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo $erroresCampos['confirmar_clave']; ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <hr>

                <!-- Sección: asignación de perfil -->
                <h5 class="section-title">
                    <i class="fas fa-user-tag mr-2"></i>
                    Perfil del usuario
                </h5>

                <div class="form-group">

                    <label>Perfil<span style="color:#dc2626;">*</span></label>

                    <!-- Select poblado dinámicamente con los perfiles activos de la BD -->
                    <select name="id_perfil"
                        class="form-control <?php echo isset($erroresCampos['id_perfil']) ? 'is-invalid' : ''; ?>">

                        <option value="">Seleccione un perfil</option>

                        <?php while ($perfil = $perfiles->fetch_object()) { ?>
                            <!-- Marca como "selected" el perfil elegido antes de un error de validación -->
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

                <!-- Acciones del formulario: cancelar a la izquierda, guardar a la derecha -->
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

<!-- Scripts: jQuery, Bootstrap y tema SB Admin 2 -->
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>