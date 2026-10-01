<?php

require_once '../../settings/conexion.php';
require_once '../../app/menu.php';
require_once __DIR__ . '/models/MedicalRecordModel.php';


/* =========================================================
   VALIDAR MASCOTA
========================================================= */

$id_mascota = (int)($_GET['id'] ?? 0);

if ($id_mascota <= 0) {
    header("Location: index.php");
    exit;
}


/* =========================================================
   MODELO
========================================================= */

$model = new MedicalRecordModel($conexion);


/* =========================================================
   DATOS DE LA MASCOTA
========================================================= */

$mascota = $model->getPetClinicalData($id_mascota);

if (!$mascota) {
    header("Location: index.php?error=noexiste");
    exit;
}


/* =========================================================
   HISTORIAL DE ATENCIONES
========================================================= */

$turnos = $model->getPetAppointments($id_mascota);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Historia Clínica - <?= htmlspecialchars($mascota['nombre_mascota']) ?>
    </title>


    <!-- FONT AWESOME -->

    <link
        href="../../vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
    >


    <!-- SB ADMIN -->

    <link
        href="../../css/sb-admin-2.min.css"
        rel="stylesheet"
    >


    <!-- ESTILOS GENERALES VETSYS -->

    <link
        href="/SoftwareVet/css/index_style.css"
        rel="stylesheet"
    >


    <!-- ESTILO DE LA FICHA -->

    <link
        href="../../css/show_medicalrecord.css"
        rel="stylesheet"
    >

</head>


<body>


<!-- =========================================================
     BREADCRUMB
========================================================= -->

<div class="vetsys-breadcrumb-container">

    <ol class="vetsys-breadcrumb">

        <li class="breadcrumb-item">

            <a href="/SoftwareVet/app/inicio.php">

                <i class="fas fa-home"></i>

                Inicio

            </a>

        </li>


        <li class="breadcrumb-item">

            <a href="index.php">

                Historia Clínica

            </a>

        </li>


        <li class="breadcrumb-item active">

            <?= htmlspecialchars($mascota['nombre_mascota']) ?>

        </li>

    </ol>

</div>



<!-- =========================================================
     CONTENIDO
========================================================= -->

