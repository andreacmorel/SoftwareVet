<?php
require_once '../../settings/conexion.php';
require_once '../../php/menu.php';

$buscar = trim($_GET['buscar'] ?? '');
$where = "";

if (!empty($buscar)) {
    $buscarSeguro = $conexion->real_escape_string($buscar);

    $where = "WHERE 
        p.nombre_persona LIKE '%$buscarSeguro%' OR
        p.apellido_persona LIKE '%$buscarSeguro%' OR
        p.telefono LIKE '%$buscarSeguro%' OR
        p.email LIKE '%$buscarSeguro%' OR
        d.barrio LIKE '%$buscarSeguro%'
    ";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Clientes</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet">
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

        .cliente-name {
            font-weight: 800;
            color: #111827;
        }

        .cliente-id {
            color: #9ca3af;
            font-size: 12px;
        }

        .cliente-icon {
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
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-users mr-2"></i> Clientes
            </h1>
            <div class="page-subtitle">Gestión del registro de clientes</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple" title="Agregar cliente">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </a>

            <button class="btn btn-success ml-2"
                    onclick="window.location.href='reporte_excel.php'"
                    title="Exportar a Excel">
                <i class="fas fa-file-excel"></i>
            </button>
        </div>

    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-10">
                <label>Buscar</label>
                <input type="text"
                       name="buscar"
                       class="form-control"
                       placeholder="Buscar por nombre, apellido, teléfono, email o barrio"
                       value="<?= htmlspecialchars($buscar) ?>">
            </div>

            <div class="col-md-2 ">
                <button type="submit" class="btn btn-purple">
                 <i class="fas fa-filter"></i>
             </button>
            </div>
        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Calle</th>
                        <th>Número</th>
                        <th>Barrio</th>
                        <th>Manzana</th>
                        <th class="text-center" style="width:120px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $sql = $conexion->query("
                      SELECT 
                       c.id_cliente,
                       p.nombre_persona,
                       p.apellido_persona,
                       p.telefono,
                       p.email,
                       d.calle,
                       d.numero_calle,
                       d.barrio,
                       d.manzana
                       FROM cliente c
                       INNER JOIN persona p ON c.id_persona = p.id_persona
                       LEFT JOIN domicilio d ON d.id_cliente = c.id_cliente
                       $where
                       ORDER BY c.id_cliente DESC;
                    ");

                    if ($sql->num_rows > 0) {
                        while ($row = $sql->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="cliente-icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <div>
                                            <div class="cliente-name">
                                                <?= htmlspecialchars($row->nombre_persona . ' ' . $row->apellido_persona) ?>
                                            </div>
                                            <div class="cliente-id">#<?= $row->id_cliente ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->telefono) ? htmlspecialchars($row->telefono) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->email) ? htmlspecialchars($row->email) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->calle) ? htmlspecialchars($row->calle) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->numero_calle) ? htmlspecialchars($row->numero_calle) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->barrio) ? htmlspecialchars($row->barrio) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->manzana) ? ($row->manzana) : '—' ?>
                                </td>

                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row->id_cliente ?>"
                                       class="btn-action btn-edit" title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarCliente"
                                        data-id="<?= $row->id_cliente ?>"
                                        data-nombre="<?=($row->nombre_persona . ' ' . $row->apellido_persona) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron clientes.
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="modal fade" id="modalEliminarCliente" tabindex="-1">
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
                <i class="fas fa-user fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar a</p>

                <h5 id="nombreClienteEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarCliente" class="btn btn-danger">
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
$('#modalEliminarCliente').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreClienteEliminar').text(nombre);
    $('#btnConfirmarEliminarCliente').attr('href', 'delete.php?id=' + id);
});
</script>
</body>
</html>