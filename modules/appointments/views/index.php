<?php
require_once '../../app/menu.php';

if(isset($_GET['success'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Turno registrado</h5>
            <p>El turno fue registrado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
if(isset($_GET['updated'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Turno actualizado</h5>
            <p>Los datos del turno fueron actualizados correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
if(isset($_GET['status'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Estado actualizado</h5>
            <p>El estado del turno fue actualizado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
if(isset($_GET['deleted'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Turno eliminado</h5>
            <p>El turno fue eliminado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
// Mensaje de error cuando se intenta modificar un turno cancelado o completado
if(isset($_GET['error']) && $_GET['error'] == 'estado') { ?>
    <div class="vet-alert-error">
        <div class="vet-alert-error-icon">
            <i class="fas fa-exclamation"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Acción no permitida</h5>
            <p>No se puede modificar un turno cancelado o completado.</p>
        </div>
    </div>
<?php }

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Listado de Turnos</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="/SoftwareVet/css/index_style.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-calendar-check mr-2"></i> Turnos
            </h1>
            <div class="page-subtitle">Gestión del registro de turnos</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nuevo Turno
            </a>

            <a href="reporte_excel.php" class="btn btn-success ml-2" title="Exportar a Excel">
                <i class="fas fa-file-excel"></i>
            </a>
        </div>
    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-3">
                <label>Profesional</label>
                <select name="profesional" class="form-control">
                    <option value="">Todos</option>

                    <?php while ($pf = $resProfiltro->fetch_assoc()) { ?>
                        <option value="<?= $pf['id_profesional'] ?>"
                            <?= ($filtro_profesional == $pf['id_profesional']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pf['nombre']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendiente" <?= $filtro_estado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="confirmado" <?= $filtro_estado === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                    <option value="en_atencion" <?= $filtro_estado === 'en_atencion' ? 'selected' : '' ?>>En atenciÃ³n</option>
                    <option value="completado" <?= $filtro_estado === 'completado' ? 'selected' : '' ?>>Completado</option>
                    <option value="cancelado" <?= $filtro_estado === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Desde</label>
                <input 
                    type="date" 
                    name="fecha_desde" 
                    class="form-control"
                    value="<?= htmlspecialchars($filtro_fecha_desde) ?>"
                >
            </div>

            <div class="col-md-2">
                <label>Hasta</label>
                <input 
                    type="date" 
                    name="fecha_hasta" 
                    class="form-control"
                    value="<?= htmlspecialchars($filtro_fecha_hasta) ?>"
                >
            </div>

            <div class="col-md-3 d-flex">
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
                        <th>Fecha / Hora</th>
                        <th>Mascota</th>
                        <th>Dueño</th>
                        <th>Profesional</th>
                        <th>Motivo</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width:130px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && $result->num_rows > 0) { ?>
                        <?php while ($t = $result->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="turno-icon">
                                            <i class="fas fa-calendar-day"></i>
                                        </span>

                                        <div>
                                            <div class="turno-date">
                                                <?= date('d/m/Y', strtotime($t->fecha)) ?>
                                            </div>

                                            <div class="turno-hour">
                                                <?= substr($t->hora, 0, 5) ?> hs
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted">
                                    <strong><?= htmlspecialchars($t->mascota) ?></strong>
                                </td>

                                <td class="dato-muted">
                                    <?= htmlspecialchars($t->duenio) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= htmlspecialchars($t->profesional) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($t->motivo) ? htmlspecialchars($t->motivo) : 'â€”' ?>
                                </td>

                            <td class="text-center">

                            <?php
                                $estadosTexto = [
                                    'pendiente' => 'Pendiente',
                                    'confirmado' => 'Confirmado',
                                    'en_atencion' => 'En atención',
                                    'completado' => 'Completado',
                                    'cancelado' => 'Cancelado'
                                ];

                                $estadoActual = $t->estado;
                            ?>

                            <?php if ($estadoActual === 'completado' || $estadoActual === 'cancelado') { ?>

                                <span class="estado-pill estado-<?= htmlspecialchars($estadoActual) ?>">
                                    <?= $estadosTexto[$estadoActual] ?>
                                </span>

                            <?php } else { ?>

                        <div class="estado-dropdown">

                            <button
                                type="button"
                                class="estado-pill estado-<?= htmlspecialchars($estadoActual) ?>"
                                onclick="toggleEstadoMenu(this)">
                                <?= $estadosTexto[$estadoActual] ?>

                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>

                            <div class="estado-menu">

                                <?php foreach ($estadosTexto as $valor => $texto) { ?>

                                    <form action="change_status.php" method="POST" class="m-0">

                                        <input type="hidden" name="id_turno" value="<?= $t->id_turno ?>">
                                        <input type="hidden" name="estado" value="<?= $valor ?>">

                                        <button
                                            type="submit"
                                            class="estado-option estado-<?= $valor ?>"
                                        >
                                            <?= $texto ?>
                                        </button>

                                    </form>

                                <?php } ?>

                            </div>

                        </div>

                            <?php } ?>

                        </td>

                                <td class="text-center">

                                    <?php if ($t->estado !== 'cancelado' && $t->estado !== 'completado') { ?>
                                        <a 
                                            href="edit.php?id=<?= $t->id_turno ?>"
                                            class="btn-action btn-edit" 
                                            title="Modificar / Reprogramar"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    <?php } ?>

                                    <button 
                                        type="button"
                                        class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarTurno"
                                        data-id="<?= $t->id_turno ?>"
                                        data-nombre="<?= htmlspecialchars($t->mascota . ' - ' . date('d/m/Y', strtotime($t->fecha)) . ' ' . substr($t->hora, 0, 5)) ?>"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron turnos.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="modalEliminarTurno" tabindex="-1">
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
                <i class="fas fa-calendar-times fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar el turno?</p>

                <h5 id="nombreTurnoEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarTurno" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Sí­, eliminar
                </a>
            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>


function toggleEstadoMenu(button){

    const menu = button.nextElementSibling;

    document.querySelectorAll('.estado-menu').forEach(function(item){

        if(item !== menu){
            item.classList.remove('show');
        }

    });

    menu.classList.toggle('show');

}

document.addEventListener('click',function(e){

    if(!e.target.closest('.estado-dropdown')){

        document.querySelectorAll('.estado-menu').forEach(function(item){

            item.classList.remove('show');

        });

    }

});


$('#modalEliminarTurno').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);
    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreTurnoEliminar').text(nombre);
    $('#btnConfirmarEliminarTurno').attr('href', 'delete.php?id=' + id);
});

setTimeout(() => {

    const alerta = document.querySelector('.vet-alert-success, .vet-alert-error');

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