<div class="container-fluid hc-page">


    <!-- =====================================================
         ENCABEZADO
    ====================================================== -->

    <div class="hc-page-header">


        <div>

            <h1 class="hc-page-title">

                <i class="fas fa-notes-medical"></i>

                Historia Clínica

            </h1>


            <div class="hc-page-subtitle">

                Registro clínico integral de la mascota

            </div>

        </div>



        <div class="hc-header-actions">


            <!-- VOLVER -->

            <a
                href="index.php"
                class="hc-btn hc-btn-back"
            >

                <i class="fas fa-arrow-left"></i>

                Volver

            </a>


            <!-- IMPRIMIR -->

            <a
                href="print.php?id=<?= $id_mascota ?>"
                class="hc-btn hc-btn-print"
            >

                <i class="fas fa-print"></i>

                Imprimir

            </a>


        </div>

    </div>



    <!-- =====================================================
         FICHA DEL PACIENTE
    ====================================================== -->

    <div class="hc-patient-card">


        <!-- CABECERA -->

        <div class="hc-patient-header">


            <div class="hc-patient-main">


                <div class="hc-patient-icon">

                    <i class="fas fa-paw"></i>

                </div>


                <div>

                    <h2 class="hc-patient-name">

                        <?= htmlspecialchars(
                            $mascota['nombre_mascota']
                        ) ?>

                    </h2>


                    <div class="hc-patient-description">


                        <?= htmlspecialchars(
                            $mascota['nombre_especie']
                        ) ?>


                        <?php if (!empty($mascota['raza'])) { ?>

                            <span class="hc-separator">
                                •
                            </span>

                            <?= htmlspecialchars(
                                $mascota['raza']
                            ) ?>

                        <?php } ?>


                    </div>

                </div>


            </div>



            <!-- NÚMERO -->

            <div class="hc-record-number">

                HISTORIA N.º

                <strong>

                    M-<?= str_pad(
                        $mascota['id_mascota'],
                        4,
                        '0',
                        STR_PAD_LEFT
                    ) ?>

                </strong>

            </div>


        </div>



        <!-- =================================================
             INFORMACIÓN DEL PACIENTE
        ================================================== -->

        <div class="hc-patient-body">


            <div class="hc-section-label">

                <i class="fas fa-paw"></i>

                Información del paciente

            </div>



            <div class="hc-info-grid">


                <!-- SEXO -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Sexo
                    </div>

                    <div class="hc-info-value">

                        <?= !empty($mascota['sexo'])
                            ? ucfirst(
                                htmlspecialchars(
                                    $mascota['sexo']
                                )
                            )
                            : 'Sin especificar'
                        ?>

                    </div>

                </div>



                <!-- EDAD -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Edad
                    </div>

                    <div class="hc-info-value">

                        <?php if (!empty($mascota['edad'])) { ?>

                            <?= htmlspecialchars(
                                $mascota['edad']
                            ) ?>

                            <?= htmlspecialchars(
                                $mascota['unidad_edad'] ?? ''
                            ) ?>

                        <?php } else { ?>

                            Sin especificar

                        <?php } ?>

                    </div>

                </div>



                <!-- PESO -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Peso
                    </div>

                    <div class="hc-info-value">

                        <?php

                        if (
                            isset($mascota['peso']) &&
                            $mascota['peso'] !== ''
                        ) {

                            echo htmlspecialchars(
                                $mascota['peso']
                            ) . ' kg';

                        } else {

                            echo 'Sin especificar';
                        }

                        ?>

                    </div>

                </div>



                <!-- COLOR -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Color
                    </div>

                    <div class="hc-info-value">

                        <?= !empty($mascota['color'])
                            ? htmlspecialchars(
                                $mascota['color']
                            )
                            : 'Sin especificar'
                        ?>

                    </div>

                </div>



                <!-- ESPECIE -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Especie
                    </div>

                    <div class="hc-info-value">

                        <?= htmlspecialchars(
                            $mascota['nombre_especie']
                        ) ?>

                    </div>

                </div>



                <!-- RAZA -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Raza
                    </div>

                    <div class="hc-info-value">

                        <?= !empty($mascota['raza'])
                            ? htmlspecialchars(
                                $mascota['raza']
                            )
                            : 'Sin especificar'
                        ?>

                    </div>

                </div>



                <!-- FECHA DE NACIMIENTO -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Fecha de nacimiento
                    </div>

                    <div class="hc-info-value">

                        <?php

                        if (!empty(
                            $mascota['fecha_nacimiento']
                        )) {

                            echo date(
                                'd/m/Y',
                                strtotime(
                                    $mascota['fecha_nacimiento']
                                )
                            );

                        } else {

                            echo 'Sin especificar';
                        }

                        ?>

                    </div>

                </div>



                <!-- PROPIETARIO -->

                <div class="hc-info-item">

                    <div class="hc-info-label">
                        Propietario
                    </div>

                    <div class="hc-info-value hc-owner">

                        <i class="fas fa-user"></i>

                        <?= htmlspecialchars(
                            $mascota['nombre_persona'] .
                            ' ' .
                            $mascota['apellido_persona']
                        ) ?>

                    </div>

                </div>


            </div>

        </div>

    </div>



    <!-- =====================================================
         ENCABEZADO HISTORIAL
    ====================================================== -->

    <div class="hc-history-header">


        <h3 class="hc-history-title">

            <i class="fas fa-stethoscope"></i>

            Historial de atenciones

        </h3>


        <div class="hc-history-count">

            <?= count($turnos) ?>

            <?= count($turnos) === 1
                ? 'atención'
                : 'atenciones'
            ?>

        </div>


    </div>



    <!-- =====================================================
         ATENCIONES
    ====================================================== -->

    <?php if (!empty($turnos)) { ?>


        <?php foreach ($turnos as $turno) { ?>


            <div class="hc-attention">


                <!-- =========================================
                     ENCABEZADO DE LA ATENCIÓN
                ========================================== -->

                <div class="hc-attention-header">


                    <div>


                        <div class="hc-attention-date">

                            <i class="far fa-calendar-alt"></i>


                            <?= date(
                                'd/m/Y',
                                strtotime($turno['fecha'])
                            ) ?>


                            <span class="hc-attention-time">

                                <i class="far fa-clock"></i>

                                <?= substr(
                                    $turno['hora'],
                                    0,
                                    5
                                ) ?>

                                hs

                            </span>


                        </div>



                        <div class="hc-professional">

                            <i class="fas fa-user-md"></i>

                            Profesional:

                            <strong>

                                <?= htmlspecialchars(
                                    $turno['profesional']
                                ) ?>

                            </strong>

                        </div>


                    </div>



                    <div class="hc-status">

                        <i class="fas fa-check"></i>

                        Atención completada

                    </div>


                </div>



                <!-- =========================================
                     CONTENIDO
                ========================================== -->

                <div class="hc-attention-body">


                    <!-- MOTIVO -->

                    <div class="hc-clinical-row">

                        <div class="hc-clinical-label">

                            <i class="fas fa-comment-medical"></i>

                            Motivo de consulta

                        </div>


                        <div class="hc-clinical-text">

                            <?= !empty($turno['motivo'])
                                ? nl2br(
                                    htmlspecialchars(
                                        $turno['motivo']
                                    )
                                )
                                : 'Sin motivo registrado'
                            ?>

                        </div>

                    </div>



                    <!-- ATENCIÓN REALIZADA -->

                    <div class="hc-clinical-row">

                        <div class="hc-clinical-label">

                            <i class="fas fa-notes-medical"></i>

                            Atención realizada

                        </div>


                        <div class="hc-clinical-text">

                            <?= !empty(
                                $turno['detalle_atencion']
                            )
                                ? nl2br(
                                    htmlspecialchars(
                                        $turno['detalle_atencion']
                                    )
                                )
                                : 'Sin detalle registrado'
                            ?>

                        </div>

                    </div>



                    <!-- TRATAMIENTO -->

                    <div class="hc-clinical-row hc-treatment">

                        <div class="hc-clinical-label">

                            <i class="fas fa-prescription-bottle-alt"></i>

                            Tratamiento / Indicaciones

                        </div>


                        <div class="hc-clinical-text">

                            <?= !empty(
                                $turno['tratamiento']
                            )
                                ? nl2br(
                                    htmlspecialchars(
                                        $turno['tratamiento']
                                    )
                                )
                                : 'Sin tratamiento registrado'
                            ?>

                        </div>

                    </div>



                    <!-- MONTO -->

                    <div class="hc-amount-box">


                        <div class="hc-amount-label">

                            <i class="fas fa-receipt"></i>

                            Monto total de la atención

                        </div>


                        <div class="hc-amount">

                            <?php

                            if (
                                isset($turno['monto_total']) &&
                                $turno['monto_total'] !== null
                            ) {

                                echo '$ ' .
                                    number_format(
                                        (float)$turno['monto_total'],
                                        2,
                                        ',',
                                        '.'
                                    );

                            } else {

                                echo 'Sin registrar';
                            }

                            ?>

                        </div>


                    </div>


                </div>

            </div>


        <?php } ?>


    <?php } else { ?>


        <!-- =================================================
             SIN ATENCIONES
        ================================================== -->

        <div class="hc-empty">


            <div class="hc-empty-icon">

                <i class="fas fa-notes-medical"></i>

            </div>


            <div class="hc-empty-title">

                Sin atenciones registradas

            </div>


            <p class="hc-empty-text">

                Esta mascota todavía no posee
                atenciones veterinarias completadas.

            </p>


        </div>


    <?php } ?>


</div>



<!-- =========================================================
     SCRIPTS
========================================================= -->

<script src="../../vendor/jquery/jquery.min.js"></script>

<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>