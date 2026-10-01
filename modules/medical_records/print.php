<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once __DIR__ . '/models/MedicalRecordModel.php';


/* =========================================================
   VALIDAR MASCOTA
========================================================= */

$id_mascota = (int)($_GET['id'] ?? 0);

if ($id_mascota <= 0) {
    die("ID de mascota no válido.");
}


/* =========================================================
   PDF
========================================================= */

$generarPDF = isset($_GET['pdf']);

if ($generarPDF) {

    require_once '../../vendor/autoload.php';

    ob_start();
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
    die("Mascota no encontrada.");
}


/* =========================================================
   HISTORIAL DE ATENCIONES
========================================================= */

$turnos = $model->getPetAppointments($id_mascota);


/* =========================================================
   PREPARAR EDAD
========================================================= */

$edadMascota = 'Sin especificar';

if (!empty($mascota['edad'])) {

    $numeroEdad = $mascota['edad'];

    $unidadEdad = $mascota['unidad_edad'] ?? '';

    if ($numeroEdad == 1) {

        if ($unidadEdad == 'dias') {
            $unidadEdad = 'día';
        } elseif ($unidadEdad == 'meses') {
            $unidadEdad = 'mes';
        } elseif ($unidadEdad == 'años') {
            $unidadEdad = 'año';
        }

    } else {

        if ($unidadEdad == 'dias') {
            $unidadEdad = 'días';
        } elseif ($unidadEdad == 'meses') {
            $unidadEdad = 'meses';
        } elseif ($unidadEdad == 'años') {
            $unidadEdad = 'años';
        }
    }

    $edadMascota = $numeroEdad . ' ' . $unidadEdad;
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Historia Clínica - <?= htmlspecialchars($mascota['nombre_mascota']) ?>
    </title>


    <?php if (!$generarPDF): ?>

        <link
            href="../../vendor/fontawesome-free/css/all.min.css"
            rel="stylesheet"
        >

        <link
            href="/SoftwareVet/css/medical_record_print.css"
            rel="stylesheet"
        >

    <?php endif; ?>


    <!-- =====================================================
         ESTILO DEL DOCUMENTO / PDF
    ====================================================== -->

    <style>

        body {

            font-family: DejaVu Sans, Arial, sans-serif;

            color: #29212e;

            margin: 22px 30px;

            font-size: 11px;

            line-height: 1.5;
        }


        /* ================================================
           ENCABEZADO
        ================================================= */

        .header {

            border-bottom: 3px solid #52266E;

            padding-bottom: 14px;

            margin-bottom: 22px;
        }


        .header-top {

            position: relative;
        }


        .system-info {

            color: #574d5d;

            font-size: 9px;

            line-height: 1.6;
        }


        .document-title {

            margin-top: 4px;

            color: #52266E;

            font-size: 23px;

            font-weight: bold;

            text-align: center;

            text-transform: uppercase;

            letter-spacing: 1px;
        }


        .record-number {

            position: absolute;

            top: 0;

            right: 0;

            padding: 5px 9px;

            color: #52266E;

            background: #f5eff8;

            border: 1px solid #e7d7f5;

            border-radius: 6px;

            font-size: 9px;

            font-weight: bold;
        }


        /* ================================================
           SECCIONES
        ================================================= */

        .section {

            margin-top: 18px;
        }


        .section-title {

            margin: 0 0 10px 0;

            padding-bottom: 6px;

            color: #52266E;

            border-bottom: 1px solid #e9dcef;

            font-size: 10px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: .5px;
        }


        /* ================================================
           INFORMACIÓN MASCOTA
        ================================================= */

        .info-table {

            width: 100%;

            border-collapse: collapse;
        }


        .info-table td {

            width: 25%;

            padding: 5px 10px 5px 0;

            vertical-align: top;
        }


        .info-label {

            display: block;

            margin-bottom: 2px;

            color: #52266E;

            font-size: 8px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: .3px;
        }


        .info-value {

            color: #29212e;

            font-size: 11px;

            font-weight: bold;
        }


        /* ================================================
           PROPIETARIO
        ================================================= */

        .owner-box {

            padding: 10px 12px;

            background: #faf7fb;

            border: 1px solid #eadff0;

            border-left: 3px solid #52266E;

            border-radius: 5px;
        }


        .owner-name {

            color: #29212e;

            font-size: 11px;

            font-weight: bold;
        }


        /* ================================================
           ATENCIÓN
        ================================================= */

        .attention {

            margin-bottom: 15px;

            border: 1px solid #e7dfea;

            border-left: 3px solid #52266E;

            border-radius: 6px;

            page-break-inside: avoid;
        }


        .attention-header {

            padding: 9px 11px;

            background: #faf7fb;

            border-bottom: 1px solid #e7dfea;
        }


        .attention-date {

            color: #29212e;

            font-size: 11px;

            font-weight: bold;
        }


        .attention-professional {

            margin-top: 3px;

            color: #574d5d;

            font-size: 9px;
        }


        .attention-professional strong {

            color: #29212e;
        }


        .attention-body {

            padding: 11px 13px;
        }


        /* ================================================
           DATOS CLÍNICOS
        ================================================= */

        .clinical-block {

            margin-bottom: 11px;
        }


        .clinical-block:last-child {

            margin-bottom: 0;
        }


        .clinical-label {

            margin-bottom: 3px;

            color: #52266E;

            font-size: 8px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: .4px;
        }


        .clinical-text {

            color: #29212e;

            font-size: 10px;

            line-height: 1.6;
        }


        /* ================================================
           TRATAMIENTO
        ================================================= */

        .treatment {

            padding: 8px 10px;

            background: #faf7fb;

            border: 1px solid #eadff0;

            border-radius: 5px;
        }


        /* ================================================
           MONTO
        ================================================= */

        .amount {

            margin-top: 11px;

            padding-top: 8px;

            border-top: 1px solid #e7dfea;

            text-align: right;
        }


        .amount-label {

            margin-right: 8px;

            color: #574d5d;

            font-size: 8px;

            font-weight: bold;

            text-transform: uppercase;
        }


        .amount-value {

            color: #52266E;

            font-size: 13px;

            font-weight: bold;
        }


        /* ================================================
           SIN ATENCIONES
        ================================================= */

        .empty {

            padding: 18px;

            color: #574d5d;

            background: #faf7fb;

            border: 1px dashed #d8c2e8;

            border-radius: 6px;

            text-align: center;
        }


        /* ================================================
           PIE
        ================================================= */

        .footer {

            margin-top: 30px;

            padding-top: 10px;

            border-top: 1px solid #eee1f6;

            color: #746a79;

            font-size: 8px;

            text-align: center;
        }


        @media print {

            body {

                margin: 15px 20px;
            }
        }

    </style>

</head>


<body>


<!-- =========================================================
     BOTONES
========================================================= -->

<?php if (!$generarPDF): ?>

    <div class="acciones-pantalla">


        <a
            href="show.php?id=<?= $id_mascota ?>"
            class="btn-volver"
        >

            <i class="fas fa-arrow-left"></i>

            <span>Volver</span>

        </a>


        <a
            href="print.php?id=<?= $id_mascota ?>&pdf=1"
            class="btn-descargar-pdf"
        >

            <i class="fas fa-file-pdf"></i>

            <span>Descargar PDF</span>

        </a>


    </div>

<?php endif; ?>



<!-- =========================================================
     ENCABEZADO
========================================================= -->

<div class="header">

    <div class="header-top">


        <div class="record-number">

            HC-M<?= str_pad(
                $id_mascota,
                4,
                '0',
                STR_PAD_LEFT
            ) ?>

        </div>


        <div class="system-info">

            <div>
                VetSys - Software Veterinario
            </div>

            <div>

                Fecha de emisión:

                <?= date('d/m/Y H:i') ?>

            </div>

        </div>


        <div class="document-title">

            Historia Clínica

        </div>


    </div>

</div>



<!-- =========================================================
     DATOS DEL PACIENTE
========================================================= -->

<div class="section">

    <div class="section-title">

        Información del paciente

    </div>


    <table class="info-table">

        <tr>


            <td>

                <span class="info-label">
                    Nombre
                </span>

                <span class="info-value">

                    <?= htmlspecialchars(
                        $mascota['nombre_mascota']
                    ) ?>

                </span>

            </td>


            <td>

                <span class="info-label">
                    Especie
                </span>

                <span class="info-value">

                    <?= htmlspecialchars(
                        $mascota['nombre_especie']
                    ) ?>

                </span>

            </td>


            <td>

                <span class="info-label">
                    Raza
                </span>

                <span class="info-value">

                    <?= !empty($mascota['raza'])
                        ? htmlspecialchars($mascota['raza'])
                        : 'Sin especificar'
                    ?>

                </span>

            </td>


            <td>

                <span class="info-label">
                    Sexo
                </span>

                <span class="info-value">

                    <?php

                    if ($mascota['sexo'] == 'M') {

                        echo 'Macho';

                    } elseif ($mascota['sexo'] == 'H') {

                        echo 'Hembra';

                    } else {

                        echo htmlspecialchars(
                            $mascota['sexo'] ?? 'Sin especificar'
                        );
                    }

                    ?>

                </span>

            </td>

        </tr>


        <tr>


            <td>

                <span class="info-label">
                    Edad
                </span>

                <span class="info-value">

                    <?= htmlspecialchars($edadMascota) ?>

                </span>

            </td>


            <td>

                <span class="info-label">
                    Peso
                </span>

                <span class="info-value">

                    <?= isset($mascota['peso']) &&
                        $mascota['peso'] !== ''
                        ? htmlspecialchars($mascota['peso']) . ' kg'
                        : 'Sin especificar'
                    ?>

                </span>

            </td>


            <td>

                <span class="info-label">
                    Color
                </span>

                <span class="info-value">

                    <?= !empty($mascota['color'])
                        ? htmlspecialchars($mascota['color'])
                        : 'Sin especificar'
                    ?>

                </span>

            </td>


            <td>

                <span class="info-label">
                    Código
                </span>

                <span class="info-value">

                    M-<?= str_pad(
                        $id_mascota,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ) ?>

                </span>

            </td>


        </tr>

    </table>

</div>



<!-- =========================================================
     PROPIETARIO
========================================================= -->

<div class="section">

    <div class="section-title">

        Propietario

    </div>


    <div class="owner-box">

        <div class="owner-name">

            <?= htmlspecialchars(
                $mascota['nombre_persona']
                . ' '
                . $mascota['apellido_persona']
            ) ?>

        </div>

    </div>

</div>



<!-- =========================================================
     HISTORIAL
========================================================= -->

<div class="section">

    <div class="section-title">

        Historial de atenciones

    </div>


    <?php if (!empty($turnos)) { ?>


        <?php foreach ($turnos as $turno) { ?>


            <div class="attention">


                <!-- CABECERA -->

                <div class="attention-header">


                    <div class="attention-date">

                        <?= date(
                            'd/m/Y',
                            strtotime($turno['fecha'])
                        ) ?>

                        -

                        <?= substr(
                            $turno['hora'],
                            0,
                            5
                        ) ?>

                        hs

                    </div>


                    <div class="attention-professional">

                        Profesional:

                        <strong>

                            <?= htmlspecialchars(
                                $turno['profesional']
                            ) ?>

                        </strong>

                    </div>


                </div>



                <!-- CUERPO -->

                <div class="attention-body">


                    <!-- MOTIVO -->

                    <div class="clinical-block">

                        <div class="clinical-label">

                            Motivo de consulta

                        </div>


                        <div class="clinical-text">

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



                    <!-- ATENCIÓN -->

                    <div class="clinical-block">

                        <div class="clinical-label">

                            Atención realizada

                        </div>


                        <div class="clinical-text">

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

                    <div class="clinical-block treatment">

                        <div class="clinical-label">

                            Tratamiento / Indicaciones

                        </div>


                        <div class="clinical-text">

                            <?= !empty($turno['tratamiento'])
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

                    <div class="amount">

                        <span class="amount-label">

                            Monto total

                        </span>


                        <span class="amount-value">

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

                        </span>

                    </div>


                </div>


            </div>


        <?php } ?>


    <?php } else { ?>


        <div class="empty">

            No se registran atenciones veterinarias
            completadas para esta mascota.

        </div>


    <?php } ?>


</div>



<!-- =========================================================
     PIE
========================================================= -->

<div class="footer">

    VetSys · Software de Gestión Veterinaria

    <br>

    Historia clínica de
    <?= htmlspecialchars($mascota['nombre_mascota']) ?>

</div>


</body>

</html>


<?php


/* =========================================================
   GENERAR PDF
========================================================= */

if ($generarPDF) {


    $html = ob_get_clean();


    $options = new Dompdf\Options();


    $options->set(
        'isRemoteEnabled',
        true
    );


    $dompdf = new Dompdf\Dompdf(
        $options
    );


    $dompdf->loadHtml(
        $html
    );


    $dompdf->setPaper(
        'A4',
        'portrait'
    );


    $dompdf->render();


    $nombreMascota = preg_replace(
        '/[^A-Za-z0-9_-]/',
        '_',
        $mascota['nombre_mascota']
    );


    $dompdf->stream(

        'Historia_Clinica_' .
        $nombreMascota .
        '.pdf',

        [
            'Attachment' => true
        ]
    );


    exit;
}

?>