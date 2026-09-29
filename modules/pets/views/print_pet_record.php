<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">

    <title>
        Ficha - <?= htmlspecialchars($mascota['nombre_mascota'] ?? 'Mascota') ?>
    </title>

    <link
        href="../../vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
    >

    <link
        href="../../css/print_record_style.css"
        rel="stylesheet"
    >

</head>

<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
<body>


<!-- =====================================================
     ACCIONES
===================================================== -->

<div class="acciones">

    <a
        href="pet_record.php?id=<?= $id ?>"
        class="btn-volver"
    >
        <i class="fas fa-arrow-left"></i>
        Volver
    </a>


    <button
        type="button"
        onclick="window.print()"
        class="btn-imprimir"
    >
        <i class="fas fa-print"></i>
        Imprimir
    </button>

</div>



<!-- =====================================================
     HOJA / FICHA
===================================================== -->

<div class="hoja-ficha">


    <!-- =================================================
         ENCABEZADO VETSYS
    ================================================== -->

    <div class="encabezado-vetsys">


        <div class="marca-vetsys">

            <div class="logo-vetsys">

                <i class="fas fa-paw"></i>

            </div>


            <div>

                <div class="nombre-vetsys">
                    VetSys
                </div>

                <div class="subtitulo-vetsys">
                    Software de Gestión Veterinaria
                </div>

            </div>

        </div>


        <div class="fecha-emision">

            <span>
                Fecha de emisión
            </span>

            <strong>
                <?= date('d/m/Y') ?>
            </strong>

        </div>

    </div>



    <!-- =================================================
         PACIENTE
    ================================================== -->

    <div class="paciente-header">


        <div class="paciente-info">


            <div class="paciente-icono">

                <i class="fas fa-dog"></i>

            </div>


            <div class="paciente-identidad">

                <h1>
                    <?= htmlspecialchars(
                        $mascota['nombre_mascota'] ?? 'Sin especificar'
                    ) ?>
                </h1>


                <div class="badges">


                    <span class="badge badge-especie">

                        <?= htmlspecialchars(
                            $mascota['nombre_especie'] ?? 'Sin especificar'
                        ) ?>

                    </span>


                    <?php if (($mascota['sexo'] ?? '') === 'M') { ?>

                        <span class="badge badge-macho">

                            <i class="fas fa-mars"></i>
                            Macho

                        </span>

                    <?php } elseif (($mascota['sexo'] ?? '') === 'H') { ?>

                        <span class="badge badge-hembra">

                            <i class="fas fa-venus"></i>
                            Hembra

                        </span>

                    <?php } ?>

                </div>

            </div>

        </div>



        <!-- NÚMERO -->

        <div class="numero-ficha">

            <span>
                Ficha clínica
            </span>

            <strong>

                N° <?= str_pad(
                    $mascota['id_mascota'] ?? $id,
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

    <section class="seccion">


        <div class="titulo-seccion">

            <i class="fas fa-paw"></i>

            Datos de la mascota

        </div>


        <div class="grid-datos mascota-datos">


            <!-- ESPECIE -->

            <div class="campo">

                <span>Especie</span>

                <strong>

                    <?= htmlspecialchars(
                        $mascota['nombre_especie']
                        ?? 'Sin especificar'
                    ) ?>

                </strong>

            </div>



            <!-- RAZA -->

            <div class="campo campo-raza">

                <span>Raza</span>

                <strong>

                    <?= !empty($mascota['raza'])
                        ? htmlspecialchars($mascota['raza'])
                        : 'Sin especificar'
                    ?>

                </strong>

            </div>



            <!-- SEXO -->

            <div class="campo">

                <span>Sexo</span>

                <strong>

                    <?php

                    if (($mascota['sexo'] ?? '') === 'M') {

                        echo 'Macho';

                    } elseif (($mascota['sexo'] ?? '') === 'H') {

                        echo 'Hembra';

                    } else {

                        echo 'Sin especificar';
                    }

                    ?>

                </strong>

            </div>



            <!-- COLOR -->

            <div class="campo">

                <span>Color</span>

                <strong>

                    <?= !empty($mascota['color'])
                        ? htmlspecialchars($mascota['color'])
                        : 'Sin especificar'
                    ?>

                </strong>

            </div>



            <!-- FECHA NACIMIENTO -->

            <div class="campo">

                <span>Fecha de nacimiento</span>

                <strong>

                    <?php

                    if (
                        !empty($mascota['fecha_nacimiento'])
                        &&
                        $mascota['fecha_nacimiento'] !== '0000-00-00'
                    ) {

                        echo date(
                            'd/m/Y',
                            strtotime($mascota['fecha_nacimiento'])
                        );

                    } else {

                        echo 'Sin especificar';
                    }

                    ?>

                </strong>

            </div>



            <!-- EDAD -->

            <div class="campo">

                <span>Edad</span>

                <strong>

                    <?php

                    if (!empty($mascota['edad'])) {

                        $edad = $mascota['edad'];

                        $unidad =
                            $mascota['unidad_edad'] ?? '';


                        if ($edad == 1) {

                            if ($unidad === 'dias') {

                                $unidad = 'día';

                            } elseif ($unidad === 'meses') {

                                $unidad = 'mes';

                            } elseif ($unidad === 'años') {

                                $unidad = 'año';
                            }

                        } else {

                            if ($unidad === 'dias') {

                                $unidad = 'días';
                            }
                        }


                        echo htmlspecialchars(
                            $edad . ' ' . $unidad
                        );

                    } else {

                        echo 'Sin especificar';
                    }

                    ?>

                </strong>

            </div>



            <!-- PESO -->

            <div class="campo">

                <span>Peso</span>

                <strong>

                    <?= !empty($mascota['peso'])
                        ? htmlspecialchars($mascota['peso']) . ' kg'
                        : 'Sin especificar'
                    ?>

                </strong>

            </div>


        </div>

    </section>



    <!-- =================================================
         PROPIETARIO
    ================================================== -->

    <section class="seccion seccion-propietario">


        <div class="titulo-seccion">

            <i class="fas fa-user"></i>

            Datos del propietario

        </div>


        <div class="grid-datos propietario-datos">


            <!-- NOMBRE -->

            <div class="campo">

                <span>Nombre completo</span>

                <strong>

                    <?= htmlspecialchars(
                        trim(
                            ($mascota['nombre_persona'] ?? '')
                            . ' ' .
                            ($mascota['apellido_persona'] ?? '')
                        )
                    ) ?>

                </strong>

            </div>



            <!-- TELÉFONO -->

            <div class="campo">

                <span>Teléfono</span>

                <strong>

                    <?= !empty($mascota['telefono'])
                        ? htmlspecialchars($mascota['telefono'])
                        : 'Sin especificar'
                    ?>

                </strong>

            </div>



            <!-- EMAIL -->

            <div class="campo campo-email">

                <span>Email</span>

                <strong>

                    <?= !empty($mascota['email'])
                        ? htmlspecialchars($mascota['email'])
                        : 'Sin especificar'
                    ?>

                </strong>

            </div>


        </div>

    </section>



    <!-- =================================================
         PIE
    ================================================== -->

    <div class="pie-ficha">

        <span>
            VetSys · Software de Gestión Veterinaria
        </span>

        <span>

            Ficha N°
            <?= str_pad(
                $mascota['id_mascota'] ?? $id,
                5,
                '0',
                STR_PAD_LEFT
            ) ?>

        </span>

    </div>


</div>


</body>

</html>