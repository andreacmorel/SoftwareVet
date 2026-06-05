<?php
// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Array donde se guardarán los errores de validación
$erroresCampos = [];

// Consulta para obtener todos los clientes y mostrarlos en el select
$sqlClientes = "
SELECT c.id_cliente, p.nombre_persona, p.apellido_persona
FROM cliente c
INNER JOIN persona p ON c.id_persona = p.id_persona
";

// Ejecuta la consulta de clientes
$resClientes = mysqli_query($conexion, $sqlClientes);

// Consulta para obtener todas las especies y razas disponibles
$sqlEspecies = "
SELECT id_especie, nombre_especie, raza 
FROM especie
";

// Ejecuta la consulta de especies
$resEspecies = mysqli_query($conexion, $sqlEspecies);

// Verifica si el formulario fue enviado por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibe y limpia los datos enviados desde el formulario
    $nombre = trim($_POST['nombre_mascota'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $peso = trim($_POST['peso'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $edad = trim($_POST['edad'] ?? '');

    // Convierte a entero los IDs recibidos
    $id_especie = (int)($_POST['id_especie'] ?? 0);
    $id_cliente = (int)($_POST['id_cliente'] ?? 0);

    // Valida que el nombre de la mascota no esté vacío y tenga al menos 2 caracteres
    if (empty($nombre)) {
        $erroresCampos['nombre_mascota'] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 2) {
        $erroresCampos['nombre_mascota'] = "Debe tener al menos 2 caracteres.";
    }

    // Valida que se haya seleccionado el sexo
    if (empty($sexo)) {
        $erroresCampos['sexo'] = "Seleccione el sexo.";
    }

    // Valida que el peso no esté vacío y sea mayor a 0
    if (empty($peso)) {
        $erroresCampos['peso'] = "El peso es obligatorio.";
    } elseif ($peso <= 0) {
        $erroresCampos['peso'] = "El peso debe ser mayor a 0.";
    }

    // Valida que la edad no sea negativa si fue ingresada
    if (!empty($edad) && $edad < 0) {
        $erroresCampos['edad'] = "La edad no puede ser negativa.";
    }

    // Valida que la fecha de nacimiento no sea mayor a la fecha actual
    if (!empty($fecha_nacimiento) && $fecha_nacimiento > date('Y-m-d')) {
        $erroresCampos['fecha_nacimiento'] = "La fecha no puede ser futura.";
    }

    // Valida que se haya seleccionado una especie
    if (empty($id_especie)) {
        $erroresCampos['id_especie'] = "Seleccione una especie.";
    }

    // Valida que se haya seleccionado un cliente
    if (empty($id_cliente)) {
        $erroresCampos['id_cliente'] = "Seleccione un cliente.";
    }

    // Si no hay errores, verifica que la mascota no esté duplicada para el mismo cliente
    if (empty($erroresCampos)) {

        // Escapa el nombre antes de usarlo en la consulta SQL
        $nombreSeguro = $conexion->real_escape_string($nombre);

        // Consulta si ya existe una mascota con el mismo nombre para ese cliente
        $sqlExiste = "
            SELECT id_mascota
            FROM mascota
            WHERE nombre_mascota = '$nombreSeguro'
            AND id_cliente = $id_cliente
        ";

        // Ejecuta la consulta de existencia
        $resExiste = mysqli_query($conexion, $sqlExiste);

        // Si encuentra un registro, muestra error de duplicado
        if ($resExiste && mysqli_num_rows($resExiste) > 0) {
            $erroresCampos['nombre_mascota'] = "La mascota ya existe para este cliente.";
        }
    }

    // Si no hay errores, guarda la mascota en la base de datos
    if (empty($erroresCampos)) {

        // Escapa los valores de texto para evitar problemas con caracteres especiales
        $nombreSeguro = $conexion->real_escape_string($nombre);
        $sexoSeguro = $conexion->real_escape_string($sexo);
        $colorSeguro = $conexion->real_escape_string($color);

        // Si la fecha está vacía, se guarda NULL; si tiene valor, se escapa y se guarda
        $fechaSQL = empty($fecha_nacimiento) ? "NULL" : "'" . $conexion->real_escape_string($fecha_nacimiento) . "'";

        // Si la edad está vacía, se guarda NULL; si tiene valor, se escapa y se guarda
        $edadSQL = empty($edad) ? "NULL" : "'" . $conexion->real_escape_string($edad) . "'";

        // Consulta para insertar la nueva mascota
        $sqlInsert = "
            INSERT INTO mascota (
                nombre_mascota,
                fecha_nacimiento,
                sexo,
                peso,
                color,
                edad,
                id_especie,
                id_cliente
            )
            VALUES (
                '$nombreSeguro',
                $fechaSQL,
                '$sexoSeguro',
                '$peso',
                '$colorSeguro',
                $edadSQL,
                $id_especie,
                $id_cliente
            )
        ";

        // Ejecuta la inserción
        $resultado = mysqli_query($conexion, $sqlInsert);

        // Si se guarda correctamente, redirige al listado con mensaje de éxito
        if ($resultado) {
            header("Location: index.php?success=1");
            exit;
        } else {

            // Si ocurre un error al guardar, muestra mensaje general
            $erroresCampos['general'] = "Error al guardar mascota.";
        }
    }
}

require_once '../../php/menu.php';
?>

<style>
.titulo-pagina { font-weight: 800; color: #1f2937; }
.titulo-pagina i { color: #52266E; }

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

.card-body { padding: 25px; }

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
    border-color:#dc2626 !important;
    box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
}

.invalid-feedback{
    display:block;
    font-size:13px;
    font-weight:600;
}

.btn-purple {
    background: #52266E;
    color: white;
    border-radius: 8px;
    font-weight: 700;
    padding: 8px 22px;
}

.btn-purple:hover { background: #3f1d55; color: white; }

.btn-cancelar {
    background: #e5e7eb;
    color: #374151;
    border-radius: 8px;
    font-weight: 700;
    padding: 8px 22px;
}

.btn-cancelar:hover { background: #d1d5db; color: #111827; }
</style>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-paw mr-2"></i>
        Registro de Mascota
    </h1>

    <div class="subtitulo-pagina">
        Completá los datos para registrar un nuevo paciente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
                Nueva Mascota
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

                        <input type="text" name="nombre_mascota"
                            class="form-control <?= isset($erroresCampos['nombre_mascota']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['nombre_mascota'] ?? '') ?>">

                        <?php if(isset($erroresCampos['nombre_mascota'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['nombre_mascota']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha nacimiento</label>

                        <input type="date" name="fecha_nacimiento"
                            class="form-control <?= isset($erroresCampos['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>">

                        <?php if(isset($erroresCampos['fecha_nacimiento'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['fecha_nacimiento']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sexo <span style="color:#dc2626;">*</span></label>

                        <select name="sexo"
                            class="form-control <?= isset($erroresCampos['sexo']) ? 'is-invalid' : '' ?>">
                            <option value="">Seleccione</option>
                            <option value="M" <?= (($_POST['sexo'] ?? '') == 'M') ? 'selected' : '' ?>>Macho</option>
                            <option value="H" <?= (($_POST['sexo'] ?? '') == 'H') ? 'selected' : '' ?>>Hembra</option>
                        </select>

                        <?php if(isset($erroresCampos['sexo'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['sexo']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso (kg) <span style="color:#dc2626;">*</span></label>

                        <input type="number" step="0.01" min="0.1" name="peso"
                            class="form-control <?= isset($erroresCampos['peso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>">

                        <?php if(isset($erroresCampos['peso'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['peso']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control"
                            value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edad</label>

                        <input type="number" min="0" name="edad"
                            class="form-control <?= isset($erroresCampos['edad']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>">

                        <?php if(isset($erroresCampos['edad'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['edad']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Especie / Raza <span style="color:#dc2626;">*</span></label>

                    <select name="id_especie"
                        class="form-control <?= isset($erroresCampos['id_especie']) ? 'is-invalid' : '' ?>">
                        <option value="">Seleccione una especie</option>

                        <?php while($e = mysqli_fetch_assoc($resEspecies)) { ?>
                            <option value="<?= $e['id_especie'] ?>"
                                <?= (($_POST['id_especie'] ?? '') == $e['id_especie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nombre_especie']." - ".$e['raza']) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if(isset($erroresCampos['id_especie'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_especie']) ?></div>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label>Cliente <span style="color:#dc2626;">*</span></label>

                    <select name="id_cliente"
                        class="form-control <?= isset($erroresCampos['id_cliente']) ? 'is-invalid' : '' ?>">
                        <option value="">Seleccione un cliente</option>

                        <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>
                            <option value="<?= $c['id_cliente'] ?>"
                                <?= (($_POST['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['apellido_persona'].", ".$c['nombre_persona']) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if(isset($erroresCampos['id_cliente'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_cliente']) ?></div>
                    <?php } ?>
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

</body>
</html>