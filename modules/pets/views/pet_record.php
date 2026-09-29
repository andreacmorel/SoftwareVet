<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">

    <title>Ficha Mascota</title>

    <link
        href="../../vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
    >

    <link
        href="../../css/sb-admin-2.min.css"
        rel="stylesheet"
    >

    <link
        href="../../css/pet_record_style.css"
        rel="stylesheet"
    >
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


        <!-- BOTONES -->

        <div class="ficha-header-actions">

            <a
                href="index.php"
                class="btn btn-volver"
            >

                <i class="fas fa-arrow-left mr-1"></i>

                Volver

            </a>


            <a
                href="print_pet_record.php?id=<?= $id ?>"
                target="_blank"
                class="btn btn-purple"
            >

                <i class="fas fa-print mr-1"></i>

                Imprimir

            </a>

        </div>

    </div>

    <div class="ficha-documento">
        <div class="ficha-paciente-header">
            <div class="paciente-principal">

                <div class="paciente-icono">

                    <i class="fas fa-dog"></i>

                </div>

                <div class="paciente-identidad">

                    <h2>

                        <?= htmlspecialchars(
                            $mascota['nombre_mascota']
                        ) ?>

                    </h2>


                    <div class="paciente-badges">
                        <span class="badge-ficha badge-especie">

                            <?= htmlspecialchars(
                                $mascota['nombre_especie']
                            ) ?>
                        </span>

                        <?php if ($mascota['sexo'] == 'M') { ?>

                            <span class="badge-ficha badge-macho">

                                <i class="fas fa-mars"></i>

                                Macho

                            </span>

                        <?php } elseif ($mascota['sexo'] == 'H') { ?>

                            <span class="badge-ficha badge-hembra">

                                <i class="fas fa-venus"></i>

                                Hembra

                            </span>

                        <?php } ?>

                    </div>

                </div>

            </div>


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


        <section class="seccion-ficha">
            <div class="titulo-seccion">

                <i class="fas fa-paw"></i>

                Datos de la mascota

            </div>



            <div class="tabla-datos mascota-datos">

                <div class="campo-ficha">

                    <span>Especie</span>

                    <strong>

                        <?= htmlspecialchars(
                            $mascota['nombre_especie']
                        ) ?>

                    </strong>

                </div>

                <div class="campo-ficha campo-raza">

                    <span>Raza</span>

                    <strong>

                        <?= !empty($mascota['raza'])
                            ? htmlspecialchars($mascota['raza'])
                            : 'Sin especificar'
                        ?>

                    </strong>

                </div>

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

                <div class="campo-ficha">

                    <span>Color</span>

                    <strong>
                        <?= $color ?>
                    </strong>

                </div>

                <div class="campo-ficha">

                    <span>
                        Fecha de nacimiento
                    </span>

                    <strong>

                        <?= $fechaNacimiento ?>

                    </strong>

                </div>

                <div class="campo-ficha">

                    <span>Edad</span>

                    <strong>

                        <?php

                        $edad =
                            $mascota['edad'] ?? '';

                        $unidad =
                            $mascota['unidad_edad'] ?? '';


                        if ($edad == 1) {

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


                        <?php if (!empty($edad)) { ?>

                            <?= htmlspecialchars($edad) ?>

                            <?= htmlspecialchars($unidad) ?>

                        <?php } else { ?>

                            Sin especificar

                        <?php } ?>

                    </strong>

                </div>

                <div class="campo-ficha">

                    <span>Peso</span>

                    <strong>
                        <?= $peso ?>
                    </strong>

                </div>


            </div>

        </section>

        <section class="seccion-ficha">


            <div class="titulo-seccion">

                <i class="fas fa-user"></i>

                Datos del propietario

            </div>



            <div class="tabla-datos propietario-datos">
                <div class="campo-ficha">

                    <span>
                        Nombre completo
                    </span>

                    <strong>

                        <?= htmlspecialchars(
                            $mascota['nombre_persona']
                            . ' ' .
                            $mascota['apellido_persona']
                        ) ?>

                    </strong>

                </div>

                <div class="campo-ficha">

                    <span>
                        Teléfono
                    </span>

                    <strong>

                        <?= $telefono ?>

                    </strong>

                </div>

                <div class="campo-ficha campo-email">

                    <span>
                        Email
                    </span>

                    <strong>

                        <?= $email ?>

                    </strong>

                </div>


            </div>

        </section>

    </div>

</div>


<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>