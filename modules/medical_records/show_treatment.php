<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$id_historia = (int)($_GET['id'] ?? 0);

if ($id_historia <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conexion->prepare("
    SELECT 
        h.id_historia_clinica,
        h.fecha,
        h.descripcion AS descripcion_historia,
        h.observacion,
        m.nombre_mascota
    FROM historia_clinica h
    INNER JOIN mascota m ON h.id_mascota = m.id_mascota
    WHERE h.id_historia_clinica = ?
");

$stmt->bind_param("i", $id_historia);
$stmt->execute();
$historia = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$historia) {
    header("Location: index.php");
    exit;
}

$stmtTrat = $conexion->prepare("
    SELECT 
        t.id_tratamiento,
        t.duracion,
        t.dosis,
        t.descripcion
    FROM detalle_historia_clinica dh
    INNER JOIN tratamientos t ON dh.id_tratamiento = t.id_tratamiento
    WHERE dh.id_historia_clinica = ?
");

$stmtTrat->bind_param("i", $id_historia);
$stmtTrat->execute();
$tratamientos = $stmtTrat->get_result();
$stmtTrat->close();

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Tratamientos</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .page-title {
            font-weight: 800;
            color: #1f2937;
        }

        .page-title i {
            color: #52266E;
        }

        .page-subtitle {
            color: #9ca3af;
            font-size: 14px;
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

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 800;
            color: #52266E;
            text-transform: uppercase;
        }

        .info-value {
            color: #374151;
            font-weight: 600;
        }

        .trat-card {
            background: #f8fffe;
            border: 1.5px solid #c8e6c9;
            border-left: 6px solid #1e8449;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,.04);
        }

        .trat-title {
            color: #1e8449;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .trat-label {
            font-size: 11px;
            font-weight: 800;
            color: #15803d;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .trat-text {
            color: #374151;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .empty-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            text-align: center;
            color: #9ca3af;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-prescription-bottle-alt mr-2"></i> Tratamientos
            </h1>
            <div class="page-subtitle">
                Detalle de tratamientos asociados a la historia clínica
            </div>
        </div>

        <a href="index.php" class="btn btn-purple">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="info-card">
        <div class="row">
            <div class="col-md-4">
                <div class="info-label">Mascota</div>
                <div class="info-value">
                    <i class="fas fa-paw mr-1" style="color:#52266E;"></i>
                    <?= htmlspecialchars($historia['nombre_mascota']) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-label">Fecha</div>
                <div class="info-value">
                    <?= date('d/m/Y', strtotime($historia['fecha'])) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-label">Historia Clínica N°</div>
                <div class="info-value">
                    <?= $historia['id_historia_clinica'] ?>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="info-label">Descripción</div>
                <div class="info-value">
                    <?= !empty($historia['descripcion_historia']) ? htmlspecialchars($historia['descripcion_historia']) : '—' ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-label">Observación</div>
                <div class="info-value">
                    <?= !empty($historia['observacion']) ? htmlspecialchars($historia['observacion']) : '—' ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($tratamientos && $tratamientos->num_rows > 0) { ?>

        <?php while ($t = $tratamientos->fetch_assoc()) { ?>
            <div class="trat-card">
                <div class="trat-title">
                    <i class="fas fa-pills mr-1"></i> Tratamiento
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="trat-label">Duración</div>
                        <div class="trat-text">
                            <?= !empty($t['duracion']) ? htmlspecialchars($t['duracion']) : '—' ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="trat-label">Dosis</div>
                        <div class="trat-text">
                            <?= !empty($t['dosis']) ? htmlspecialchars($t['dosis']) : '—' ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="trat-label">Descripción</div>
                        <div class="trat-text">
                            <?= !empty($t['descripcion']) ? htmlspecialchars($t['descripcion']) : '—' ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    <?php } else { ?>

        <div class="empty-card">
            <i class="fas fa-prescription-bottle-alt fa-3x mb-3" style="color:#c8e6c9;"></i>
            <h5 style="font-weight:800;">Sin tratamientos cargados</h5>
            <p class="mb-0">Esta historia clínica todavía no tiene tratamientos asociados.</p>
        </div>

    <?php } ?>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>