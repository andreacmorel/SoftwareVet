<?php

// CONEXIÓN A LA BASE DE DATOS
require_once __DIR__ . '/../settings/conexion.php';

// MENÚ PRINCIPAL DEL SISTEMA
require_once __DIR__ . '/menu.php';


// DATOS REALES PARA LAS TARJETAS DEL DASHBOARD

// TOTAL DE MASCOTAS
$sqlMascotas = "SELECT COUNT(*) AS total FROM mascota";

$resultadoMascotas = mysqli_query($conexion, $sqlMascotas);
$datosMascotas = mysqli_fetch_assoc($resultadoMascotas);
$totalMascotas = $datosMascotas['total'];


// TOTAL DE CLIENTES
$sqlClientes = "SELECT COUNT(*) AS total FROM cliente";

$resultadoClientes = mysqli_query($conexion, $sqlClientes);
$datosClientes = mysqli_fetch_assoc($resultadoClientes);
$totalClientes = $datosClientes['total'];


// TOTAL DE PROFESIONALES
$sqlProfesionales = "SELECT COUNT(*) AS total FROM profesional";

$resultadoProfesionales = mysqli_query($conexion, $sqlProfesionales);
$datosProfesionales = mysqli_fetch_assoc($resultadoProfesionales);
$totalProfesionales = $datosProfesionales['total'];


// TURNOS DE HOY
$sqlTurnosHoy = "SELECT COUNT(*) AS total FROM turnos WHERE fecha = CURDATE()";

$resultadoTurnosHoy = mysqli_query($conexion, $sqlTurnosHoy);
$datosTurnosHoy = mysqli_fetch_assoc($resultadoTurnosHoy);
$totalTurnosHoy = $datosTurnosHoy['total'];


// TURNOS PENDIENTES DE HOY
$sqlPendientesHoy = "SELECT COUNT(*) AS total FROM turnos
    WHERE fecha = CURDATE() AND estado = 'pendiente'";

$resultadoPendientesHoy = mysqli_query($conexion, $sqlPendientesHoy);
$datosPendientesHoy = mysqli_fetch_assoc($resultadoPendientesHoy);
$totalPendientesHoy = $datosPendientesHoy['total'];

// TURNOS POR ESTADO
$sqlTurnosEstado = " SELECT estado, COUNT(*) AS cantidad FROM turnos GROUP BY estado";

$resultadoTurnosEstado = mysqli_query($conexion, $sqlTurnosEstado);

// Valores iniciales
$pendientes = 0;
$confirmados = 0;
$enAtencion = 0;
$completados = 0;
$cancelados = 0;

// Recorremos los resultados
while ($filaEstado = mysqli_fetch_assoc($resultadoTurnosEstado)) {

    if ($filaEstado['estado'] == 'pendiente') {
        $pendientes = $filaEstado['cantidad'];
    }

    if ($filaEstado['estado'] == 'confirmado') {
        $confirmados = $filaEstado['cantidad'];
    }

    if ($filaEstado['estado'] == 'en_atencion') {
        $enAtencion = $filaEstado['cantidad'];
    }

    if ($filaEstado['estado'] == 'completado') {
        $completados = $filaEstado['cantidad'];
    }

    if ($filaEstado['estado'] == 'cancelado') {
        $cancelados = $filaEstado['cantidad'];
    }

}

// TURNOS POR MES

$sqlTurnosMes = "
    SELECT 
        YEAR(fecha) AS anio,
        MONTH(fecha) AS mes,
        COUNT(*) AS cantidad
    FROM turnos
    GROUP BY YEAR(fecha), MONTH(fecha)
    ORDER BY YEAR(fecha), MONTH(fecha)
";

$resultadoTurnosMes = mysqli_query($conexion, $sqlTurnosMes);

$mesesTurnos = [];
$cantidadTurnosMes = [];

$nombresMeses = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
];

while ($filaMes = mysqli_fetch_assoc($resultadoTurnosMes)) {

    $numeroMes = (int)$filaMes['mes'];

    $mesesTurnos[] = $nombresMeses[$numeroMes];

    $cantidadTurnosMes[] = (int)$filaMes['cantidad'];
}

