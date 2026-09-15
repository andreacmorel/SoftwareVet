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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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

    <!-- TURNOS POR ESTADO -->

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

                    <button type="button" class="btn btn-sm btn-outline-success"
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


    <!-- TURNOS POR MES -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <i class="fas fa-chart-line mr-2"></i>
                Turnos por mes

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

    <!-- HISTORIAS CLÍNICAS POR MES -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <i class="fas fa-notes-medical mr-2"></i>
                Historias clínicas por mes

            </div>

            <div class="card-body">

                <div style="height: 320px;">

                    <canvas id="graficoHistoriasMes"></canvas>

                </div>

            </div>

        </div>

    </div>


    <!-- MASCOTAS CON MÁS REGISTROS CLÍNICOS -->

    <div class="col-lg-6 mb-4">

        <div class="card card-pro h-100">

            <div class="card-header">

                <i class="fas fa-paw mr-2"></i>
                Mascotas con más registros clínicos

            </div>

            <div class="card-body">

                <div style="height: 320px;">

                    <canvas id="graficoMascotasHistorias"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>



<!-- GRÁFICO TURNOS POR ESTADO -->
<script>

const ctxTurnosEstado = document.getElementById('graficoTurnosEstado'); 
// busca un elemento del html que contenga id "graficoTurnosEstado"
// que lo encontramos arriba  en el html donde dice canva id
// y creamos una constante ctxTurnoEstado donde guardamos ese elemento
// osea donde queremos colocar el grafico 

new Chart(ctxTurnosEstado, { // creamos el grafico, aca comienza toda la config del grafico que queremos

    type: 'doughnut', // doughnut es el tipo de grafico que queremos en este caso el circular
                      // llamado DONA 

    data: { // aca comienza la informacion osea los datos que va a representar el grafico

        labels: [ //labels -> representa cada dato SON LAS ETIQUETAS
            'Pendiente',
            'Confirmado',
            'En atención',
            'Completado',
            'Cancelado'
        ],

        datasets: [{ //dataset -> representa cuales son los valores
                    // conjunto de datos que queremos representar

            data: [
                <?= $pendientes ?>, //muestra el valor de cada php
                <?= $confirmados ?>,
                <?= $enAtencion ?>,
                <?= $completados ?>,
                <?= $cancelados ?>
            ],

            backgroundColor: [ //representa el color de cada seccion 
                '#f6c23e',
                '#4e73df',
                '#36b9cc',
                '#1cc88a',
                '#e74a3b'
            ],

            borderWidth: 3,// borde osea el grosor
            borderColor: '#ffffff' //establece el color del borde

        }]

    },

    options: { // aca mostramos como se comporta y se visualiza el grafico 

        responsive: true, // diseño responsive se adapta a la pantalla

        maintainAspectRatio: false, //adapta al alto y ancho del contenedor donde se encuentra

        cutout: '65%', // el tamaño del agujero del grafico el centro

        plugins: { // son los distintos componentes del grafico

            legend: { //leyenda

                position: 'right', //posicion de la leyenda la leyenda son los estados que vemos 
                                   // de los turnos

                labels: {
                    usePointStyle: true, // estilo compacto
                    padding: 18 // separacion
                }

            }

        }

    }

}); // cerramos todo

</script>

<script>

const ctxTurnosMes = document.getElementById('graficoTurnosMes');
// busca un elemento del html que contenga id "graficoTurnosMe"
// que lo encontramos arriba  en el html donde dice canva id
// y creamos una constante ctxTurnosMes donde guardamos ese elemento
// osea donde queremos colocar el grafico 

