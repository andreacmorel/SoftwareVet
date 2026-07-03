<?php
require_once '../../app/menu.php';
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
<link href="../../css/index_user.css" rel="stylesheet">

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
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por usuario, email o perfil"
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
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
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Usuario</th>
    <th>Email</th>
    <th class="text-center">Perfil</th>
    <th class="text-center">Estado</th>
    <th class="text-center acciones-th">Acciones</th>
</tr>
</thead>

<tbody>
<!-- Verifica si existen usuarios para mostrar -->
<?php if ($usuarios && $usuarios->num_rows > 0) { ?>
<!-- Recorre todos los usuarios encontrados -->
<?php while ($user = $usuarios->fetch_object()) { ?>

<tr class="<?= $user->estado == 0 ? 'inactivo-row' : '' ?>">
<!-- Datos del usuario -->
<td>
    <div class="d-flex align-items-center">
        <span class="user-icon mr-2">
            <i class="fas fa-user"></i>
        </span>

        <div>
            <div class="user-name">
                <?= htmlspecialchars($user->nombre ?? '') ?>
            </div>

            <div class="user-id">
                #<?= $user->id_usuario ?>
            </div>
        </div>
    </div>
</td>

<td>
    <?= htmlspecialchars($user->apellido ?? '') ?>
</td>

<td>
    <?= htmlspecialchars($user->usuario ?? '') ?>
</td>

<td class="dato-muted">
    <?= htmlspecialchars($user->email ?? '') ?>
</td>

<td class="text-center">
    <span class="perfil-badge">
        <?= htmlspecialchars($user->nombre_perfil ?? '') ?>
    </span>
</td>

<!-- Columna que muestra el estado actual del usuario -->
<td class="text-center">
    <!-- Verifica si el usuario se encuentra activo -->
    <?php if ($user->estado == 1) { ?>
    <!-- Muestra la etiqueta "Activo" con estilo verde -->
        <span class="badge-estado activo">Activo</span>
    <?php } else { ?>
    <!-- Si el estado es 0, muestra la etiqueta "Inactivo" con estilo rojo -->
        <span class="badge-estado inactivo">Inactivo</span>
    <?php } ?>
</td>

<td class="acciones-td">
    <div class="acciones-wrap">
    <!-- Botón para activar o desactivar usuario -->
    <button class="btn-action <?= $user->estado == 1 ? 'btn-desactivar' : 'btn-activar' ?>"
    data-toggle="modal"
    data-target="#modalEstadoUsuario"
    data-id="<?= $user->id_usuario ?>"
    data-usuario="<?= htmlspecialchars($user->usuario) ?>"
    data-estado="<?= $user->estado ?>">

    <i class="<?= $user->estado == 1 ? 'fas fa-user-lock' : 'fas fa-user-check' ?>"></i>

</button>
    <!-- Botón para modificar usuario -->
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
<!-- Cuerpo del modal-->
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

    <div id="boxEstadoUsuario" style="border-radius:10px;
        padding:11px 14px;
        display:flex;
        align-items:flex-start;
        gap:10px;
        text-align:left;
        margin-top:18px;
        width:100%;
">

    <i id="iconoInfoEstado" class="fas fa-info-circle" style="font-size:14px;margin-top:2px;flex-shrink:0;">
    </i>

    <p id="mensajeInfoEstado" style="font-size:12.5px; line-height:1.55; margin:0;">
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
    // Se ejecuta cuando se abre el modal de cambio de estado.
$('#modalEstadoUsuario').on('show.bs.modal', function (event) {
    // Obtiene el botón que abre el modal.
    var button = $(event.relatedTarget);
    // Obtiene los datos del usuario.
    var id = button.data('id');
    var usuario = button.data('usuario');
    var estado = button.data('estado');
    // Muestra el nombre del usuario en el modal.
    $('#nombreEstadoUsuario').text(usuario);
    // Si el usuario está activo.
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
        // Configura el modal para desactivar usuario.
        // Cambia textos, colores, iconos y botón.
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
// Oculta automáticamente los mensajes de éxito luego de 3.5 segundos.
setTimeout(() => {

    const alerta = document.querySelector('.vet-alert-success');

    if(alerta){

        // Aplica animación de salida.
        alerta.style.transition = '.4s';
        alerta.style.opacity = '0';
        alerta.style.transform = 'translateY(-10px)';

        // Elimina la alerta del DOM.
        setTimeout(() => {
            alerta.remove();
        }, 400);
    }

}, 3500);
</script>

</body>
</html>

