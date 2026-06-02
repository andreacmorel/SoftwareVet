<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';
$erroresCampos = [];

$fecha = '';
$hora = '';
$motivo = '';
$id_profesional = 0;
$id_mascota = 0;

$sqlProfesionales = "
    SELECT p.id_profesional, per.nombre_persona, per.apellido_persona
    FROM profesional p
    INNER JOIN persona per ON p.id_persona = per.id_persona
    ORDER BY per.apellido_persona ASC
";

$resProfesionales = mysqli_query($conexion, $sqlProfesionales);

$sqlMascotas = "
    SELECT id_mascota, nombre_mascota
    FROM mascota
    ORDER BY nombre_mascota ASC
";

$resMascotas = mysqli_query($conexion, $sqlMascotas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fecha = trim($_POST['fecha'] ?? '');
    $hora = trim($_POST['hora'] ?? '');
    $motivo = trim($_POST['motivo'] ?? '');
    $id_profesional = (int)($_POST['id_profesional'] ?? 0);
    $id_mascota = (int)($_POST['id_mascota'] ?? 0);

    if (empty($fecha)) {
        $erroresCampos['fecha'] = "La fecha es obligatoria.";
    } elseif ($fecha < date('Y-m-d')) {
        $erroresCampos['fecha'] = "La fecha no puede ser anterior a la actual.";
    }

    if (empty($hora)) {
        $erroresCampos['hora'] = "La hora es obligatoria.";
    } elseif ($fecha == date('Y-m-d') && $hora < date('H:i')) {
        $erroresCampos['hora'] = "La hora no puede ser anterior a la actual.";
    }

    if (empty($motivo)) {
        $erroresCampos['motivo'] = "El motivo es obligatorio.";
    } elseif (strlen($motivo) < 3) {
        $erroresCampos['motivo'] = "Debe tener al menos 3 caracteres.";
    } elseif (strlen($motivo) > 150) {
        $erroresCampos['motivo'] = "No puede superar los 150 caracteres.";
    }

    if ($id_profesional <= 0) {
        $erroresCampos['id_profesional'] = "Debe seleccionar un profesional.";
    } else {
        $validarProfesional = $conexion->query("
            SELECT id_profesional
            FROM profesional
            WHERE id_profesional = $id_profesional
            LIMIT 1
        ");

        if (!$validarProfesional || $validarProfesional->num_rows == 0) {
            $erroresCampos['id_profesional'] = "El profesional seleccionado no es válido.";
        }
    }

    if ($id_mascota <= 0) {
        $erroresCampos['id_mascota'] = "Debe seleccionar una mascota.";
    } else {
        $validarMascota = $conexion->query("
            SELECT id_mascota
            FROM mascota
            WHERE id_mascota = $id_mascota
            LIMIT 1
        ");

        if (!$validarMascota || $validarMascota->num_rows == 0) {
            $erroresCampos['id_mascota'] = "La mascota seleccionada no es válida.";
        }
    }

    if (empty($erroresCampos)) {

        $validarTurno = $conexion->prepare("
            SELECT id_turno
            FROM turnos
            WHERE fecha = ?
            AND hora = ?
            AND id_profesional = ?
            LIMIT 1
        ");

        $validarTurno->bind_param("ssi", $fecha, $hora, $id_profesional);
        $validarTurno->execute();
        $resTurno = $validarTurno->get_result();

        if ($resTurno->num_rows > 0) {
            $erroresCampos['hora'] = "El profesional ya tiene un turno asignado en esa fecha y hora.";
        }

        $validarTurno->close();
    }

    if (empty($erroresCampos)) {

        $stmt = $conexion->prepare("
            INSERT INTO turnos 
            (fecha, hora, motivo, id_profesional, id_mascota)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssii",
            $fecha,
            $hora,
            $motivo,
            $id_profesional,
            $id_mascota
        );

        if ($stmt->execute()) {
            header("Location: index.php?success=1");
            exit;
        } else {
            $erroresCampos['general'] = "Error al registrar el turno.";
        }

        $stmt->close();
    }
    if (empty($hora)) {
    $erroresCampos['hora'] = "La hora es obligatoria.";
} elseif ($hora < '08:00' || $hora > '21:00') {
    $erroresCampos['hora'] = "El horario debe estar entre 08:00 y 21:00.";
} elseif ($fecha == date('Y-m-d') && $hora < date('H:i')) {
    $erroresCampos['hora'] = "La hora no puede ser anterior a la actual.";

}
}
require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Turnos</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/SoftwareVet/css/style_system.css" rel="stylesheet">

    <style>
        .form-control.is-invalid {
            border-color:#dc2626 !important;
            box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
        }

        .invalid-feedback {
            display:block;
            font-size:13px;
            font-weight:600;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-calendar-plus mr-2"></i>Registro de Turnos
    </h1>

    <div class="subtitulo-pagina">Completá los datos para registrar un nuevo turno.</div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>Nuevo Turno
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <?= htmlspecialchars($erroresCampos['general']) ?>
                </div>
            <?php } ?>

            <form method="POST" novalidate>

                <h5 class="section-title">
                    <i class="fas fa-calendar-check mr-2"></i>Datos del turno
                </h5>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="fecha">Fecha <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="date" 
                            class="form-control <?= isset($erroresCampos['fecha']) ? 'is-invalid' : '' ?>" 
                            id="fecha" 
                            name="fecha"  
                            min="<?= date('Y-m-d') ?>"
                            value="<?= htmlspecialchars($fecha) ?>"
                        >

                        <?php if (isset($erroresCampos['fecha'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['fecha']) ?>
                            </div>
                        <?php } ?>
                    </div>  

                    <div class="form-group col-md-6">
                        <label for="hora">Hora <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="time" 
    class="form-control <?= isset($erroresCampos['hora']) ? 'is-invalid' : '' ?>" 
    id="hora" 
    name="hora"
    value="<?= htmlspecialchars($hora) ?>"
    min="08:00"
    max="20:00"
    step="1800"
                        >

                        <?php if (isset($erroresCampos['hora'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['hora']) ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <div class="form-group">
                    <label for="motivo">Motivo <span style="color:#dc2626;">*</span></label>

                    <input 
                        type="text" 
                        class="form-control <?= isset($erroresCampos['motivo']) ? 'is-invalid' : '' ?>" 
                        id="motivo" 
                        name="motivo"
                        value="<?= htmlspecialchars($motivo) ?>"
                    >

                    <?php if (isset($erroresCampos['motivo'])) { ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($erroresCampos['motivo']) ?>
                        </div>
                    <?php } ?>
                </div>

                <hr>

                <h5 class="section-title">
                    <i class="fas fa-user-md mr-2"></i>
                    Profesional y paciente
                </h5>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="id_profesional">Profesional <span style="color:#dc2626;">*</span></label>

                        <select 
                            name="id_profesional" 
                            id="id_profesional" 
                            class="form-control <?= isset($erroresCampos['id_profesional']) ? 'is-invalid' : '' ?>"
                        >
                            <option value="">Seleccione un profesional</option>

                            <?php while($p = mysqli_fetch_assoc($resProfesionales)) { ?>
                                <option 
                                    value="<?= $p['id_profesional']; ?>"
                                    <?= $id_profesional == $p['id_profesional'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($p['apellido_persona'] . ", " . $p['nombre_persona']); ?>
                                </option>
                            <?php } ?>
                        </select>

                        <?php if (isset($erroresCampos['id_profesional'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['id_profesional']) ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="id_mascota">Mascota <span style="color:#dc2626;">*</span></label>

                        <select 
                            name="id_mascota" 
                            id="id_mascota" 
                            class="form-control <?= isset($erroresCampos['id_mascota']) ? 'is-invalid' : '' ?>"
                        >
                            <option value="">Seleccione una mascota</option>

                            <?php while($m = mysqli_fetch_assoc($resMascotas)) { ?>
                                <option 
                                    value="<?= $m['id_mascota']; ?>"
                                    <?= $id_mascota == $m['id_mascota'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($m['nombre_mascota']); ?>
                                </option>
                            <?php } ?>
                        </select>

                        <?php if (isset($erroresCampos['id_mascota'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['id_mascota']) ?>
                            </div>
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
                        Guardar
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