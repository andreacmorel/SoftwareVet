<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Historia Clí­nica</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style_medicalrecord.css" rel="stylesheet">
    <link href="/SoftwareVet/css/index_style.css" rel="stylesheet">
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
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
            Historia Clínica 
        </li>

    </ol>
</div>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-notes-medical mr-2"></i> Historia Clí­nica
            </h1>
            <div class="page-subtitle">Gestión del historial clí­nico de mascotas</div>
        </div>

        <div id="botonesExportacion"></div>

        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nueva Historia Clí­nica
        </a>
    </div>

    <?php if(isset($_GET['success']) || (isset($_GET['ok']) && $_GET['ok'] == 'alta')) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-check"></i>
            </div>

            <div class="vet-alert-content">
                <h5>Registro exitoso</h5>
                <p>La historia Clí­nica fue registrada correctamente.</p>
            </div>
        </div>
    <?php } ?>

    <?php if(isset($_GET['updated'])) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-pen"></i>
            </div>

            <div class="vet-alert-content">
                <h5>Cambios guardados</h5>
                <p>La historia clí­nica fue modificada correctamente.</p>
            </div>
        </div>
    <?php } ?>

    <?php if(isset($_GET['deleted'])) { ?>
        <div class="vet-alert-success">
            <div class="vet-alert-icon">
                <i class="fas fa-trash-alt"></i>
            </div>

            <div class="vet-alert-content">
                <h5>Registro eliminado</h5>
                <p>La historia clí­nica fue eliminada correctamente.</p>
            </div>
        </div>
    <?php } ?>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-4">
                <label>Buscar</label>
                <input 
                    type="text" 
                    name="buscar" 
                    class="form-control"
                    placeholder="Mascota, descripción, observación o código HC..."
                    value="<?= htmlspecialchars($buscar) ?>"
                >
            </div>

            <div class="col-md-2">
                <label>Desde</label>
                <input 
                    type="date" 
                    name="fecha_desde" 
                    class="form-control"
                    value="<?= htmlspecialchars($fecha_desde) ?>"
                >
            </div>

            <div class="col-md-2">
                <label>Hasta</label>
                <input 
                    type="date" 
                    name="fecha_hasta" 
                    class="form-control"
                    value="<?= htmlspecialchars($fecha_hasta) ?>"
                >
            </div>

            <div class="col-md-4 d-flex">
                <button type="submit" class="btn btn-filtro" title="Filtrar">
                    <i class="fas fa-search"></i>
                </button>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%" id="tablaHistorias">
                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Observación</th>
                        <th class="text-center" style="width:150px;">Tratamiento</th>
                        <th class="text-center" style="width:150px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && $result->num_rows > 0) { ?>
                        <?php while ($h = $result->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="historia-icon">
                                            <i class="fas fa-paw"></i>
                                        </span>

                                        <div>
                                            <div class="mascota-name">
                                                <?= htmlspecialchars($h->nombre_mascota) ?>
                                            </div>
                                            <div class="hc-code">
                                                HC-<?= str_pad($h->id_historia_clinica, 5, '0', STR_PAD_LEFT) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($h->descripcion) ? htmlspecialchars($h->descripcion) : 'Sin descripción' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= date('d/m/Y', strtotime($h->fecha)) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($h->observacion) ? htmlspecialchars($h->observacion) : 'Sin observaciones' ?>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="show_treatment.php?id=<?= $h->id_historia_clinica ?>"
                                    class="btn-action btn-treatment"
                                    title="Ver tratamientos">
                                        <i class="fas fa-pills"></i>
                                    </a>
                                </td>

                                <td class="text-center align-middle">

                                <a href="print.php?id=<?= $h->id_historia_clinica ?>"
                                class="btn-action btn-view"
                                title="Ver historia clínica">
                                    <i class="fas fa-eye"></i>
                                </a>

                                    <a 
                                        href="edit.php?id=<?= $h->id_historia_clinica ?>"
                                        class="btn-action btn-edit"
                                        title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button 
                                        type="button"
                                        class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarHistoria"
                                        data-id="<?= $h->id_historia_clinica ?>"
                                        data-nombre="<?= htmlspecialchars($h->nombre_mascota . ' - ' . date('d/m/Y', strtotime($h->fecha))) ?>"
                                        title="Eliminar"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron registros de historia clínica.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<<!-- MODAL ELIMINAR HISTORIA CLÍNICA -->
<div class="modal fade modal-eliminar-historia"
     id="modalEliminarHistoria"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Eliminar historia clínica
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

                <div class="modal-delete-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>

                <p class="modal-delete-question">
                    ¿Estás seguro de que deseas eliminar esta historia clínica?
                </p>

                <div id="nombreHistoriaEliminar"
                     class="modal-delete-name">
                </div>

                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>
                        <strong>Esta acción no se puede deshacer.</strong>
                        <br>
                        El registro de la historia clínica será eliminado permanentemente.
                    </div>

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
                   id="btnConfirmarEliminarHistoria"
                   class="btn btn-modal-eliminar">

                    <i class="fas fa-trash-alt mr-1"></i>
                    Eliminar

                </a>

            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<!-- DataTables -->
<script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Imprimir -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- VetSys DataTables -->
<script src="../../js/vetsys-datatables.js"></script>

<script>

$(document).ready(function () {

    inicializarDataTableVetSys({

        tabla: '#tablaHistorias',

        titulo: 'Historias Clínicas',

        subtitulo: 'Registro de historias clínicas veterinarias',

        nombreArchivo: 'Historias_Clinicas',

        /*
         IMPORTANTE:
         Acá van solamente las columnas que queremos exportar.
         Tratamiento y Acciones NO se exportan.
        */
        columnasExportar: [0, 1, 2, 3, 4],

        pageLength: 10,

        orientacionPDF: 'landscape',

        anchosExcel: [
            18,
            25,
            25,
            35,
            35
        ],

        anchosPDF: [
            '13%',
            '17%',
            '17%',
            '26%',
            '27%'
        ]

    });

});

</script>

<script>
$('#modalEliminarHistoria').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);
    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreHistoriaEliminar').text(nombre);
    $('#btnConfirmarEliminarHistoria').attr('href', 'delete.php?id=' + id);
});

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

