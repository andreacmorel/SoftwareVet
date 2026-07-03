<?php
require_once '../../app/menu.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificar Historia Clí­nica</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style_system1.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-notes-medical mr-2"></i> Modificar Historia Clí­nica
            </h1>
            <div class="page-subtitle">Editar datos clínicos registrados</div>
        </div>
        <a href="index.php" class="btn btn-light-pro">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <?php if (!empty($errors)) { ?>
            <div class="alert-pro">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Revisá los siguientes campos:
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $e) { ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form method="POST">
            <div class="section-title">
                <i class="fas fa-paw mr-1"></i> Datos de la consulta
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Mascota</label>
                        <select name="id_mascota" class="form-control" required>
                            <option value="">Seleccione una mascota</option>

                            <?php foreach ($mascotas as $m) { ?>
                                <option value="<?= $m['id_mascota'] ?>"
                                    <?= $historia['id_mascota'] == $m['id_mascota'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nombre_mascota'] . ' - ' . $m['apellido_persona'] . ', ' . $m['nombre_persona']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control"value="<?= htmlspecialchars($historia['fecha']) ?>"required>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                <i class="fas fa-clipboard-list mr-1"></i> Notas clínicas
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"
                ><?= htmlspecialchars($historia['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Observación</label>
                <textarea name="observacion" class="form-control" rows="3"
                ><?= htmlspecialchars($historia['observacion'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="index.php" class="btn btn-light-pro mr-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>

        </form>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>

