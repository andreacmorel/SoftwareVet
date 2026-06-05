<?php

// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Valida que el usuario tenga acceso a la ruta
require_once '../../php/validateRoute.php';

// Array donde se almacenarán los errores de validación
$erroresCampos = [];

// Verifica que exista un ID recibido por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de mascota no válido.");
}

// Convierte el ID recibido a entero por seguridad
$id = (int) $_GET['id'];


// ===============================
// OBTENER CLIENTES
// ===============================

// Consulta para obtener clientes junto con su nombre y apellido
$sqlClientes = "
SELECT c.id_cliente, p.nombre_persona, p.apellido_persona
FROM cliente c
INNER JOIN persona p ON c.id_persona = p.id_persona
";

// Ejecuta la consulta
$resClientes = mysqli_query($conexion, $sqlClientes);


// ===============================
// OBTENER ESPECIES
// ===============================

// Consulta para obtener especies registradas
$sqlEspecies = "
SELECT id_especie, nombre_especie, raza
FROM especie
";

// Ejecuta la consulta
$resEspecies = mysqli_query($conexion, $sqlEspecies);


// ===============================
// OBTENER DATOS DE LA MASCOTA
// ===============================

// Busca la mascota a editar
$sqlMascota = "SELECT * FROM mascota WHERE id_mascota = $id";

// Ejecuta la consulta
$resMascota = mysqli_query($conexion, $sqlMascota);

// Obtiene los datos en un array asociativo
$mascota = mysqli_fetch_assoc($resMascota);

// Si la mascota no existe se detiene la ejecución
if (!$mascota) {
    die("Mascota no encontrada.");
}


