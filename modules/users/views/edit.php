<?php

require_once '../../app/menu.php';
?>

<style>
.titulo-pagina {
    font-weight: 800;
    color: #1f2937;
}

.titulo-pagina i {
    color: #52266E;
}

.subtitulo {
    color: #9ca3af;
    font-size: 14px;
    margin-bottom: 25px;
}

.card-form {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 18px rgba(0,0,0,.06);
}

.card-header-form {
    background: #fbf7ff;
    border-bottom: 1px solid #eee1f6;
    padding: 18px;
}

.card-header-form h5 {
    color: #52266E;
    font-weight: 800;
}

label {
    color: #52266E;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #d8c2e8;
}

.form-control:focus {
    border-color: #52266E;
    box-shadow: 0 0 0 3px rgba(82,38,110,.1);
}

.form-control.is-invalid {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220,38,38,.12) !important;
}

.invalid-feedback {
    display: block;
    font-size: 13px;
    font-weight: 600;
}

.btn-purple {
    background: #52266E;
    color: white;
    border-radius: 8px;
    font-weight: 700;
    padding: 8px 22px;
}

.btn-purple:hover {
    background: #3f1d55;
    color: white;
}

.btn-cancelar {
    background: #e5e7eb;
    color: #374151;
    border-radius: 8px;
    font-weight: 700;
    padding: 8px 22px;
}

.btn-cancelar:hover {
    background: #d1d5db;
    color: #111827;
}

select.form-control.is-invalid {
    background-image: none !important;
}

.help-text {
    color: #9ca3af;
    font-size: 13px;
    margin-top: 5px;
}

/* Estilo para mostrar una aclaración cuando el perfil está bloqueado. */
.alert-info-vet {
    background: #eef6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 13px;
    font-weight: 700;
    margin-top: 8px;
}
</style>

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
                    | Como el select disabled no se envía por POST,
                    | el PHP conserva el perfil original desde la base de datos.
                    | Este mensaje explica al usuario por qué no puede modificarlo.
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
                        Guardar cambios
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