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

<style>

.page-title { font-weight:800; color:#1f2937; }
.page-title i { color:#52266E; }
.page-subtitle { color:#9ca3af; font-size:14px; }

.btn-purple {
    background:#52266E;
    color:white;
    border-radius:8px;
    font-weight:600;
}

.btn-purple:hover {
    background:#3f1d55;
    color:white;
}

.filter-card {
    background:white;
    border-radius:15px;
    padding:18px 20px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    margin-top:25px;
    margin-bottom:25px;
}

.filter-card label {
    color:#52266E;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.form-control {
    border-radius:8px;
    border:1px solid #d8c2e8;
}

.table-card {
    background:white;
    border-radius:15px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    overflow:hidden;
}

thead th {
    background:#fbf7ff !important;
    color:#52266E !important;
    font-size:12px;
    text-transform:uppercase;
    font-weight:800;
}

tbody td {
    vertical-align:middle !important;
}

tbody tr:hover {
    background:#fcf8ff;
}

.especie-icon {
    width:34px;
    height:34px;
    border-radius:8px;
    background:#f0e6f6;
    color:#52266E;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-right:10px;
}

.especie-name {
    font-weight:800;
    color:#111827;
}

.dato-muted {
    color:#6b7280;
}

.btn-action {
    width:31px;
    height:31px;
    border-radius:8px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:none;
    margin:0 2px;
}

.btn-edit {
    background:#fef3c7;
    color:#92400e;
}

.btn-delete {
    background:#fee2e2;
    color:#b91c1c;
}
.vet-alert-success{
    width:100%;
    background:linear-gradient(135deg,#f6fffa,#eefcf4);
    border:1px solid #d7f3e3;
    border-radius:16px;
    padding:18px 22px;
    display:flex;
    align-items:center;
    gap:16px;
    box-shadow:0 6px 18px rgba(25,135,84,.08);
    margin-bottom:25px;
    animation:fadeIn .35s ease;
}

.vet-alert-icon{
    width:48px;
    height:48px;
    min-width:48px;
    border-radius:50%;
    background:#198754;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    box-shadow:0 4px 10px rgba(25,135,84,.25);
}

.vet-alert-content h5{
    margin:0;
    font-size:15px;
    font-weight:800;
    color:#166534;
}

.vet-alert-content p{
    margin:3px 0 0;
    color:#4b5563;
    font-size:14px;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-8px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

</style>
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

// Se ejecuta cuando se está por abrir el modal de eliminación.
$('#modalEliminar').on('show.bs.modal', function (event) {

    // Obtiene el botón que activó o abrió el modal.
    var boton = $(event.relatedTarget);

    // Coloca dentro del modal el nombre del registro que se quiere eliminar.
    // Ese nombre viene desde el atributo data-nombre del botón.
    $('#nombreEliminar').text(boton.data('nombre'));

    // Arma dinámicamente el enlace de eliminación.
    // Toma el ID desde data-id y lo envía por URL al archivo delete.php.
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