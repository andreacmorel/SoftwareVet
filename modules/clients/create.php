<?php
// Conexión a la base de datos
require_once '../../settings/conexion.php';

// Validación de acceso según sesión/perfil
require_once '../../php/validateRoute.php';

// Array donde se guardan los errores de cada campo
$erroresCampos = [];

// Se ejecuta solo cuando el formulario se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Se reciben y limpian los datos enviados desde el formulario
    $nombre = trim($_POST['nombre_persona']);
    $apellido = trim($_POST['apellido_persona']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $calle = trim($_POST['calle']);
    $numero_calle = trim($_POST['numero_calle']);
    $barrio = trim($_POST['barrio']);
    $manzana = trim($_POST['manzana']);

    // Validación del nombre
    if (empty($nombre)) {
        $erroresCampos['nombre_persona'] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 3) {
        $erroresCampos['nombre_persona'] = "Debe tener al menos 3 caracteres.";
    }

    // Validación del apellido
    if (empty($apellido)) {
        $erroresCampos['apellido_persona'] = "El apellido es obligatorio.";
    } elseif (strlen($apellido) < 3) {
        $erroresCampos['apellido_persona'] = "Debe tener al menos 3 caracteres.";
    }

    // Validación del teléfono
    if (empty($telefono)) {
        $erroresCampos['telefono'] = "El teléfono es obligatorio.";
    } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
        $erroresCampos['telefono'] = "Ingrese solo números.";
    }

    // Validación del email, solo si fue ingresado
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresCampos['email'] = "Ingrese un correo válido.";
    }

    // Validación del número de calle, solo si fue ingresado
    if (!empty($numero_calle) && !preg_match('/^[0-9]+$/', $numero_calle)) {
        $erroresCampos['numero_calle'] = "Ingrese solo números.";
    }

    // Si no hay errores, se verifica si el cliente ya existe
    if (empty($erroresCampos)) {

        // Se escapan los datos para evitar problemas en la consulta SQL
        $nombreSeguro = $conexion->real_escape_string($nombre);
        $apellidoSeguro = $conexion->real_escape_string($apellido);
        $telefonoSeguro = $conexion->real_escape_string($telefono);

        // Consulta para comprobar si ya existe una persona con el mismo nombre, apellido y teléfono
        $sqlExiste = " SELECT id_persona FROM persona
            WHERE nombre_persona = '$nombreSeguro'
            AND   apellido_persona = '$apellidoSeguro'
            AND   telefono = '$telefonoSeguro'
        ";

        $resExiste = mysqli_query($conexion, $sqlExiste);

        // Si existe, se carga un error
        if ($resExiste && mysqli_num_rows($resExiste) > 0) {
            $erroresCampos['telefono'] = "Este cliente ya está registrado.";
        }
    }

    // Si no hay errores, se procede a guardar los datos
    if (empty($erroresCampos)) {

        // Se escapan todos los datos antes de insertarlos
        $nombre = $conexion->real_escape_string($nombre);
        $apellido = $conexion->real_escape_string($apellido);
        $telefono  = $conexion->real_escape_string($telefono);
        $email = $conexion->real_escape_string($email);
        $calle = $conexion->real_escape_string($calle);
        $numero_calle = $conexion->real_escape_string($numero_calle);
        $barrio = $conexion->real_escape_string($barrio);
        $manzana = $conexion->real_escape_string($manzana);

        // Inserta los datos personales en la tabla persona
        $sqlPersona = " INSERT INTO persona (nombre_persona, apellido_persona, telefono, email)
            VALUES ('$nombre', '$apellido', '$telefono', '$email')";

        $resPersona = mysqli_query($conexion, $sqlPersona);

        // Si la persona se guardó correctamente
        if ($resPersona) {

            // Se obtiene el ID de la persona recién insertada
            $id_persona = mysqli_insert_id($conexion);

            // Se registra el cliente usando el id_persona
            $sqlCliente = "INSERT INTO cliente (id_persona) VALUES ('$id_persona')";

            $resCliente = mysqli_query($conexion, $sqlCliente);

            // Si el cliente se guardó correctamente
            if ($resCliente) {

                // Se obtiene el ID del cliente recién insertado
                $id_cliente = mysqli_insert_id($conexion);

                // Si se ingresó algún dato de domicilio, se guarda el domicilio
                if (!empty($calle) || !empty($numero_calle) || !empty($barrio) || !empty($manzana)) {

                    // Inserta el domicilio asociado al cliente
                    $sqlDomicilio = " INSERT INTO domicilio (calle, numero_calle, barrio, manzana, id_cliente)
                        VALUES ('$calle', '$numero_calle', '$barrio', '$manzana', '$id_cliente')";

                    mysqli_query($conexion, $sqlDomicilio);
                }

                // Redirecciona al listado con mensaje de éxito
                header("Location: index.php?success=1");
                exit;

            } else {
                // Error si no se pudo guardar el cliente
                $erroresCampos['general'] = "Error al guardar cliente.";
            }

        } else {
            // Error si no se pudo guardar la persona
            $erroresCampos['general'] = "Error al guardar persona.";
        }
    }
}

// Se incluye el menú después del procesamiento
require_once '../../php/menu.php';
?>

<style>
    .titulo-pagina {
        font-weight: 800;
        color: #1f2937;
    }

    .titulo-pagina i {
        color: #52266E;
    }

    .subtitulo-pagina {
        color: #9ca3af;
        font-size: 14px;
        margin-top: -8px;
        margin-bottom: 25px;
    }

    .card-form {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        overflow: hidden;
    }

    .card-header-form {
        background: #fbf7ff;
        border-bottom: 1px solid #eee1f6;
        padding: 18px 22px;
    }

    .card-header-form h5 {
        color: #52266E;
        font-weight: 800;
        margin: 0;
    }

    .card-body {
        padding: 25px;
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
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #52266E;
        box-shadow: 0 0 0 3px rgba(82,38,110,.12);
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

    .section-title {
        color: #52266E;
        font-weight: 800;
        font-size: 15px;
        margin-bottom: 18px;
    }

    .section-title i {
        color: #52266E;
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
</style>

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