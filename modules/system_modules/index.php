<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';
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
    SELECT id_modulo, nombre_modulo, ruta, icono, estado
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
    background:#fee2e2;
    color:#991b1b;
}

.inactivo-row {
    opacity:.55;
    background:#fafafa;
}

.acciones-th,
.acciones-td {
    width:120px !important;
    min-width:120px !important;
    max-width:120px !important;
    text-align:center !important;
    vertical-align:middle !important;
}

.acciones-wrap {
    width:100%;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
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

.btn-action:hover {
    transform:scale(1.08);
    text-decoration:none;
}

.btn-edit {
    background:#fef3c7;
    color:#92400e;
}

.btn-edit:hover {
    background:#fde68a;
    color:#78350f;
}

.btn-desactivar {
    background:#fee2e2;
    color:#b91c1c;
}

.btn-activar {
    background:#dcfce7;
    color:#166534;
}

.vet-alert-success{
    width:100%;
    background:linear-gradient(135deg,#f6fffa,#eefcf4);
    border:1px solid #d7f3e3;
    border-radius:16px;
    padding:18px 22px;
    display:flex;
    align-items:center;
    gap:16px;
    box-shadow:0 6px 18px rgba(25,135,84,.08);
    margin-bottom:25px;
    animation:fadeIn .35s ease;
}

.vet-alert-icon{
    width:48px;
    height:48px;
    min-width:48px;
    border-radius:50%;
    background:#198754;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    box-shadow:0 4px 10px rgba(25,135,84,.25);
}

.vet-alert-content h5{
    margin:0;
    font-size:15px;
    font-weight:800;
    color:#166534;
}

.vet-alert-content p{
    margin:3px 0 0;
    color:#4b5563;
    font-size:14px;
}

@keyframes fadeIn{
    from{ opacity:0; transform:translateY(-8px); }
    to{ opacity:1; transform:translateY(0); }
}
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

    <?php if(isset($_GET['success'])) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="vet-alert-content">
                <h5>Registro exitoso</h5>
                <p>El módulo fue registrado correctamente.</p>
            </div>
        </div>
    <?php } ?>

    <?php if(isset($_GET['updated'])) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="vet-alert-content">
                <h5>Cambios guardados</h5>
                <p>El módulo fue modificado correctamente.</p>
            </div>
        </div>
    <?php } ?>

    <?php if(isset($_GET['activated'])) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="vet-alert-content">
                <h5>Módulo activado</h5>
                <p>El módulo fue activado correctamente.</p>
            </div>
        </div>
    <?php } ?>

    <?php if(isset($_GET['deactivated'])) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="vet-alert-content">
                <h5>Módulo desactivado</h5>
                <p>El módulo fue desactivado correctamente.</p>
            </div>
        </div>
    <?php } ?>

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
                                            <i class="<?= !empty($row->icono) ? htmlspecialchars($row->icono) : 'fas fa-th-large' ?>"></i>
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
                                    <?php if (!empty($row->icono)) { ?>
                                        <i class="<?= htmlspecialchars($row->icono) ?> mr-1"></i>
                                        <?= htmlspecialchars($row->icono) ?>
                                    <?php } else { ?>
                                        —
                                    <?php } ?>
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

                                        <button
                                            class="btn-action <?= $row->estado == 1 ? 'btn-desactivar' : 'btn-activar' ?>"
                                            data-toggle="modal"
                                            data-target="#modalEstadoModulo"
                                            data-id="<?= $row->id_modulo ?>"
                                            data-nombre="<?= htmlspecialchars($row->nombre_modulo) ?>"
                                            data-estado="<?= $row->estado ?>"
                                            title="<?= $row->estado == 1 ? 'Desactivar módulo' : 'Activar módulo' ?>">

                                            <i class="<?= $row->estado == 1 ? 'fas fa-lock' : 'fas fa-check' ?>"></i>
                                        </button>

                                        <a href="edit.php?id=<?= $row->id_modulo ?>"
                                           class="btn-action btn-edit"
                                           title="Modificar">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
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

<div class="modal fade" id="modalEstadoModulo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">

            <div style="background:#52266E;color:white;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;">
                <h5 style="margin:0;font-weight:700;">
                    <i class="fas fa-th-large mr-2"></i>
                    Cambiar estado
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:1;">
                    &times;
                </button>
            </div>

            <div class="text-center p-4">
                <i id="iconoEstadoModulo" class="fas fa-lock fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1" id="textoEstadoModulo"></p>

                <h5 id="nombreEstadoModulo" style="color:#52266E;font-weight:800;"></h5>

                <div id="boxEstadoModulo"
                     style="border-radius:10px;padding:11px 14px;display:flex;align-items:flex-start;gap:10px;text-align:left;margin-top:18px;width:100%;">

                    <i id="iconoInfoModulo" class="fas fa-info-circle" style="font-size:14px;margin-top:2px;flex-shrink:0;"></i>

                    <p id="mensajeInfoModulo" style="font-size:12.5px;line-height:1.55;margin:0;"></p>
                </div>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px;border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>

                <a href="#" id="btnConfirmarEstadoModulo" class="btn btn-danger">
                    <i id="iconoBotonModulo" class="fas fa-lock"></i>
                    <span id="textoBotonModulo">Desactivar</span>
                </a>
            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
$('#modalEstadoModulo').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget);
    var id = button.data('id');
    var nombre = button.data('nombre');
    var estado = button.data('estado');

    $('#nombreEstadoModulo').text(nombre);

    if (estado == 1) {

        $('#textoEstadoModulo').text('¿Estás seguro de desactivar este módulo?');

        $('#mensajeInfoModulo').text('El módulo dejará de estar disponible para nuevas asignaciones de permisos.');

        $('#boxEstadoModulo').css({
            background:'#FDEDEC',
            border:'1px solid #F1948A'
        });

        $('#mensajeInfoModulo').css({ color:'#C0392B' });
        $('#iconoInfoModulo').css({ color:'#C0392B' });

        $('#btnConfirmarEstadoModulo')
            .attr('href', 'change_status.php?id=' + id)
            .removeClass('btn-success')
            .addClass('btn-danger');

        $('#textoBotonModulo').text('Desactivar');

        $('#iconoEstadoModulo')
            .removeClass('fa-check')
            .addClass('fa-lock');

        $('#iconoBotonModulo')
            .removeClass('fa-check')
            .addClass('fa-lock');

    } else {

        $('#textoEstadoModulo').text('¿Estás seguro de activar este módulo?');

        $('#mensajeInfoModulo').text('El módulo volverá a estar disponible para asignarlo a perfiles.');

        $('#boxEstadoModulo').css({
            background:'#ECFDF5',
            border:'1px solid #86EFAC'
        });

        $('#mensajeInfoModulo').css({ color:'#166534' });
        $('#iconoInfoModulo').css({ color:'#166534' });

        $('#btnConfirmarEstadoModulo')
            .attr('href', 'change_status.php?id=' + id)
            .removeClass('btn-danger')
            .addClass('btn-success');

        $('#textoBotonModulo').text('Activar');

        $('#iconoEstadoModulo')
            .removeClass('fa-lock')
            .addClass('fa-check');

        $('#iconoBotonModulo')
            .removeClass('fa-lock')
            .addClass('fa-check');
    }
});
</script>

<script>
setTimeout(() => {
    const alerta = document.querySelector('.vet-alert-success');

    if(alerta){
        alerta.style.transition = '.4s';
        alerta.style.opacity = '0';
        alerta.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            alerta.remove();
        }, 400);
    }
}, 3500);
</script>

</body>
</html>