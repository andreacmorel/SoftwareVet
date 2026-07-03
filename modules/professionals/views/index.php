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
    <link href="../../css/indexprof.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-user-md mr-2"></i> Profesionales
            </h1>
            <div class="page-subtitle">Gestión del registro de profesionales</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nuevo Profesional
            </a>

            <button class="btn btn-success ml-2"
                    onclick="window.location.href='reporte_excel.php'"
                    title="Exportar a Excel">
                <i class="fas fa-file-excel"></i>
            </button>
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

<div class="modal fade" id="modalEliminarProfesional" tabindex="-1">
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
                <i class="fas fa-user-md fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar a</p>

                <h5 id="nombreProfesionalEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarProfesional" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Sí­, eliminar
                </a>
            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

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

