<?php
// Incluye el archivo de conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de rutas según el perfil del usuario
require_once '../../php/validateRoute.php';

// Array donde se guardarán los errores de validación de cada campo
$erroresCampos = [];

// Verifica si el formulario fue enviado por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibe y limpia los datos personales enviados desde el formulario
    $nombre = trim($_POST['nombre_persona']);
    $apellido = trim($_POST['apellido_persona']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);

    // Recibe y limpia los datos del domicilio enviados desde el formulario
    $calle = trim($_POST['calle']);
    $numero_calle = trim($_POST['numero_calle']);
    $barrio = trim($_POST['barrio']);
    $manzana = trim($_POST['manzana']);

    // Valida que el nombre no esté vacío y tenga al menos 3 caracteres
    if (empty($nombre)) {
        $erroresCampos['nombre_persona'] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 3) {
        $erroresCampos['nombre_persona'] = "Debe tener al menos 3 caracteres.";
    }

    // Valida que el apellido no esté vacío y tenga al menos 3 caracteres
    if (empty($apellido)) {
        $erroresCampos['apellido_persona'] = "El apellido es obligatorio.";
    } elseif (strlen($apellido) < 3) {
        $erroresCampos['apellido_persona'] = "Debe tener al menos 3 caracteres.";
    }

    // Valida que el teléfono no esté vacío y contenga solo números
    if (empty($telefono)) {
        $erroresCampos['telefono'] = "El teléfono es obligatorio.";
    } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
        $erroresCampos['telefono'] = "Ingrese solo números.";
    }

    // Valida que el correo no esté vacío y tenga formato válido
    if (empty($email)) {
        $erroresCampos['email'] = "El correo es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresCampos['email'] = "Ingrese un correo válido.";
    }

    // Valida que la calle no esté vacía
    if (empty($calle)) {
        $erroresCampos['calle'] = "La calle es obligatoria.";
    }

    // Valida que el número de calle no esté vacío y sea numérico
    if (empty($numero_calle)) {
        $erroresCampos['numero_calle'] = "El número es obligatorio.";
    } elseif (!preg_match('/^[0-9]+$/', $numero_calle)) {
        $erroresCampos['numero_calle'] = "Ingrese solo números.";
    }

    // Valida que el barrio no esté vacío
    if (empty($barrio)) {
        $erroresCampos['barrio'] = "El barrio es obligatorio.";
    }

    // Si no hay errores de validación, verifica si el profesional ya existe
    if (empty($erroresCampos)) {

        // Escapa los datos para evitar problemas con caracteres especiales o inyección SQL
        $nombreSeguro = $conexion->real_escape_string($nombre);
        $apellidoSeguro = $conexion->real_escape_string($apellido);
        $telefonoSeguro = $conexion->real_escape_string($telefono);

        // Consulta si ya existe un profesional con el mismo nombre, apellido y teléfono
        $sqlExiste = "
            SELECT p.id_persona
            FROM persona p
            INNER JOIN profesional pr ON p.id_persona = pr.id_persona
            WHERE p.nombre_persona = '$nombreSeguro'
            AND p.apellido_persona = '$apellidoSeguro'
            AND p.telefono = '$telefonoSeguro'
        ";

        // Ejecuta la consulta
        $resExiste = mysqli_query($conexion, $sqlExiste);

        // Si encuentra registros, significa que el profesional ya está registrado
        if ($resExiste && mysqli_num_rows($resExiste) > 0) {
            $erroresCampos['telefono'] = "Este profesional ya está registrado.";
        }
    }

    // Si después de validar y verificar duplicados no hay errores, guarda los datos
    if (empty($erroresCampos)) {

        // Escapa todos los valores antes de insertarlos en la base de datos
        $nombre = $conexion->real_escape_string($nombre);
        $apellido = $conexion->real_escape_string($apellido);
        $telefono = $conexion->real_escape_string($telefono);
        $email = $conexion->real_escape_string($email);

        $calle = $conexion->real_escape_string($calle);
        $numero_calle = $conexion->real_escape_string($numero_calle);
        $barrio = $conexion->real_escape_string($barrio);
        $manzana = $conexion->real_escape_string($manzana);

        // Inserta los datos personales en la tabla persona
        $sqlPersona = "
            INSERT INTO persona 
            (nombre_persona, apellido_persona, telefono, email)
            VALUES 
            ('$nombre', '$apellido', '$telefono', '$email')
        ";

        // Ejecuta la inserción de persona
        $resPersona = mysqli_query($conexion, $sqlPersona);

        // Si se guardó correctamente la persona
        if ($resPersona) {

            // Obtiene el ID de la persona recién insertada
            $id_persona = mysqli_insert_id($conexion);

            // Inserta el profesional asociado a esa persona
            $sqlProfesional = "
                INSERT INTO profesional (id_persona)
                VALUES ('$id_persona')
            ";

            // Ejecuta la inserción del profesional
            $resProfesional = mysqli_query($conexion, $sqlProfesional);

            // Si se guardó correctamente el profesional
            if ($resProfesional) {

                // Obtiene el ID del profesional recién insertado
                $id_profesional = mysqli_insert_id($conexion);

                // Inserta el domicilio asociado al profesional
                $sqlDomicilio = "
                    INSERT INTO domicilio 
                    (calle, numero_calle, barrio, manzana, id_profesional)
                    VALUES 
                    ('$calle', '$numero_calle', '$barrio', '$manzana', '$id_profesional')
                ";

                // Si el domicilio se guarda correctamente, redirige al listado con mensaje de éxito
                if (mysqli_query($conexion, $sqlDomicilio)) {
                    header("Location: index.php?success=1");
                    exit;
                } else {
                    // Error si falla el guardado del domicilio
                    $erroresCampos['general'] = "Error al guardar domicilio.";
                }

            } else {
                // Error si falla el guardado del profesional
                $erroresCampos['general'] = "Error al guardar profesional.";
            }

        } else {
            // Error si falla el guardado de la persona
            $erroresCampos['general'] = "Error al guardar persona.";
        }
    }
}

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

    <!-- Muestra un mensaje de error general si ocurrió algún problema al guardar -->
    <?php if (isset($erroresCampos['general'])) { ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($erroresCampos['general']); ?>
        </div>
    <?php } ?>

    <!-- Formulario para registrar un profesional -->
    <form method="POST" id="frmProfesional" novalidate>

        <!-- Título de la sección de datos personales -->
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

            <!-- Campo Correo Electrónico -->
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

        <!-- Separador visual entre secciones -->
        <hr>

        <!-- Título de la sección domicilio -->
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

    // Función para quitar el estado de error de un campo
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
            setError('inputTelefono', 'err-telefono', 'El teléfono es obligatorio.');
        } else if (!/^[0-9]+$/.test(telefono)) {
            setError('inputTelefono', 'err-telefono', 'Ingrese solo números.');
        } else {
            clearError('inputTelefono');
        }

        // VALIDACIÓN DEL EMAIL
        var email = document.getElementById('inputEmail').value.trim();

        if (email === '') {
            setError('inputEmail', 'err-email', 'El correo es obligatorio.');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('inputEmail', 'err-email', 'Ingrese un correo válido.');
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
            setError('inputNumeroCalle', 'err-numero_calle', 'El número es obligatorio.');
        } else if (!/^[0-9]+$/.test(numeroCalle)) {
            setError('inputNumeroCalle', 'err-numero_calle', 'Ingrese solo números.');
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

    // Si existe algún error, se cancela el envío del formulario
    if (!valid) {

        // Evita que el formulario se envíe al servidor
        e.preventDefault();

        // Busca el primer campo inválido
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

// Recorre todos los campos que tienen validación
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
