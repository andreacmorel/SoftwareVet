<?php
require_once '../../settings/conexion.php';
require_once '../../php/menu.php';

$buscar = trim($_GET['buscar'] ?? '');
$whereBuscar = "";

if (!empty($buscar)) {
    $buscarSeguro = $conexion->real_escape_string($buscar);

    $whereBuscar = " AND (
        nombre_modulo LIKE '%$buscarSeguro%' OR
        ruta LIKE '%$buscarSeguro%'
    )";
}

$sql = $conexion->query("
    SELECT id_modulo, nombre_modulo, ruta, estado
    FROM modulo
    WHERE 1=1
    $whereBuscar
    ORDER BY id_modulo DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Listado de Módulos</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>
.page-title { font-weight:800; color:#1f2937; margin-bottom:2px; }
.page-title i { color:#52266E; }
.page-subtitle { color:#9ca3af; font-size:14px; }

.btn-purple {
    background:#52266E;
    color:white;
    border-radius:8px;
    font-weight:600;
}

.btn-purple:hover {
    background:#3f1d55;
    color:white;
}

.filter-card {
    background:white;
    border-radius:15px;
    padding:18px 20px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    margin-top:25px;
    margin-bottom:25px;
}

.filter-card label {
    color:#52266E;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.filter-card .form-control {
    border-radius:8px;
    border:1px solid #d8c2e8;
    font-size:14px;
}

.table-card {
    background:white;
    border-radius:15px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    overflow:hidden;
}

.table { margin-bottom:0; }

thead th {
    background:#fbf7ff !important;
    color:#52266E !important;
    font-size:12px;
    text-transform:uppercase;
    border-bottom:2px solid #eee1f6 !important;
    font-weight:800;
}

tbody td {
    vertical-align:middle !important;
    font-size:14px;
    color:#374151;
}

tbody tr:hover { background:#fcf8ff; }

.modulo-icon {
    width:34px;
    height:34px;
    border-radius:8px;
    background:#f0e6f6;
    color:#52266E;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-right:10px;
}

.modulo-name {
    font-weight:800;
    color:#111827;
}

.modulo-id {
    color:#9ca3af;
    font-size:12px;
}

.dato-muted { color:#6b7280; }

.ruta-badge {
    background:#f3f4f6;
    color:#374151;
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}

.badge-estado {
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    font-weight:800;
    display:inline-block;
    min-width:75px;
    text-align:center;
}

.badge-estado.activo {
    background:#dcfce7;
    color:#166534;
}

.badge-estado.inactivo {
    background:#e5e7eb;
    color:#374151;
}

.inactivo-row {
    opacity: 0.6;
}

.acciones-th,
.acciones-td {
    width: 130px !important;
    min-width: 130px !important;
    max-width: 130px !important;
    text-align: center !important;
    vertical-align: middle !important;
}

.acciones-wrap {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.btn-action {
    width:32px;
    height:32px;
    min-width:32px;
    border-radius:8px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:none;
    margin:0;
    text-decoration:none;
    line-height:1;
    transition:.2s;
}

.btn-action:hover { transform:scale(1.08); }

.btn-edit { background:#fef3c7; color:#92400e; }
.btn-delete { background:#fee2e2; color:#b91c1c; }
.btn-toggle { background:#e0e7ff; color:#3730a3; }
.btn-toggle:hover { background:#c7d2fe; }
</style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-th-large mr-2"></i> Módulos
            </h1>
            <div class="page-subtitle">Gestión de módulos del sistema</div>
        </div>

        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nuevo Módulo
        </a>
    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-10">
                <label>Buscar</label>
                <input 
                    type="text"
                    name="buscar"
                    class="form-control"
                    placeholder="Buscar por nombre o ruta"
                    value="<?= htmlspecialchars($buscar) ?>"
                >
            </div>

            <div class="col-md-2">
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
                        <th>Módulo</th>
                        <th>Ruta</th>
                        <th>Ícono</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center acciones-th">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($sql && $sql->num_rows > 0) { ?>
                        <?php while ($row = $sql->fetch_object()) { ?>
                            <tr class="<?= $row->estado == 0 ? 'inactivo-row' : '' ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="modulo-icon">
                                            <i class="fas fa-th-large"></i>
                                        </span>

                                        <div>
                                            <div class="modulo-name">
                                                <?= htmlspecialchars($row->nombre_modulo) ?>
                                            </div>
                                            <div class="modulo-id">
                                                #<?= $row->id_modulo ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="ruta-badge">
                                        <?= htmlspecialchars($row->ruta) ?>
                                    </span>
                                </td>
                                 <td class="dato-muted">
                                    <?= !empty($m->icono) ? '<i class="' . htmlspecialchars($m->icono) . ' mr-1"></i>' . htmlspecialchars($m->icono) : '—' ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($row->estado == 1) { ?>
                                        <span class="badge-estado activo">Activo</span>
                                    <?php } else { ?>
                                        <span class="badge-estado inactivo">Inactivo</span>
                                    <?php } ?>
                                </td>

                                <td class="acciones-td">
                                    <div class="acciones-wrap">
                                        <a href="cambiarEstadoModulo.php?id=<?= $row->id_modulo ?>"
                                           class="btn-action btn-toggle"
                                           title="Cambiar estado"
                                           onclick="return confirm('¿Seguro que desea cambiar el estado de este módulo?')">
                                            <i class="<?= $row->estado == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off' ?>"></i>
                                        </a>

                                        <a href="edit.php?id=<?= $row->id_modulo ?>"
                                           class="btn-action btn-edit"
                                           title="Modificar">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <button type="button"
                                                class="btn-action btn-delete"
                                                data-toggle="modal"
                                                data-target="#modalEliminarModulo"
                                                data-id="<?= $row->id_modulo ?>"
                                                data-nombre="<?= htmlspecialchars($row->nombre_modulo) ?>"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron módulos.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="modalEliminarModulo" tabindex="-1">
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
                <i class="fas fa-th-large fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar este módulo?</p>

                <h5 id="nombreModuloEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarModulo" class="btn btn-danger">
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
$('#modalEliminarModulo').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);
    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreModuloEliminar').text(nombre);
    $('#btnConfirmarEliminarModulo').attr('href', 'delete.php?id=' + id);
});
</script>

</body>
</html>