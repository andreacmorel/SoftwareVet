<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificación de Profesional</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/editprof.css" rel="stylesheet">
</head>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-edit mr-2"></i>
        Modificar Profesional
    </h1>

    <div class="subtitulo-pagina">
        Actualizá los datos personales y el domicilio del profesional.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos del Profesional
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($erroresCampos['general']); ?>
                </div>
            <?php } ?>

            <form method="POST" id="frmEditar" novalidate>

                <h5 class="section-title">
                    <i class="fas fa-user-md mr-2"></i>
                    Datos personales
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nombre_persona" id="inputNombre"
                            class="form-control <?php echo isset($erroresCampos['nombre_persona']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['nombre_persona'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['nombre_persona'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['nombre_persona']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-nombre_persona"></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellido <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="apellido_persona" id="inputApellido"
                            class="form-control <?php echo isset($erroresCampos['apellido_persona']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['apellido_persona'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['apellido_persona'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['apellido_persona']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-apellido_persona"></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Teléfono <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="telefono"id="inputTelefono"
                            class="form-control <?php echo isset($erroresCampos['telefono']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['telefono'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['telefono'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['telefono']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-telefono"></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Correo <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" id="inputEmail"
                            class="form-control <?php echo isset($erroresCampos['email']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['email'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['email']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-email"></div>
                        <?php } ?>
                    </div>
                </div>

                <hr>

                <h5 class="section-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Domicilio
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Calle <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="calle" id="inputCalle"
                            class="form-control <?php echo isset($erroresCampos['calle']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['calle'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['calle'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['calle']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-calle"></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Número <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="numero_calle" id="inputNumeroCalle"
                            class="form-control <?php echo isset($erroresCampos['numero_calle']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['numero_calle'] ?? ''); ?>">
                            
                        <?php if (isset($erroresCampos['numero_calle'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['numero_calle']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-numero_calle"></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Barrio <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="barrio" id="inputBarrio"
                            class="form-control <?php echo isset($erroresCampos['barrio']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['barrio'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['barrio'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['barrio']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-barrio"></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Manzana</label>
                        <input type="text" name="manzana" class="form-control"
                            value="<?php echo htmlspecialchars($row['manzana'] ?? ''); ?>">
                    </div>
                </div>

                <hr>

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

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>


<script>
// Escucha el envío del formulario
document.getElementById('frmEditar').addEventListener('submit', function (e) {

    // Se asume válido hasta encontrar un error
    var valid = true;

    // Marca un campo como inválido y muestra el mensaje
    function setError(inputId, errId, msg) {
        var el = document.getElementById(inputId);
        var errEl = document.getElementById(errId);

        el.classList.add('is-invalid');

        if (errEl) {
            errEl.textContent = msg;
        }

        valid = false;
    }

    // Limpia el error visual de un campo
    function clearError(inputId, errId) {
        var el = document.getElementById(inputId);
        var errEl = document.getElementById(errId);

        el.classList.remove('is-invalid');

        if (errEl) {
            errEl.textContent = '';
        }
    }

    // =========================
    // LIMPIAR ERRORES PREVIOS
    // =========================

    clearError('inputNombre', 'err-nombre_persona');
    clearError('inputApellido', 'err-apellido_persona');
    clearError('inputTelefono', 'err-telefono');
    clearError('inputEmail', 'err-email');
    clearError('inputCalle', 'err-calle');
    clearError('inputNumeroCalle', 'err-numero_calle');
    clearError('inputBarrio', 'err-barrio');

    // =========================
    // VALIDACIÓN NOMBRE
    // =========================

    var nombre = document.getElementById('inputNombre').value.trim();

    if (nombre === '') {
        setError(
            'inputNombre',
            'err-nombre_persona',
            'El nombre es obligatorio.'
        );
    } else if (nombre.length < 3) {
        setError(
            'inputNombre',
            'err-nombre_persona',
            'Debe tener al menos 3 caracteres.'
        );
    }

    // =========================
    // VALIDACIÓN APELLIDO
    // =========================

    var apellido = document.getElementById('inputApellido').value.trim();

    if (apellido === '') {
        setError(
            'inputApellido',
            'err-apellido_persona',
            'El apellido es obligatorio.'
        );
    } else if (apellido.length < 3) {
        setError(
            'inputApellido',
            'err-apellido_persona',
            'Debe tener al menos 3 caracteres.'
        );
    }

    // =========================
    // VALIDACIÓN TELÉFONO
    // =========================

    var telefono = document.getElementById('inputTelefono').value.trim();

    if (telefono === '') {
        setError(
            'inputTelefono',
            'err-telefono',
            'El teléfono es obligatorio.'
        );
    } else if (!/^[0-9]+$/.test(telefono)) {
        setError(
            'inputTelefono',
            'err-telefono',
            'Ingrese solo números.'
        );
    }

    // =========================
    // VALIDACIÓN EMAIL
    // =========================

    var email = document.getElementById('inputEmail').value.trim();

    if (email === '') {
        setError(
            'inputEmail',
            'err-email',
            'El correo es obligatorio.'
        );
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError(
            'inputEmail',
            'err-email',
            'Ingrese un correo válido.'
        );
    }

    // =========================
    // VALIDACIÓN CALLE
    // =========================

    var calle = document.getElementById('inputCalle').value.trim();

    if (calle === '') {
        setError(
            'inputCalle',
            'err-calle',
            'La calle es obligatoria.'
        );
    }

    // =========================
    // VALIDACIÓN NÚMERO CALLE
    // =========================

    var numeroCalle = document.getElementById('inputNumeroCalle').value.trim();

    if (numeroCalle === '') {
        setError(
            'inputNumeroCalle',
            'err-numero_calle',
            'El número es obligatorio.'
        );
    } else if (!/^[0-9]+$/.test(numeroCalle)) {
        setError(
            'inputNumeroCalle',
            'err-numero_calle',
            'Ingrese solo números.'
        );
    }

    // =========================
    // VALIDACIÓN BARRIO
    // =========================

    var barrio = document.getElementById('inputBarrio').value.trim();

    if (barrio === '') {
        setError(
            'inputBarrio',
            'err-barrio',
            'El barrio es obligatorio.'
        );
    }

    // =========================
    // SI HAY ERRORES
    // =========================

    if (!valid) {

        // Cancela el envío
        e.preventDefault();

        // Busca el primer campo inválido
        var firstInvalid = document.querySelector('.is-invalid');

        if (firstInvalid) {

            // Lleva la vista al campo
            firstInvalid.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Coloca el foco
            firstInvalid.focus();
        }
    }
});

// ====================================
// LIMPIA ERRORES AL EDITAR EL CAMPO
// ====================================

[
    'inputNombre',
    'inputApellido',
    'inputTelefono',
    'inputEmail',
    'inputCalle',
    'inputNumeroCalle',
    'inputBarrio'
].forEach(function (id) {

    var el = document.getElementById(id);

    if (el) {

        el.addEventListener('input', function () {
            el.classList.remove('is-invalid');
        });

        el.addEventListener('change', function () {
            el.classList.remove('is-invalid');
        });
    }
});
</script>
</body>
</html>

