<?php

// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../app/validateRoute.php';


// Verifica si se pidió generar PDF mediante el parámetro ?pdf
$generarPDF = isset($_GET['pdf']);


// Si se solicitó PDF, carga Dompdf y activa el buffer de salida
if ($generarPDF) {

    require_once '../../vendor/autoload.php';

    ob_start();
}


// Obtiene el ID de la historia clínica desde la URL
$id = (int)($_GET['id'] ?? 0);


// Valida que el ID sea correcto
if ($id <= 0) {

    die("ID de historia clínica no válido.");
}


/*
|--------------------------------------------------------------------------
| OBTENER HISTORIA CLÍNICA
|--------------------------------------------------------------------------
*/

$stmt = $conexion->prepare("

    SELECT
        h.id_historia_clinica,
        h.fecha,
        h.descripcion,
        h.observacion,

        m.id_mascota,
        m.nombre_mascota,
        m.sexo,
        m.peso,
        m.color,
        m.edad,
        m.unidad_edad,

        e.nombre_especie,
        e.raza,

        p.nombre_persona,
        p.apellido_persona,
        p.telefono,
        p.email

    FROM historia_clinica h

    INNER JOIN mascota m
        ON h.id_mascota = m.id_mascota

    LEFT JOIN especie e
        ON m.id_especie = e.id_especie

    INNER JOIN cliente c
        ON m.id_cliente = c.id_cliente

    INNER JOIN persona p
        ON c.id_persona = p.id_persona

    WHERE h.id_historia_clinica = ?

");


// Vincula el ID
$stmt->bind_param("i", $id);


// Ejecuta la consulta
$stmt->execute();


// Obtiene el resultado
$res = $stmt->get_result();


// Si no encuentra la historia clínica
if ($res->num_rows == 0) {

    die("Historia clínica no encontrada.");
}


// Guarda los datos
$hc = $res->fetch_assoc();


// Cierra la consulta
$stmt->close();


/*
|--------------------------------------------------------------------------
| PREPARAR EDAD DE LA MASCOTA
|--------------------------------------------------------------------------
|
| Permite mostrar:
|
| 1 día
| 2 días
| 1 mes
| 5 meses
| 1 año
| 4 años
|
*/

$edadMascota = 'Sin especificar';


if (!empty($hc['edad'])) {

    $numeroEdad = $hc['edad'];

    $unidadEdad = $hc['unidad_edad'] ?? '';


    // Singular
    if ($numeroEdad == 1) {

        if ($unidadEdad == 'dias') {

            $unidadEdad = 'día';

        } elseif ($unidadEdad == 'meses') {

            $unidadEdad = 'mes';

        } elseif ($unidadEdad == 'años') {

            $unidadEdad = 'año';
        }

    } else {

        // Plural
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


/*
|--------------------------------------------------------------------------
| OBTENER TRATAMIENTOS
|--------------------------------------------------------------------------
*/

$stmtTrat = $conexion->prepare("

    SELECT
        t.duracion,
        t.dosis,
        t.descripcion

    FROM detalle_historia_clinica dh

    INNER JOIN tratamientos t
        ON dh.id_tratamiento = t.id_tratamiento

    WHERE dh.id_historia_clinica = ?

");


// Vincula el ID
$stmtTrat->bind_param("i", $id);


// Ejecuta la consulta
$stmtTrat->execute();


// Obtiene los tratamientos
$tratamientos = $stmtTrat->get_result();

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Historia Clínica</title>


    <!-- =====================================================
         CSS SOLO PARA LA VISTA EN PANTALLA
    ====================================================== -->

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
         CSS DEL DOCUMENTO / PDF
         Se mantiene dentro del archivo para Dompdf
    ====================================================== -->

    <style>

        /* =========================================================
           DOCUMENTO GENERAL
        ========================================================= */

        body {

            font-family: DejaVu Sans, Arial, sans-serif;

            color: #1f2937;

            margin: 22px 30px;

            font-size: 12px;

            line-height: 1.5;
        }


        /* =========================================================
           ENCABEZADO
        ========================================================= */

        .header {

            border-bottom: 3px solid #52266E;

            padding-bottom: 14px;

            margin-bottom: 22px;
        }


        .header-top {

            position: relative;
        }


        .header_one {

            font-size: 10px;

            color: #7b8494;

            line-height: 1.6;
        }


        .logo-title {

            font-family: DejaVu Sans, Arial, sans-serif;

            font-size: 23px;

            font-weight: 700;

            color: #52266E;

            text-transform: uppercase;

            letter-spacing: 1px;

            text-align: center;

            margin-top: 4px;
        }


        .badge-hc {

            position: absolute;

            top: 0;

            right: 0;

            background: #f3e8ff;

            border: 1px solid #e7d7f5;

            border-radius: 6px;

            color: #52266E;

            font-size: 10px;

            font-weight: 700;

            padding: 5px 9px;
        }


        /* =========================================================
           SECCIONES
        ========================================================= */

        .section {

            margin-top: 18px;

            page-break-inside: avoid;
        }


        .section h3 {

            color: #52266E;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .5px;

            border-bottom: 1px solid #e9dcef;

            padding-bottom: 6px;

            margin: 0 0 10px 0;
        }


        /* =========================================================
           DATOS EN DOS COLUMNAS
        ========================================================= */

        .grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 7px 25px;
        }


        .item {

            font-size: 12px;

            color: #374151;

            padding: 2px 0;
        }


        .label {

            font-weight: 700;

            color: #6b7280;
        }


        /* =========================================================
           DESCRIPCIÓN / OBSERVACIÓN
        ========================================================= */

        .box {

            border: 1px solid #eadff0;

            border-left: 3px solid #52266E;

            border-radius: 6px;

            padding: 11px 13px;

            background: #fcf9fe;

            color: #374151;

            font-size: 12px;

            line-height: 1.6;

            min-height: 28px;
        }


        /* =========================================================
           TRATAMIENTOS
        ========================================================= */

        .table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 8px;
        }


        .table th {

            background: #f7f0fa;

            color: #52266E;

            text-align: left;

            padding: 8px 9px;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .3px;

            border: 1px solid #e7d7f5;
        }


        .table td {

            padding: 8px 9px;

            border: 1px solid #ece5f0;

            color: #374151;

            font-size: 11px;

            vertical-align: top;
        }


        /* =========================================================
           SIN TRATAMIENTOS
        ========================================================= */

        .no-tratamientos {

            background: #fcf9fe;

            border: 1px dashed #d8c2e8;

            color: #6b7280;

            padding: 12px;

            border-radius: 6px;

            text-align: center;

            font-size: 11px;
        }


        /* =========================================================
           PIE DEL DOCUMENTO
        ========================================================= */

        .footer {

            margin-top: 30px;

            padding-top: 10px;

            border-top: 1px solid #eee1f6;

            font-size: 9px;

            color: #9ca3af;

            text-align: center;
        }


        /* =========================================================
           IMPRESIÓN
        ========================================================= */

        @media print {

            body {

                margin: 15px 20px;
            }
        }

    </style>

</head>


<body>


<!-- =====================================================
     BOTONES DE LA VISTA
     NO APARECEN EN EL PDF
===================================================== -->

<?php if (!$generarPDF): ?>

    <div class="acciones-pantalla">


        <!-- VOLVER -->

        <a
            href="index.php"
            class="btn-volver"
        >

            <i class="fas fa-arrow-left"></i>

            <span>Volver</span>

        </a>


        <!-- DESCARGAR PDF -->

        <a
            href="print.php?id=<?= $hc['id_historia_clinica'] ?>&pdf=1"
            class="btn-descargar-pdf"
        >

            <i class="fas fa-file-pdf"></i>

            <span>Descargar PDF</span>

        </a>


    </div>

<?php endif; ?>



<!-- =====================================================
     ENCABEZADO
===================================================== -->

<div class="header">

    <div class="header-top">


        <!-- Código de historia clínica -->

        <div class="badge-hc">

            HC-<?= str_pad(
                $hc['id_historia_clinica'],
                5,
                '0',
                STR_PAD_LEFT
            ) ?>

        </div>


        <!-- Información del sistema -->

        <div class="header_one">

            <div>
                VetSys - Software Veterinario
            </div>

            <div>
                Fecha de emisión:
                <?= date('d/m/Y H:i') ?>
            </div>

        </div>


        <!-- Título -->

        <div class="logo-title">

            Historia Clínica

        </div>


    </div>

</div>



<!-- =====================================================
     DATOS DE LA CONSULTA
===================================================== -->

<div class="section">

    <h3>Datos de la consulta</h3>


    <div class="grid">


        <div class="item">

            <span class="label">
                Fecha:
            </span>

            <?= date(
                'd/m/Y',
                strtotime($hc['fecha'])
            ) ?>

        </div>


        <div class="item">

            <span class="label">
                Código mascota:
            </span>

            M-<?= str_pad(
                $hc['id_mascota'],
                4,
                '0',
                STR_PAD_LEFT
            ) ?>

        </div>


    </div>

</div>



<!-- =====================================================
     DATOS DE LA MASCOTA
===================================================== -->

<div class="section">

    <h3>Datos de la mascota</h3>


    <div class="grid">


        <!-- Nombre -->

        <div class="item">

            <span class="label">
                Nombre:
            </span>

            <?= !empty($hc['nombre_mascota'])
                ? htmlspecialchars($hc['nombre_mascota'])
                : 'Sin especificar'
            ?>

        </div>


        <!-- Especie y raza -->

        <div class="item">

            <span class="label">
                Especie / Raza:
            </span>

            <?= !empty($hc['nombre_especie'])
                ? htmlspecialchars($hc['nombre_especie'])
                : 'Sin especificar'
            ?>

            /

            <?= !empty($hc['raza'])
                ? htmlspecialchars($hc['raza'])
                : 'Sin especificar'
            ?>

        </div>


        <!-- Sexo -->

        <div class="item">

            <span class="label">
                Sexo:
            </span>

            <?php

            if ($hc['sexo'] == 'M') {

                echo 'Macho';

            } elseif ($hc['sexo'] == 'H') {

                echo 'Hembra';

            } else {

                echo 'Sin especificar';
            }

            ?>

        </div>


        <!-- Peso -->

        <div class="item">

            <span class="label">
                Peso:
            </span>

            <?= !empty($hc['peso'])
                ? htmlspecialchars($hc['peso']) . ' kg'
                : 'Sin especificar'
            ?>

        </div>


        <!-- Edad -->

        <div class="item">

            <span class="label">
                Edad:
            </span>

            <?= htmlspecialchars($edadMascota) ?>

        </div>


        <!-- Color -->

        <div class="item">

            <span class="label">
                Color:
            </span>

            <?= !empty($hc['color'])
                ? htmlspecialchars($hc['color'])
                : 'Sin especificar'
            ?>

        </div>


    </div>

</div>



<!-- =====================================================
     DATOS DEL PROPIETARIO
===================================================== -->

<div class="section">

    <h3>Datos del propietario</h3>


    <div class="grid">


        <!-- Cliente -->

        <div class="item">

            <span class="label">
                Cliente:
            </span>

            <?= htmlspecialchars(
                $hc['apellido_persona']
                . ', '
                . $hc['nombre_persona']
            ) ?>

        </div>


        <!-- Teléfono -->

        <div class="item">

            <span class="label">
                Teléfono:
            </span>

            <?= !empty($hc['telefono'])
                ? htmlspecialchars($hc['telefono'])
                : 'Sin especificar'
            ?>

        </div>


        <!-- Email -->

        <div class="item">

            <span class="label">
                Email:
            </span>

            <?= !empty($hc['email'])
                ? htmlspecialchars($hc['email'])
                : 'Sin especificar'
            ?>

        </div>


    </div>

</div>



<!-- =====================================================
     DESCRIPCIÓN CLÍNICA
===================================================== -->

<div class="section">

    <h3>Descripción clínica</h3>


    <div class="box">

        <?= !empty($hc['descripcion'])
            ? nl2br(htmlspecialchars($hc['descripcion']))
            : 'Sin descripción'
        ?>

    </div>

</div>



<!-- =====================================================
     OBSERVACIÓN
===================================================== -->

<div class="section">

    <h3>Observación</h3>


    <div class="box">

        <?= !empty($hc['observacion'])
            ? nl2br(htmlspecialchars($hc['observacion']))
            : 'Sin observaciones'
        ?>

    </div>

</div>



<!-- =====================================================
     TRATAMIENTOS
===================================================== -->

<div class="section">

    <h3>Tratamientos</h3>


    <?php if (
        $tratamientos &&
        $tratamientos->num_rows > 0
    ) { ?>


        <table class="table">


            <thead>

                <tr>

                    <th>Duración</th>

                    <th>Dosis</th>

                    <th>Descripción</th>

                </tr>

            </thead>


            <tbody>


                <?php while (
                    $t = $tratamientos->fetch_assoc()
                ) { ?>


                    <tr>


                        <td>

                            <?= !empty($t['duracion'])
                                ? htmlspecialchars($t['duracion'])
                                : 'Sin especificar'
                            ?>

                        </td>


                        <td>

                            <?= !empty($t['dosis'])
                                ? htmlspecialchars($t['dosis'])
                                : 'Sin especificar'
                            ?>

                        </td>


                        <td>

                            <?= !empty($t['descripcion'])
                                ? htmlspecialchars($t['descripcion'])
                                : 'Sin descripción'
                            ?>

                        </td>


                    </tr>


                <?php } ?>


            </tbody>


        </table>


    <?php } else { ?>


        <div class="no-tratamientos">

            No se registraron tratamientos para esta consulta.

        </div>


    <?php } ?>


</div>



<!-- =====================================================
     PIE DEL DOCUMENTO
===================================================== -->

<div class="footer">

    VetSys · Software de Gestión Veterinaria

</div>


</body>

</html>


<?php


// Cierra la consulta de tratamientos
$stmtTrat->close();


/*
|--------------------------------------------------------------------------
| GENERACIÓN DEL PDF
|--------------------------------------------------------------------------
*/

if ($generarPDF) {


    // Obtiene todo el HTML generado anteriormente
    $html = ob_get_clean();


    // Opciones de Dompdf
    $options = new Dompdf\Options();


    // Permite cargar recursos remotos
    $options->set(
        'isRemoteEnabled',
        true
    );


    // Crea Dompdf
    $dompdf = new Dompdf\Dompdf(
        $options
    );


    // Carga el HTML
    $dompdf->loadHtml(
        $html
    );


    // Tamaño de hoja
    $dompdf->setPaper(
        'A4',
        'portrait'
    );


    // Genera el PDF
    $dompdf->render();


    // Envía el PDF al navegador
    $dompdf->stream(

        'Historia_Clinica_HC_'
        . str_pad(
            $hc['id_historia_clinica'],
            5,
            '0',
            STR_PAD_LEFT
        )
        . '.pdf',

        [
            'Attachment' => true
        ]
    );


    // Finaliza la ejecución
    exit;
}


?>