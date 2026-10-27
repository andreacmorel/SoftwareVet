
<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ficha Mascota | VetSys</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/pet_record_style.css" rel="stylesheet">
</head>

<body>

<!-- =====================================================
     BREADCRUMB
===================================================== -->

<div class="vetsys-breadcrumb-container">

    <ol class="vetsys-breadcrumb">

        <li class="breadcrumb-item">

            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i>
                Inicio
            </a>

        </li>

        <li class="breadcrumb-item">

            <a href="/SoftwareVet/modules/pets/index.php">
                Mascotas
            </a>

        </li>

        <li class="breadcrumb-item active">
            Ficha de mascota
        </li>

    </ol>

</div>


<div class="container-fluid">

    <!-- =================================================
         CABECERA DE LA PÁGINA
    ================================================== -->

    <div class="ficha-page-header">

        <div>

            <h1 class="h3 titulo-pagina">

                <i class="fas fa-paw mr-2"></i>
                Ficha de Mascota

            </h1>

            <div class="subtitulo-pagina">
                Información completa del paciente
                y su propietario.
            </div>

        </div>


        <!-- BOTONES SUPERIORES -->

        <div class="ficha-header-actions">

            <!-- VOLVER -->

            <a
                href="index.php"
                class="btn btn-volver"
            >

                <i class="fas fa-arrow-left mr-1"></i>
                Volver

            </a>


            <!-- CÓDIGO QR -->

            <a
                href="qr.php?id=<?= (int)$id ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-ficha-qr"
            >

                <i class="fas fa-qrcode mr-1"></i>
                Código QR

            </a>


            <!-- IMPRIMIR -->

            <a
                href="print_pet_record.php?id=<?= (int)$id ?>"
                target="_blank"
                class="btn btn-purple"
            >

                <i class="fas fa-print mr-1"></i>
                Imprimir

            </a>

        </div>

    </div>


    <!-- =================================================
         DOCUMENTO DE LA MASCOTA
    ================================================== -->

    <div class="ficha-documento">


        <!-- =================================================
             CABECERA DEL PACIENTE
        ================================================== -->

        <div class="ficha-paciente-header">

            <div class="paciente-principal">

                <!-- FOTOGRAFÍA DE LA MASCOTA -->

                <div class="paciente-icono">

                    <?php if (!empty($mascota['foto'])): ?>

                        <img
                            src="/SoftwareVet/<?= htmlspecialchars(
                                ltrim($mascota['foto'], '/'),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            alt="Fotografía de <?= htmlspecialchars(
                                $mascota['nombre_mascota'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            class="paciente-foto"
                        >

                    <?php else: ?>

                        <i class="fas fa-dog"></i>

                    <?php endif; ?>

                </div>


                <!-- IDENTIFICACIÓN -->

                <div class="paciente-identidad">

                    <h2>

                        <?= htmlspecialchars(
                            $mascota['nombre_mascota'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </h2>


                    <div class="paciente-badges">

                        <!-- ESPECIE -->

                        <span class="badge-ficha badge-especie">

                            <?= htmlspecialchars(
                                $mascota['nombre_especie'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </span>


                        <!-- SEXO -->

                        <?php if ($mascota['sexo'] == 'M'): ?>

                            <span class="badge-ficha badge-macho">

                                <i class="fas fa-mars"></i>
                                Macho

                            </span>

                        <?php elseif ($mascota['sexo'] == 'H'): ?>

                            <span class="badge-ficha badge-hembra">

                                <i class="fas fa-venus"></i>
                                Hembra

                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <!-- NÚMERO DE FICHA -->

            <div class="numero-ficha">

                <span class="numero-label">
                    Ficha clínica
                </span>

                <strong>

                    N° <?= str_pad(
                        $mascota['id_mascota'],
                        5,
                        '0',
                        STR_PAD_LEFT
                    ) ?>

                </strong>

            </div>

        </div>


        <!-- =================================================
             DATOS DE LA MASCOTA
        ================================================== -->

        <section class="seccion-ficha">

            <div class="titulo-seccion">

                <i class="fas fa-paw"></i>
                Datos de la mascota

            </div>


            <div class="tabla-datos mascota-datos">

                <!-- ESPECIE -->

                <div class="campo-ficha">

                    <span>Especie</span>

                    <strong>

                        <?= htmlspecialchars(
                            $mascota['nombre_especie'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </strong>

                </div>


                <!-- RAZA -->

                <div class="campo-ficha campo-raza">

                    <span>Raza</span>

                    <strong>

                        <?= !empty($mascota['raza'])
                            ? htmlspecialchars(
                                $mascota['raza'],
                                ENT_QUOTES,
                                'UTF-8'
                              )
                            : 'Sin especificar'
                        ?>

                    </strong>

                </div>


                <!-- SEXO -->

                <div class="campo-ficha">

                    <span>Sexo</span>

                    <strong>

                        <?php

                        if ($mascota['sexo'] == 'M') {

                            echo 'Macho';

                        } elseif ($mascota['sexo'] == 'H') {

                            echo 'Hembra';

                        } else {

                            echo 'Sin especificar';

                        }

                        ?>

                    </strong>

                </div>


                <!-- COLOR -->

                <div class="campo-ficha">

                    <span>Color</span>

                    <strong>
                        <?= $color ?>
                    </strong>

                </div>


                <!-- FECHA DE NACIMIENTO -->

                <div class="campo-ficha">

                    <span>Fecha de nacimiento</span>

                    <strong>
                        <?= $fechaNacimiento ?>
                    </strong>

                </div>


                <!-- EDAD -->

                <div class="campo-ficha">

                    <span>Edad</span>

                    <strong>

                        <?php

                        $edadMascota = $mascota['edad'] ?? '';
                        $unidad = $mascota['unidad_edad'] ?? '';

                        if ($edadMascota == 1) {

                            if ($unidad == 'dias') {
                                $unidad = 'día';

                            } elseif ($unidad == 'meses') {
                                $unidad = 'mes';

                            } elseif ($unidad == 'años') {
                                $unidad = 'año';
                            }

                        } else {

                            if ($unidad == 'dias') {
                                $unidad = 'días';
                            }
                        }

                        ?>

                        <?php if ($edadMascota !== '' && $edadMascota !== null): ?>

                            <?= htmlspecialchars(
                                (string)$edadMascota,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                            <?= htmlspecialchars(
                                $unidad,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        <?php else: ?>

                            Sin especificar

                        <?php endif; ?>

                    </strong>

                </div>


                <!-- PESO -->

                <div class="campo-ficha">

                    <span>Peso</span>

                    <strong>
                        <?= $peso ?>
                    </strong>

                </div>

            </div>

        </section>


      

        <!-- =================================================
             DATOS DEL PROPIETARIO
        ================================================== -->

        <section class="seccion-ficha">

            <div class="titulo-seccion">

                <i class="fas fa-user"></i>
                Datos del propietario

            </div>


            <div class="tabla-datos propietario-datos">

                <!-- NOMBRE COMPLETO -->

                <div class="campo-ficha">

                    <span>Nombre completo</span>

                    <strong>

                        <?= htmlspecialchars(
                            $mascota['nombre_persona']
                            . ' ' .
                            $mascota['apellido_persona'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </strong>

                </div>


                <!-- TELÉFONO -->

                <div class="campo-ficha">

                    <span>Teléfono</span>

                    <strong>
                        <?= $telefono ?>
                    </strong>

                </div>


                <!-- EMAIL -->

                <div class="campo-ficha campo-email">

                    <span>Email</span>

                    <strong>
                        <?= $email ?>
                    </strong>

                </div>

            </div>

        </section>

    </div>

</div>


<!-- =====================================================
     SCRIPTS ORIGINALES
===================================================== -->

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>
