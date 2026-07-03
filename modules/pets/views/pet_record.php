<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Ficha Mascota</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/pet_record_style.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-paw mr-2"></i>
        Ficha de Mascota
    </h1>

    <div class="subtitulo-pagina">
        Información completa del paciente y su propietario.
    </div>

    <div class="card card-ficha mb-4">
        <div class="card-header-ficha">
            <h6>
                <i class="fas fa-dog mr-2"></i>
                Datos de la Mascota
            </h6>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Nombre</div>
                        <div class="dato-valor">
                            <?= htmlspecialchars($mascota['nombre_mascota']) ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Fecha de nacimiento</div>
                        <div class="dato-valor">
                            <?= $fechaNacimiento ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Sexo</div>
                        <div class="dato-valor">
                            <?php if ($mascota['sexo'] == 'M') { ?>
                                <span class="badge-vet badge-macho">
                                    <i class="fas fa-mars mr-1"></i> Macho
                                </span>
                            <?php } elseif ($mascota['sexo'] == 'H') { ?>
                                <span class="badge-vet badge-hembra">
                                    <i class="fas fa-venus mr-1"></i> Hembra
                                </span>
                            <?php } else { ?>
                                <span class="text-muted-vet">No registrado</span>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Peso</div>
                        <div class="dato-valor">
                            <span class="badge-vet badge-peso">
                                <i class="fas fa-weight mr-1"></i>
                                <?= $peso ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Color</div>
                        <div class="dato-valor">
                            <?= $color ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Edad</div>
                        <div class="dato-valor">
                            <?= $edad ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Especie</div>
                        <div class="dato-valor">
                            <span class="badge-vet badge-especie">
                                <?= htmlspecialchars($mascota['nombre_especie']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Raza</div>
                        <div class="dato-valor">
                            <?= !empty($mascota['raza']) ? htmlspecialchars($mascota['raza']) : 'Sin especificar' ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-ficha mb-4">
        <div class="card-header-ficha">
            <h6>
                <i class="fas fa-user mr-2"></i>
                Datos del Dueño
            </h6>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Nombre completo</div>
                        <div class="dato-valor">
                            <?= htmlspecialchars($mascota['nombre_persona'] . " " . $mascota['apellido_persona']) ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Teléfono</div>
                        <div class="dato-valor">
                            <?= $telefono ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Email</div>
                        <div class="dato-valor">
                            <?= $email ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end" style="gap: 12px;">
        <a href="index.php" class="btn btn-volver">
            <i class="fas fa-arrow-left mr-1"></i>
            Volver
        </a>

        <a href="print_pet_record.php?id=<?= $id ?>" target="_blank" class="btn btn-purple">
            <i class="fas fa-print mr-1"></i>
            Imprimir
        </a>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>


