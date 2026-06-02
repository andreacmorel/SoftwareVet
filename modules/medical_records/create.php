<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$erroresCampos = [];

$rMas = $conexion->query("
    SELECT m.id_mascota, m.nombre_mascota, p.apellido_persona, p.nombre_persona
    FROM mascota m
    INNER JOIN cliente c ON m.id_cliente = c.id_cliente
    INNER JOIN persona p ON c.id_persona = p.id_persona
    ORDER BY m.nombre_mascota ASC
");

$mascotas = [];
while ($rm = $rMas->fetch_assoc()) {
    $mascotas[] = $rm;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idMascota   = (int)($_POST['id_mascota'] ?? 0);
    $fecha       = trim($_POST['fecha'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $observacion = trim($_POST['observacion'] ?? '');

    if ($idMascota <= 0) {
        $erroresCampos['id_mascota'] = "Debe seleccionar una mascota.";
    }

    if ($fecha === '') {
        $erroresCampos['fecha'] = "La fecha es obligatoria.";
    } elseif ($fecha > date('Y-m-d')) {
        $erroresCampos['fecha'] = "La fecha no puede ser futura.";
    }

    if ($descripcion === '') {
        $erroresCampos['descripcion'] = "La descripción es obligatoria.";
    } elseif (strlen($descripcion) < 5) {
        $erroresCampos['descripcion'] = "Debe tener al menos 5 caracteres.";
    } elseif (strlen($descripcion) > 500) {
        $erroresCampos['descripcion'] = "No puede superar los 500 caracteres.";
    }

    if (strlen($observacion) > 500) {
        $erroresCampos['observacion'] = "No puede superar los 500 caracteres.";
    }

    $tDuraciones = $_POST['trat_duracion'] ?? [];
    $tDosis      = $_POST['trat_dosis'] ?? [];
    $tDescs      = $_POST['trat_desc'] ?? [];

    foreach ($tDescs as $i => $desc) {
        $duracion = trim($tDuraciones[$i] ?? '');
        $dosis    = trim($tDosis[$i] ?? '');
        $desc     = trim($desc ?? '');

        if ($duracion !== '' || $dosis !== '' || $desc !== '') {

            if ($desc === '') {
                $erroresCampos['tratamientos'] = "Si agrega un tratamiento, debe completar la descripción.";
                break;
            }

            if (strlen($desc) > 500) {
                $erroresCampos['tratamientos'] = "La descripción del tratamiento no puede superar los 500 caracteres.";
                break;
            }

            if (strlen($duracion) > 100) {
                $erroresCampos['tratamientos'] = "La duración del tratamiento no puede superar los 100 caracteres.";
                break;
            }

            if (strlen($dosis) > 100) {
                $erroresCampos['tratamientos'] = "La dosis del tratamiento no puede superar los 100 caracteres.";
                break;
            }
        }
    }

    if (empty($erroresCampos)) {

        $stmt = $conexion->prepare("
            INSERT INTO historia_clinica 
            (fecha, descripcion, observacion, id_mascota)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("sssi", $fecha, $descripcion, $observacion, $idMascota);
        $stmt->execute();

        $idHC = $stmt->insert_id;
        $stmt->close();

        foreach ($tDuraciones as $i => $duracion) {

            $duracion = trim($duracion);
            $dosis    = trim($tDosis[$i] ?? '');
            $desc     = trim($tDescs[$i] ?? '');

            if ($duracion !== '' || $dosis !== '' || $desc !== '') {

                $stmtTrat = $conexion->prepare("
                    INSERT INTO tratamientos 
                    (duracion, dosis, descripcion)
                    VALUES (?, ?, ?)
                ");

                $stmtTrat->bind_param("sss", $duracion, $dosis, $desc);
                $stmtTrat->execute();

                $idTrat = $stmtTrat->insert_id;
                $stmtTrat->close();

                $stmtDet = $conexion->prepare("
                    INSERT INTO detalle_historia_clinica 
                    (id_historia_clinica, id_tratamiento)
                    VALUES (?, ?)
                ");

                $stmtDet->bind_param("ii", $idHC, $idTrat);
                $stmtDet->execute();
                $stmtDet->close();
            }
        }

        header("Location: index.php?success=1");
        exit;
    }

    $postMascota = $idMascota;
    $postFecha   = $fecha;
    $postDesc    = $descripcion;
    $postObs     = $observacion;

} else {

    $postMascota = (int)($_GET['mascota'] ?? 0);
    $postFecha   = date('Y-m-d');
    $postDesc    = '';
    $postObs     = '';
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Alta Historia Clínica</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .page-title {
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .page-title i {
            color: #52266E;
        }

        .page-subtitle {
            color: #9ca3af;
            font-size: 14px;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            margin-top: 25px;
        }

        .section-title {
            color: #52266E;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 18px;
            border-bottom: 1px solid #eee1f6;
            padding-bottom: 8px;
        }

        .form-group label {
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
            box-shadow: 0 0 0 .2rem rgba(82, 38, 110, .15);
        }

        .form-control.is-invalid {
            border-color:#dc2626 !important;
            box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
        }

        .invalid-feedback {
            display:block;
            font-size:13px;
            font-weight:600;
        }

        .btn-purple {
            background: #52266E;
            color: white;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-purple:hover {
            background: #3f1d55;
            color: white;
        }

        .btn-light-pro {
            background: #f8f9fc;
            color: #6b7280;
            border-radius: 8px;
            font-weight: 600;
        }

        .trat-row {
            background: #fbf7ff;
            border: 1px solid #eee1f6;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            position: relative;
        }

        .btn-del-trat {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            border-radius: 8px;
            width: 28px;
            height: 28px;
        }

        .btn-add-trat {
            border: 1px dashed #52266E;
            color: #52266E;
            background: #fbf7ff;
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 700;
        }

        .btn-add-trat:hover {
            background: #f0e6f6;
            color: #52266E;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-notes-medical mr-2"></i> Nueva Historia Clínica
            </h1>
            <div class="page-subtitle">Registro clínico y tratamientos asociados</div>
        </div>

        <a href="index.php" class="btn btn-light-pro">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">

        <form method="POST" id="frmAlta" novalidate>

            <div class="section-title">
                <i class="fas fa-paw mr-1"></i> Datos de la consulta
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Mascota <span style="color:#dc2626;">*</span></label>

                        <select 
                            name="id_mascota" 
                            id="selMascota" 
                            class="form-control <?= isset($erroresCampos['id_mascota']) ? 'is-invalid' : '' ?>"
                        >
                            <option value="">Seleccione una mascota</option>

                            <?php foreach ($mascotas as $m) { ?>
                                <option value="<?= $m['id_mascota'] ?>"
                                    <?= $postMascota == $m['id_mascota'] ? 'selected' : '' ?>>
                                    HC-<?= str_pad($m['id_mascota'], 4, '0', STR_PAD_LEFT) ?>
                                    |
                                    <?= htmlspecialchars($m['nombre_mascota'] . ' - ' . $m['apellido_persona'] . ', ' . $m['nombre_persona']) ?>
                                </option>
                            <?php } ?>
                        </select>

                        <?php if(isset($erroresCampos['id_mascota'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['id_mascota']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="date" 
                            name="fecha" 
                            class="form-control <?= isset($erroresCampos['fecha']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($postFecha) ?>"
                        >

                        <?php if(isset($erroresCampos['fecha'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['fecha']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                <i class="fas fa-clipboard-list mr-1"></i> Notas clínicas
            </div>

            <div class="form-group">
                <label>Descripción <span style="color:#dc2626;">*</span></label>

                <textarea 
                    name="descripcion" 
                    class="form-control <?= isset($erroresCampos['descripcion']) ? 'is-invalid' : '' ?>" 
                    rows="3"
                    placeholder="Ej: Control general, vacunación, revisión de herida..."
                ><?= htmlspecialchars($postDesc) ?></textarea>

                <?php if(isset($erroresCampos['descripcion'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['descripcion']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label>Observación</label>

                <textarea 
                    name="observacion" 
                    class="form-control <?= isset($erroresCampos['observacion']) ? 'is-invalid' : '' ?>" 
                    rows="3"
                    placeholder="Ej: El paciente se encuentra en buen estado general..."
                ><?= htmlspecialchars($postObs) ?></textarea>

                <?php if(isset($erroresCampos['observacion'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['observacion']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="section-title mt-4">
                <i class="fas fa-pills mr-1"></i> Tratamientos
            </div>

            <?php if(isset($erroresCampos['tratamientos'])) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <?= htmlspecialchars($erroresCampos['tratamientos']) ?>
                </div>
            <?php } ?>

            <div id="tratList">
                <div class="text-center text-muted py-3" id="emptyTrat">
                    <i class="fas fa-pills fa-2x mb-2" style="color:#d8c2e8;"></i>
                    <br>
                    Ningún tratamiento agregado aún
                </div>
            </div>

            <button type="button" class="btn-add-trat mt-2" onclick="addTrat()">
                <i class="fas fa-plus"></i> Agregar tratamiento
            </button>

            <div class="d-flex justify-content-end mt-4">
                <a href="index.php" class="btn btn-light-pro mr-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-save"></i> Guardar Historia Clínica
                </button>
            </div>

        </form>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
let tratIdx = 0;

function addTrat() {
    const empty = document.getElementById('emptyTrat');

    if (empty) {
        empty.remove();
    }

    const div = document.createElement('div');
    div.className = 'trat-row';
    div.id = 'trat-' + tratIdx;

    div.innerHTML = `
        <button type="button" class="btn-del-trat" onclick="removeTrat(${tratIdx})">
            <i class="fas fa-times"></i>
        </button>

        <strong style="color:#52266E;">
            <i class="fas fa-pills mr-1"></i> Tratamiento
        </strong>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Duración</label>
                    <input 
                        type="text" 
                        name="trat_duracion[]" 
                        class="form-control"
                        placeholder="Ej: 7 días"
                    >
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Dosis</label>
                    <input 
                        type="text" 
                        name="trat_dosis[]" 
                        class="form-control"
                        placeholder="Ej: 1 comprimido cada 12 hs"
                    >
                </div>
            </div>
        </div>

        <div class="form-group mb-0">
            <label>Descripción del tratamiento</label>
            <textarea 
                name="trat_desc[]" 
                class="form-control" 
                rows="2"
                placeholder="Ej: Medicamento, indicaciones o cuidados..."
            ></textarea>
        </div>
    `;

    document.getElementById('tratList').appendChild(div);
    tratIdx++;
}

function removeTrat(id) {
    const row = document.getElementById('trat-' + id);

    if (row) {
        row.remove();
    }

    if (document.querySelectorAll('.trat-row').length === 0) {
        document.getElementById('tratList').innerHTML = `
            <div class="text-center text-muted py-3" id="emptyTrat">
                <i class="fas fa-pills fa-2x mb-2" style="color:#d8c2e8;"></i>
                <br>
                Ningún tratamiento agregado aún
            </div>
        `;
    }
}
</script>

</body>
</html>