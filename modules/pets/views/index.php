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
    
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-paw mr-2"></i> Mascotas
            </h1>
            <div class="page-subtitle">Gestión del registro de pacientes</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nueva Mascota
            </a>
            <a href="reporte_excel.php" class="btn btn-success ml-2" title="Exportar a Excel">
            <i class="fas fa-file-excel"></i>
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
                <button type="submit" class="btn btn-purple btn-block">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover">
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
                                    <span class="mascota-edad">
                                    <?= htmlspecialchars($row->edad) ?>
                                        <?= htmlspecialchars($row->unidad_edad ?? '') ?>
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

<div class="modal fade" id="modalEliminar" tabindex="-1">
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

                <i class="fas fa-paw fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar a</p>

                <h5 id="nombreMascotaEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>. Se eliminaran también sus historias clínicas y turnos asociados.
                </p>

            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">

                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
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

