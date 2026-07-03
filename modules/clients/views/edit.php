<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Modificar Cliente</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/edit.css" rel="stylesheet">
</head>

<body>
<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-edit mr-2"></i>
        Modificar Cliente
    </h1>

    <div class="subtitulo-pagina">
        Actualiza los datos personales y el domicilio del cliente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos del Cliente
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
                    <i class="fas fa-user mr-2"></i>
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
                        <input type="text" name="telefono" id="inputTelefono"
                            class="form-control <?php echo isset($erroresCampos['telefono']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['telefono'] ?? ''); ?>">

                        <?php if (isset($erroresCampos['telefono'])) { ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($erroresCampos['telefono']); ?></div>
                        <?php } else { ?>
                            <div class="invalid-feedback" id="err-telefono"></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Correo <span style="color:#9ca3af;font-weight:400;text-transform:none;">(opcional)</span></label>
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
                    Domicilio <span style="color:#9ca3af;font-size:12px;font-weight:400;text-transform:none;">(opcional)</span>
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Calle</label>
                        <input type="text" name="calle" id="inputCalle" class="form-control"
                            value="<?php echo htmlspecialchars($row['calle'] ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Número</label>
                        <input type="text" name="numero_calle"id="inputNumeroCalle"
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
                        <label>Barrio</label>
                        <input type="text" name="barrio" id="inputBarrio"class="form-control"
                            value="<?php echo htmlspecialchars($row['barrio'] ?? ''); ?>">
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

// Evento que se ejecuta cuando el usuario intenta enviar el formulario
document.getElementById('frmEditar').addEventListener('submit', function (e) {

    // Variable que controla si el formulario es válido
    var valid = true;

    // Función para mostrar errores en los campos
    function setError(inputId, errId, msg) {

        // Obtiene el campo a validar
        var el = document.getElementById(inputId);

        // Obtiene el contenedor donde se mostrará el mensaje de error
        var errEl = document.getElementById(errId);

        // Agrega la clase de Bootstrap para marcar el campo como inválido
        el.classList.add('is-invalid');

        // Muestra el mensaje de error
        if (errEl) errEl.textContent = msg;

        // Marca el formulario como inválido
        valid = false;

    }

    // Función para eliminar el estado de error
    function clearError(inputId) {

        // Obtiene el campo por ID
        var el = document.getElementById(inputId);

        // Elimina la clase de error visual
        el.classList.remove('is-invalid');

    }

    // Verifica si existen errores generados previamente desde PHP
    var soloNuevos = !document.querySelector('.is-invalid');

    // Solo ejecuta las validaciones JavaScript si no hay errores previos
    if (soloNuevos) {

        // ==========================
        // VALIDACIÓN DEL NOMBRE
        // ==========================

        // Obtiene el valor ingresado
        var nombre = document.getElementById('inputNombre').value.trim();
        // Verifica si está vacío
        if (nombre === '') {
            setError('inputNombre','err-nombre_persona','El nombre es obligatorio.' );
        // Verifica longitud mínima
        } else if (nombre.length < 3) {
            setError('inputNombre','err-nombre_persona','Debe tener al menos 3 caracteres.');
        } else {
            // Elimina el error si es válido
            clearError('inputNombre');

        }

        // ==========================
        // VALIDACIÓN DEL APELLIDO
        // ==========================

        // Obtiene el apellido ingresado
        var apellido = document.getElementById('inputApellido').value.trim();

        // Verifica si está vacío
        if (apellido === '') {
            setError('inputApellido','err-apellido_persona','El apellido es obligatorio.');
        // Verifica longitud mínima
        } else if (apellido.length < 3) {
            setError('inputApellido','err-apellido_persona','Debe tener al menos 3 caracteres.');
        } else {
            // Elimina el error si es válido
            clearError('inputApellido');

        }

        // ==========================
        // VALIDACIÓN DEL TELÉFONO
        // ==========================

        // Obtiene el teléfono ingresado
        var telefono = document.getElementById('inputTelefono').value.trim();
        // Verifica si está vacío
        if (telefono === '') {
            setError('inputTelefono','err-telefono','El teléfono es obligatorio.'
            );

        // Verifica que solo contenga números
        } else if (!/^[0-9]+$/.test(telefono)) {
            setError('inputTelefono','err-telefono','Ingrese solo números.');
        } else {
            // Elimina el error si es válido
            clearError('inputTelefono');

        }

        // ==========================
        // VALIDACIÓN DEL EMAIL
        // ==========================

        // Obtiene el correo electrónico
        var email = document.getElementById('inputEmail').value.trim();
        // El email es opcional, pero si se completa debe tener formato válido
        if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('inputEmail','err-email','Ingrese un correo válido.');
        } else {
            // Elimina el error si es válido
            clearError('inputEmail');

        }

        // ==========================
        // VALIDACIÓN DEL NÚMERO DE CALLE
        // ==========================

        // Obtiene el número de calle
        var numeroCalle = document.getElementById('inputNumeroCalle').value.trim();
        // El campo es opcional, pero si se completa debe contener solo números
        if (numeroCalle !== '' && !/^[0-9]+$/.test(numeroCalle)) {
            setError('inputNumeroCalle','err-numero_calle','Ingrese solo números.');
        } else {

            // Elimina el error si es válido
            clearError('inputNumeroCalle');

        }

    }

    // Si existe algún error se cancela el envío
    if (!valid) {
        // Evita que el formulario sea enviado
        e.preventDefault();
        // Busca el primer campo inválido
        var firstInvalid = document.querySelector('.is-invalid');
        // Si existe un campo con error
        if (firstInvalid) {
            // Desplaza la pantalla hasta ese campo
            firstInvalid.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            // Coloca el cursor en el campo inválido
            firstInvalid.focus();

        }

    }

});

// Lista de campos que eliminarán automáticamente el error visual
['inputNombre','inputApellido','inputTelefono','inputEmail','inputNumeroCalle'].forEach(function (id) {
    // Obtiene el elemento por ID
    var el = document.getElementById(id);
    // Verifica que el elemento exista
    if (el) {
        // Cuando el usuario escribe, elimina la clase de error
        el.addEventListener('input', function () {el.classList.remove('is-invalid');});
        // Cuando cambia el valor, también elimina la clase de error
        el.addEventListener('change', function () {el.classList.remove('is-invalid');});

    }

});

</script>
</body>
</html>

