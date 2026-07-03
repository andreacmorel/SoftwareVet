<?php
require_once '../../app/menu.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Modificar Turno</title>
<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
<link href="/SoftwareVet/css/style_system_edit.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-calendar-edit mr-2"></i>
        Modificar Turno
    </h1>

    <div class="subtitulo-pagina">
        Actualizá los datos del turno seleccionado.
    </div>

<div class="card card-form mb-4">

    <div class="card-header-form">
        <h5>
            <i class="fas fa-edit mr-2"></i>
            Editar Turno
        </h5>
    </div>

<div class="card-body">

    <?php if (isset($erroresCampos['general'])) { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erroresCampos['general']) ?>
        </div>
    <?php } ?>

<form method="POST" novalidate>

    <h5 class="section-title">
        <i class="fas fa-calendar-check mr-2"></i>
        Datos del turno
    </h5>

<div class="row">

    <div class="form-group col-md-6">
        <label>Fecha <span style="color:#dc2626;">*</span></label>
        <input type="date"name="fecha"
            class="form-control <?= isset($erroresCampos['fecha']) ? 'is-invalid' : '' ?>"
            min="<?= date('Y-m-d') ?>"
            value="<?= htmlspecialchars($fecha) ?>">

        <?php if(isset($erroresCampos['fecha'])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['fecha']) ?></div>
        <?php } ?>
    </div>

    <div class="form-group col-md-6">
        <label>Hora <span style="color:#dc2626;">*</span></label>

        <input type="time"name="hora"
        class="form-control <?= isset($erroresCampos['hora']) ? 'is-invalid' : '' ?>"
        value="<?= htmlspecialchars($hora) ?>">

        <?php if(isset($erroresCampos['hora'])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['hora']) ?></div>
        <?php } ?>
    </div>

</div>

    <div class="form-group">
        <label>Motivo <span style="color:#dc2626;">*</span></label>
        <input type="text" name="motivo"
            class="form-control <?= isset($erroresCampos['motivo']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($motivo) ?>">

        <?php if(isset($erroresCampos['motivo'])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['motivo']) ?></div>
        <?php } ?>
    </div>

<hr>

    <h5 class="section-title">
        <i class="fas fa-user-md mr-2"></i>
        Profesional y paciente
    </h5>

<div class="row">

    <div class="form-group col-md-6">
        <label>Profesional <span style="color:#dc2626;">*</span></label>

        <select name="id_profesional" class="form-control <?= isset($erroresCampos['id_profesional']) ? 'is-invalid' : '' ?>">
            <option value="">Seleccione un profesional</option>

            <?php while ($p = $profesionales->fetch_object()) { ?>
                <option value="<?= $p->id_profesional ?>" <?= $p->id_profesional == $id_profesional ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p->nombre) ?>
                </option>
            <?php } ?>
        </select>

        <?php if(isset($erroresCampos['id_profesional'])) { ?>
            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_profesional']) ?></div>
        <?php } ?>
    </div>

<div class="form-group col-md-6">
    <label>Mascota <span style="color:#dc2626;">*</span></label>

    <select name="id_mascota" class="form-control <?= isset($erroresCampos['id_mascota']) ? 'is-invalid' : '' ?>">
        <option value="">Seleccione una mascota</option>

        <?php while ($m = $mascotas->fetch_object()) { ?>
            <option value="<?= $m->id_mascota ?>" <?= $m->id_mascota == $id_mascota ? 'selected' : '' ?>>
                <?= htmlspecialchars($m->nombre_mascota) ?>
            </option>
        <?php } ?>
    </select>

    <?php if(isset($erroresCampos['id_mascota'])) { ?>
        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_mascota']) ?></div>
    <?php } ?>
</div>

</div>

<hr>

<div class="d-flex justify-content-between">
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