// HISTORIAS CLÍNICAS POR MES

$sqlHistoriasMes = "
    SELECT
        YEAR(fecha) AS anio,
        MONTH(fecha) AS mes,
        COUNT(*) AS cantidad
    FROM historia_clinica
    WHERE activo = 1
    GROUP BY YEAR(fecha), MONTH(fecha)
    ORDER BY YEAR(fecha), MONTH(fecha)
";

$resultadoHistoriasMes = mysqli_query($conexion, $sqlHistoriasMes);

$mesesHistorias = [];
$cantidadHistoriasMes = [];

$nombresMesesHistorias = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
];

while ($filaHistoriaMes = mysqli_fetch_assoc($resultadoHistoriasMes)) {

    $numeroMes = (int)$filaHistoriaMes['mes'];

    $mesesHistorias[] =
        $nombresMesesHistorias[$numeroMes];

    $cantidadHistoriasMes[] =
        (int)$filaHistoriaMes['cantidad'];
}

// MASCOTAS CON MÁS REGISTROS CLÍNICOS

$sqlMascotasHistorias = "
    SELECT
        m.nombre_mascota,
        COUNT(h.id_historia_clinica) AS cantidad
    FROM historia_clinica h
    INNER JOIN mascota m
        ON h.id_mascota = m.id_mascota
    WHERE h.activo = 1
    GROUP BY m.id_mascota, m.nombre_mascota
    ORDER BY cantidad DESC
    LIMIT 5
";

$resultadoMascotasHistorias =
    mysqli_query($conexion, $sqlMascotasHistorias);

$nombresMascotasHistorias = [];
$cantidadHistoriasMascotas = [];

while (
    $filaMascotaHistoria =
    mysqli_fetch_assoc($resultadoMascotasHistorias)
) {

    $nombresMascotasHistorias[] =
        $filaMascotaHistoria['nombre_mascota'];

    $cantidadHistoriasMascotas[] =
        (int)$filaMascotaHistoria['cantidad'];
}
?>


<?php if(isset($_GET['error']) && $_GET['error'] == 'sin_permiso') { ?>

<div class="vet-alert-danger">

    <div class="vet-alert-icon">
        <i class="fas fa-lock"></i>
    </div>

    <div class="vet-alert-content">
        <h5>Acceso denegado</h5>
        <p>Su perfil no posee permisos para acceder a este módulo.</p>
    </div>

</div>

<?php } ?>

<head>
    <meta charset="UTF-8">
    <title>VETSYS - Dashboard</title>
    <link href="/SoftwareVet/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/SoftwareVet/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/SoftwareVet/css/style_panel.css" rel="stylesheet">
</head>

<div class="container-fluid">


    <!-- ====================================================== -->
    <!-- BIENVENIDA -->
    <!-- ====================================================== -->

    <div class="card welcome-card mb-4 shadow">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h1 class="h3 mb-1 font-weight-bold">
                    Bienvenido a VETSYS
                </h1>

                <p class="mb-0">
                    Panel general del sistema veterinario
                </p>

            </div>

            <i class="fas fa-paw"></i>

        </div>

    </div>



    <!-- ====================================================== -->
    <!-- TARJETAS COLORIDAS -->
    <!-- ====================================================== -->

    <div class="row">


        <!-- MASCOTAS -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card stat-card stat-purple h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="stat-icon-box mr-3">

                            <i class="fas fa-paw"></i>

                        </div>


                        <div>

                            <div class="stat-label">
                                Mascotas registradas
                            </div>

                            <div class="stat-number">

                                <?= $totalMascotas ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-paw mr-1"></i>

                        Total de mascotas

                    </div>


                    <i class="fas fa-paw stat-decoration"></i>

                </div>

            </div>

        </div>



        <!-- CLIENTES -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card stat-card stat-green h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">


                        <div class="stat-icon-box mr-3">

                            <i class="fas fa-users"></i>

                        </div>


                        <div>

                            <div class="stat-label">
                                Clientes registrados
                            </div>

                            <div class="stat-number">

                                <?= $totalClientes ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-user-check mr-1"></i>

                        Total de clientes

                    </div>


                    <i class="fas fa-users stat-decoration"></i>

                </div>

            </div>

        </div>



        <!-- PROFESIONALES -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card stat-card stat-orange h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">


                        <div class="stat-icon-box mr-3">

                            <i class="fas fa-user-md"></i>

                        </div>


                        <div>

                            <div class="stat-label">
                                Profesionales registrados
                            </div>

                            <div class="stat-number">

                                <?= $totalProfesionales ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-stethoscope mr-1"></i>

                        Total de profesionales

                    </div>


                    <i class="fas fa-stethoscope stat-decoration"></i>

                </div>

            </div>

        </div>

        <!-- TURNOS -->
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card stat-card stat-pink h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">


                        <div class="stat-icon-box mr-3">

                            <i class="fas fa-calendar-check"></i>

                        </div>


                        <div>

                            <div class="stat-label">
                                Turnos de hoy
                            </div>

                            <div class="stat-number">

                                <?= $totalTurnosHoy ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-clock mr-1"></i>

                        <?= $totalPendientesHoy ?> pendientes

                    </div>


                    <i class="fas fa-calendar-alt stat-decoration"></i>

                </div>

            </div>

        </div>


    </div>

