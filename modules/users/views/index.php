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
<!-- DataTables clásico (igual que Mascotas) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

</head>

<body>

<div class="vetsys-breadcrumb-container">
    <ol class="vetsys-breadcrumb">

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i> Inicio
            </a>
        </li>

        <li class="breadcrumb-item active">
            Usuarios
        </li>

    </ol>
</div>


<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-users mr-2"></i> Usuarios
        </h1>
        <div class="page-subtitle">Gestión de usuarios del sistema</div>
    </div>

        <div id="botonesExportacion" class="mb-3"></div>

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

        <div class="col-md-11">
            <label>Buscar</label>
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por usuario, email o perfil"
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
        </div>

        <div class="col-md-1">
            <button type="submit" class="btn btn-purple btn-block btn-filtro" title="Buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

    </div>
</form>

<div class="table-card">
<div class="table-responsive">

<table class="table table-hover" width="100%"  id="tablaUsuarios">

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

        <span class="usuario-icon">
            <i class="fas fa-user"></i>
        </span>

        <div>
            <div class="usuario-name">
                <?= htmlspecialchars($user->nombre ?? '') ?>
            </div>

            <div class="usuario-id">
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
<td colspan="7" class="text-center text-muted py-4">
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
<!-- =========================================================
     MODAL ACTIVAR / DESACTIVAR USUARIO
========================================================= -->

<div class="modal fade modal-estado"
     id="modalEstadoUsuario"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Cambiar estado
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>


            <!-- BODY -->
            <div class="modal-body">

                <div class="modal-estado-icon">

                    <i id="iconoEstadoUsuario"
                       class="fas fa-user-lock">
                    </i>

                </div>


                <p class="modal-estado-question"
                   id="textoEstadoUsuario">
                </p>


                <div>
                    <span id="nombreEstadoUsuario"
                          class="modal-estado-name">
                    </span>
                </div>


                <div id="boxEstadoUsuario"
                     class="modal-estado-warning">

                    <i id="iconoInfoEstado"
                       class="fas fa-info-circle">
                    </i>

                    <p id="mensajeInfoEstado"></p>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="modal-footer">

                <button type="button"
                        class="btn btn-modal-cancelar"
                        data-dismiss="modal">

                 
                    Cancelar

                </button>


                <a href="#"
                   id="btnConfirmarEstadoUsuario"
                   class="btn btn-modal-estado">

                    <i id="iconoBotonEstado"
                       class="fas fa-user-lock mr-1">
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
<!-- DataTables clásico (igual que Mascotas) -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Imprimir -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- DataTable reutilizable VetSys -->
<script src="../../js/vetsys-datatables.js"></script>

<script>

$(document).ready(function () {

    inicializarDataTableVetSys({

        tabla: '#tablaUsuarios',

        titulo: 'Listado de Usuarios',

        subtitulo: 'Gestión de usuarios del sistema',

        nombreArchivo: 'Listado_Usuarios',

        columnasExportar: [0, 1, 2, 3, 4, 5],

        pageLength: 10,

        orientacionPDF: 'landscape',

        anchosExcel: [
            20, // Nombre
            20, // Apellido
            20, // Usuario
            30, // Email
            20, // Perfil
            15  // Estado
        ],

        anchosPDF: [
            '15%', // Nombre
            '15%', // Apellido
            '15%', // Usuario
            '25%', // Email
            '17%', // Perfil
            '13%'  // Estado
        ]

    });

});

</script>

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
        // Configura el modal para activar usuario.
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