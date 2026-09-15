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
    <link href="../../css/edit.css" rel="stylesheet">
</head>

<body>
<div class="vetsys-breadcrumb-container">
    <ol class="vetsys-breadcrumb">
        <li class="breadcrumb-item">

            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i>
                Inicio
            </a>
        </li>


        <li class="breadcrumb-item">
            <a href="/SoftwareVet/modules/medical_records/index.php">
                Historia Clínica
            </a>
        </li>

        <li class="breadcrumb-item active">
            Editar historia clínica
        </li>
    </ol>

</div>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-notes-medical mr-2"></i>
        Editar Historia Clínica
    </h1>

    <div class="subtitulo-pagina">
        Modifica los datos clínicos registrados.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos de la Historia Clínica
            </h5>
        </div>

        <div class="card-body">

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

            <div class="d-flex justify-content-between mt-4">

                <a href="index.php" class="btn btn-cancelar">
                    <i class="fas fa-times mr-1"></i>
                    Cancelar
                </a>

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-save mr-1"></i>
                    Guardar
                </button>

            </div>

        </form>

    </div>

    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>