<!-- ====================================================== -->
<!-- GRÁFICOS -->
<!-- ====================================================== -->
<!-- ====================================================== -->
<!-- PRIMERA FILA DE GRÁFICOS -->
<!-- ====================================================== -->

<div class="row">

    <!-- =====================================================
        TURNOS POR ESTADO
    ====================================================== -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center w-100">

                    <!-- Título -->
                    <div>
                        <i class="fas fa-chart-pie mr-2"></i>
                        Turnos por estado
                    </div>

                    <!-- Botones -->
                    <div class="d-flex align-items-center">

                        <button type="button" class="btn btn-sm btn-outline-danger mr-2"
                            title="Exportar PDF"
                            onclick="exportarTurnosEstadoPDF()">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button"class="btn btn-sm btn-outline-success"
                            title="Exportar Excel"
                            onclick="exportarTurnosEstadoExcel()">
                            <i class="fas fa-file-excel"></i>
                        </button>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div style="height: 320px;">

                    <canvas id="graficoTurnosEstado"></canvas>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
        TURNOS POR MES
    ====================================================== -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center w-100">

                    <!-- Título -->
                    <div>
                        <i class="fas fa-chart-line mr-2"></i>
                        Turnos por mes
                    </div>

                    <!-- Botones -->
                    <div class="d-flex align-items-center">

                        <button type="button" class="btn btn-sm btn-outline-danger mr-2"
                            title="Exportar PDF"
                            onclick="exportarTurnosMesPDF()">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-outline-success"
                            title="Exportar Excel"
                            onclick="exportarTurnosMesExcel()">
                            <i class="fas fa-file-excel"></i>
                        </button>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div style="height: 320px;">

                    <canvas id="graficoTurnosMes"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- ====================================================== -->
<!-- SEGUNDA FILA DE GRÁFICOS -->
<!-- ====================================================== -->

<div class="row">

    <!-- =====================================================
        HISTORIAS CLÍNICAS POR MES
    ====================================================== -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center w-100">

                    <!-- Título -->
                    <div>
                        <i class="fas fa-notes-medical mr-2"></i>
                        Historias clínicas por mes
                    </div>

                    <!-- Botones -->
                    <div class="d-flex align-items-center">

                        <button type="button" class="btn btn-sm btn-outline-danger mr-2" title="Exportar PDF"
                            onclick="exportarHistoriasMesPDF()">

                            <i class="fas fa-file-pdf"></i>

                        </button>

                        <button type="button" class="btn btn-sm btn-outline-success" title="Exportar Excel"
                            onclick="exportarHistoriasMesExcel()">

                            <i class="fas fa-file-excel"></i>

                        </button>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div style="height: 320px;">

                    <canvas id="graficoHistoriasMes"></canvas>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
        MASCOTAS CON MÁS REGISTROS CLÍNICOS
    ====================================================== -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center w-100">

                    <!-- Título -->
                    <div>
                        <i class="fas fa-paw mr-2"></i>
                        Mascotas con más registros clínicos
                    </div>

                    <!-- Botones -->
                    <div class="d-flex align-items-center">

                        <button type="button" class="btn btn-sm btn-outline-danger mr-2" title="Exportar PDF"
                            onclick="exportarMascotasHistoriasPDF()">

                            <i class="fas fa-file-pdf"></i>

                        </button>

                        <button type="button" class="btn btn-sm btn-outline-success"title="Exportar Excel"
                            onclick="exportarMascotasHistoriasExcel()">

                            <i class="fas fa-file-excel"></i>

                        </button>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div style="height: 320px;">

                    <canvas id="graficoMascotasHistorias"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- jQuery -->
