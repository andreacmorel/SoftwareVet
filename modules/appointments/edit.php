<?php

// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Array donde se almacenarán los errores de validación
$erroresCampos = [];

// Obtiene el ID del turno desde la URL y lo convierte a entero
$id_turno = (int)($_GET["id"] ?? 0);

// Verifica que el ID sea válido
if ($id_turno <= 0) {

    // Redirige al listado si el ID es incorrecto
    header("Location: index.php?error=id");
    exit;
}

// Consulta para obtener los datos del turno seleccionado
$sql = $conexion->query("
    SELECT 
        id_turno,
        fecha,
        hora,
        motivo,
        id_profesional,
        id_mascota,
        estado
    FROM turnos
    WHERE id_turno = $id_turno
");

// Obtiene los datos del turno
$datos = $sql ? $sql->fetch_object() : null;

// Si no encuentra el turno, redirige al listado
if (!$datos) {
    header("Location: index.php?error=noexiste");
    exit;
}

// Evita modificar turnos completados o cancelados
if ($datos->estado == 'Completado' || $datos->estado == 'Cancelado') {
    header("Location: index.php?error=estado");
    exit;
}

// Carga los datos actuales del turno en variables
$fecha = $datos->fecha;
$hora = substr($datos->hora, 0, 5);
$motivo = $datos->motivo;
$id_profesional = (int)$datos->id_profesional;
$id_mascota = (int)$datos->id_mascota;

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibe y limpia los datos enviados por el formulario
    $fecha = trim($_POST["fecha"] ?? '');
    $hora = trim($_POST["hora"] ?? '');
    $motivo = trim($_POST["motivo"] ?? '');
    $id_profesional = (int)($_POST["id_profesional"] ?? 0);
    $id_mascota = (int)($_POST["id_mascota"] ?? 0);

    // Valida que la fecha no esté vacía
    if (empty($fecha)) {
        $erroresCampos['fecha'] = "La fecha es obligatoria.";

    // Valida que la fecha no sea anterior a la actual
    } elseif ($fecha < date('Y-m-d')) {
        $erroresCampos['fecha'] = "La fecha no puede ser anterior a la actual.";
    }

    // Valida que la hora no esté vacía
    if (empty($hora)) {
        $erroresCampos['hora'] = "La hora es obligatoria.";

    // Valida horario permitido
    } elseif ($hora < '08:00' || $hora > '20:00') {
        $erroresCampos['hora'] = "El horario debe estar entre 08:00 y 20:00.";

    // Si es hoy, valida que la hora no sea anterior a la actual
    } elseif ($fecha == date('Y-m-d') && $hora < date('H:i')) {
        $erroresCampos['hora'] = "La hora no puede ser anterior a la actual.";
    }

    // Valida que el motivo no esté vacío
    if (empty($motivo)) {
        $erroresCampos['motivo'] = "El motivo es obligatorio.";

    // Valida longitud mínima
    } elseif (strlen($motivo) < 3) {
        $erroresCampos['motivo'] = "Debe tener al menos 3 caracteres.";

    // Valida longitud máxima
    } elseif (strlen($motivo) > 150) {
        $erroresCampos['motivo'] = "No puede superar los 150 caracteres.";
    }

    // Valida que se haya seleccionado un profesional
    if ($id_profesional <= 0) {
        $erroresCampos['id_profesional'] = "Debe seleccionar un profesional.";
    }

    // Valida que se haya seleccionado una mascota
    if ($id_mascota <= 0) {
        $erroresCampos['id_mascota'] = "Debe seleccionar una mascota.";
    }

    // Si no hay errores, verifica que no exista otro turno para el mismo profesional
    if (empty($erroresCampos)) {

        // Consulta preparada para validar turnos duplicados
        $validarTurno = $conexion->prepare("
            SELECT id_turno
            FROM turnos
            WHERE fecha = ?
            AND hora = ?
            AND id_profesional = ?
            AND id_turno != ?
            LIMIT 1
        ");

        // Vincula los parámetros
        $validarTurno->bind_param("ssii", $fecha, $hora, $id_profesional, $id_turno);

        // Ejecuta la consulta
        $validarTurno->execute();

        // Obtiene el resultado
        $resTurno = $validarTurno->get_result();

        // Si existe un turno en el mismo horario para ese profesional, muestra error
        if ($resTurno->num_rows > 0) {
            $erroresCampos['hora'] = "El profesional ya posee un turno asignado para esa fecha y horario.";
        }

        // Cierra la consulta preparada
        $validarTurno->close();
    }

    // Si no existen errores, actualiza el turno
    if (empty($erroresCampos)) {

        // Consulta preparada para modificar el turno
        $stmt = $conexion->prepare("
            UPDATE turnos 
            SET fecha = ?,
                hora = ?,
                motivo = ?,
                id_profesional = ?,
                id_mascota = ?
            WHERE id_turno = ?
        ");

        // Vincula los parámetros de actualización
        $stmt->bind_param(
            "sssiii",
            $fecha,
            $hora,
            $motivo,
            $id_profesional,
            $id_mascota,
            $id_turno
        );

        // Ejecuta la actualización
        if ($stmt->execute()) {

            // Redirige al listado con mensaje de éxito
            header("Location: index.php?updated=1");
            exit;

        } else {

            // Guarda mensaje de error si falla la actualización
            $erroresCampos['general'] = "Error al modificar el turno.";
        }

        // Cierra la consulta preparada
        $stmt->close();
    }
}

// Consulta para cargar los profesionales en el select
$profesionales = $conexion->query("
    SELECT p.id_profesional, CONCAT(per.apellido_persona, ', ', per.nombre_persona) AS nombre
    FROM profesional p
    INNER JOIN persona per ON per.id_persona = p.id_persona
    ORDER BY per.apellido_persona ASC
");

// Consulta para cargar las mascotas en el select
$mascotas = $conexion->query("
    SELECT id_mascota, nombre_mascota 
    FROM mascota
    ORDER BY nombre_mascota ASC
");

// Incluye el menú principal del sistema
require_once '../../php/menu.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Modificar Turno</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>
.titulo-pagina { font-weight:800; color:#1f2937; }
.titulo-pagina i { color:#52266E; }

.subtitulo-pagina {
    color:#9ca3af;
    font-size:14px;
    margin-top:-8px;
    margin-bottom:25px;
}

.card-form {
    border:none;
    border-radius:15px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    overflow:hidden;
}

.card-header-form {
    background:#fbf7ff;
    border-bottom:1px solid #eee1f6;
    padding:18px 22px;
}

.card-header-form h5 {
    color:#52266E;
    font-weight:800;
    margin:0;
}

.card-body { padding:25px; }

label {
    color:#52266E;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.form-control {
    border-radius:8px;
    border:1px solid #d8c2e8;
    font-size:14px;
}

.form-control:focus {
    border-color:#52266E;
    box-shadow:0 0 0 3px rgba(82,38,110,.12);
}

.form-control.is-invalid {
    border-color:#dc2626 !important;
    box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
}

.invalid-feedback {
    display:block;
    margin-top:6px;
    padding:8px 10px;
    background:#FEF2F2;
    border:1px solid #FECACA;
    border-radius:8px;
    color:#B91C1C;
    font-size:13px;
    font-weight:600;
}

.section-title {
    color:#52266E;
    font-weight:800;
    font-size:15px;
    margin-bottom:18px;
}

.btn-purple {
    background:#52266E;
    color:white;
    border-radius:8px;
    font-weight:700;
    padding:8px 22px;
}

.btn-purple:hover {
    background:#3f1d55;
    color:white;
}

.btn-cancelar {
    background:#e5e7eb;
    color:#374151;
    border-radius:8px;
    font-weight:700;
    padding:8px 22px;
}

.btn-cancelar:hover {
    background:#d1d5db;
    color:#111827;
}
</style>
</head>

<body>

<div class="container-fluid">

<h1 class="h3 titulo-pagina">
    <i class="fas fa-calendar-edit mr-2"></i>
    Modificar Turno
</h1>

<div class="subtitulo-pagina">
    Actualizá o reprogramá los datos del turno seleccionado.
</div>

<div class="card card-form mb-4">

<div class="card-header-form">
    <h5>
        <i class="fas fa-edit mr-2"></i>
        Editar Turno
    </h5>
</div>

<div class="card-body">

<?php if (isset($erroresCampos['general'])) { ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($erroresCampos['general']) ?>
    </div>
<?php } ?>

<form method="POST" novalidate>

<h5 class="section-title">
    <i class="fas fa-calendar-check mr-2"></i>
    Datos del turno
</h5>

<div class="row">

<div class="form-group col-md-6">
    <label>Fecha <span style="color:#dc2626;">*</span></label>
    <input type="date"name="fecha"
        class="form-control <?= isset($erroresCampos['fecha']) ? 'is-invalid' : '' ?>"
        min="<?= date('Y-m-d') ?>"
        value="<?= htmlspecialchars($fecha) ?>">

    <?php if(isset($erroresCampos['fecha'])) { ?>
        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['fecha']) ?></div>
    <?php } ?>
</div>

<div class="form-group col-md-6">
    <label>Hora <span style="color:#dc2626;">*</span></label>

    <input type="time"name="hora"
    class="form-control <?= isset($erroresCampos['hora']) ? 'is-invalid' : '' ?>"
    value="<?= htmlspecialchars($hora) ?>">

    <?php if(isset($erroresCampos['hora'])) { ?>
        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['hora']) ?></div>
    <?php } ?>
</div>

</div>

<div class="form-group">
    <label>Motivo <span style="color:#dc2626;">*</span></label>
    <input type="text" name="motivo"
        class="form-control <?= isset($erroresCampos['motivo']) ? 'is-invalid' : '' ?>"
        value="<?= htmlspecialchars($motivo) ?>">

    <?php if(isset($erroresCampos['motivo'])) { ?>
        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['motivo']) ?></div>
    <?php } ?>
</div>

<hr>

<h5 class="section-title">
    <i class="fas fa-user-md mr-2"></i>
    Profesional y paciente
</h5>

<div class="row">

<div class="form-group col-md-6">
    <label>Profesional <span style="color:#dc2626;">*</span></label>

    <select name="id_profesional" class="form-control <?= isset($erroresCampos['id_profesional']) ? 'is-invalid' : '' ?>">
        <option value="">Seleccione un profesional</option>

        <?php while ($p = $profesionales->fetch_object()) { ?>
            <option value="<?= $p->id_profesional ?>" <?= $p->id_profesional == $id_profesional ? 'selected' : '' ?>>
                <?= htmlspecialchars($p->nombre) ?>
            </option>
        <?php } ?>
    </select>

    <?php if(isset($erroresCampos['id_profesional'])) { ?>
        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_profesional']) ?></div>
    <?php } ?>
</div>

<div class="form-group col-md-6">
    <label>Mascota <span style="color:#dc2626;">*</span></label>

    <select name="id_mascota" class="form-control <?= isset($erroresCampos['id_mascota']) ? 'is-invalid' : '' ?>">
        <option value="">Seleccione una mascota</option>

        <?php while ($m = $mascotas->fetch_object()) { ?>
            <option value="<?= $m->id_mascota ?>" <?= $m->id_mascota == $id_mascota ? 'selected' : '' ?>>
                <?= htmlspecialchars($m->nombre_mascota) ?>
            </option>
        <?php } ?>
    </select>

    <?php if(isset($erroresCampos['id_mascota'])) { ?>
        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_mascota']) ?></div>
    <?php } ?>
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

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>