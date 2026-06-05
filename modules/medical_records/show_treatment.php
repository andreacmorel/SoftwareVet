<?php
// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Obtiene el ID de la historia clínica desde la URL y lo convierte a entero
$id_historia = (int)($_GET['id'] ?? 0);

// Si el ID no es válido, redirige al listado
if ($id_historia <= 0) {
    header("Location: index.php");
    exit;
}

// Consulta preparada para obtener los datos principales de la historia clínica
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

// Vincula el ID de historia clínica a la consulta
$stmt->bind_param("i", $id_historia);

// Ejecuta la consulta
$stmt->execute();

// Obtiene los datos de la historia clínica
$historia = $stmt->get_result()->fetch_assoc();

// Cierra la consulta preparada
$stmt->close();

// Si no se encontró la historia clínica, redirige al listado
if (!$historia) {
    header("Location: index.php");
    exit;
}

// Consulta preparada para obtener los tratamientos asociados a esa historia clínica
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

// Vincula el ID de historia clínica para buscar sus tratamientos
$stmtTrat->bind_param("i", $id_historia);

// Ejecuta la consulta de tratamientos
$stmtTrat->execute();

// Guarda el resultado de los tratamientos
$tratamientos = $stmtTrat->get_result();

// Cierra la consulta preparada
$stmtTrat->close();

// Incluye el menú principal del sistema
require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Tratamientos</title>

    <!-- Importa los íconos de FontAwesome -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Importa los estilos de la plantilla SB Admin 2 -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Estilo del título principal */
        .page-title {
            font-weight: 800;
            color: #1f2937;
        }

        /* Color del ícono del título */
        .page-title i {
            color: #52266E;
        }

        /* Subtítulo de la página */
        .page-subtitle {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Botón violeta personalizado */
        .btn-purple {
            background: #52266E;
            color: white;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Efecto hover del botón violeta */
        .btn-purple:hover {
            background: #3f1d55;
            color: white;
        }

        /* Tarjeta con información de la historia clínica */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            margin-bottom: 20px;
        }

        /* Etiqueta de cada dato */
        .info-label {
            font-size: 12px;
            font-weight: 800;
            color: #52266E;
            text-transform: uppercase;
        }

        /* Valor de cada dato */
        .info-value {
            color: #374151;
            font-weight: 600;
        }

        /* Tarjeta individual de tratamiento */
        .trat-card {
            background: #f8fffe;
            border: 1.5px solid #c8e6c9;
            border-left: 6px solid #1e8449;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,.04);
        }

        /* Título de cada tratamiento */
        .trat-title {
            color: #1e8449;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        /* Etiquetas dentro del tratamiento */
        .trat-label {
            font-size: 11px;
            font-weight: 800;
            color: #15803d;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        /* Texto de los datos del tratamiento */
        .trat-text {
            color: #374151;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Tarjeta que se muestra cuando no existen tratamientos */
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

    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-prescription-bottle-alt mr-2"></i> Tratamientos
            </h1>
            <div class="page-subtitle">
                Detalle de tratamientos asociados a la historia clínica
            </div>
        </div>

        <!-- Botón para volver al listado de historias clínicas -->
        <a href="index.php" class="btn btn-purple">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Tarjeta con datos principales de la historia clínica -->
    <div class="info-card">
        <div class="row">

            <!-- Nombre de la mascota -->
            <div class="col-md-4">
                <div class="info-label">Mascota</div>
                <div class="info-value">
                    <i class="fas fa-paw mr-1" style="color:#52266E;"></i>
                    <?= htmlspecialchars($historia['nombre_mascota']) ?>
                </div>
            </div>

            <!-- Fecha de la historia clínica -->
            <div class="col-md-4">
                <div class="info-label">Fecha</div>
                <div class="info-value">
                    <?= date('d/m/Y', strtotime($historia['fecha'])) ?>
                </div>
            </div>

            <!-- Número de historia clínica -->
            <div class="col-md-4">
                <div class="info-label">Historia Clínica N°</div>
                <div class="info-value">
                    <?= $historia['id_historia_clinica'] ?>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">

            <!-- Descripción de la historia clínica -->
            <div class="col-md-6">
                <div class="info-label">Descripción</div>
                <div class="info-value">
                    <?= !empty($historia['descripcion_historia']) ? htmlspecialchars($historia['descripcion_historia']) : '—' ?>
                </div>
            </div>

            <!-- Observación de la historia clínica -->
            <div class="col-md-6">
                <div class="info-label">Observación</div>
                <div class="info-value">
                    <?= !empty($historia['observacion']) ? htmlspecialchars($historia['observacion']) : '—' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Si existen tratamientos, los muestra -->
    <?php if ($tratamientos && $tratamientos->num_rows > 0) { ?>

        <!-- Recorre cada tratamiento asociado a la historia clínica -->
        <?php while ($t = $tratamientos->fetch_assoc()) { ?>
            <div class="trat-card">

                <!-- Título de la tarjeta de tratamiento -->
                <div class="trat-title">
                    <i class="fas fa-pills mr-1"></i> Tratamiento
                </div>

                <div class="row">

                    <!-- Duración del tratamiento -->
                    <div class="col-md-4">
                        <div class="trat-label">Duración</div>
                        <div class="trat-text">
                            <?= !empty($t['duracion']) ? htmlspecialchars($t['duracion']) : '—' ?>
                        </div>
                    </div>

                    <!-- Dosis del tratamiento -->
                    <div class="col-md-4">
                        <div class="trat-label">Dosis</div>
                        <div class="trat-text">
                            <?= !empty($t['dosis']) ? htmlspecialchars($t['dosis']) : '—' ?>
                        </div>
                    </div>

                    <!-- Descripción del tratamiento -->
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

        <!-- Mensaje cuando no hay tratamientos asociados -->
        <div class="empty-card">
            <i class="fas fa-prescription-bottle-alt fa-3x mb-3" style="color:#c8e6c9;"></i>
            <h5 style="font-weight:800;">Sin tratamientos cargados</h5>
            <p class="mb-0">Esta historia clínica todavía no tiene tratamientos asociados.</p>
        </div>

    <?php } ?>

</div>

<!-- Scripts necesarios para Bootstrap y la plantilla -->
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>