<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Alta Usuario</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <!-- Titulo e icono de la vista -->
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
                    <div class="form-group col-md-6">
                    <label>Nombre<span style="color:#dc2626;">*</span></label>

                    <input type="text" name="nombre"
                    class="form-control <?php echo isset($erroresCampos['nombre']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

                    <?php if(isset($erroresCampos['nombre'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo $erroresCampos['nombre']; ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="form-group col-md-6">
                    <label>Apellido<span style="color:#dc2626;">*</span></label>

                    <input type="text" name="apellido"
                        class="form-control <?php echo isset($erroresCampos['apellido']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>">

                    <?php if(isset($erroresCampos['apellido'])) { ?>
                        <div class="invalid-feedback">
                    <?php echo $erroresCampos['apellido']; ?>
                    </div>
                    <?php } ?>
                </div>
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

                    <!-- Campo: correo-->
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