new Chart(ctxTurnosMes, { //creamos el grafico, aca comienza toda la config del grafico q queremos

    type: 'line', //definimos tipo de grafico line: grafico de linea

    data: { // informacion que muestra el grafico

        labels: <?= json_encode($mesesTurnos) ?>,
        //json_encode convierte una estructura PHP en un formato que javascript puede interpretar

        datasets: [{

            label: 'Cantidad de turnos',

            data: <?= json_encode($cantidadTurnosMes) ?>,
            //misma linea pero con las cantidades de turnos

            borderColor: '#7c3aed', //color de la linea

            backgroundColor: 'rgba(124, 58, 237, 0.12)', //color debajo de la linea

            borderWidth: 3, // grosor del borde

            tension: 0.4, // curvas suaves

            fill: true, //rellená el espacio que queda debajo de la línea

            pointRadius: 5, // tamaño de los puntos

            pointHoverRadius: 7

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        },

        scales: {

            y: {

                beginAtZero: true,

                ticks: {
                    precision: 0
                }

            },

            x: {

                grid: {
                    display: false
                }

            }

        }

    }

});

</script>

<script>

const ctxHistoriasMes =
    document.getElementById('graficoHistoriasMes');

new Chart(ctxHistoriasMes, {

    type: 'line',

    data: {

        labels: <?= json_encode($mesesHistorias) ?>,

        datasets: [{

            label: 'Historias clínicas',

            data: <?= json_encode($cantidadHistoriasMes) ?>,

            borderColor: '#16b89c',

            backgroundColor: 'rgba(22, 184, 156, 0.12)',

            borderWidth: 3,

            tension: 0.4,

            fill: true,

            pointRadius: 5,

            pointHoverRadius: 7

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        },

        scales: {

            y: {

                beginAtZero: true,

                ticks: {
                    precision: 0
                }

            },

            x: {

                grid: {
                    display: false
                }

            }

        }

    }

});

</script>

<script>

const ctxMascotasHistorias =
    document.getElementById('graficoMascotasHistorias');

new Chart(ctxMascotasHistorias, {

    type: 'bar',

    data: {

        labels:
            <?= json_encode($nombresMascotasHistorias) ?>,

        datasets: [{

            label: 'Registros clínicos',

            data:
                <?= json_encode($cantidadHistoriasMascotas) ?>,

            backgroundColor: [
                '#7c3aed',
                '#16b89c',
                '#f29a2e',
                '#ec3f72',
                '#36b9cc'
            ],

            borderRadius: 8,

            borderSkipped: false

        }]

    },

    options: {

        indexAxis: 'y',

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        },

        scales: {

            x: {

                beginAtZero: true,

                ticks: {
                    precision: 0
                }

            },

            y: {

                grid: {
                    display: false
                }

            }

        }

    }

});

</script>

<script>

/* =========================================================
EXPORTAR TURNOS POR ESTADO A PDF
========================================================= */

function exportarTurnosEstadoPDF() {

    // Busca el canvas donde está dibujado el gráfico
    const canvas = document.getElementById('graficoTurnosEstado');

    // Convierte el gráfico en una imagen
    const imagen = canvas.toDataURL('image/png', 1.0);

    // Obtiene jsPDF
    const { jsPDF } = window.jspdf;

    // Crea el documento PDF
    const pdf = new jsPDF();


    /* ==========================================
    ENCABEZADO VETSYS
    ========================================== */

    pdf.setFillColor(82, 38, 110);

    pdf.rect(
        0,
        0,
        210,
        28,
        'F'
    );


    // Nombre del sistema

    pdf.setTextColor(255, 255, 255);

    pdf.setFontSize(18);

    pdf.setFont(undefined, 'bold');

    pdf.text(
        'VetSys',
        15,
        12
    );


    // Subtítulo del sistema

    pdf.setFontSize(9);

    pdf.setFont(undefined, 'normal');

    pdf.text(
        'Software Veterinario',
        15,
        19
    );


    // Texto REPORTE

    pdf.setFontSize(10);

    pdf.setFont(undefined, 'bold');

    pdf.text(
        'REPORTE',
        195,
        14,
        {
            align: 'right'
        }
    );


    /* ==========================================
    TÍTULO DEL REPORTE
    ========================================== */

    pdf.setTextColor(82, 38, 110);

    pdf.setFontSize(16);

    pdf.setFont(undefined, 'bold');

    pdf.text(
        'Turnos por estado',
        15,
        42
    );


    // Descripción

    pdf.setTextColor(110, 110, 110);

    pdf.setFontSize(9);

    pdf.setFont(undefined, 'normal');

    pdf.text(
        'Resumen estadístico de los turnos registrados en el sistema.',
        15,
        49
    );


    /* ==========================================
    FECHA
    ========================================== */

    const fechaActual =
        new Date().toLocaleDateString('es-AR');


    pdf.setFontSize(8);

    pdf.text(
        'Generado: ' + fechaActual,
        195,
        42,
        {
            align: 'right'
        }
    );


    /* ==========================================
    GRÁFICO
    ========================================== */

    pdf.addImage(
        imagen,
        'PNG',
        40,
        57,
        130,
        85
    );


    /* ==========================================
    TABLA
    ========================================== */

    pdf.autoTable({

        startY: 150,

        head: [

            [
                'Estado',
                'Cantidad'
            ]

        ],

        body: [

            [
                'Pendiente',
                <?= $pendientes ?>
            ],

            [
                'Confirmado',
                <?= $confirmados ?>
            ],

            [
                'En atención',
                <?= $enAtencion ?>
            ],

            [
                'Completado',
                <?= $completados ?>
            ],

            [
                'Cancelado',
                <?= $cancelados ?>
            ]

        ],

        theme: 'grid',

        headStyles: {

            fillColor: [82, 38, 110],

            textColor: [255, 255, 255],

            fontStyle: 'bold'

        },

        styles: {

            fontSize: 9,

            cellPadding: 3

        },

        columnStyles: {

            1: {

                halign: 'center'

            }

        },

        margin: {

            left: 25,

            right: 25

        }

    });


    /* ==========================================
    FOOTER
    ========================================== */

    pdf.setDrawColor(
        220,
        220,
        220
    );


    pdf.line(
        15,
        280,
        195,
        280
    );


    pdf.setTextColor(
        130,
        130,
        130
    );


    pdf.setFontSize(8);

    pdf.setFont(
        undefined,
        'normal'
    );


    pdf.text(
        '© 2026 VetSys - Software Veterinario',
        15,
        287
    );


    /* ==========================================
    DESCARGA
    ========================================== */

    pdf.save(
        'VetSys_Turnos_por_estado.pdf'
    );

}


function exportarTurnosEstadoExcel() {

    /* ==========================================
       DATOS DEL REPORTE
    ========================================== */

    const datos = [

        ['VETSYS - SOFTWARE VETERINARIO', ''],
        ['Reporte: Turnos por estado', ''],
        ['Fecha de generación:', new Date().toLocaleDateString('es-AR')],
        ['', ''],

        ['Estado', 'Cantidad'],

        ['Pendiente', <?= $pendientes ?>],
        ['Confirmado', <?= $confirmados ?>],
        ['En atención', <?= $enAtencion ?>],
        ['Completado', <?= $completados ?>],
        ['Cancelado', <?= $cancelados ?>]

    ];


    /* ==========================================
       CREAR HOJA
    ========================================== */

    const hoja = XLSX.utils.aoa_to_sheet(datos);


    /* ==========================================
       ANCHO DE COLUMNAS
    ========================================== */

    hoja['!cols'] = [

        {
            wch: 30
        },

        {
            wch: 18
        }

    ];


    /* ==========================================
       COMBINAR TÍTULOS
    ========================================== */

    hoja['!merges'] = [

        // VetSys
        {
            s: { r: 0, c: 0 },
            e: { r: 0, c: 1 }
        },

        // Reporte
        {
            s: { r: 1, c: 0 },
            e: { r: 1, c: 1 }
        }

    ];


    /* ==========================================
       CREAR LIBRO EXCEL
    ========================================== */

    const libro = XLSX.utils.book_new();


    /* ==========================================
       AGREGAR HOJA
    ========================================== */

    XLSX.utils.book_append_sheet(
        libro,
        hoja,
        'Turnos por estado'
    );


    /* ==========================================
       DESCARGAR ARCHIVO
    ========================================== */

    XLSX.writeFile(
        libro,
        'VetSys_Turnos_por_estado.xlsx'
    );

}

</script>