// ===============================
// PROCESAR FORMULARIO
// ===============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtiene y limpia los datos enviados
    $nombre = trim($_POST['nombre_mascota'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $peso = trim($_POST['peso'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $id_especie = (int)($_POST['id_especie'] ?? 0);
    $id_cliente = (int)($_POST['id_cliente'] ?? 0);


    // ===============================
    // VALIDACIONES
    // ===============================

    // Validación del nombre
    if (empty($nombre)) {
        $erroresCampos['nombre_mascota'] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 2) {
        $erroresCampos['nombre_mascota'] = "Debe tener al menos 2 caracteres.";
    }

    // Validación del sexo
    if (empty($sexo)) {
        $erroresCampos['sexo'] = "Seleccione el sexo.";
    }

    // Validación del peso
    if (empty($peso)) {
        $erroresCampos['peso'] = "El peso es obligatorio.";
    } elseif ($peso <= 0) {
        $erroresCampos['peso'] = "El peso debe ser mayor a 0.";
    }

    // Validación de edad
    if (!empty($edad) && $edad < 0) {
        $erroresCampos['edad'] = "La edad no puede ser negativa.";
    }

    // Validación de fecha futura
    if (!empty($fecha_nacimiento) && $fecha_nacimiento > date('Y-m-d')) {
        $erroresCampos['fecha_nacimiento'] = "La fecha no puede ser futura.";
    }

    // Validación de especie
    if (empty($id_especie)) {
        $erroresCampos['id_especie'] = "Seleccione una especie.";
    }

    // Validación de cliente
    if (empty($id_cliente)) {
        $erroresCampos['id_cliente'] = "Seleccione un cliente.";
    }


    // ===============================
    // VALIDAR DUPLICADOS
    // ===============================

    // Solo si no hubo errores previos
    if (empty($erroresCampos)) {

        // Escapa el nombre para evitar problemas en SQL
        $nombreSeguro = $conexion->real_escape_string($nombre);

        // Busca otra mascota con el mismo nombre y cliente
        // excluyendo la mascota actual
        $sqlExiste = "
            SELECT id_mascota
            FROM mascota
            WHERE nombre_mascota = '$nombreSeguro'
            AND id_cliente = $id_cliente
            AND id_mascota != $id
        ";

        // Ejecuta la consulta
        $resExiste = mysqli_query($conexion, $sqlExiste);

        // Si encuentra registros se genera error
        if ($resExiste && mysqli_num_rows($resExiste) > 0) {
            $erroresCampos['nombre_mascota'] =
                "Ya existe otra mascota con ese nombre para este cliente.";
        }
    }


    // ===============================
    // ACTUALIZAR REGISTRO
    // ===============================

    if (empty($erroresCampos)) {

        // Escapa valores de texto
        $nombreSeguro = $conexion->real_escape_string($nombre);
        $sexoSeguro = $conexion->real_escape_string($sexo);
        $colorSeguro = $conexion->real_escape_string($color);

        // Maneja campos opcionales
        $fechaSQL = empty($fecha_nacimiento)
            ? "NULL"
            : "'" . $conexion->real_escape_string($fecha_nacimiento) . "'";

        $edadSQL = empty($edad)
            ? "NULL"
            : "'" . $conexion->real_escape_string($edad) . "'";

        // Consulta UPDATE
        $sqlUpdate = "
            UPDATE mascota SET
                nombre_mascota = '$nombreSeguro',
                fecha_nacimiento = $fechaSQL,
                sexo = '$sexoSeguro',
                peso = '$peso',
                color = '$colorSeguro',
                edad = $edadSQL,
                id_especie = $id_especie,
                id_cliente = $id_cliente
            WHERE id_mascota = $id
        ";

        // Ejecuta la actualización
        if (mysqli_query($conexion, $sqlUpdate)) {

            // Redirecciona al listado indicando éxito
            header("Location: index.php?updated=1");
            exit;

        } else {

            // Error general de actualización
            $erroresCampos['general'] =
                "Error al modificar mascota.";
        }
    }

    // Conserva los datos ingresados en el formulario
    // en caso de que existan errores
    $mascota = $_POST;
}
require_once '../../php/menu.php';
?>

<style>
.titulo-pagina { font-weight: 800; color: #1f2937; }
.titulo-pagina i { color: #52266E; }

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
    border-color:#dc2626 !important;
    box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
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

.btn-purple:hover { background: #3f1d55; color: white; }

.btn-cancelar {
    background: #e5e7eb;
    color: #374151;
    border-radius: 8px;
    font-weight: 700;
    padding: 8px 22px;
}

.btn-cancelar:hover { background: #d1d5db; color: #111827; }

select.form-control.is-invalid {
    background-image: none !important;
}
</style>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-pen mr-2"></i> Editar Mascota
    </h1>

    <div class="subtitulo">
        Modificá los datos del paciente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5><i class="fas fa-edit mr-2"></i> Datos de la Mascota</h5>
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
                            value="<?= htmlspecialchars($mascota['nombre_mascota'] ?? '') ?>">

                        <?php if(isset($erroresCampos['nombre_mascota'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['nombre_mascota']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha nacimiento</label>

                        <input type="date" name="fecha_nacimiento"
                            class="form-control <?= isset($erroresCampos['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($mascota['fecha_nacimiento'] ?? '') ?>">

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
                            <option value="M" <?= (($mascota['sexo'] ?? '') == 'M') ? 'selected' : '' ?>>Macho</option>
                            <option value="H" <?= (($mascota['sexo'] ?? '') == 'H') ? 'selected' : '' ?>>Hembra</option>
                        </select>

                        <?php if(isset($erroresCampos['sexo'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['sexo']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso (kg) <span style="color:#dc2626;">*</span></label>

                        <input type="number" step="0.01" min="0.1" name="peso"
                            class="form-control <?= isset($erroresCampos['peso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($mascota['peso'] ?? '') ?>">

                        <?php if(isset($erroresCampos['peso'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['peso']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control"
                            value="<?= htmlspecialchars($mascota['color'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edad</label>

                        <input type="number" min="0" name="edad"
                            class="form-control <?= isset($erroresCampos['edad']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($mascota['edad'] ?? '') ?>">

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
                                <?= (($mascota['id_especie'] ?? '') == $e['id_especie']) ? 'selected' : '' ?>>
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
                                <?= (($mascota['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>>
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
                        <i class="fas fa-save mr-1"></i> Guardar cambios
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