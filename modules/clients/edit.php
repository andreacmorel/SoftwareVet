<?php
// Conexión a la base de datos
require_once '../../settings/conexion.php';

// Validación de acceso según sesión/perfil
require_once '../../php/validateRoute.php';

// Array donde se guardan los errores de validación
$erroresCampos = [];

// Verifica que llegue un ID por la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cliente no válido.");
}

// Convierte el ID recibido a número entero
$id = (int) $_GET['id'];

// Consulta los datos del cliente, persona y domicilio
$sql = " SELECT c.id_cliente,c.id_persona,p.nombre_persona,p.apellido_persona,p.telefono,p.email,d.calle,
    d.numero_calle,d.barrio,d.manzana
    FROM cliente c
    INNER JOIN persona p ON c.id_persona = p.id_persona
    LEFT JOIN domicilio d ON d.id_cliente = c.id_cliente
    WHERE c.id_cliente = $id
";

// Ejecuta la consulta
$res = mysqli_query($conexion, $sql);

// Verifica si el cliente existe
if (!$res || mysqli_num_rows($res) == 0) {
    die("Cliente no encontrado.");
}

// Guarda los datos encontrados en un array
$row = mysqli_fetch_assoc($res);

// Guarda el ID de persona asociado al cliente
$id_persona = $row['id_persona'];

// Se ejecuta cuando el formulario se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibe y limpia los datos enviados desde el formulario
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

    // Si no hay errores, verifica que no exista otro cliente con los mismos datos
    if (empty($erroresCampos)) {

        // Escapa datos para usarlos de forma segura en la consulta
        $nombreSeguro   = $conexion->real_escape_string($nombre);
        $apellidoSeguro = $conexion->real_escape_string($apellido);
        $telefonoSeguro = $conexion->real_escape_string($telefono);

        // Busca otro cliente con mismo nombre, apellido y teléfono, excluyendo el cliente actual
        $sqlExiste = " SELECT p.id_persona
            FROM persona p INNER JOIN cliente c ON p.id_persona = c.id_persona
            WHERE p.nombre_persona = '$nombreSeguro'
            AND   p.apellido_persona = '$apellidoSeguro'
            AND   p.telefono = '$telefonoSeguro'
            AND   c.id_cliente != $id
        ";

        // Ejecuta la consulta de existencia
        $resExiste = mysqli_query($conexion, $sqlExiste);

        // Si existe otro cliente, carga un error
        if ($resExiste && mysqli_num_rows($resExiste) > 0) {
            $erroresCampos['telefono'] = "Ya existe otro cliente con esos datos.";
        }
    }

    // Si no hay errores, actualiza los datos
    if (empty($erroresCampos)) {

        // Escapa todos los datos antes de actualizar
        $nombre = $conexion->real_escape_string($nombre);
        $apellido = $conexion->real_escape_string($apellido);
        $telefono = $conexion->real_escape_string($telefono);
        $email = $conexion->real_escape_string($email);
        $calle = $conexion->real_escape_string($calle);
        $numero_calle = $conexion->real_escape_string($numero_calle);
        $barrio = $conexion->real_escape_string($barrio);
        $manzana = $conexion->real_escape_string($manzana);

        // Actualiza los datos personales en la tabla persona
        $sqlPersona = " UPDATE persona SET nombre_persona = '$nombre',apellido_persona = '$apellido',
            telefono = '$telefono',email = '$email' 
            WHERE id_persona = '$id_persona'
        ";

        // Si falla la modificación de persona, guarda un error
        if (!mysqli_query($conexion, $sqlPersona)) {
            $erroresCampos['general'] = "Error al modificar persona.";
        } else {

            // Consulta si el cliente ya tiene domicilio registrado
            $sqlDomicilioExiste = "
                SELECT id_domicilio 
                FROM domicilio 
                WHERE id_cliente = '$id'
            ";

            // Ejecuta la consulta del domicilio existente
            $resDomicilioExiste = mysqli_query($conexion, $sqlDomicilioExiste);

            // Si ya existe domicilio, se actualiza
            if ($resDomicilioExiste && mysqli_num_rows($resDomicilioExiste) > 0) {

                // Obtiene los datos del domicilio
                $domicilio    = mysqli_fetch_assoc($resDomicilioExiste);

                // Guarda el ID del domicilio
                $id_domicilio = $domicilio['id_domicilio'];

                // Consulta para actualizar el domicilio
                $sqlDomicilio = " UPDATE domicilio SET calle = '$calle',
                    numero_calle = '$numero_calle',barrio = '$barrio',manzana = '$manzana'
                    WHERE id_domicilio = '$id_domicilio' ";

            } else {

                // Si no existe domicilio, se inserta uno nuevo
                $sqlDomicilio = "
                    INSERT INTO domicilio 
                    (calle, numero_calle, barrio, manzana, id_cliente)
                    VALUES 
                    ('$calle', '$numero_calle', '$barrio', '$manzana', '$id')
                ";
            }

            // Ejecuta la consulta de domicilio
            if (!mysqli_query($conexion, $sqlDomicilio)) {
                $erroresCampos['general'] = "Error al modificar domicilio.";
            } else {

                // Redirecciona al listado con mensaje de actualización correcta
                header("Location: index.php?updated=1");
                exit;
            }
        }
    }

    // Mantiene los datos enviados en el formulario si hay errores
    $row = $_POST;
}

// Inclusión del menú principal del sistema
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
        <i class="fas fa-user-edit mr-2"></i>
        Modificar Cliente
    </h1>

    <div class="subtitulo-pagina">
        Actualizá los datos personales y el domicilio del cliente.
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