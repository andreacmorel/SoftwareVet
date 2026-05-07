<?php
require_once '../../settings/conexion.php';

$buscar = $_GET['buscar'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

$where = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($buscar)) {
    $where .= " AND (
        m.nombre_mascota LIKE ? OR
        h.descripcion LIKE ? OR
        h.observacion LIKE ?
    )";

    $busqueda = "%$buscar%";
    $params[] = $busqueda;
    $params[] = $busqueda;
    $params[] = $busqueda;
    $types .= "sss";
}

if (!empty($fecha_desde)) {
    $where .= " AND h.fecha >= ?";
    $params[] = $fecha_desde;
    $types .= "s";
}

if (!empty($fecha_hasta)) {
    $where .= " AND h.fecha <= ?";
    $params[] = $fecha_hasta;
    $types .= "s";
}

$sql = "
    SELECT 
        h.id_historia_clinica,
        h.descripcion,
        h.fecha,
        h.observacion,
        m.nombre_mascota
    FROM historia_clinica h
    INNER JOIN mascota m ON h.id_mascota = m.id_mascota
    $where
    ORDER BY h.fecha DESC
";

if ($params) {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conexion->query($sql);
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Historia Clínica</title>

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

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 18px 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .filter-card label {
            color: #52266E;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .filter-card .form-control {
            border-radius: 8px;
            border: 1px solid #d8c2e8;
            font-size: 14px;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        thead th {
            background: #fbf7ff !important;
            color: #52266E !important;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #eee1f6 !important;
            font-weight: 800;
        }

        tbody td {
            vertical-align: middle !important;
            font-size: 14px;
            color: #374151;
        }

        tbody tr:hover {
            background: #fcf8ff;
        }

        .historia-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #f0e6f6;
            color: #52266E;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .mascota-name {
            font-weight: 800;
            color: #111827;
        }

        .dato-muted {
            color: #6b7280;
        }

        .btn-action {
            width: 31px;
            height: 31px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            margin: 0 2px;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        td.text-center {
            vertical-align: middle !important;
        }

        .btn-tratamiento {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #dcfce7;
            color: #15803d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-notes-medical mr-2"></i> Historia Clínica
            </h1>
            <div class="page-subtitle">Gestión del historial clínico de mascotas</div>
        </div>

        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nueva Historia Clínica
        </a>
    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-4">
                <label>Buscar</label>
                <input  type="text" name="buscar" class="form-control"placeholder="Mascota, descripción u observación..."value="<?= htmlspecialchars($buscar) ?>">
            </div>

            <div class="col-md-2">
                <label>Desde</label>
                <input type="date" name="fecha_desde" class="form-control"value="<?= htmlspecialchars($fecha_desde) ?>">
            </div>

            <div class="col-md-2">
                <label>Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control"value="<?= htmlspecialchars($fecha_hasta) ?>">
            </div>

            <div class="col-md-4 d-flex">
                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-filter"></i> 
                </button>

               <!-- <a href="index.php" class="btn btn-secondary ml-2">
                    <i class="fas fa-times"></i>-->
                </a>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Observación</th>
                        <th class="text-center" style="width:150px;">Tratamiento</th>
                        <th class="text-center" style="width:130px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && $result->num_rows > 0) { ?>
                        <?php while ($h = $result->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="historia-icon">
                                            <i class="fas fa-paw"></i>
                                        </span>

                                        <span class="mascota-name">
                                            <?= htmlspecialchars($h->nombre_mascota) ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($h->descripcion) ? htmlspecialchars($h->descripcion) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= date('d/m/Y', strtotime($h->fecha)) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($h->observacion) ? htmlspecialchars($h->observacion) : '—' ?>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="show_treatment.php?id=<?= $h->id_historia_clinica ?>"
                                       class="btn-action"
                                       style="background:#dcfce7; color:#15803d;"
                                       title="Ver tratamientos">
                                        <i class="fas fa-pills"></i>
                                    </a>
                                </td>

                                <td class="text-center align-middle">

                                    <a 
                                        href="edit.php?id=<?= $h->id_historia_clinica ?>"
                                        class="btn-action btn-edit"
                                        title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button 
                                        type="button"
                                        class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarHistoria"
                                        data-id="<?= $h->id_historia_clinica ?>"
                                        data-nombre="<?= htmlspecialchars($h->nombre_mascota . ' - ' . date('d/m/Y', strtotime($h->fecha))) ?>"
                                        title="Eliminar"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron registros de historia clínica.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="modalEliminarHistoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px; overflow:hidden; border:none;">

            <div style="background:#52266E; color:white; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
                <h5 style="margin:0; font-weight:700;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirmar eliminación
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <div class="text-center p-4">
                <i class="fas fa-notes-medical fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar este registro?</p>

                <h5 id="nombreHistoriaEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarHistoria" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Sí, eliminar
                </a>
            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
$('#modalEliminarHistoria').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);
    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreHistoriaEliminar').text(nombre);
    $('#btnConfirmarEliminarHistoria').attr('href', 'delete.php?id=' + id);
});
</script>

</body>
</html>