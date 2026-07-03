<?php

require_once '../../app/menu.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificar Usuario</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/edit_user.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-edit mr-2"></i> Editar Usuario
    </h1>

    <div class="subtitulo">
        Modificá los datos de acceso del usuario.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos del Usuario
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erroresCampos['general']) ?>
                </div>
            <?php } ?>

            <form method="POST" novalidate>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Nombre <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="nombre"
                            class="form-control <?= isset($erroresCampos['nombre']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($usuarioEditar->nombre ?? '') ?>">

                            <?php if(isset($erroresCampos['nombre'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['nombre']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellido <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="apellido"
                            class="form-control <?= isset($erroresCampos['apellido']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($usuarioEditar->apellido ?? '') ?>">

                        <?php if(isset($erroresCampos['apellido'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['apellido']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Usuario <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="usuario"
                            class="form-control <?= isset($erroresCampos['usuario']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($usuarioEditar->usuario ?? '') ?>">

                        <?php if(isset($erroresCampos['usuario'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['usuario']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Email <span style="color:#dc2626;">*</span></label>

                        <input type="email" name="email"
                            class="form-control <?= isset($erroresCampos['email']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($usuarioEditar->email ?? '') ?>">

                        <?php if(isset($erroresCampos['email'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['email']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nueva contraseña</label>

                        <input type="password" name="clave"
                            class="form-control <?= isset($erroresCampos['clave']) ? 'is-invalid' : '' ?>">

                        <?php if(isset($erroresCampos['clave'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['clave']) ?></div>
                        <?php } ?>

                        <div class="help-text">
                            Dejar vacío si no desea cambiarla.
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Confirmar nueva contraseña</label>

                        <input type="password" name="confirmar_clave"
                            class="form-control <?= isset($erroresCampos['confirmar_clave']) ? 'is-invalid' : '' ?>">

                        <?php if(isset($erroresCampos['confirmar_clave'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['confirmar_clave']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Perfil <span style="color:#dc2626;">*</span></label>

                    <!--
                    | CAMBIO AGREGADO:
                    | Si el usuario está editando su propia cuenta,
                    | el select aparece bloqueado para impedir que cambie su propio rol.
                    -->
                    <select name="id_perfil"
                        class="form-control <?= isset($erroresCampos['id_perfil']) ? 'is-invalid' : '' ?>"
                        <?= $esMiUsuario ? 'disabled' : '' ?>>

                        <option value="">Seleccione un perfil</option>

                        <?php while ($perfil = $perfiles->fetch_object()) { ?>
                            <option value="<?= $perfil->id_perfil ?>"
                                <?= (($usuarioEditar->id_perfil ?? '') == $perfil->id_perfil) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($perfil->nombre_perfil) ?>
                            </option>
                        <?php } ?>

                    </select>

                    <!--
                    | Como el select disabled no se envia por POST,
                    | el PHP conserva el perfil original desde la base de datos.
                    | Este mensaje explica al usuario por quÃ© no puede modificarlo.
                    -->
                    <?php if ($esMiUsuario) { ?>
                        <div class="alert-info-vet">
                            <i class="fas fa-info-circle mr-1"></i>
                            Por seguridad, no puede modificar el perfil de su propia cuenta.
                        </div>
                    <?php } ?>

                    <?php if(isset($erroresCampos['id_perfil'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_perfil']) ?></div>
                    <?php } ?>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" name="btnActualizar" value="1" class="btn btn-purple">
                        <i class="fas fa-save mr-1"></i>
                        Guardar 
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

</body>
</html>

