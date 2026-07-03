<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de Mascota</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/createpet.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-paw mr-2"></i>
        Registro de Mascota
    </h1>

    <div class="subtitulo-pagina">
        Completá los datos para registrar un nuevo paciente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
                Nueva Mascota
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erroresCampos['general']) ?>
                </div>
            <?php } ?>

            <form method="POST" novalidate>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="nombre_mascota"
                            class="form-control <?= isset($erroresCampos['nombre_mascota']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['nombre_mascota'] ?? '') ?>">

                        <?php if(isset($erroresCampos['nombre_mascota'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['nombre_mascota']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha nacimiento</label>

                        <input type="date" name="fecha_nacimiento"
                            class="form-control <?= isset($erroresCampos['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>">

                        <?php if(isset($erroresCampos['fecha_nacimiento'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['fecha_nacimiento']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sexo <span style="color:#dc2626;">*</span></label>

                        <select name="sexo"
                            class="form-control <?= isset($erroresCampos['sexo']) ? 'is-invalid' : '' ?>">
                            <option value="">Seleccione</option>
                            <option value="M" <?= (($_POST['sexo'] ?? '') == 'M') ? 'selected' : '' ?>>Macho</option>
                            <option value="H" <?= (($_POST['sexo'] ?? '') == 'H') ? 'selected' : '' ?>>Hembra</option>
                        </select>

                        <?php if(isset($erroresCampos['sexo'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['sexo']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso (kg) <span style="color:#dc2626;">*</span></label>

                        <input type="number" step="0.01" min="0.1" name="peso"
                            class="form-control <?= isset($erroresCampos['peso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>">

                        <?php if(isset($erroresCampos['peso'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['peso']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Color</label>
                        <input type="text" name="color"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edad</label>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" min="0" name="edad"
                                    class="form-control <?= isset($erroresCampos['edad']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>"
                                    placeholder="Ej: 3">
                            </div>

                            <div class="col-md-6">
                                <select name="unidad_edad"
                                    class="form-control <?= isset($erroresCampos['unidad_edad']) ? 'is-invalid' : '' ?>">
                                    <option value="">Unidad</option>
                                    <option value="dias" <?= (($_POST['unidad_edad'] ?? '') == 'dias') ? 'selected' : '' ?>>Días</option>
                                    <option value="meses" <?= (($_POST['unidad_edad'] ?? '') == 'meses') ? 'selected' : '' ?>>Meses</option>
                                    <option value="años" <?= (($_POST['unidad_edad'] ?? '') == 'años') ? 'selected' : '' ?>>Años</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <label>Especie / Raza <span style="color:#dc2626;">*</span></label>

                    <select name="id_especie"
                        class="form-control <?= isset($erroresCampos['id_especie']) ? 'is-invalid' : '' ?>">
                        <option value="">Seleccione una especie</option>

                        <?php while($e = mysqli_fetch_assoc($resEspecies)) { ?>
                            <option value="<?= $e['id_especie'] ?>"
                                <?= (($_POST['id_especie'] ?? '') == $e['id_especie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nombre_especie']." - ".$e['raza']) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if(isset($erroresCampos['id_especie'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_especie']) ?></div>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label>Cliente <span style="color:#dc2626;">*</span></label>

                    <select name="id_cliente"
                        class="form-control <?= isset($erroresCampos['id_cliente']) ? 'is-invalid' : '' ?>">
                        <option value="">Seleccione un cliente</option>

                        <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>
                            <option value="<?= $c['id_cliente'] ?>"
                                <?= (($_POST['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['apellido_persona'].", ".$c['nombre_persona']) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if(isset($erroresCampos['id_cliente'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_cliente']) ?></div>
                    <?php } ?>
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

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

</body>
</html>

