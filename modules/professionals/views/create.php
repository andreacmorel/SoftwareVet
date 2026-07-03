<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de Profesional</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/createprof.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-md mr-2"></i>
        Registro de Profesional
    </h1>

    <div class="subtitulo-pagina">
        Completá los datos para registrar un nuevo profesional.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
                Nuevo Profesional
            </h5>
        </div>

    <div class="card-body">

    <!-- Muestra un mensaje de error general si ocurria algun problema al guardar -->
    <?php if (isset($erroresCampos['general'])) { ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($erroresCampos['general']); ?>
        </div>
    <?php } ?>

    <!-- Formulario para registrar un profesional -->
    <form method="POST" id="frmProfesional" novalidate>


        <h5 class="section-title">
            <i class="fas fa-user-md mr-2"></i>
            Datos personales
        </h5>

        <div class="row">

            <!-- Campo Nombre -->
            <div class="form-group col-md-6">
                <label>Nombre <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar el nombre -->
                <input type="text" name="nombre_persona" id="inputNombre"
                    class="form-control <?php echo isset($erroresCampos['nombre_persona']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['nombre_persona'] ?? ''); ?>">

                <!-- Si existe error del lado del servidor lo muestra -->
                <?php if (isset($erroresCampos['nombre_persona'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['nombre_persona']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Contenedor para errores generados por JavaScript -->
                    <div class="invalid-feedback" id="err-nombre_persona"></div>
                <?php } ?>
            </div>

            <!-- Campo Apellido -->
            <div class="form-group col-md-6">
                <label>Apellido <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar el apellido -->
                <input type="text" name="apellido_persona" id="inputApellido"
                    class="form-control <?php echo isset($erroresCampos['apellido_persona']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['apellido_persona'] ?? ''); ?>">

                <!-- Error generado por PHP -->
                <?php if (isset($erroresCampos['apellido_persona'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['apellido_persona']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Error generado por JavaScript -->
                    <div class="invalid-feedback" id="err-apellido_persona"></div>
                <?php } ?>
            </div>
        </div>

        <div class="row">

            <!-- Campo Teléfono -->
            <div class="form-group col-md-6">
                <label>Teléfono <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar el teléfono -->
                <input type="text" name="telefono" id="inputTelefono"
                    class="form-control <?php echo isset($erroresCampos['telefono']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">

                <!-- Error generado por PHP -->
                <?php if (isset($erroresCampos['telefono'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['telefono']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Error generado por JavaScript -->
                    <div class="invalid-feedback" id="err-telefono"></div>
                <?php } ?>
            </div>

            <!-- Campo Correo -->
            <div class="form-group col-md-6">
                <label>Correo <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar el correo -->
                <input type="email" name="email" id="inputEmail"
                    class="form-control <?php echo isset($erroresCampos['email']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

                <!-- Error generado por PHP -->
                <?php if (isset($erroresCampos['email'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['email']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Error generado por JavaScript -->
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

            <!-- Campo Calle -->
            <div class="form-group col-md-6">
                <label>Calle <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar la calle -->
                <input type="text" name="calle" id="inputCalle"
                    class="form-control <?php echo isset($erroresCampos['calle']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['calle'] ?? ''); ?>">

                <!-- Error generado por PHP -->
                <?php if (isset($erroresCampos['calle'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['calle']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Error generado por JavaScript -->
                    <div class="invalid-feedback" id="err-calle"></div>
                <?php } ?>
            </div>

            <!-- Campo Número de Calle -->
            <div class="form-group col-md-6">
                <label>Número <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar el número de calle -->
                <input type="text" name="numero_calle" id="inputNumeroCalle"
                    class="form-control <?php echo isset($erroresCampos['numero_calle']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['numero_calle'] ?? ''); ?>">

                <!-- Error generado por PHP -->
                <?php if (isset($erroresCampos['numero_calle'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['numero_calle']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Error generado por JavaScript -->
                    <div class="invalid-feedback" id="err-numero_calle"></div>
                <?php } ?>
            </div>
        </div>

        <div class="row">

            <!-- Campo Barrio -->
            <div class="form-group col-md-6">
                <label>Barrio <span style="color:#dc2626;">*</span></label>

                <!-- Input para ingresar el barrio -->
                <input type="text" name="barrio" id="inputBarrio"
                    class="form-control <?php echo isset($erroresCampos['barrio']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['barrio'] ?? ''); ?>">

                <!-- Error generado por PHP -->
                <?php if (isset($erroresCampos['barrio'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['barrio']); ?>
                    </div>
                <?php } else { ?>

                    <!-- Error generado por JavaScript -->
                    <div class="invalid-feedback" id="err-barrio"></div>
                <?php } ?>
            </div>

            <!-- Campo Manzana (opcional) -->
            <div class="form-group col-md-6">
                <label>Manzana</label>

                <!-- Input para ingresar la manzana -->
                <input type="text" name="manzana" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['manzana'] ?? ''); ?>">
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

// Escucha el evento submit del formulario de profesionales
document.getElementById('frmProfesional').addEventListener('submit', function (e) {

    // Variable que controla si el formulario es válido
    var valid = true;

    // Función para mostrar error en un campo
    function setError(inputId, errId, msg) {

        // Obtiene el input y el contenedor del mensaje de error
        var el = document.getElementById(inputId);
        var errEl = document.getElementById(errId);

        // Agrega la clase de Bootstrap para marcar el campo como inválido
        el.classList.add('is-invalid');

        // Muestra el mensaje de error correspondiente
        if (errEl) errEl.textContent = msg;

        // Marca el formulario como inválido
        valid = false;
    }

    // FunciÃ³n para quitar el estado de error de un campo
    function clearError(inputId) {

        // Obtiene el input
        var el = document.getElementById(inputId);

        // Elimina la clase de error
        el.classList.remove('is-invalid');
    }

    // Verifica si ya existen errores provenientes del servidor (PHP)
    var soloNuevos = !document.querySelector('.is-invalid');

    // Solo ejecuta las validaciones JavaScript si no hay errores previos
    if (soloNuevos) {


        // VALIDACIÓN DEL NOMBRE

        var nombre = document.getElementById('inputNombre').value.trim();

        if (nombre === '') {
            setError('inputNombre', 'err-nombre_persona', 'El nombre es obligatorio.');
        } else if (nombre.length < 3) {
            setError('inputNombre', 'err-nombre_persona', 'Debe tener al menos 3 caracteres.');
        } else {
            clearError('inputNombre');
        }


        // VALIDACIÓN DEL APELLIDO

        var apellido = document.getElementById('inputApellido').value.trim();

        if (apellido === '') {
            setError('inputApellido', 'err-apellido_persona', 'El apellido es obligatorio.');
        } else if (apellido.length < 3) {
            setError('inputApellido', 'err-apellido_persona', 'Debe tener al menos 3 caracteres.');
        } else {
            clearError('inputApellido');
        }

        // VALIDACIÓN DEL TELÉFONO
        var telefono = document.getElementById('inputTelefono').value.trim();

        if (telefono === '') {
            setError('inputTelefono', 'err-telefono', 'El telÃ©fono es obligatorio.');
        } else if (!/^[0-9]+$/.test(telefono)) {
            setError('inputTelefono', 'err-telefono', 'Ingrese solo nÃºmeros.');
        } else {
            clearError('inputTelefono');
        }

        // VALIDACIÓN DEL EMAIL
        var email = document.getElementById('inputEmail').value.trim();

        if (email === '') {
            setError('inputEmail', 'err-email', 'El correo es obligatorio.');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('inputEmail', 'err-email', 'Ingrese un correo vÃ¡lido.');
        } else {
            clearError('inputEmail');
        }

        // VALIDACIÓN DE LA CALLE
        var calle = document.getElementById('inputCalle').value.trim();

        if (calle === '') {
            setError('inputCalle', 'err-calle', 'La calle es obligatoria.');
        } else {
            clearError('inputCalle');
        }

        // VALIDACIÓN DEL NÚMERO DE CALLE

        var numeroCalle = document.getElementById('inputNumeroCalle').value.trim();

        if (numeroCalle === '') {
            setError('inputNumeroCalle', 'err-numero_calle', 'El nÃºmero es obligatorio.');
        } else if (!/^[0-9]+$/.test(numeroCalle)) {
            setError('inputNumeroCalle', 'err-numero_calle', 'Ingrese solo nÃºmeros.');
        } else {
            clearError('inputNumeroCalle');
        }


        // VALIDACIÓN DEL BARRIO
        var barrio = document.getElementById('inputBarrio').value.trim();

        if (barrio === '') {
            setError('inputBarrio', 'err-barrio', 'El barrio es obligatorio.');
        } else {
            clearError('inputBarrio');
        }
    }

    // Si existe algun error, se cancela el envio del formulario
    if (!valid) {

        // Evita que el formulario se envie al servidor
        e.preventDefault();

        // Busca el primer campo invÃ¡lido
        var firstInvalid = document.querySelector('.is-invalid');

        // Hace scroll hasta el primer error y le da foco
        if (firstInvalid) {
            firstInvalid.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            firstInvalid.focus();
        }
    }
});


// LIMPIAR ERRORES AL MODIFICAR CAMPOS

// Recorre todos los campos que tienen validacion
['inputNombre','inputApellido','inputTelefono','inputEmail',
'inputCalle','inputNumeroCalle','inputBarrio'].forEach(function (id) {

    // Obtiene el elemento actual
    var el = document.getElementById(id);

    // Verifica que exista
    if (el) {

        // Cuando el usuario escribe, elimina la clase de error
        el.addEventListener('input', function () {
            el.classList.remove('is-invalid');
        });

        // Cuando cambia el valor del campo, elimina la clase de error
        el.addEventListener('change', function () {
            el.classList.remove('is-invalid');
        });
    }
});

</script>

</body>
</body>
</html>


