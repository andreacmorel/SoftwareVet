<?php
require_once '../../app/menu.php';
?>

<!-- Mensaje de éxito al registrar un cliente -->

<?php if(isset($_GET['success'])) { ?>

    <!-- Contenedor principal de la alerta -->

    <div class="vet-alert-success">

        <!-- Icono de confirmación -->

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Contenido de la alerta -->

        <div class="vet-alert-content">

            <!-- Título del mensaje -->

            <h5>Registro exitoso</h5>

            <!-- Descripción del mensaje -->

            <p>El cliente fue registrado correctamente.</p>

        </div>

    </div>

<?php } ?>

<!-- Mensaje de éxito al modificar un cliente -->

<?php if(isset($_GET['updated'])) { ?>

    <!-- Contenedor principal de la alerta -->

    <div class="vet-alert-success">

        <!-- Icono de confirmación -->

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Contenido de la alerta -->

        <div class="vet-alert-content">

            <!-- Título del mensaje -->

            <h5>Cambios guardados</h5>

            <!-- Descripción del mensaje -->

            <p>La información fue actualizada correctamente.</p>

        </div>

    </div>

<?php } ?>

<!-- Mensaje de éxito al eliminar un cliente -->

<?php if(isset($_GET['deleted'])) { ?>

    <!-- Contenedor principal de la alerta -->

    <div class="vet-alert-success">

        <!-- Icono de confirmación -->

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Contenido de la alerta -->

        <div class="vet-alert-content">

            <!-- Título del mensaje -->

            <h5>Registro eliminado</h5>

            <!-- Descripción del mensaje -->

            <p>La información fue eliminada correctamente.</p>

        </div>

    </div>

<?php } ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Clientes</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/indexclient.css" rel="stylesheet">
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
            Clientes
        </li>

    </ol>
</div>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-users mr-2"></i> Clientes
            </h1>
            <div class="page-subtitle">Gestión del registro de clientes</div>
        </div>

        <div id="botonesExportacion" class="mr-2"></div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple" title="Agregar cliente">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </a>
        </div>

    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-10">
                <label>Buscar</label>
                <input type="text"name="buscar"class="form-control"
                    placeholder="Buscar por nombre, apellido, teléfono, email o barrio"
                    value="<?= htmlspecialchars($buscar) ?>">
            </div>

            <div class="col-md-2 ">
                <button type="submit" class="btn btn-filtro btn-block">
                <i class="fas fa-search"></i>
            </button>
            </div>
        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%" id="tablaClientes">
                <thead>
                    <tr>
                        <th>Cliente</th>
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
                    if ($clientes->num_rows > 0) {
                        while ($row = $clientes->fetch_object()) {
                    ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="cliente-icon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <div>
                                            <div class="cliente-name">
                                                <?= htmlspecialchars($row->nombre_persona . ' ' . $row->apellido_persona) ?>
                                            </div>
                                            <div class="cliente-id">#<?= $row->id_cliente ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->telefono) ? htmlspecialchars($row->telefono) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->email) ? htmlspecialchars($row->email) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->calle) ? htmlspecialchars($row->calle) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->numero_calle) ? htmlspecialchars($row->numero_calle) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->barrio) ? htmlspecialchars($row->barrio) : '—' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($row->manzana) ? ($row->manzana) : '—' ?>
                                </td>

                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row->id_cliente ?>"
                                    class="btn-action btn-edit" title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarCliente"
                                        data-id="<?= $row->id_cliente ?>"
                                        data-nombre="<?=($row->nombre_persona . ' ' . $row->apellido_persona) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron clientes.
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- =====================================================
     MODAL CONFIRMAR ELIMINACIÓN DE CLIENTE
===================================================== -->

<div class="modal fade modal-eliminar"
     id="modalEliminarCliente"
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
                    <i class="fas fa-trash-alt"></i>
                </div>

                <p class="modal-delete-question">
                    ¿Estás seguro de eliminar al cliente?
                </p>

                <div id="nombreClienteEliminar"
                     class="modal-delete-name">
                </div>


                <!-- ADVERTENCIA -->
                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>
                        Esta acción es <strong>irreversible</strong>.
                        El cliente dejará de estar disponible en el sistema.
                    </div>

                </div>

            </div>


            <!-- BOTONES -->
            <div class="modal-footer">

                <button type="button"
                        class="btn btn-modal-cancelar"
                        data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>
                    Cancelar

                </button>

                <a href="#"
                   id="btnConfirmarEliminarCliente"
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
<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Excel / PDF -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<!-- Imprimir -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- Configuración general VetSys -->
<script src="../../js/vetsys-datatables.js"></script>

<script>
// =====================================================
// DATATABLE CLIENTES
// =====================================================

const tablaClientes = inicializarDataTableVetSys({

    // Tabla
    tabla: '#tablaClientes',

    // Título de exportaciones
    titulo: 'Listado de Clientes',

    // Subtítulo
    subtitulo: 'Gestión del registro de clientes',

    // Nombre del archivo
    nombreArchivo: 'VetSys_Clientes',

    // No exportar Acciones
    columnasExportar: ':not(:last-child)',

    // Registros por página
    pageLength: 10,


    // =================================================
    // ANCHOS EXCEL
    // =================================================

anchosExcel: [
    28, // Cliente
    18, // Teléfono
    32, // Email
    25, // Calle
    18, // Número
    27, // Barrio
    18  // Manzana
],


    // =================================================
    // ANCHOS PDF
    // =================================================
anchosPDF: [
    '16%', // Cliente
    '14%', // Teléfono
    '20%', // Email
    '16%', // Calle
    '11%', // Número
    '14%', // Barrio
    '9%'   // Manzana
],

    // =================================================
    // FORMATO ESPECIAL DE CLIENTES
    // =================================================

    formatearCelda: function (
        texto,
        data,
        row,
        column,
        node
    ) {

        // ---------------------------------------------
        // CLIENTE
        // ---------------------------------------------

        if (column === 0) {

            const celda = $(node).clone();

            // No exportamos el ID visual #1, #2...
            celda.find('.cliente-id').remove();

            texto = celda
                .text()
                .replace(/\s+/g, ' ')
                .trim();
        }


        // ---------------------------------------------
        // DATOS VACÍOS
        // ---------------------------------------------

        if (
            texto === '—' ||
            texto === 'â€”' ||
            texto === ''
        ) {
            texto = 'Sin especificar';
        }


        return texto;
    }

});

$('#modalEliminarCliente').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreClienteEliminar').text(nombre);
    $('#btnConfirmarEliminarCliente').attr('href', 'delete.php?id=' + id);
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