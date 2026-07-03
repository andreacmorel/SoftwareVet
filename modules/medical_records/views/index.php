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
    <link href="../../css/style_system2.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-notes-medical mr-2"></i> Historia Clí­nica
            </h1>
            <div class="page-subtitle">Gestión del historial clí­nico de mascotas</div>
        </div>

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
                                    <?= !empty($h->descripcion) ? htmlspecialchars($h->descripcion) : 'â€”' ?>
                                </td>

                                <td class="dato-muted">
                                    <?= date('d/m/Y', strtotime($h->fecha)) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($h->observacion) ? htmlspecialchars($h->observacion) : 'â€”' ?>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="show_treatment.php?id=<?= $h->id_historia_clinica ?>"
                                    class="btn-action btn-treatment"
                                    title="Ver tratamientos">
                                        <i class="fas fa-pills"></i>
                                    </a>
                                </td>

                                <td class="text-center align-middle">

                                <a href="print.php?id=<?= $h->id_historia_clinica ?>&pdf=1"
                                    class="btn-action btn-print"
                                    title="Descargar PDF">
                                    <i class="fas fa-file-pdf"></i>
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

<div class="modal fade" id="modalEliminarHistoria" tabindex="-1">
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
                <i class="fas fa-notes-medical fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar este registro?</p>

                <h5 id="nombreHistoriaEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarHistoria" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Si­, eliminar
                </a>
            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

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

