<?php
require_once '../../settings/conexion.php';
require_once '../../php/menu.php';

$buscar = trim($_GET['buscar'] ?? '');
$id_especie = (int)($_GET['id_especie'] ?? 0);
$sexo = trim($_GET['sexo'] ?? '');

$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 10;
$desde = ($pagina - 1) * $porPagina;

$where = "WHERE 1=1";

if ($buscar !== '') {
    $buscarSeguro = $conexion->real_escape_string($buscar);
    $where .= " AND (
        m.nombre_mascota LIKE '%$buscarSeguro%' 
        OR CONCAT(p.nombre_persona, ' ', p.apellido_persona) LIKE '%$buscarSeguro%'
    )";
}

if ($id_especie > 0) {
    $where .= " AND m.id_especie = $id_especie";
}

if ($sexo !== '') {
    $sexoSeguro = $conexion->real_escape_string($sexo);
    $where .= " AND m.sexo = '$sexoSeguro'";
}

$totalQuery = $conexion->query("
    SELECT COUNT(*) AS total
    FROM mascota m
    INNER JOIN especie e ON m.id_especie = e.id_especie
    INNER JOIN cliente c ON m.id_cliente = c.id_cliente
    INNER JOIN persona p ON c.id_persona = p.id_persona
    $where
");

$total = $totalQuery->fetch_object()->total;
$totalPaginas = ceil($total / $porPagina);

$mascotas = $conexion->query("
    SELECT 
        m.id_mascota,
        m.nombre_mascota,
        m.sexo,
        m.peso,
        m.edad,
        m.color,
        e.nombre_especie,
        e.raza,
        CONCAT(p.nombre_persona, ' ', p.apellido_persona) AS cliente
    FROM mascota m
    INNER JOIN especie e ON m.id_especie = e.id_especie
    INNER JOIN cliente c ON m.id_cliente = c.id_cliente
    INNER JOIN persona p ON c.id_persona = p.id_persona
    $where
    ORDER BY m.id_mascota DESC
    LIMIT $desde, $porPagina
");

$especies = $conexion->query("
    SELECT id_especie, nombre_especie
    FROM especie
    ORDER BY nombre_especie
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Mascotas</title>
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

        .badge-total {
            background: white;
            color: #52266E;
            border-radius: 20px;
            padding: 8px 15px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
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
            background: #fbf7ff;
            color: #52266E;
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

        .pet-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #fff3df;
            color: #f97316;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .pet-name {
            font-weight: 800;
            color: #111827;
        }

        .pet-id {
            color: #9ca3af;
            font-size: 12px;
        }

        .badge-raza {
            background: #ead7f7;
            color: #52266E;
            padding: 4px 9px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-macho {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 10px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-hembra {
            background: #fce7f3;
            color: #be185d;
            padding: 5px 10px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700;
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

        .btn-view {
            background: #e0f2fe;
            color: #0284c7;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        .pagination .page-link {
            color: #52266E;
            border-radius: 8px;
            margin: 0 2px;
        }

        .pagination .active .page-link {
            background: #52266E;
            border-color: #52266E;
            color: white;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-paw mr-2"></i> Mascotas
            </h1>
            <div class="page-subtitle">Gestión del registro de pacientes</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nueva Mascota
            </a>
             <a href="reporte_excel.php" class="btn btn-success ml-2" title="Exportar a Excel">
            <i class="fas fa-file-excel"></i>
        </a>
        </div>

    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-7">
                <label>Buscar</label>
                <input type="text" name="buscar" class="form-control"
                       placeholder="Nombre o propietario"
                       value="<?= htmlspecialchars($buscar) ?>">
            </div>

            <div class="col-md-2">
                <label>Especie</label>
                <select name="id_especie" class="form-control">
                    <option value="0">Todas</option>
                    <?php while ($esp = $especies->fetch_object()) { ?>
                        <option value="<?= $esp->id_especie ?>"
                            <?= $id_especie == $esp->id_especie ? 'selected' : '' ?>>
                            <?= htmlspecialchars($esp->nombre_especie) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>Sexo</label>
                <select name="sexo" class="form-control">
                    <option value="">Todos</option>
                    <option value="Macho" <?= $sexo == 'Macho' ? 'selected' : '' ?>>Macho</option>
                    <option value="Hembra" <?= $sexo == 'Hembra' ? 'selected' : '' ?>>Hembra</option>
                </select>
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-purple btn-block">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Especie / Raza</th>
                        <th>Sexo</th>
                        <th>Peso</th>
                        <th>Edad</th>
                        <th>Color</th>
                        <th>Propietario</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($mascotas->num_rows > 0) { ?>
                        <?php while ($row = $mascotas->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="pet-icon">
                                            <i class="fas fa-dog"></i>
                                        </span>
                                        <div>
                                            <div class="pet-name"><?= htmlspecialchars($row->nombre_mascota) ?></div>
                                            <div class="pet-id">#<?= $row->id_mascota ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row->nombre_especie) ?></strong><br>
                                    <span class="badge-raza"><?= htmlspecialchars($row->raza) ?></span>
                                </td>

                                <td>
                                    <?php if ($row->sexo == 'H') { ?>
                                        <span class="badge-hembra">
                                        <i class="fas fa-venus"></i> Hembra
                                        </span>
                                    <?php } elseif ($row->sexo == 'M') { ?>
                                        <span class="badge-macho">
                                        <i class="fas fa-mars"></i> Macho
                                         </span>
                                     <?php } else { ?>
                                           —
                                        <?php } ?>
                                </td>
                                <td>
                                    <?= !empty($row->peso) ? htmlspecialchars($row->peso) . ' <small class="text-muted">kg</small>' : '—' ?>
                                </td>

                                <td>
                                    <?= !empty($row->edad) ? htmlspecialchars($row->edad) . ' años' : '—' ?>
                                </td>

                                <td>
                                    <?= !empty($row->color) ? htmlspecialchars($row->color) : '—' ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row->cliente) ?></strong>
                                </td>

                                <td class="text-center">
                                    <a href="pet_record.php?id=<?= $row->id_mascota ?>"
                                       class="btn-action btn-view" title="Ver ficha">
                                        <i class="fas fa-file-medical"></i>
                                    </a>

                                    <a href="edit.php?id=<?= $row->id_mascota ?>"
                                       class="btn-action btn-edit" title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="btn-action btn-delete"
                                         data-toggle="modal"
                                         data-target="#modalEliminar"
                                         data-id="<?= $row->id_mascota ?>"
                                         data-nombre="<?=($row->nombre_mascota) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron mascotas.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

    <?php if ($totalPaginas > 1) { ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-end">

                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                        <a class="page-link"
                           href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>&id_especie=<?= $id_especie ?>&sexo=<?= urlencode($sexo) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php } ?>

            </ul>
        </nav>
    <?php } ?>

</div>

<div class="modal fade" id="modalEliminar" tabindex="-1">
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

                <i class="fas fa-paw fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar a</p>

                <h5 id="nombreMascotaEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>. Se eliminarán también sus historias clínicas y turnos asociados.
                </p>

            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">

                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
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
$('#modalEliminar').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreMascotaEliminar').text(nombre);
    $('#btnConfirmarEliminar').attr('href', 'delete.php?id=' + id);
});
</script>
</body>
</html>