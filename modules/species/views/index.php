<?php

require_once '../../app/menu.php';


// Verifica si viene el parámetro success por URL.
// Esto indica que una especie fue registrada correctamente.
if(isset($_GET['success'])) { ?>

    <!-- Mensaje de éxito al registrar una especie -->
    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro exitoso</h5>
            <p>La especie fue registrada correctamente.</p>
        </div>

    </div>

<?php } ?>

<?php if(isset($_GET['updated'])) { ?>

    <!-- Mensaje de éxito al modificar una especie -->
    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-pen"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Cambios guardados</h5>
            <p>La especie fue modificada correctamente.</p>
        </div>

    </div>

<?php } ?>

<?php if(isset($_GET['deleted'])) { ?>

    <!-- Mensaje de éxito al eliminar una especie -->
    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-trash"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro eliminado</h5>
            <p>La especie fue eliminada correctamente.</p>
        </div>

    </div>

<?php } ?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Listado de Especies</title>
<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../../css/indexspecies.css" rel="stylesheet">
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
            Especies
        </li>

    </ol>
</div>
<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-dna mr-2"></i> Especies
        </h1>
        <div class="page-subtitle">Gestión de especies y razas</div>
    </div>

    <div id="botonesExportacion" class="mb-3"></div>

    <div class="d-flex">
        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nueva Especie
        </a>
    </div>
</div>

<form method="GET" class="filter-card">
    <div class="row align-items-end">

        <div class="col-md-10">
            <label>Buscar</label>
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por especie o raza" 
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

<table class="table table-hover" width="100%" id="tablaEspecies">

<thead>
<tr>
    <th>Especie</th>
    <th>Raza</th>
    <th class="text-center" style="width:120px;">Acciones</th>
</tr>
</thead>

<tbody>
<?php
if ($especies->num_rows > 0) {
    while ($row = $especies->fetch_object()) { ?>
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <span class="especie-icon">
                        <i class="fas fa-dna"></i>
                    </span>
                    <div class="especie-name">
                        <?= htmlspecialchars($row->nombre_especie) ?>
                    </div>
                </div>
            </td>

            <td class="dato-muted">
                <?= htmlspecialchars($row->raza) ?>
            </td>

            <td class="text-center">

                <a href="edit.php?id=<?= $row->id_especie ?>"
                class="btn-action btn-edit">
                    <i class="fas fa-pen"></i>
                </a>

                <button class="btn-action btn-delete"
                        data-toggle="modal"
                        data-target="#modalEliminar"
                        data-id="<?= $row->id_especie ?>"
                        data-nombre="<?= htmlspecialchars($row->nombre_especie . ' - ' . $row->raza) ?>">
                    <i class="fas fa-trash"></i>
                </button>

            </td>

        </tr>
<?php }
} else { ?>
<tr>
<td colspan="3" class="text-center text-muted py-4">
    <i class="fas fa-search mr-1"></i>
    No se encontraron especies.
</td>
</tr>
<?php } ?>

</tbody>

</table>

</div>
</div>

</div>

<!-- =========================================================
     MODAL ELIMINAR ESPECIE
========================================================= -->

<div class="modal fade modal-eliminar"
     id="modalEliminar"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Confirmar eliminación
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
                    ¿Está seguro que desea eliminar esta especie?
                </p>

                <div>
                    <span id="nombreEliminar"
                          class="modal-delete-name">
                    </span>
                </div>

                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>
                        <strong>Esta acción no se puede deshacer.</strong>
                        La especie será eliminada del sistema.
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
                   id="btnEliminar"
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

        tabla: '#tablaEspecies',

        titulo: 'Listado de Especies',

        subtitulo: 'Gestión de especies y razas',

        nombreArchivo: 'Listado_Especies',

        columnasExportar: [0, 1],

        pageLength: 10,

        orientacionPDF: 'portrait',

        anchosExcel: [
            25,
            30
        ],

        anchosPDF: [
            '50%',
            '50%'
        ]

    });

});


// Se ejecuta cuando se está por abrir el modal de eliminación.
$('#modalEliminar').on('show.bs.modal', function (event) {

    // Obtiene el botón que activó o abrirá el modal.
    var boton = $(event.relatedTarget);

    // Coloca dentro del modal el nombre del registro que se quiere eliminar.
    // Ese nombre viene desde el atributo data-nombre del botón.
    $('#nombreEliminar').text(boton.data('nombre'));

    // Arma dinámicamente el enlace de eliminación.
    // Toma el ID desde data-id y lo envía por URL al archivo delete.php.
    $('#btnEliminar').attr('href', 'delete.php?id=' + boton.data('id'));
});



// Espera 3.5 segundos antes de ocultar el mensaje de éxito.
setTimeout(() => {

    // Busca en la página si existe una alerta de éxito.
    const alerta = document.querySelector('.vet-alert-success');

    // Verifica que la alerta exista.
    if(alerta){

        // Aplica una transición suave para la animación.
        alerta.style.transition = '.4s';

        // Hace que la alerta se vuelva transparente.
        alerta.style.opacity = '0';

        // Mueve la alerta un poco hacia arriba.
        alerta.style.transform = 'translateY(-10px)';

        // Espera que termine la animación.
        setTimeout(() => {

            // Elimina la alerta del HTML.
            alerta.remove();

        }, 400);
    }

}, 3500);
</script>
</body>
</html>