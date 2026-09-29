<?php
require_once '../../app/menu.php';

if(isset($_GET['success'])) { ?>

    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro exitoso</h5>
            <p>El perfil fue registrado correctamente.</p>
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
            <p>El perfil fue modificado correctamente.</p>
        </div>

    </div>

<?php } ?>

<?php if(isset($_GET['deleted'])) { ?>

    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro eliminado</h5>
            <p>El perfil fue eliminado correctamente.</p>
        </div>

    </div>

<?php }

?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'admin') { ?>

    <div class="vet-alert-danger">

        <div class="vet-alert-danger-icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <div class="vet-alert-danger-content">
            <h5>Acción bloqueada</h5>
            <p>No se puede eliminar el perfil Administrador del sistema.</p>
        </div>

    </div>

<?php } ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Perfiles</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/indexperfil.css" rel="stylesheet">
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
            Perfiles
        </li>

    </ol>
</div>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-user-tag mr-2"></i> Perfiles
            </h1>
            <div class="page-subtitle">Gestión de perfiles y permisos del sistema</div>
        </div>

        <div id="botonesExportacion" class="mb-3"></div>

        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nuevo Perfil
        </a>
    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-10">
                <label>Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre de perfil"
                    value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
            </div>

            <div class="col-md-2">
            <button type="submit"
                    class="btn btn-filtro"
                    title="Buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">

            <table class="table table-hover" width="100%" id="tablaPerfiles">
                <thead>
                    <tr>
                        <th>Perfil</th>
                        <th class="text-center" style="width:150px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($perfiles && $perfiles->num_rows > 0) { ?>
                        <?php while ($row = $perfiles->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="perfil-icon">
                                            <i class="fas fa-user-tag"></i>
                                        </span>

                                        <div>
                                            <div class="perfil-name">
                                                <?= htmlspecialchars($row->nombre_perfil) ?>
                                            </div>
                                            <div class="perfil-id">
                                                #<?= $row->id_perfil ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <a href="assign_modules.php?id=<?= $row->id_perfil ?>"
                                    class="btn-action btn-modulos"
                                    title="Asignar módulos">
                                        <i class="fas fa-lock"></i>
                                    </a>

                                    <a href="edit.php?id=<?= $row->id_perfil ?>"
                                    class="btn-action btn-edit"
                                    title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarPerfil"
                                        data-id="<?= $row->id_perfil ?>"
                                        data-nombre="<?= htmlspecialchars($row->nombre_perfil) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron perfiles.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

</div>
<!-- =========================================================
     MODAL ELIMINAR PERFIL
========================================================= -->

<div
    class="modal fade modal-eliminar-perfil"
    id="modalEliminarPerfil"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
        role="document">

        <div class="modal-content">


            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Eliminar perfil

                </h5>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Cerrar">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


            <!-- BODY -->
            <div class="modal-body">


                <!-- ICONO -->
                <div class="modal-delete-icon">

                    <i class="fas fa-user-tag"></i>

                </div>


                <!-- PREGUNTA -->
                <p class="modal-delete-question">

                    ¿Estás seguro de eliminar el perfil?

                </p>


                <!-- NOMBRE -->
                <div>

                    <span
                        id="nombrePerfilEliminar"
                        class="modal-delete-name">
                    </span>

                </div>


                <!-- ADVERTENCIA -->
                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>

                        Esta acción es
                        <strong>irreversible</strong>.

                        El perfil será eliminado del sistema.

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="modal-footer">


                <button
                    type="button"
                    class="btn btn-modal-cancelar"
                    data-dismiss="modal">
                    Cancelar

                </button>


                <a
                    href="#"
                    id="btnConfirmarEliminarPerfil"
                    class="btn btn-modal-eliminar">

                    <i class="fas fa-trash mr-1"></i>

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

        tabla: '#tablaPerfiles',

        titulo: 'Listado de Perfiles',

        subtitulo: 'Gestión de perfiles y permisos del sistema',

        nombreArchivo: 'Listado_Perfiles',

        columnasExportar: [0],

        pageLength: 10,

        orientacionPDF: 'portrait',

        anchosExcel: [
            35
        ],

        anchosPDF: [
            '100%'
        ]

    });

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
<script>

$('#modalEliminarPerfil').on('show.bs.modal', function (event) {

    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombrePerfilEliminar').text(nombre);

    $('#btnConfirmarEliminarPerfil')
        .attr('href', 'delete.php?id=' + id);
});

</script>
</body>
</html>