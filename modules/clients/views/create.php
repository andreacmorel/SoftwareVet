<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de Cliente</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style_system.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-plus mr-2"></i>
        Registro de Cliente
    </h1>

    <div class="subtitulo-pagina">
        Completá los datos para registrar un nuevo cliente.
    </div>

    <div class="card card-form mb-4">
        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
                Nuevo Cliente
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($erroresCampos['general']); ?>
                </div>
            <?php } ?>

            <form method="POST" id="frmCliente" novalidate>

                <h5 class="section-title">
                    <i class="fas fa-user mr-2"></i>
                    Datos personales
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nombre_persona" id="inputNombre"
                            class="form-control <?php echo isset($erroresCampos['nombre_persona']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($_POST['nombre_persona'] ?? ''); ?>">

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
                            value="<?php echo htmlspecialchars($_POST['apellido_persona'] ?? ''); ?>">

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
                            value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">

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
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

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
                        <input type="text" name="calle" id="inputCalle " class="form-control"
                            value="<?php echo htmlspecialchars($_POST['calle'] ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Número</label>
                        <input type="text" name="numero_calle" id="inputNumeroCalle"
                            class="form-control <?php echo isset($erroresCampos['numero_calle']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($_POST['numero_calle'] ?? ''); ?>">

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
                        <input type="text" name="barrio" class="form-control"
                            value="<?php echo htmlspecialchars($_POST['barrio'] ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Manzana</label>
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
// Se agrega un evento al formulario para ejecutarse al enviarlo
document.getElementById('frmCliente').addEventListener('submit', function (e) {
    // Variable que controla si el formulario puede enviarse
    var valid = true;
    // Función encargada de mostrar errores en los campos
    function setError(inputId, errId, msg) {
        // Obtiene el campo que contiene el dato
        var el = document.getElementById(inputId);
        // Obtiene el contenedor donde se mostrará el mensaje de error
        var errEl = document.getElementById(errId);
        // Agrega la clase de Bootstrap para mostrar el campo inválido
        el.classList.add('is-invalid');
        // Si existe el contenedor del error, muestra el mensaje
        if (errEl) errEl.textContent = msg;
        // Marca el formulario como inválido
        valid = false;
    }
    // Función que elimina el estado de error de un campo
    function clearError(inputId) {
        // Obtiene el campo por su ID
        var el = document.getElementById(inputId);
        // Elimina la clase de error visual
        el.classList.remove('is-invalid');
    }
     // Comprueba si existen errores generados previamente desde PHP
    var soloNuevos = !document.querySelector('.is-invalid');
     // Solo ejecuta las validaciones JavaScript si no existen errores previos
    if (soloNuevos) {

        // ==========================
        // VALIDACIÓN DEL NOMBRE
        // ==========================

        // Obtiene y limpia espacios del nombre
        var nombre = document.getElementById('inputNombre').value.trim();
        // Verifica si el nombre está vacío
        if (nombre === '') {
            setError('inputNombre', 'err-nombre_persona', 'El nombre es obligatorio.');
        // Verifica que tenga al menos 3 caracteres
        } else if (nombre.length < 3) {
            setError('inputNombre', 'err-nombre_persona', 'Debe tener al menos 3 caracteres.');
        // Elimina el error si el dato es válido
        } else { clearError('inputNombre'); }

        // ==========================
        // VALIDACIÓN DEL APELLIDO
        // ==========================

        // Obtiene y limpia espacios del apellido
        var apellido = document.getElementById('inputApellido').value.trim();
        // Verifica si el apellido está vacío
        if (apellido === '') {
            setError('inputApellido', 'err-apellido_persona', 'El apellido es obligatorio.');
        // Verifica longitud mínima
        } else if (apellido.length < 3) {
            setError('inputApellido', 'err-apellido_persona', 'Debe tener al menos 3 caracteres.');
        // Elimina el error
        } else { clearError('inputApellido'); }

        // ==========================
        // VALIDACIÓN DEL TELÉFONO
        // ==========================

        // Obtiene el teléfono ingresado
        var telefono = document.getElementById('inputTelefono').value.trim();
        // Verifica si está vacío
        if (telefono === '') {
            setError('inputTelefono', 'err-telefono', 'El teléfono es obligatorio.');
        // Verifica que solo contenga números
        } else if (!/^[0-9]+$/.test(telefono)) {
            setError('inputTelefono', 'err-telefono', 'Ingrese solo números.');
        // Elimina el error
        } else { clearError('inputTelefono'); }
        
        // ==========================
        // VALIDACIÓN DEL EMAIL
        // ==========================

        // Obtiene el correo electrónico
        var email = document.getElementById('inputEmail').value.trim();
        // Si se ingresó un correo, verifica el formato
        if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('inputEmail', 'err-email', 'Ingrese un correo válido.');
        // Elimina el error
        } else { clearError('inputEmail'); }

        // ==========================
        // VALIDACIÓN DEL NÚMERO DE CALLE
        // ==========================
         // Obtiene el número de calle
        var numeroCalle = document.getElementById('inputNumeroCalle').value.trim();
        // Si tiene valor, verifica que sean solo números
        if (numeroCalle !== '' && !/^[0-9]+$/.test(numeroCalle)) {
            setError('inputNumeroCalle', 'err-numero_calle', 'Ingrese solo números.');
            // Elimina el error
        } else { clearError('inputNumeroCalle'); }
    }// Fin validaciones principales

    // Si existe algún error se cancela el envío del formulario
    if (!valid) {
        // Evita que se envíe el formulario
        e.preventDefault();
        // Busca el primer campo con error
        var firstInvalid = document.querySelector('.is-invalid');
        // Si encontró un campo inválido
        if (firstInvalid) {
            // Desplaza la pantalla hasta el campo
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Coloca el cursor sobre el campo
            firstInvalid.focus();
        }
    }
});

// Lista de campos que eliminarán el error automáticamente
['inputNombre','inputApellido','inputTelefono','inputEmail','inputNumeroCalle'].forEach(function (id) {
    // Obtiene el campo actual de la lista
    var el = document.getElementById(id);
    // Verifica que el elemento exista
    if (el) {
        // Cuando el usuario escribe se elimina la clase de error
        el.addEventListener('input',  function () { el.classList.remove('is-invalid'); });
        // Cuando cambia el valor también elimina el error
        el.addEventListener('change', function () { el.classList.remove('is-invalid'); });
    }
});// Fin recorrido de campos
</script>

</body>
</html>