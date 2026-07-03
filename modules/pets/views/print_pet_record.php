<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Imprimir Ficha Mascota</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/print_record_style.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5 mb-5">

    <div class="card shadow">
        <div class="card-body p-5">

            <div class="d-flex justify-content-between align-items-center">
                <img src="../../img/logoFicha.png" style="width:75px;">

                <div class="text-right">
                    <h2 style="color:#52266E; margin:0;"><b>VetSys</b></h2>
                    <p style="margin:0;">Software Veterinario</p>
                    <p class="fecha-impresion">
                        Fecha de impresión: <?= date("d/m/Y H:i") ?>
                    </p>
                </div>
            </div>

            <hr class="linea-principal">

            <h4>Datos de la Mascota</h4>
            <hr class="linea-seccion">

            <div class="row">
                <div class="col-md-6">
                    <p class="dato">
                        <b>Nombre:</b>
                        <?= htmlspecialchars($mascota['nombre_mascota'] ?? 'â€”') ?>
                    </p>

                    <p class="dato">
                        <b>Fecha de nacimiento:</b>
                        <?php
                        if (!empty($mascota['fecha_nacimiento']) && $mascota['fecha_nacimiento'] != '0000-00-00') {
                            echo date("d/m/Y", strtotime($mascota['fecha_nacimiento']));
                        } else {
                            echo 'â€”';
                        }
                        ?>
                    </p>

                    <p class="dato">
                        <b>Sexo:</b>
                        <?php
                        if ($mascota['sexo'] == 'M') {
                            echo 'Macho';
                        } elseif ($mascota['sexo'] == 'H') {
                            echo 'Hembra';
                        } else {
                            echo htmlspecialchars($mascota['sexo'] ?? 'â€”');
                        }
                        ?>
                    </p>

                    <p class="dato">
                        <b>Peso:</b>
                        <?= !empty($mascota['peso']) ? htmlspecialchars($mascota['peso']) . ' kg' : 'â€”' ?>
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="dato">
                        <b>Color:</b>
                        <?= !empty($mascota['color']) ? htmlspecialchars($mascota['color']) : 'â€”' ?>
                    </p>

                    <p class="dato">
                        <b>Edad:</b>
                            <?php if (!empty($mascota['edad'])) { ?>
                            <?= htmlspecialchars($mascota['edad']) . ' ' . htmlspecialchars($mascota['unidad_edad'] ?? '') ?>
                        <?php } else { ?>
                        —
                        <?php } ?>
                    </p>

                    <p class="dato">
                        <b>Especie:</b>
                        <?= htmlspecialchars($mascota['nombre_especie'] ?? 'â€”') ?>
                    </p>

                    <p class="dato">
                        <b>Raza:</b>
                        <?= !empty($mascota['raza']) ? htmlspecialchars($mascota['raza']) : 'â€”' ?>
                    </p>
                </div>
            </div>

            <br>

            <h4>Datos del Dueño</h4>
            <hr class="linea-seccion">

            <div class="row">
                <div class="col-md-6">
                    <p class="dato">
                        <b>Nombre:</b>
                        <?= htmlspecialchars(($mascota['nombre_persona'] ?? '') . " " . ($mascota['apellido_persona'] ?? '')) ?>
                    </p>

                    <p class="dato">
                        <b>Teléfono:</b>
                        <?= !empty($mascota['telefono']) ? htmlspecialchars($mascota['telefono']) : 'â€”' ?>
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="dato">
                        <b>Email:</b>
                        <?= !empty($mascota['email']) ? htmlspecialchars($mascota['email']) : 'â€”' ?>
                    </p>
                </div>
            </div>

            <div class="text-right mt-4 acciones">
                <a href="pet_record.php?id=<?= $id ?>" class="btn btn-secondary">
                    Volver
                </a>

                <button onclick="window.print()" class="btn" style="background:#52266E; color:white;">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>

        </div>
    </div>

</div>

</body>
</html>


