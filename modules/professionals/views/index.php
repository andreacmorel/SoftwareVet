<?php
require_once '../../app/menu.php';


if(isset($_GET['success'])) { ?>

    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro exitoso</h5>
            <p>El profesional fue registrado correctamente.</p>
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
            <h5>Cambios guardados</h5>
            <p>La información fue actualizada correctamente.</p>
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
            <h5>Registro eliminado</h5>
            <p>El profesional fue eliminado correctamente.</p>
        </div>

    </div>

<?php } ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Profesionales</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/indexprofessional.css" rel="stylesheet">
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
            Profesionales
        </li>

    </ol>
</div>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-user-md mr-2"></i> Profesionales
            </h1>
            <div class="page-subtitle">Gestión del registro de profesionales</div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <div id="botonesExportacion"></div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nuevo Profesional
            </a>

           
            
        </div>
    </div>

<form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-10">
                <label>Buscar</label>
                <input type="text" name="buscar" class="form-control"
                    placeholder="Buscar por nombre, apellido, teléfono, email o barrio"
                    value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
            </div>

            <div class="col-md-2 ">
                <button type="submit"  class="btn btn-filtro" title="Buscar">
                <i class="fas fa-search"></i>
            </button>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%"  id="tablaProfesionales">
                <thead>
                    <tr>
                        <th>Profesional</th>
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
                    if ($profesionales->num_rows > 0){
                        while ($row = $profesionales->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="prof-icon">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                        <div>
                                            <div class="prof-name">
                                                <?= htmlspecialchars($row->nombre_persona . ' ' . $row->apellido_persona) ?>
                                            </div>
                                            <div class="prof-id">#<?= $row->id_profesional ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted"><?= !empty($row->telefono) ? htmlspecialchars($row->telefono) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->email) ? htmlspecialchars($row->email) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->calle) ? htmlspecialchars($row->calle) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->numero_calle) ? htmlspecialchars($row->numero_calle) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->barrio) ? htmlspecialchars($row->barrio) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->manzana) ? htmlspecialchars($row->manzana) : '—' ?></td>

                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row->id_profesional ?>"
                                    class="btn-action btn-edit" title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button type="button"
                                            class="btn-action btn-delete"
                                            data-toggle="modal"
                                            data-target="#modalEliminarProfesional"
                                            data-id="<?= $row->id_profesional ?>"
                                            data-nombre="<?= htmlspecialchars($row->nombre_persona . ' ' . $row->apellido_persona) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron profesionales.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<!-- =====================================================
     MODAL CONFIRMAR ELIMINACIÓN DE PROFESIONAL
===================================================== -->

<div class="modal fade modal-eliminar"
     id="modalEliminarProfesional"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirmar eliminación
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Cerrar">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


            <!-- CUERPO -->
            <div class="modal-body">

                <div class="modal-delete-icon">
                    <i class="fas fa-user-md"></i>
                </div>

                <p class="modal-delete-question">
                    ¿Estás seguro de eliminar al profesional?
                </p>

                <div id="nombreProfesionalEliminar"
                     class="modal-delete-name">
                </div>


                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>
                        El profesional dejará de estar disponible en los
                        <strong>listados y selecciones del sistema</strong>.
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
                   id="btnConfirmarEliminarProfesional"
                   class="btn btn-modal-eliminar">

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
<!-- DataTable reutilizable de VetSys -->
<script src="../../js/vetsys-datatables.js"></script>

<script>
$('#modalEliminarProfesional').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreProfesionalEliminar').text(nombre);
    $('#btnConfirmarEliminarProfesional').attr('href', 'delete.php?id=' + id);
});
</script>

<script>

$(document).ready(function () {

    inicializarDataTableVetSys({

        tabla: '#tablaProfesionales',

        titulo: 'Listado de Profesionales',

        subtitulo: 'Gestión del registro de profesionales',

        nombreArchivo: 'Listado_Profesionales',

        columnasExportar: [0, 1, 2, 3, 4, 5, 6],

        pageLength: 10,

        orientacionPDF: 'landscape',

        anchosExcel: [
            25,
            16,
            30,
            20,
            12,
            20,
            14
        ],

        anchosPDF: [
            '18%',
            '13%',
            '21%',
            '14%',
            '10%',
            '14%',
            '10%'
        ]

    });

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

