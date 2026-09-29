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
<link href="../../css/indexappointment.css" rel="stylesheet">
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
            Turnos
        </li>

    </ol>
</div>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-calendar-check mr-2"></i> Turnos
            </h1>
            <div class="page-subtitle">Gestión del registro de turnos</div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <div id="botonesExportacion"></div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nuevo Turno
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
                <button type="submit"  class="btn btn-filtro" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%" id="tablaTurnos">
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

                                <?php
                                    // Normalizamos el estado para evitar problemas
                                    // con mayúsculas, espacios, etc.
                                    $estadoAccion = strtolower(trim($t->estado));

                                    $turnoCerrado = in_array(
                                        $estadoAccion,
                                        ['completado', 'cancelado'],
                                        true
                                    );
                                ?>


                                <?php if (!$turnoCerrado) { ?>

                                    <!-- EDITAR -->
                                    <a 
                                        href="edit.php?id=<?= $t->id_turno ?>"
                                        class="btn-action btn-edit" 
                                        title="Modificar / Reprogramar"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>


                                    <!-- ELIMINAR -->
                                    <button 
                                        type="button"
                                        class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarTurno"
                                        data-id="<?= $t->id_turno ?>"
                                        data-nombre="<?= htmlspecialchars(
                                            $t->mascota . ' - ' .
                                            date('d/m/Y', strtotime($t->fecha)) . ' ' .
                                            substr($t->hora, 0, 5),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        title="Eliminar"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                <?php } ?>

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

<!-- =========================================================
     MODAL ELIMINAR TURNO
========================================================= -->

<div 
    class="modal fade modal-eliminar"
    id="modalEliminarTurno"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalEliminarTurnoLabel"
    aria-hidden="true"
>

    <div 
        class="modal-dialog modal-dialog-centered"
        role="document"
    >

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 
                    class="modal-title"
                    id="modalEliminarTurnoLabel"
                >
                    Confirmar eliminación
                </h5>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Cerrar"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>


            <!-- BODY -->
            <div class="modal-body">

                <div class="modal-delete-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>

                <p class="modal-delete-question">
                    ¿Estás seguro de eliminar el turno?
                </p>

                <div 
                    class="modal-delete-name"
                    id="nombreTurnoEliminar"
                >
                </div>

                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>
                        Esta acción es
                        <strong>irreversible</strong>.
                        El turno dejará de estar disponible
                        en el listado.
                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-modal-cancelar"
                    data-dismiss="modal"
                >
                    <i class="fas fa-times mr-1"></i>
                    Cancelar
                </button>

                <a
                    href="#"
                    id="btnConfirmarEliminarTurno"
                    class="btn btn-modal-eliminar"
                >
                    <i class="fas fa-trash mr-1"></i>
                    Sí, eliminar
                </a>

            </div>

        </div>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<!-- DataTables -->
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
<!-- Configuración general VetSys -->
<script src="../../js/vetsys-datatables.js"></script>

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

$(document).ready(function () {

    inicializarDataTableVetSys({

        tabla: '#tablaTurnos',

        titulo: 'Listado de Turnos',

        subtitulo: 'Gestión del registro de turnos',

        nombreArchivo: 'Listado_Turnos',

        columnasExportar: [0, 1, 2, 3, 4, 5],

        pageLength: 10,

        orientacionPDF: 'landscape',

        anchosExcel: [
            20,
            18,
            22,
            22,
            45,
            15
        ],

        anchosPDF: [
            '16%',
            '15%',
            '18%',
            '18%',
            '22%',
            '11%'
        ]

    });

});
</script>

</body>
</html>


