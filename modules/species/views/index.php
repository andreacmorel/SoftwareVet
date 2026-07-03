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
            <i class="fas fa-pen"></i>
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
<link href="../../css/indexspe.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-dna mr-2"></i> Especies
        </h1>
        <div class="page-subtitle">Gestión de especies y razas</div>
    </div>

    <div class="d-flex">
        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nueva Especie
        </a>

        <button class="btn btn-success ml-2"
                onclick="window.location.href='reporte_excel.php'">
            <i class="fas fa-file-excel"></i>
        </button>
    </div>
</div>

<form method="GET" class="filter-card">
    <div class="row align-items-end">

        <div class="col-md-10">
            <label>Buscar</label>
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por especie o raza" 
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
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

<div class="modal fade" id="modalEliminar" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content" style="border-radius:15px; overflow:hidden;">

<div style="background:#52266E; color:white; padding:15px;">
    <h5><i class="fas fa-exclamation-triangle"></i> Confirmar eliminación</h5>
</div>

<div class="text-center p-4">
    <p>¿Eliminar esta especie?</p>
    <h5 id="nombreEliminar" style="color:#52266E;"></h5>
</div>

<div class="d-flex justify-content-end p-3">
    <button class="btn btn-light mr-2" data-dismiss="modal">Cancelar</button>
    <a href="#" id="btnEliminar" class="btn btn-danger">Eliminar</a>
</div>

</div>
</div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>

// Se ejecuta cuando se está por abrir el modal de eliminaciÃ³n.
$('#modalEliminar').on('show.bs.modal', function (event) {

    // Obtiene el botón que activá o abrirá el modal.
    var boton = $(event.relatedTarget);

    // Coloca dentro del modal el nombre del registro que se quiere eliminar.
    // Ese nombre viene desde el atributo data-nombre del botón.
    $('#nombreEliminar').text(boton.data('nombre'));

    // Arma dinámicamente el enlace de eliminaciÃ³n.
    // Toma el ID desde data-id y lo enviá por URL al archivo delete.php.
    $('#btnEliminar').attr('href', 'delete.php?id=' + boton.data('id'));
});

</script>

<script>

// Espera 3.5 segundos antes de ocultar el mensaje de éxito.
setTimeout(() => {

    // Busca en la página si existe una alerta de éxito.
    const alerta = document.querySelector('.vet-alert-success');

    // Verifica que la alerta exista.
    if(alerta){

        // Aplica una transiciÃ³n suave para la animación.
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