<script src="../../vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SB Admin 2 -->
<script src="../../js/sb-admin-2.min.js"></script>
<!-- Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Exportación PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Tablas dentro del PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<!-- Exportación Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    /*
     * ==========================================================
     * DATOS DEL DASHBOARD
     * ==========================================================
     * Este objeto funciona como puente entre PHP y JavaScript.
     *
     * Los datos se obtienen previamente desde la base de datos
     * mediante PHP y se convierten a un formato que JavaScript
     * puede interpretar utilizando json_encode().
     *
     * Estos datos después son utilizados por:
     *
     * - charts.js       → para generar los gráficos.
     * - export_pdf.js   → para generar los reportes PDF.
     * - export_excel.js → para generar los archivos Excel.
     * 
     * json_encode() convierte los datos de PHP a un formato que JavaScript puede interpretar.
     */
const dashboardData = {
        /*
         * ------------------------------------------------------
         * TURNOS POR ESTADO
         * ------------------------------------------------------
         *
         * Guarda la cantidad de turnos correspondientes 
         *  a cada uno de los estados disponibles en el sistema.
         */
    turnosEstado: {
        pendientes: <?= json_encode($pendientes) ?>,
        confirmados: <?= json_encode($confirmados) ?>,
        enAtencion: <?= json_encode($enAtencion) ?>,
        completados: <?= json_encode($completados) ?>,
        cancelados: <?= json_encode($cancelados) ?>
    },
    /*
         * ------------------------------------------------------
         * TURNOS POR MES
         * ------------------------------------------------------
         *
         * mesesTurnos contiene los nombres de los meses.
         *
         * cantidadTurnosMes contiene la cantidad de turnos
         * registrados en cada uno de esos meses.
         */

        // Meses que aparecen en el gráfico
    mesesTurnos: <?= json_encode($mesesTurnos) ?>,
    // Cantidad de turnos correspondiente a cada mes
    cantidadTurnosMes: <?= json_encode($cantidadTurnosMes) ?>,
    /*
         * ------------------------------------------------------
         * HISTORIAS CLÍNICAS POR MES
         * ------------------------------------------------------
         *
         * Contiene los meses y la cantidad de historias
         * clínicas registradas durante cada mes.
         */

        // Meses que aparecen en el gráfico de historias clínicas
    mesesHistorias: <?= json_encode($mesesHistorias) ?>,
     // Cantidad de historias clínicas correspondiente a cada mes
    cantidadHistoriasMes: <?= json_encode($cantidadHistoriasMes) ?>,
    /*
         * ------------------------------------------------------
         * MASCOTAS CON MÁS REGISTROS CLÍNICOS
         * ------------------------------------------------------
         *
         * Guarda los nombres de las mascotas y la cantidad
         * de registros clínicos que posee cada una.
         */

        // Nombres de las mascotas
    nombresMascotasHistorias: <?= json_encode($nombresMascotasHistorias) ?>,
    // Cantidad de registros clínicos de cada mascota
    cantidadHistoriasMascotas: <?= json_encode($cantidadHistoriasMascotas) ?>

};

</script>


<!-- Donde estan los gráficos -->
<script src="../js/dashboard/charts.js"></script>
<!-- Funciones para exportar PDF -->
<script src="../js/dashboard/export_pdf.js"></script>
<!-- Funciones para exportar Excel -->
<script src="../js/dashboard/export_excel.js"></script>