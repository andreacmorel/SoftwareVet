<?php
require_once '../../app/menu.php';
?>

<?php
if(isset($_GET['success'])) { ?>

    <div class="vet-alert-success">

        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Registro exitoso</h5>
            <p>La mascota fue registrada correctamente.</p>
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mascotas</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/indexpet.css" rel="stylesheet">
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
            Mascotas
        </li>

    </ol>
</div>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-paw mr-2"></i> Mascotas
            </h1>
            <div class="page-subtitle">Gestión del registro de pacientes</div>
        </div>

        <div id="botonesExportacion" class="mr-2"></div>
        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nueva Mascota
            </a>
        </div>

    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-7">
                <label>Buscar</label>
                <input type="text" name="buscar" class="form-control"placeholder="Nombre o propietario"
                    value="<?= htmlspecialchars($_GET['buscar'] ?? '')  ?>">
            </div>

            <div class="col-md-2">
                <label>Especie</label>
                <select name="id_especie" class="form-control">
                    <option value="0">Todas</option>
                    <?php while ($esp = $especies->fetch_object()) { ?>
                        <option value="<?= $esp->id_especie ?>"
                            <?= $id_especie == $esp->id_especie ? 'selected' : '' ?>>
                            <?= htmlspecialchars($esp->nombre_especie) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>Sexo</label>
                <select name="sexo" class="form-control">
                    <option value="">Todos</option>
                    <option value="Macho" <?= $sexo == 'Macho' ? 'selected' : '' ?>>Macho</option>
                    <option value="Hembra" <?= $sexo == 'Hembra' ? 'selected' : '' ?>>Hembra</option>
                </select>
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
            <table id="tablaMascotas" class="table table-hover">
                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Especie / Raza</th>
                        <th>Sexo</th>
                        <th>Peso</th>
                        <th>Edad</th>
                        <th>Color</th>
                        <th>Propietario</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($mascotas->num_rows > 0) { ?>
                        <?php while ($row = $mascotas->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="pet-icon">
                                            <i class="fas fa-dog"></i>
                                        </span>
                                        <div>
                                            <div class="pet-name"><?= htmlspecialchars($row->nombre_mascota) ?></div>
                                            <div class="pet-id">#<?= $row->id_mascota ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row->nombre_especie) ?></strong><br>
                                    <span class="badge-raza"><?= htmlspecialchars($row->raza) ?></span>
                                </td>

                                <td>
                                    <?php
                                    // Verifica si el sexo de la mascota es H (Hembra)
                                    if ($row->sexo == 'H') {    ?>
                                    <!-- Badge visual para mascota hembra -->
                                    <span class="badge-hembra">
                                    <i class="fas fa-venus"></i> Hembra</span>
                                    <?php
                                    // Verifica si el sexo de la mascota es M (Macho)
                                    } elseif ($row->sexo == 'M') {
                                    ?>
                                        <!-- Badge visual para mascota macho -->
                                        <span class="badge-macho">
                                            <i class="fas fa-mars"></i> Macho
                                        </span>

                                    <?php

                                    // Si no tiene sexo definido o contiene otro valor
                                    } else {
                                    ?>

                                        <!-- Muestra un guion cuando no hay información -->
                                        —

                                    <?php } ?>

                                </td>
                                <td>
                                    <?= !empty($row->peso) ? htmlspecialchars($row->peso) . ' <small class="text-muted">kg</small>' : 'â€”' ?>
                                </td>

                                <td>
                                    <?php if (!empty($row->edad)) { ?>

                                        <?php
                                        // Unidad de edad guardada en la base de datos
                                        $unidad = $row->unidad_edad ?? '';

                                        // Si la edad es 1, mostramos la unidad en singular
                                        if ($row->edad == 1) {

                                            if ($unidad == 'dias') {
                                                $unidad = 'día';

                                            } elseif ($unidad == 'meses') {
                                                $unidad = 'mes';

                                            } elseif ($unidad == 'años') {
                                                $unidad = 'año';
                                            }

                                        } else {

                                            // Para edades mayores a 1
                                            if ($unidad == 'dias') {
                                                $unidad = 'días';
                                            }
                                        }
                                        ?>

                                        <span class="mascota-edad">
                                            <?= htmlspecialchars($row->edad) ?>
                                            <?= htmlspecialchars($unidad) ?>
                                        </span>

                                    <?php } else { ?>

                                        —

                                    <?php } ?>
                                </td>

                                <td>
                                    <?= !empty($row->color) ? htmlspecialchars($row->color) : '—' ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row->cliente) ?></strong>
                                </td>

                                <td class="text-center">
                                    <a href="pet_record.php?id=<?= $row->id_mascota ?>"
                                    class="btn-action btn-view" title="Ver ficha">
                                        <i class="fas fa-file-medical"></i>
                                    </a>

                                    <a href="edit.php?id=<?= $row->id_mascota ?>"
                                    class="btn-action btn-edit" title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminar"
                                        data-id="<?= $row->id_mascota ?>"
                                        data-nombre="<?=($row->nombre_mascota) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron mascotas.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<!-- =====================================================
     MODAL CONFIRMAR ELIMINACIÓN
===================================================== -->

<div class="modal fade modal-eliminar" id="modalEliminar" tabindex="-1">

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
                    ¿Estás seguro de eliminar a
                </p>

                <div id="nombreMascotaEliminar"
                     class="modal-delete-name">
                </div>


                <!-- ADVERTENCIA -->
                <div class="modal-delete-warning">

                    <i class="fas fa-exclamation-circle"></i>

                    <div>
                        Esta acción es <strong>irreversible</strong>.
                        Se eliminarán también sus historias clínicas
                        y turnos asociados.
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
                   id="btnConfirmarEliminar"
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
<!-- Necesario para exportar a Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- Necesario para exportar a PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- Exportación Excel, PDF e Imprimir -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="../../js/vetsys-datatables.js"></script>

<script>

// =====================================================
// DATATABLE MASCOTAS
// =====================================================

const tablaMascotas = inicializarDataTableVetSys({

    // Tabla que vamos a convertir en DataTable
    tabla: '#tablaMascotas',

    // Título utilizado en las exportaciones
    titulo: 'Listado de Mascotas',

    // Subtítulo para PDF / impresión
    subtitulo: 'Gestión del registro de pacientes',

    // Nombre de los archivos descargados
    nombreArchivo: 'VetSys_Mascotas',

    // No exportar la última columna (Acciones)
    columnasExportar: ':not(:last-child)',

    // Cantidad de registros por página
    pageLength: 10,


    // =================================================
    // ANCHOS DEL EXCEL
    // =================================================

    anchosExcel: [
        18, // Mascota
        25, // Especie / Raza
        13, // Sexo
        13, // Peso
        15, // Edad
        22, // Color
        28  // Propietario
    ],

    anchosPDF: [
    65,  // Mascota
    100, // Especie / Raza
    50,  // Sexo
    55,  // Peso
    60,  // Edad
    70,  // Color
    90   // Propietario
    ],


    // =================================================
    // FORMATO ESPECIAL DE MASCOTAS
    // =================================================

    formatearCelda: function (
        texto,
        data,
        row,
        column,
        node
    ) {

        // ---------------------------------------------
        // MASCOTA
        // ---------------------------------------------

        if (column === 0) {

            // Sacamos el ID interno:
            // #26, #1, etc.
            const celda = $(node).clone();

            celda.find('.pet-id').remove();

            texto = celda
                .text()
                .replace(/\s+/g, ' ')
                .trim();
        }


        // ---------------------------------------------
        // ESPECIE / RAZA
        // ---------------------------------------------

        if (column === 1) {

            const especie = $(node)
                .find('strong')
                .first()
                .text()
                .trim();

            const raza = $(node)
                .find('.badge-raza')
                .text()
                .trim();


            if (especie && raza) {

                texto =
                    especie + ' - ' + raza;

            } else if (especie) {

                texto = especie;

            } else if (raza) {

                texto = raza;
            }
        }


        // ---------------------------------------------
        // EDAD
        // ---------------------------------------------

        if (column === 4) {

            if (
                texto === '—' ||
                texto === '' ||
                texto === 'â€”'
            ) {

                texto = 'Sin especificar';
            }
        }


        // ---------------------------------------------
        // COLOR
        // ---------------------------------------------

        if (column === 5) {

            if (
                texto === '—' ||
                texto === '' ||
                texto === 'â€”'
            ) {

                texto = 'Sin especificar';
            }
        }


        return texto;
    }

});

$('#modalEliminar').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreMascotaEliminar').text(nombre);
    $('#btnConfirmarEliminar').attr('href', 'delete.php?id=' + id);
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

