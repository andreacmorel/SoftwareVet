<?php

// CONEXIÓN A LA BASE DE DATOS
require_once __DIR__ . '/../settings/conexion.php';

// MENÚ PRINCIPAL DEL SISTEMA
require_once __DIR__ . '/menu.php';

// TOKEN DE SEGURIDAD PARA ENVIAR REPORTES
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_reportes'])) {
    $_SESSION['csrf_reportes'] = bin2hex(random_bytes(32));
}

// =====================================================
// DATOS DE LAS TARJETAS DEL DASHBOARD
// =====================================================


// TOTAL DE MASCOTAS ACTIVAS
$sqlMascotas = "
    SELECT COUNT(*) AS total
    FROM mascota
    WHERE activo = 1
";

$resultadoMascotas = mysqli_query($conexion, $sqlMascotas);
$datosMascotas = mysqli_fetch_assoc($resultadoMascotas);
$totalMascotas = $datosMascotas['total'];


// TOTAL DE CLIENTES ACTIVOS
$sqlClientes = "
    SELECT COUNT(*) AS total
    FROM cliente
    WHERE activo = 1
";

$resultadoClientes = mysqli_query($conexion, $sqlClientes);
$datosClientes = mysqli_fetch_assoc($resultadoClientes);
$totalClientes = $datosClientes['total'];


// TOTAL DE PROFESIONALES ACTIVOS
$sqlProfesionales = "
    SELECT COUNT(*) AS total
    FROM profesional
    WHERE activo = 1
";

$resultadoProfesionales = mysqli_query($conexion, $sqlProfesionales);
$datosProfesionales = mysqli_fetch_assoc($resultadoProfesionales);
$totalProfesionales = $datosProfesionales['total'];


// TURNOS POR ATENDER
$sqlTurnosPorAtender = "
    SELECT COUNT(*) AS total
    FROM turnos
    WHERE activo = 1
    AND estado IN ('pendiente', 'confirmado', 'en_atencion')
";

$resultadoTurnosPorAtender = mysqli_query($conexion, $sqlTurnosPorAtender);
$datosTurnosPorAtender = mysqli_fetch_assoc($resultadoTurnosPorAtender);
$totalTurnosPorAtender = $datosTurnosPorAtender['total'];

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
    FROM turnos
    WHERE estado = 'completado'
    AND activo = 1
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
        COUNT(t.id_turno) AS cantidad
    FROM turnos t
    INNER JOIN mascota m
        ON t.id_mascota = m.id_mascota
    WHERE t.estado = 'completado'
    AND t.activo = 1
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
                                Mascotas 
                            </div>

                            <div class="stat-number">

                                <?= $totalMascotas ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-paw mr-1"></i>

                        Total de mascotas activas

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
                                Clientes
                            </div>

                            <div class="stat-number">

                                <?= $totalClientes ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-user-check mr-1"></i>

                        Total de clientes activos

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
                                Profesionales
                            </div>

                            <div class="stat-number">

                                <?= $totalProfesionales ?>

                            </div>

                        </div>

                    </div>


                    <div class="stat-detail">

                        <i class="fas fa-stethoscope mr-1"></i>

                        Total de profesionales activos

                    </div>


                    <i class="fas fa-stethoscope stat-decoration"></i>

                </div>

            </div>

        </div>

        <!-- TURNOS POR ATENDER -->
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card stat-card stat-pink h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="stat-icon-box mr-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <div>
                            <div class="stat-label">
                                Turnos por atender
                            </div>

                            <div class="stat-number">
                                <?= $totalTurnosPorAtender ?>
                            </div>
                        </div>

                    </div>

                    <div class="stat-detail">
                        <i class="fas fa-clock mr-1"></i>
                        Consultas por atender
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

                        <button type="button"
                            class="btn-exportar btn-exportar-pdf"
                            title="Exportar PDF"
                            aria-label="Exportar PDF"
                            onclick="exportarTurnosEstadoPDF()">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button"
                            class="btn-exportar btn-exportar-excel"
                            title="Exportar Excel"
                            aria-label="Exportar Excel"
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

                        <button type="button"
                            class="btn-exportar btn-exportar-pdf"
                            title="Exportar PDF"
                            aria-label="Exportar PDF"
                            onclick="exportarTurnosMesPDF()">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button"
                            class="btn-exportar btn-exportar-excel"
                            title="Exportar Excel"
                            aria-label="Exportar Excel"
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
                        Atenciones veterinarias por mes
                    </div>

                    <!-- Botones -->
                    <div class="d-flex align-items-center">

                    <button type="button"
                        class="btn-exportar btn-exportar-pdf"
                        title="Exportar PDF"
                        aria-label="Exportar PDF"
                        onclick="exportarHistoriasMesPDF()">
                        <i class="fas fa-file-pdf"></i>
                    </button>

                    <button type="button"
                        class="btn-exportar btn-exportar-excel"
                        title="Exportar Excel"
                        aria-label="Exportar Excel"
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
        MASCOTAS CON MÁS ATENCIONES 
    ====================================================== -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center w-100">

                    <!-- Título -->
                    <div>
                        <i class="fas fa-paw mr-2"></i>
                        Mascotas con más atenciones
                    </div>

                    <!-- Botones -->
                    <div class="d-flex align-items-center">

                        <button type="button"
                            class="btn-exportar btn-exportar-pdf"
                            title="Exportar PDF"
                            aria-label="Exportar PDF"
                            onclick="exportarMascotasHistoriasPDF()">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button"
                            class="btn-exportar btn-exportar-excel"
                            title="Exportar Excel"
                            aria-label="Exportar Excel"
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

    <!-- ====================================================== -->
