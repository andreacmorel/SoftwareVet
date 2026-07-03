<?php
require_once '../../app/menu.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Listado de Módulos</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../../css/index_module.css" rel="stylesheet">

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
                    value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
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

    <!-- Verifica que la consulta se haya ejecutado correctamente y que existan módulos para mostrar -->
    <?php if ($modulos && $modulos->num_rows > 0) { ?>

        <!-- Recorre todos los módulos obtenidos desde la base de datos -->
        <?php while ($row = $modulos->fetch_object()) { ?>

            <!-- Si el módulo está inactivo se aplica una clase especial a la fila -->
            <tr class="<?= $row->estado == 0 ? 'inactivo-row' : '' ?>">

                <!-- Columna con datos principales del módulo -->
                <td>

                    <div class="d-flex align-items-center">

                        <!-- Muestra el ícono del módulo -->
                        <span class="modulo-icon">

                            <!-- Si existe un ícono lo muestra,caso contrario utiliza uno por defecto -->
                            <i class="<?= !empty($row->icono) ? htmlspecialchars($row->icono) : 'fas fa-th-large' ?>"></i>

                        </span>

                        <div>

                            <!-- Nombre del módulo -->
                            <div class="modulo-name">
                                <?= htmlspecialchars($row->nombre_modulo) ?>
                            </div>

                            <!-- ID del módulo -->
                            <div class="modulo-id">
                                #<?= $row->id_modulo ?>
                            </div>

                        </div>

                    </div>

                </td>

                <!-- Columna que muestra la ruta del módulo -->
                <td>

                    <span class="ruta-badge">
                        <?= htmlspecialchars($row->ruta) ?>
                    </span>

                </td>

                <!-- Columna que muestra el nombre del ícono utilizado -->
                <td class="dato-muted">

                    <!-- Verifica si el módulo tiene un ícono configurado -->
                    <?php if (!empty($row->icono)) { ?>

                        <!-- Muestra el ícono y su nombre -->
                        <i class="<?= htmlspecialchars($row->icono) ?> mr-1"></i>
                        <?= htmlspecialchars($row->icono) ?>

                    <?php } else { ?>

                        <!-- Si no tiene ícono muestra un guion -->
                        —

                    <?php } ?>

                </td>

                <!-- Columna que muestra el estado del módulo -->
                <td class="text-center">

                    <!-- Verifica si el módulo está activo -->
                    <?php if ($row->estado == 1) { ?>

                        <!-- Estado activo -->
                        <span class="badge-estado activo">Activo</span>

                    <?php } else { ?>

                        <!-- Estado inactivo -->
                        <span class="badge-estado inactivo">Inactivo</span>

                    <?php } ?>

                </td>

                <!-- Columna de acciones -->
                <td class="acciones-td">

                    <div class="acciones-wrap">

                        <!-- Botón para activar o desactivar el módulo -->
                        <button
                            class="btn-action <?= $row->estado == 1 ? 'btn-desactivar' : 'btn-activar' ?>"
                            data-toggle="modal"
                            data-target="#modalEstadoModulo"
                            data-id="<?= $row->id_modulo ?>"
                            data-nombre="<?= htmlspecialchars($row->nombre_modulo) ?>"
                            data-estado="<?= $row->estado ?>"
                            title="<?= $row->estado == 1 ? 'Desactivar módulo' : 'Activar módulo' ?>">

                            <!-- Cambia el ícono según el estado actual -->
                            <i class="<?= $row->estado == 1 ? 'fas fa-lock' : 'fas fa-check' ?>"></i>

                        </button>

                        <!-- Botón para modificar el módulo -->
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

        <!-- Mensaje mostrado cuando no existen módulos registrados -->
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