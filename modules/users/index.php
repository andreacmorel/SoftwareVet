<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';
require_once '../../php/menu.php';

$buscar = trim($_GET['buscar'] ?? '');
$whereBuscar = "";

if (!empty($buscar)) {
    $buscarSeguro = $conexion->real_escape_string($buscar);

    $whereBuscar = " AND (
        u.usuario LIKE '%$buscarSeguro%' OR
        u.email LIKE '%$buscarSeguro%' OR
        p.nombre_perfil LIKE '%$buscarSeguro%'
    )";
}

$usuarios = $conexion->query("
    SELECT u.id_usuario, u.usuario, u.email, u.estado, p.nombre_perfil
    FROM usuario u
    INNER JOIN perfil p ON u.id_perfil = p.id_perfil
    WHERE 1=1
    $whereBuscar
    ORDER BY u.id_usuario DESC
");
?>
<?php if(isset($_GET['activated'])) { ?>
<div class="vet-alert-success">
    <div class="vet-alert-icon">
        <i class="fas fa-user-check"></i>
    </div>

    <div class="vet-alert-content">
        <h5>Usuario activado</h5>
        <p>El usuario fue activado correctamente.</p>
    </div>
</div>
<?php } ?>

<?php if(isset($_GET['deactivated'])) { ?>
<div class="vet-alert-success">
    <div class="vet-alert-icon">
        <i class="fas fa-user-lock"></i>
    </div>

    <div class="vet-alert-content">
        <h5>Usuario desactivado</h5>
        <p>El usuario fue desactivado correctamente.</p>
    </div>
</div>
<?php } ?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Listado Usuarios</title>

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

.user-icon {
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

.user-name {
    font-weight:800;
    color:#111827;
}

.user-id {
    color:#9ca3af;
    font-size:12px;
}

.dato-muted { color:#6b7280; }

.perfil-badge {
    background:#eef2ff;
    color:#4338ca;
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:800;
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

.btn-desactivar:hover {
    background:#fecaca;
    color:#991b1b;
}

.btn-activar {
    background:#dcfce7;
    color:#166534;
}

.btn-activar:hover {
    background:#bbf7d0;
    color:#14532d;
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
    from{
        opacity:0;
        transform:translateY(-8px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

</style>
</head>

<body>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-users mr-2"></i> Usuarios
        </h1>
        <div class="page-subtitle">Gestión de usuarios del sistema</div>
    </div>

    <a href="create.php" class="btn btn-purple">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>
</div>

<?php if(isset($_GET['success'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro exitoso</h5>
            <p>El usuario fue registrado correctamente.</p>
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
            <p>El usuario fue modificado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php if(isset($_GET['status'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-sync-alt"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Estado actualizado</h5>
            <p>El estado del usuario fue actualizado correctamente.</p>
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
                placeholder="Buscar por usuario, email o perfil"
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
    <th>Usuario</th>
    <th>Email</th>
    <th class="text-center">Perfil</th>
    <th class="text-center">Estado</th>
    <th class="text-center acciones-th">Acciones</th>
</tr>
</thead>

<tbody>

<?php if ($usuarios && $usuarios->num_rows > 0) { ?>
<?php while ($user = $usuarios->fetch_object()) { ?>

<tr class="<?= $user->estado == 0 ? 'inactivo-row' : '' ?>">

<td>
    <div class="d-flex align-items-center">
        <span class="user-icon">
            <i class="fas fa-user"></i>
        </span>

        <div>
            <div class="user-name">
                <?= htmlspecialchars($user->usuario) ?>
            </div>
            <div class="user-id">
                #<?= $user->id_usuario ?>
            </div>
        </div>
    </div>
</td>

<td class="dato-muted">
    <?= htmlspecialchars($user->email) ?>
</td>

<td class="text-center">
    <span class="perfil-badge">
        <?= htmlspecialchars($user->nombre_perfil) ?>
    </span>
</td>

<td class="text-center">
    <?php if ($user->estado == 1) { ?>
        <span class="badge-estado activo">Activo</span>
    <?php } else { ?>
        <span class="badge-estado inactivo">Inactivo</span>
    <?php } ?>
</td>

<td class="acciones-td">
    <div class="acciones-wrap">

       <button
    class="btn-action <?= $user->estado == 1 ? 'btn-desactivar' : 'btn-activar' ?>"
    data-toggle="modal"
    data-target="#modalEstadoUsuario"
    data-id="<?= $user->id_usuario ?>"
    data-usuario="<?= htmlspecialchars($user->usuario) ?>"
    data-estado="<?= $user->estado ?>">

    <i class="<?= $user->estado == 1 ? 'fas fa-user-lock' : 'fas fa-user-check' ?>"></i>

</button>

        <a href="edit.php?id=<?= $user->id_usuario ?>" 
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
    No se encontraron usuarios.
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>
</div>

</div>
<div class="modal fade" id="modalEstadoUsuario" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">

            <div style="background:#52266E;color:white;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;">

                <h5 style="margin:0;font-weight:700;">
                    <i class="fas fa-user-lock mr-2"></i>
                    Cambiar estado
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        style="opacity:1;">
                    &times;
                </button>

            </div>

            <div class="text-center p-4">

                <i id="iconoEstadoUsuario"
                   class="fas fa-user-lock fa-3x mb-3"
                   style="color:#d8c2e8;">
                </i>

                <p class="mb-1" id="textoEstadoUsuario"></p>

                <h5 id="nombreEstadoUsuario"
                    style="color:#52266E;font-weight:800;">
                </h5>

               <div id="boxEstadoUsuario"
     style="
        border-radius:10px;
        padding:11px 14px;
        display:flex;
        align-items:flex-start;
        gap:10px;
        text-align:left;
        margin-top:18px;
        width:100%;
">

    <i id="iconoInfoEstado"
       class="fas fa-info-circle"
       style="font-size:14px;margin-top:2px;flex-shrink:0;">
    </i>

    <p id="mensajeInfoEstado"
       style="
            font-size:12.5px;
            line-height:1.55;
            margin:0;
       ">
    </p>

</div>

            </div>

            <div class="d-flex justify-content-end p-3"
                 style="gap:10px;border-top:1px solid #eee;">

                <button type="button"
                        class="btn btn-light"
                        data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>

                <a href="#"
                   id="btnConfirmarEstadoUsuario"
                   class="btn btn-danger">

                    <i id="iconoBotonEstado"
                       class="fas fa-user-lock">
                    </i>

                    <span id="textoBotonEstado">
                        Desactivar
                    </span>

                </a>

            </div>

        </div>

    </div>

</div>
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<script>

$('#modalEstadoUsuario').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget);

    var id = button.data('id');
    var usuario = button.data('usuario');
    var estado = button.data('estado');

    $('#nombreEstadoUsuario').text(usuario);

    if (estado == 1) {

        $('#textoEstadoUsuario').text(
            '¿Estás seguro de desactivar al usuario?'
        );

        $('#mensajeInfoEstado').text(
            'El usuario no podrá acceder al sistema mientras permanezca inactivo.'
        );

        $('#boxEstadoUsuario').css({
            background:'#FDEDEC',
            border:'1px solid #F1948A'
        });

        $('#mensajeInfoEstado').css({
            color:'#C0392B'
        });

        $('#iconoInfoEstado').css({
            color:'#C0392B'
        });

        $('#btnConfirmarEstadoUsuario')
            .attr('href', 'change_status.php?id=' + id)
            .removeClass('btn-success')
            .addClass('btn-danger');

        $('#textoBotonEstado').text('Desactivar');

        $('#iconoEstadoUsuario')
            .removeClass('fa-user-check')
            .addClass('fa-user-lock');

        $('#iconoBotonEstado')
            .removeClass('fa-user-check')
            .addClass('fa-user-lock');

    } else {

        $('#textoEstadoUsuario').text(
            '¿Estás seguro de activar al usuario?'
        );

        $('#mensajeInfoEstado').text(
            'El usuario recuperará el acceso al sistema y podrá iniciar sesión nuevamente.'
        );

        $('#boxEstadoUsuario').css({
            background:'#ECFDF5',
            border:'1px solid #86EFAC'
        });

        $('#mensajeInfoEstado').css({
            color:'#166534'
        });

        $('#iconoInfoEstado').css({
            color:'#166534'
        });

        $('#btnConfirmarEstadoUsuario')
            .attr('href', 'change_status.php?id=' + id)
            .removeClass('btn-danger')
            .addClass('btn-success');

        $('#textoBotonEstado').text('Activar');

        $('#iconoEstadoUsuario')
            .removeClass('fa-user-lock')
            .addClass('fa-user-check');

        $('#iconoBotonEstado')
            .removeClass('fa-user-lock')
            .addClass('fa-user-check');
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