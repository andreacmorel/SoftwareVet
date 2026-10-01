<?php

require_once '../../app/menu.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">

    <title>Historia Clínica</title>

    <!-- Font Awesome -->
    <link
        href="../../vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
    >

    <!-- SB Admin -->
    <link
        href="../../css/sb-admin-2.min.css"
        rel="stylesheet"
    >

    <!-- Estilos Historia Clínica -->
    <link
        href="../../css/style_medicalrecord.css"
        rel="stylesheet"
    >

    <!-- DataTables -->
    <link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<link rel="stylesheet"
      href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

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


        <li class="breadcrumb-item active">

            Historia Clínica

        </li>

    </ol>

</div>



<!-- =========================================================
     CONTENIDO
========================================================= -->

<div class="container-fluid">


    <!-- =====================================================
         ENCABEZADO
    ====================================================== -->

    <div
        class="d-flex justify-content-between align-items-center flex-wrap mb-4"
    >

        <div>

            <h1 class="h3 page-title">

                <i class="fas fa-notes-medical mr-2"></i>

                Historia Clínica

            </h1>


            <div class="page-subtitle">

                Historial clínico de mascotas

            </div>

        </div>


        <!-- BOTONES DATATABLE -->

        <div id="botonesExportacion"></div>

    </div>



    <!-- =====================================================
         FILTRO
    ====================================================== -->

    <form
        method="GET"
        class="filter-card"
    >

        <div class="row align-items-end">


            <!-- BUSCAR -->

            <div class="col-md-10">

                <label>
                    Buscar
                </label>

                <input
                    type="text"
                    name="buscar"
                    class="form-control"
                    placeholder="Buscar por mascota, propietario, especie o raza..."
                    value="<?= htmlspecialchars($buscar ?? '') ?>"
                >

            </div>


            <!-- BOTÓN -->

            <div class="col-md-2">

                <button
                    type="submit"
                    class="btn btn-filtro"
                    title="Buscar"
                >

                    <i class="fas fa-search"></i>

                </button>

            </div>


        </div>

    </form>



    <!-- =====================================================
         TABLA
    ====================================================== -->

    <div class="table-card">

        <div class="table-responsive">

            <table
                class="table table-hover"
                width="100%"
                id="tablaHistorias"
            >

                <thead>

                    <tr>

                        <th>
                            Mascota
                        </th>

                        <th>
                            Propietario
                        </th>

                        <th>
                            Especie
                        </th>

                        <th>
                            Raza
                        </th>

                        <th
                            class="text-center"
                            style="width: 100px;"
                        >
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php if (
                    $result &&
                    $result->num_rows > 0
                ) { ?>


                    <?php while (
                        $h = $result->fetch_object()
                    ) { ?>


                        <tr>


                            <!-- =============================
                                 MASCOTA
                            ============================== -->

                            <td>

                                <div class="d-flex align-items-center">


                                    <span class="historia-icon">

                                        <i class="fas fa-paw"></i>

                                    </span>


                                    <div>


                                        <div class="mascota-name">

                                            <?= htmlspecialchars(
                                                $h->nombre_mascota
                                            ) ?>

                                        </div>


                                        <div class="hc-code">

                                            M-<?= str_pad(
                                                $h->id_mascota,
                                                4,
                                                '0',
                                                STR_PAD_LEFT
                                            ) ?>

                                        </div>


                                    </div>


                                </div>

                            </td>



                            <!-- =============================
                                 PROPIETARIO
                            ============================== -->

                            <td>

                                <div class="dato-principal">

                                    <?= htmlspecialchars(
                                        $h->propietario
                                    ) ?>

                                </div>

                            </td>



                            <!-- =============================
                                 ESPECIE
                            ============================== -->

                            <td>

                                <span class="badge-especie">

                                    <i class="fas fa-paw mr-1"></i>

                                    <?= htmlspecialchars(
                                        $h->nombre_especie
                                    ) ?>

                                </span>

                            </td>



                            <!-- =============================
                                 RAZA
                            ============================== -->

                            <td>

                                <div class="dato-muted">

                                    <?= !empty($h->raza)
                                        ? htmlspecialchars($h->raza)
                                        : 'Sin especificar'
                                    ?>

                                </div>

                            </td>



                            <!-- =============================
                                 ACCIONES
                            ============================== -->

                            <td class="text-center align-middle">


                                <div class="action-buttons">


                                    <!-- VER HISTORIA -->

                                    <a
                                        href="show.php?id=<?= $h->id_mascota ?>"
                                        class="btn-action btn-view"
                                        title="Ver historia clínica"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>


                                </div>


                            </td>


                        </tr>


                    <?php } ?>


                <?php } else { ?>


                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-muted py-4"
                        >

                            <i class="fas fa-search mr-1"></i>

                            No se encontraron mascotas.

                        </td>

                    </tr>


                <?php } ?>


                </tbody>


            </table>

        </div>

    </div>


</div>



<!-- =========================================================
     SCRIPTS GENERALES
========================================================= -->

<script src="../../vendor/jquery/jquery.min.js"></script>

<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../../js/sb-admin-2.min.js"></script>


<!-- =========================================================
     DATATABLES
========================================================= -->

<script src="../../vendor/datatables/jquery.dataTables.min.js"></script>

<script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>


<!-- =========================================================
     DATATABLES BUTTONS
========================================================= -->

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Imprimir -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- VetSys DataTables -->
<script src="../../js/vetsys-datatables.js"></script>



<script>

$(document).ready(function () {


    /* =====================================================
       DATATABLE HISTORIA CLÍNICA
    ===================================================== */

    inicializarDataTableVetSys({

        tabla: '#tablaHistorias',

        titulo: 'Historia Clínica',

        subtitulo: 'Historial clínico de mascotas',

        nombreArchivo: 'Historias_Clinicas',


        /*
         * Exportamos:
         *
         * 0 Mascota
         * 1 Propietario
         * 2 Especie
         * 3 Raza
         *
         * Acciones NO se exporta.
         */

        columnasExportar: [
            0,
            1,
            2,
            3
        ],


        pageLength: 10,


        orientacionPDF: 'landscape',


        anchosExcel: [
            25,
            30,
            20,
            25
        ],


        anchosPDF: [
            '25%',
            '30%',
            '20%',
            '25%'
        ]

    });


});

</script>



</body>

</html>