<!-- REPORTES GENERALES -->
<!-- ====================================================== -->

<div class="row mb-4">
    <div class="col-12">

        <div class="card card-pro">

            <div class="card-header d-flex align-items-center">
                <span class="report-header-icon">
                    <i class="fas fa-file-export"></i>
                </span>
                <div>
                    <div class="report-header-title">Reportes generales de VetSys</div>
                    <div class="report-header-sub">Descargá o enviá la información completa del sistema</div>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="row">

                    <!-- DESCARGA -->
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="report-section-title">
                            <i class="fas fa-download mr-2"></i>Descargar
                        </div>
                        <p class="report-section-desc">
                            Exportá todos los registros en el formato que necesites.
                        </p>

                        <div class="d-flex flex-wrap" style="gap: 12px;">

                            <button type="button"
                                class="report-action report-action-pdf"
                                onclick="exportarTodosPDF()">
                                <span class="report-action-icon"><i class="fas fa-file-pdf"></i></span>
                                <span class="report-action-text">
                                    <strong>Exportar a PDF</strong>
                                    <small>Todos los reportes</small>
                                </span>
                            </button>

                            <button type="button"
                                class="report-action report-action-excel"
                                onclick="exportarTodosExcel()">
                                <span class="report-action-icon"><i class="fas fa-file-excel"></i></span>
                                <span class="report-action-text">
                                    <strong>Exportar a Excel</strong>
                                    <small>Todos los reportes</small>
                                </span>
                            </button>

                        </div>
                    </div>

                    <!-- ENVÍO POR CORREO -->
                    <div class="col-lg-6 report-divider">
                        <form id="formEnviarReportes">
                            <!-- TOKEN DE SEGURIDAD -->
                        <input type="hidden"
                            id="csrfReportes"
                            value="<?= htmlspecialchars($_SESSION['csrf_reportes'], ENT_QUOTES, 'UTF-8') ?>">


                            <div class="report-section-title">
                                <i class="fas fa-envelope mr-2"></i>Enviar por correo
                            </div>
                            <p class="report-section-desc">
                                Se adjuntarán el PDF y el Excel en un solo mensaje.
                            </p>

                            <div class="input-group report-mail-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>
                                </div>

                                <input
                                    type="email"
                                    id="correoReportes"
                                    class="form-control"
                                    placeholder="correo@destinatario.com"
                                    autocomplete="email"
                                    required>

                                <div class="input-group-append">
                                    <button type="submit"
                                        id="btnEnviarReportes"
                                        class="btn btn-vet">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Enviar
                                    </button>
                                </div>
                            </div>

                            <div id="mensajeEnvioReportes"
                                class="mt-3"
                                role="status"
                                aria-live="polite"></div>

                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<!-- ====================================================== -->
<!-- SCRIPTS                                                -->
<!-- Orden: librerías -> datos (dashboardData) -> archivos   -->
<!-- que usan esos datos (charts, export_pdf, export_excel) -->
<!-- ====================================================== -->

<!-- jQuery, Bootstrap y SB Admin 2 -->
<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

<!-- Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Exportación PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<!-- Exportación Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    /*
     * ==========================================================
     * DATOS DEL DASHBOARD
     * ==========================================================
     * Puente entre PHP y JavaScript: los datos que salen de la
     * base de datos se convierten con json_encode().
     *
     * Los usan:
     * - charts.js       -> gráficos
     * - export_pdf.js   -> reportes PDF
     * - export_excel.js -> archivos Excel
     *
     * IMPORTANTE: este bloque tiene que ir ANTES de esos tres
     * archivos, porque ellos lo leen apenas se cargan.
     */
    const dashboardData = {

        // Turnos por estado
        turnosEstado: {
            pendientes:  <?= json_encode($pendientes) ?>,
            confirmados: <?= json_encode($confirmados) ?>,
            enAtencion:  <?= json_encode($enAtencion) ?>,
            completados: <?= json_encode($completados) ?>,
            cancelados:  <?= json_encode($cancelados) ?>
        },

        // Turnos por mes
        mesesTurnos:       <?= json_encode($mesesTurnos) ?>,
        cantidadTurnosMes: <?= json_encode($cantidadTurnosMes) ?>,

        // Historias clínicas por mes
        mesesHistorias:       <?= json_encode($mesesHistorias) ?>,
        cantidadHistoriasMes: <?= json_encode($cantidadHistoriasMes) ?>,

        // Mascotas con más registros clínicos
        nombresMascotasHistorias:  <?= json_encode($nombresMascotasHistorias) ?>,
        cantidadHistoriasMascotas: <?= json_encode($cantidadHistoriasMascotas) ?>
    };
</script>

<!-- Donde están los gráficos -->
<script src="/SoftwareVet/js/dashboard/charts.js?v=2"></script>
<!-- Funciones para exportar PDF -->
<script src="/SoftwareVet/js/dashboard/export_pdf.js?v=2"></script>
<!-- Funciones para exportar Excel -->
<script src="/SoftwareVet/js/dashboard/export_excel.js?v=8"></script>
<!-- ENVÍO DE REPORTES POR CORREO -->
<script src="/SoftwareVet/js/dashboard/send_mail.js?v=1"></script>