<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de profesional no válido.");
}

$id = (int)$_GET['id'];
$erroresCampos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre       = trim($_POST['nombre_persona']);
    $apellido     = trim($_POST['apellido_persona']);
    $telefono     = trim($_POST['telefono']);
    $email        = trim($_POST['email']);
    $calle        = trim($_POST['calle']);
    $numero_calle = trim($_POST['numero_calle']);
    $barrio       = trim($_POST['barrio']);
    $manzana      = trim($_POST['manzana']);

    if (empty($nombre)) {
        $erroresCampos['nombre_persona'] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 3) {
        $erroresCampos['nombre_persona'] = "Debe tener al menos 3 caracteres.";
    }

    if (empty($apellido)) {
        $erroresCampos['apellido_persona'] = "El apellido es obligatorio.";
    } elseif (strlen($apellido) < 3) {
        $erroresCampos['apellido_persona'] = "Debe tener al menos 3 caracteres.";
    }

    if (empty($telefono)) {
        $erroresCampos['telefono'] = "El teléfono es obligatorio.";
    } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
        $erroresCampos['telefono'] = "Ingrese solo números.";
    }

    if (empty($email)) {
        $erroresCampos['email'] = "El correo es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresCampos['email'] = "Ingrese un correo válido.";
    }

    if (empty($calle)) {
        $erroresCampos['calle'] = "La calle es obligatoria.";
    }

    if (empty($numero_calle)) {
        $erroresCampos['numero_calle'] = "El número es obligatorio.";
    } elseif (!preg_match('/^[0-9]+$/', $numero_calle)) {
        $erroresCampos['numero_calle'] = "Ingrese solo números.";
    }

    if (empty($barrio)) {
        $erroresCampos['barrio'] = "El barrio es obligatorio.";
    }

    if (empty($erroresCampos)) {

        $sqlBuscar = "SELECT id_persona FROM profesional WHERE id_profesional = '$id'";
        $resBuscar = mysqli_query($conexion, $sqlBuscar);

        if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
            $erroresCampos['general'] = "Profesional no encontrado.";
        } else {

            $profesional = mysqli_fetch_assoc($resBuscar);
            $id_persona  = $profesional['id_persona'];
            $nombre       = $conexion->real_escape_string($nombre);
            $apellido     = $conexion->real_escape_string($apellido);
            $telefono     = $conexion->real_escape_string($telefono);
            $email        = $conexion->real_escape_string($email);
            $calle        = $conexion->real_escape_string($calle);
            $numero_calle = $conexion->real_escape_string($numero_calle);
            $barrio       = $conexion->real_escape_string($barrio);
            $manzana      = $conexion->real_escape_string($manzana);

            $sqlPersona = "UPDATE persona SET nombre_persona   = '$nombre',apellido_persona = '$apellido',telefono         = '$telefono',
                email = '$email' WHERE id_persona = '$id_persona'";

            if (!mysqli_query($conexion, $sqlPersona)) {
                $erroresCampos['general'] = "Error al modificar persona.";
            } else {

                $sqlDomicilioExiste = "SELECT id_domicilio FROM domicilio WHERE id_profesional = '$id'";
                $resDomicilioExiste = mysqli_query($conexion, $sqlDomicilioExiste);

                if (!$resDomicilioExiste) {
                    $erroresCampos['general'] = "Error al buscar domicilio.";
                } else {

                    if (mysqli_num_rows($resDomicilioExiste) > 0) {
                        $domicilio    = mysqli_fetch_assoc($resDomicilioExiste);
                        $id_domicilio = $domicilio['id_domicilio'];

                        $sqlDomicilio = "UPDATE domicilio SET calle = '$calle',numero_calle = '$numero_calle',
                            barrio = '$barrio',manzana = '$manzana'
                            WHERE id_domicilio = '$id_domicilio'
                        ";
                    } else {
                        $sqlDomicilio = "
                            INSERT INTO domicilio (calle, numero_calle, barrio, manzana, id_profesional)
                            VALUES ('$calle', '$numero_calle', '$barrio', '$manzana', '$id')
                        ";
                    }

                    if (!mysqli_query($conexion, $sqlDomicilio)) {
                        $erroresCampos['general'] = "Error al modificar domicilio.";
                    } else {
                        header("Location: index.php?updated=1");
                        exit;
                    }
                }
            }
        }
    }

    $row = [
        'nombre_persona'   => $_POST['nombre_persona'],
        'apellido_persona' => $_POST['apellido_persona'],
        'telefono'         => $_POST['telefono'],
        'email'            => $_POST['email'],
        'calle'            => $_POST['calle'],
        'numero_calle'     => $_POST['numero_calle'],
        'barrio'           => $_POST['barrio'],
        'manzana'          => $_POST['manzana'],
    ];

} else {

    $sql = "SELECT c.id_profesional,c.id_persona,p.nombre_persona,p.apellido_persona,p.telefono,
            p.email,d.calle,d.numero_calle,d.barrio,d.manzana
        FROM profesional c
        INNER JOIN persona p ON c.id_persona = p.id_persona
        LEFT JOIN domicilio d ON d.id_profesional = c.id_profesional
        WHERE c.id_profesional = '$id'";

    $res = mysqli_query($conexion, $sql);

    if (!$res || mysqli_num_rows($res) == 0) {
        die("Profesional no encontrado.");
    }

    $row = mysqli_fetch_assoc($res);
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
document.getElementById('frmEditar').addEventListener('submit', function (e) {
    var valid = true;

    function setError(inputId, errId, msg) {
        var el = document.getElementById(inputId);
        var errEl = document.getElementById(errId);
        el.classList.add('is-invalid');
        if (errEl) errEl.textContent = msg;
        valid = false;
    }

    function clearError(inputId) {
        var el = document.getElementById(inputId);
        el.classList.remove('is-invalid');
    }

    var soloNuevos = !document.querySelector('.is-invalid');

    if (soloNuevos) {
        var nombre = document.getElementById('inputNombre').value.trim();
        if (nombre === '') {
            setError('inputNombre', 'err-nombre_persona', 'El nombre es obligatorio.');
        } else if (nombre.length < 3) {
            setError('inputNombre', 'err-nombre_persona', 'Debe tener al menos 3 caracteres.');
        } else { clearError('inputNombre'); }

        var apellido = document.getElementById('inputApellido').value.trim();
        if (apellido === '') {
            setError('inputApellido', 'err-apellido_persona', 'El apellido es obligatorio.');
        } else if (apellido.length < 3) {
            setError('inputApellido', 'err-apellido_persona', 'Debe tener al menos 3 caracteres.');
        } else { clearError('inputApellido'); }

        var telefono = document.getElementById('inputTelefono').value.trim();
        if (telefono === '') {
            setError('inputTelefono', 'err-telefono', 'El teléfono es obligatorio.');
        } else if (!/^[0-9]+$/.test(telefono)) {
            setError('inputTelefono', 'err-telefono', 'Ingrese solo números.');
        } else { clearError('inputTelefono'); }

        var email = document.getElementById('inputEmail').value.trim();
        if (email === '') {
            setError('inputEmail', 'err-email', 'El correo es obligatorio.');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('inputEmail', 'err-email', 'Ingrese un correo válido.');
        } else { clearError('inputEmail'); }

        var calle = document.getElementById('inputCalle').value.trim();
        if (calle === '') {
            setError('inputCalle', 'err-calle', 'La calle es obligatoria.');
        } else { clearError('inputCalle'); }

        var numeroCalle = document.getElementById('inputNumeroCalle').value.trim();
        if (numeroCalle === '') {
            setError('inputNumeroCalle', 'err-numero_calle', 'El número es obligatorio.');
        } else if (!/^[0-9]+$/.test(numeroCalle)) {
            setError('inputNumeroCalle', 'err-numero_calle', 'Ingrese solo números.');
        } else { clearError('inputNumeroCalle'); }

        var barrio = document.getElementById('inputBarrio').value.trim();
        if (barrio === '') {
            setError('inputBarrio', 'err-barrio', 'El barrio es obligatorio.');
        } else { clearError('inputBarrio'); }
    }

    if (!valid) {
        e.preventDefault();
        var firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
    }
});

['inputNombre','inputApellido','inputTelefono','inputEmail',
 'inputCalle','inputNumeroCalle','inputBarrio'].forEach(function (id) {
    var el = document.getElementById(id);
    if (el) {
        el.addEventListener('input',  function () { el.classList.remove('is-invalid'); });
        el.addEventListener('change', function () { el.classList.remove('is-invalid'); });
    }
});
</script>

</body>
</html>