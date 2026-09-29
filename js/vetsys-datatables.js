// =========================================================
// VETSYS - DATATABLES REUTILIZABLE
// =========================================================

function inicializarDataTableVetSys(config) {

   const {
    tabla,
    titulo,
    subtitulo = '',
    nombreArchivo,
    columnasExportar = ':not(:last-child)',
    pageLength = 10,
    formatearCelda = null,
    anchosExcel = null,
    anchosPDF = null,
    textoVacioPDF = 'S/E',

    // Por defecto los PDF salen verticales
    orientacionPDF = 'portrait',

    contenedorBotones = '#botonesExportacion'
    } = config;


    // =====================================================
    // NOMBRE DEL ARCHIVO CON FECHA
    // =====================================================

    function generarNombreArchivo() {

        const fecha = new Date();

        const dia =
            String(fecha.getDate()).padStart(2, '0');

        const mes =
            String(fecha.getMonth() + 1).padStart(2, '0');

        const anio =
            fecha.getFullYear();

        return `${nombreArchivo}_${dia}-${mes}-${anio}`;
    }


    // =====================================================
    // LIMPIEZA GENERAL DE CELDAS
    // =====================================================

    function limpiarCelda(data, row, column, node) {

        const celda = $(node).clone();

        // Elimina el menú desplegable de estados
        // para que no aparezcan todas las opciones al exportar
        celda.find('.estado-menu').remove();

        // Elementos que no queremos exportar
        celda.find('.no-export').remove();

        let texto = celda
            .text()
            .replace(/\s+/g, ' ')
            .trim();


        // Corrige valores vacíos
        if (
            texto === '—' ||
            texto === 'â€”' ||
            texto === ''
        ) {
            texto = 'Sin especificar';
        }


        // Formato particular de cada módulo
        if (typeof formatearCelda === 'function') {

            texto = formatearCelda(
                texto,
                data,
                row,
                column,
                node
            );
        }

        return texto;
    }


    // =====================================================
    // DATATABLE
    // =====================================================

    const tablaDT = $(tabla).DataTable({

        dom: 'rtip',

        pageLength: pageLength,

        order: [],

        buttons: [

            // =================================================
            // EXCEL
            // =================================================
            {
                extend: 'excelHtml5',

                text:
                    '<i class="fas fa-file-excel"></i> Excel',

                className:
                    'btn-exportar btn-excel',

                title: titulo,

                filename: generarNombreArchivo,

                exportOptions: {

                    columns: columnasExportar,

                    format: {
                        body: limpiarCelda
                    }
                },


                // =============================================
                // DISEÑO EXCEL VETSYS
                // =============================================

                customize: function (xlsx) {

                    const sheet =
                        xlsx.xl.worksheets['sheet1.xml'];

                    const styles =
                        xlsx.xl['styles.xml'];


                    // =========================================
                    // ANCHO DE COLUMNAS
                    // =========================================

                    if (
                        Array.isArray(anchosExcel) &&
                        anchosExcel.length > 0
                    ) {

                        $('col', sheet).each(
                            function (index) {

                                if (anchosExcel[index]) {

                                    $(this).attr(
                                        'width',
                                        anchosExcel[index]
                                    );

                                    $(this).attr(
                                        'customWidth',
                                        '1'
                                    );
                                }
                            }
                        );
                    }


                    // =========================================
                    // ALTURA DE FILAS
                    // =========================================

                    $('row', sheet).each(function () {

                        $(this)
                            .attr('ht', '20')
                            .attr('customHeight', '1');
                    });


                    // Título
                    $('row[r="1"]', sheet)
                        .attr('ht', '28')
                        .attr('customHeight', '1');


                    // Encabezados
                    $('row[r="2"]', sheet)
                        .attr('ht', '24')
                        .attr('customHeight', '1');


                    // =========================================
                    // COLOR VIOLETA VETSYS
                    // =========================================

                    const fills =
                        $('fills', styles);

                    let cantidadFills =
                        parseInt(
                            fills.attr('count'),
                            10
                        );


                    fills.append(
                        '<fill>' +
                            '<patternFill patternType="solid">' +
                                '<fgColor rgb="FF52266E"/>' +
                                '<bgColor indexed="64"/>' +
                            '</patternFill>' +
                        '</fill>'
                    );


                    const fillVioleta =
                        cantidadFills;

                    cantidadFills++;


                    fills.attr(
                        'count',
                        cantidadFills
                    );


                    // =========================================
                    // FUENTES
                    // =========================================

                    const fonts =
                        $('fonts', styles);

                    let cantidadFonts =
                        parseInt(
                            fonts.attr('count'),
                            10
                        );


                    // Fuente blanca para encabezados
                    fonts.append(
                        '<font>' +
                            '<sz val="11"/>' +
                            '<name val="Arial"/>' +
                            '<family val="2"/>' +
                            '<b/>' +
                            '<color rgb="FFFFFFFF"/>' +
                        '</font>'
                    );


                    const fuenteBlanca =
                        cantidadFonts;

                    cantidadFonts++;


                    // Fuente violeta para título
                    fonts.append(
                        '<font>' +
                            '<sz val="14"/>' +
                            '<name val="Arial"/>' +
                            '<family val="2"/>' +
                            '<b/>' +
                            '<color rgb="FF52266E"/>' +
                        '</font>'
                    );


                    const fuenteTitulo =
                        cantidadFonts;

                    cantidadFonts++;


                    fonts.attr(
                        'count',
                        cantidadFonts
                    );


                    // =========================================
                    // ESTILOS DE CELDAS
                    // =========================================

                    const cellXfs =
                        $('cellXfs', styles);

                    let cantidadEstilos =
                        parseInt(
                            cellXfs.attr('count'),
                            10
                        );


                    // =========================================
                    // ESTILO TÍTULO
                    // =========================================

                    cellXfs.append(
                        '<xf ' +
                            'numFmtId="0" ' +
                            'fontId="' + fuenteTitulo + '" ' +
                            'fillId="0" ' +
                            'borderId="0" ' +
                            'xfId="0" ' +
                            'applyFont="1" ' +
                            'applyAlignment="1">' +

                            '<alignment ' +
                                'horizontal="center" ' +
                                'vertical="center"/>' +

                        '</xf>'
                    );


                    const estiloTitulo =
                        cantidadEstilos;

                    cantidadEstilos++;


                    // =========================================
                    // ESTILO ENCABEZADO
                    // =========================================

                    cellXfs.append(
                        '<xf ' +
                            'numFmtId="0" ' +
                            'fontId="' + fuenteBlanca + '" ' +
                            'fillId="' + fillVioleta + '" ' +
                            'borderId="0" ' +
                            'xfId="0" ' +
                            'applyFont="1" ' +
                            'applyFill="1" ' +
                            'applyAlignment="1">' +

                            '<alignment ' +
                                'horizontal="left" ' +
                                'vertical="center"/>' +

                        '</xf>'
                    );


                    const estiloEncabezado =
                        cantidadEstilos;

                    cantidadEstilos++;


                    cellXfs.attr(
                        'count',
                        cantidadEstilos
                    );


                    // =========================================
                    // APLICAR ESTILOS
                    // =========================================

                    $('row[r="1"] c', sheet)
                        .attr(
                            's',
                            estiloTitulo
                        );


                    $('row[r="2"] c', sheet)
                        .attr(
                            's',
                            estiloEncabezado
                        );


                    // Las filas de datos quedan blancas.
                }

            },


            // =================================================
            // PDF
            // =================================================
            {
                extend: 'pdfHtml5',

                text:
                    '<i class="fas fa-file-pdf"></i> PDF',

                className:
                    'btn-exportar btn-pdf',

                // Título propio
                title: '',

                filename:
                    generarNombreArchivo,

                orientation:
                    orientacionPDF,

                pageSize:
                    'A4',

                exportOptions: {

                    columns:
                        columnasExportar,

                    format: {
                        body: limpiarCelda
                    }
                },


                // =============================================
                // DISEÑO PDF VETSYS
                // =============================================

                customize: function (doc) {

                    const fecha =
                        new Date()
                            .toLocaleDateString('es-AR');


                    // =========================================
                    // MÁRGENES
                    // =========================================

                    doc.pageMargins = [
                        35,
                        35,
                        35,
                        35
                    ];


                    // =========================================
                    // ENCABEZADO
                    // =========================================

                    doc.content.unshift({

                        columns: [

                            {
                                width: '*',

                                stack: [

                                    {
                                        text: 'VETSYS',

                                        color: '#52266E',

                                        fontSize: 18,

                                        bold: true
                                    },

                                    {
                                        text:
                                            'Software Veterinario',

                                        color:
                                            '#6B7280',

                                        fontSize: 9,

                                        margin: [
                                            0,
                                            2,
                                            0,
                                            0
                                        ]
                                    }

                                ]
                            },


                            {
                                width: 'auto',

                                stack: [

                                    {
                                        text:
                                            'Fecha de emisión',

                                        color:
                                            '#9CA3AF',

                                        fontSize: 8,

                                        alignment:
                                            'right'
                                    },

                                    {
                                        text: fecha,

                                        color:
                                            '#374151',

                                        fontSize: 9,

                                        bold: true,

                                        alignment:
                                            'right',

                                        margin: [
                                            0,
                                            2,
                                            0,
                                            0
                                        ]
                                    }

                                ]
                            }

                        ],

                        margin: [
                            0,
                            0,
                            0,
                            12
                        ]

                    });


                    // =========================================
                    // LÍNEA VIOLETA
                    // =========================================

                    doc.content.splice(
                        1,
                        0,
                        {

                            canvas: [

                                {
                                    type: 'line',

                                    x1: 0,
                                    y1: 0,

                                    x2: orientacionPDF === 'landscape' ? 772 : 525,
                                    y2: 0,

                                    lineWidth: 2,

                                    lineColor:
                                        '#52266E'
                                }

                            ],

                            margin: [
                                0,
                                0,
                                0,
                                15
                            ]

                        }
                    );


                    // =========================================
                    // TÍTULO
                    // =========================================

                    doc.content.splice(
                        2,
                        0,
                        {

                            stack: [

                                {
                                    text:
                                        titulo.toUpperCase(),

                                    alignment:
                                        'center',

                                    color:
                                        '#52266E',

                                    fontSize:
                                        16,

                                    bold:
                                        true
                                },

                                {
                                    text:
                                        subtitulo,

                                    alignment:
                                        'center',

                                    color:
                                        '#6B7280',

                                    fontSize:
                                        9,

                                    margin: [
                                        0,
                                        4,
                                        0,
                                        15
                                    ]
                                }

                            ]

                        }
                    );


                    // =========================================
                    // BUSCAR TABLA
                    // =========================================

                    let tablaPDF = null;


                    doc.content.forEach(
                        function (elemento) {

                            if (
                                elemento.table &&
                                elemento.table.body
                            ) {

                                tablaPDF =
                                    elemento;
                            }

                        }
                    );


                    // =========================================
                    // DISEÑO DE TABLA
                    // =========================================

                    if (tablaPDF) {

                        const cantidadColumnas =
                            tablaPDF
                                .table
                                .body[0]
                                .length;


                        // =====================================
                        // ANCHOS
                        // =====================================

                        if (
                            Array.isArray(anchosPDF) &&
                            anchosPDF.length ===
                                cantidadColumnas
                        ) {

                            tablaPDF.table.widths =
                                anchosPDF;

                        } else {

                            tablaPDF.table.widths =
                                Array(
                                    cantidadColumnas
                                ).fill('*');
                        }


                        // =====================================
                        // ENCABEZADO
                        // =====================================

                        tablaPDF
                            .table
                            .body[0]
                            .forEach(
                                function (celda) {

                                    celda.fillColor =
                                        '#52266E';

                                    celda.color =
                                        '#FFFFFF';

                                    celda.bold =
                                        true;

                                    celda.fontSize =
                                        8;

                                    celda.alignment =
                                        'left';

                                    celda.margin = [
                                        4,
                                        5,
                                        4,
                                        5
                                    ];

                                }
                            );


                        // =====================================
                        // FILAS
                        // =====================================

                        for (
    let i = 1;
    i < tablaPDF.table.body.length;
    i++
) {

    tablaPDF
        .table
        .body[i]
        .forEach(
            function (celda) {

                // =====================================
                // ABREVIAR "SIN ESPECIFICAR" EN PDF
                // =====================================

                if (
                    celda.text === 'Sin especificar' ||
                    celda.text === '—' ||
                    celda.text === 'â€”' ||
                    celda.text === ''
                ) {
                    celda.text = 'S/E';
                }


                // =====================================
                // ESTILO DE CELDA
                // =====================================

                celda.fontSize = 8;

                celda.color = '#374151';

                celda.fillColor = '#FFFFFF';

                celda.margin = [
                    4,
                    5,
                    4,
                    5
                ];

            }
        );

}


                        // =====================================
                        // BORDES
                        // =====================================

                        tablaPDF.layout = {

                            hLineWidth:
                                function () {
                                    return 0.5;
                                },

                            vLineWidth:
                                function () {
                                    return 0.5;
                                },

                            hLineColor:
                                function () {
                                    return '#E5E7EB';
                                },

                            vLineColor:
                                function () {
                                    return '#E5E7EB';
                                },

                            paddingLeft:
                                function () {
                                    return 4;
                                },

                            paddingRight:
                                function () {
                                    return 4;
                                },

                            paddingTop:
                                function () {
                                    return 4;
                                },

                            paddingBottom:
                                function () {
                                    return 4;
                                }

                        };

                    }


                    // =========================================
                    // TOTAL
                    // =========================================

                    const totalRegistros =
                        tablaPDF &&
                        tablaPDF.table

                            ? tablaPDF
                                .table
                                .body
                                .length - 1

                            : 0;


                    doc.content.push({

                        text:
                            'Total de registros: ' +
                            totalRegistros,

                        color:
                            '#52266E',

                        bold:
                            true,

                        fontSize:
                            9,

                        margin: [
                            0,
                            20,
                            0,
                            0
                        ]

                    });


                    // =========================================
                    // PIE DE PÁGINA
                    // =========================================

                    doc.footer =
                        function (
                            currentPage,
                            pageCount
                        ) {

                            return {

                                columns: [

                                    {
                                        text:
                                            'VetSys · Software de Gestión Veterinaria',

                                        alignment:
                                            'left',

                                        color:
                                            '#9CA3AF',

                                        fontSize:
                                            8
                                    },

                                    {
                                        text:
                                            'Página ' +
                                            currentPage +
                                            ' de ' +
                                            pageCount,

                                        alignment:
                                            'right',

                                        color:
                                            '#9CA3AF',

                                        fontSize:
                                            8
                                    }

                                ],

                                margin: [
                                    35,
                                    10,
                                    35,
                                    0
                                ]

                            };

                        };

                }

            },


            // =================================================
            // IMPRIMIR
            // =================================================
            {
                extend: 'print',

                text:
                    '<i class="fas fa-print"></i> Imprimir',

                className:
                    'btn-exportar btn-print',

                // Quitamos el título automático
                title: '',

                exportOptions: {

                    columns:
                        columnasExportar,

                    format: {
                        body:
                            limpiarCelda
                    }
                },


                // =============================================
                // DISEÑO DE IMPRESIÓN VETSYS
                // =============================================

                customize: function (win) {

                    const fecha =
                        new Date()
                            .toLocaleDateString('es-AR');


                    const totalRegistros =
                        tablaDT
                            .rows({
                                search: 'applied'
                            })
                            .count();


                    // =========================================
                    // ENCABEZADO
                    // =========================================

                    const encabezado = `

                        <div class="vetsys-print-header">

                            <div>

                                <div class="vetsys-logo">
                                    VETSYS
                                </div>

                                <div class="vetsys-subtitle">
                                    Software Veterinario
                                </div>

                            </div>


                            <div class="vetsys-print-date">

                                <div class="date-label">
                                    Fecha de emisión
                                </div>

                                <div class="date-value">
                                    ${fecha}
                                </div>

                            </div>

                        </div>


                        <div class="vetsys-line"></div>


                        <div class="vetsys-report-title">

                            <h1>
                                ${titulo.toUpperCase()}
                            </h1>

                            <p>
                                ${subtitulo}
                            </p>

                        </div>

                    `;


                    // =========================================
                    // INSERTAR ENCABEZADO
                    // =========================================

                    $(win.document.body)
                        .find('table')
                        .before(encabezado);


                    // =========================================
                    // ENCABEZADOS DE LA TABLA
                    // =========================================

                    const encabezados = [];


                    // Cantidad real de columnas impresas
                    const cantidadColumnasImpresas =
                        $(win.document.body)
                            .find(
                                'table tbody tr:first td'
                            )
                            .length;


                    $(tabla)
                        .find('thead th')
                        .each(function (index) {

                            if (
                                index <
                                cantidadColumnasImpresas
                            ) {

                                encabezados.push(
                                    $(this)
                                        .text()
                                        .replace(
                                            /\s+/g,
                                            ' '
                                        )
                                        .trim()
                                );
                            }

                        });


                    // Ocultamos encabezado generado
                    // automáticamente por DataTables
                    $(win.document.body)
                        .find('table thead')
                        .hide();


                    // Creamos nuestro encabezado
                    let encabezadoTabla =
                        '<tr class="encabezado-impresion">';


                    encabezados.forEach(
                        function (nombre) {

                            encabezadoTabla +=
                                '<th>' +
                                nombre +
                                '</th>';
                        }
                    );


                    encabezadoTabla +=
                        '</tr>';


                    $(win.document.body)
                        .find('table tbody')
                        .prepend(
                            encabezadoTabla
                        );


                    // =========================================
                    // TOTAL Y PIE
                    // =========================================

                    $(win.document.body).append(`

                        <div class="vetsys-total">

                            Total de registros:
                            ${totalRegistros}

                        </div>


                        <div class="vetsys-footer">

                            <span>
                                VetSys · Software de Gestión Veterinaria
                            </span>

                            <span>
                                ${titulo}
                            </span>

                        </div>

                    `);


                    // =========================================
                    // CSS DE IMPRESIÓN
                    // =========================================

                    $(win.document.head).append(`

                        <style>

                            /* ===============================
                               HOJA
                            =============================== */

                            @page {
                                size: A4 portrait;
                                margin: 15mm;
                            }


                            * {
                                box-sizing:
                                    border-box;
                            }


                            body {

                                margin:
                                    0 !important;

                                padding:
                                    0 !important;

                                background:
                                    #FFFFFF !important;

                                color:
                                    #374151 !important;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;
                            }


                            /* ===============================
                               ENCABEZADO
                            =============================== */

                            .vetsys-print-header {

                                display:
                                    flex;

                                justify-content:
                                    space-between;

                                align-items:
                                    flex-start;

                                margin-bottom:
                                    12px;
                            }


                            .vetsys-logo {

                                color:
                                    #52266E;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    24px;

                                font-weight:
                                    700;
                            }


                            .vetsys-subtitle {

                                color:
                                    #6B7280;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    10px;

                                margin-top:
                                    3px;
                            }


                            /* ===============================
                               FECHA
                            =============================== */

                            .vetsys-print-date {

                                text-align:
                                    right;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;
                            }


                            .date-label {

                                color:
                                    #9CA3AF;

                                font-size:
                                    9px;
                            }


                            .date-value {

                                color:
                                    #374151;

                                font-size:
                                    10px;

                                font-weight:
                                    700;

                                margin-top:
                                    3px;
                            }


                            /* ===============================
                               LÍNEA
                            =============================== */

                            .vetsys-line {

                                width:
                                    100%;

                                height:
                                    2px;

                                background:
                                    #52266E;

                                margin-bottom:
                                    20px;

                                -webkit-print-color-adjust:
                                    exact !important;

                                print-color-adjust:
                                    exact !important;
                            }


                            /* ===============================
                               TÍTULO
                            =============================== */

                            .vetsys-report-title {

                                text-align:
                                    center;

                                margin-bottom:
                                    20px;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;
                            }


                            .vetsys-report-title h1 {

                                color:
                                    #52266E;

                                font-size:
                                    19px;

                                line-height:
                                    1.2;

                                margin:
                                    0;

                                font-weight:
                                    700;
                            }


                            .vetsys-report-title p {

                                color:
                                    #6B7280;

                                font-size:
                                    10px;

                                margin:
                                    5px 0 0 0;
                            }


                            /* ===============================
                               TABLA
                            =============================== */

                            table {

                                width:
                                    100% !important;

                                border-collapse:
                                    collapse !important;

                                border-spacing:
                                    0 !important;

                                margin-top:
                                    10px !important;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    10px !important;
                            }


                            /* ===============================
                               ENCABEZADOS
                            =============================== */

                            .encabezado-impresion th {

                                background:
                                    #52266E !important;

                                color:
                                    #FFFFFF !important;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    10px !important;

                                font-weight:
                                    700 !important;

                                padding:
                                    8px 6px !important;

                                border:
                                    1px solid
                                    #E5E7EB !important;

                                text-align:
                                    left !important;

                                vertical-align:
                                    middle !important;

                                -webkit-print-color-adjust:
                                    exact !important;

                                print-color-adjust:
                                    exact !important;
                            }


                            /* ===============================
                               DATOS
                            =============================== */

                            table tbody td {

                                background:
                                    #FFFFFF !important;

                                color:
                                    #374151 !important;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    10px !important;

                                font-weight:
                                    400 !important;

                                padding:
                                    8px 6px !important;

                                border:
                                    1px solid
                                    #E5E7EB !important;

                                vertical-align:
                                    middle !important;

                                -webkit-print-color-adjust:
                                    exact !important;

                                print-color-adjust:
                                    exact !important;
                            }


                            /* ===============================
                               TOTAL
                            =============================== */

                            .vetsys-total {

                                color:
                                    #52266E;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    10px;

                                font-weight:
                                    700;

                                margin-top:
                                    20px;
                            }


                            /* ===============================
                               PIE
                            =============================== */

                            .vetsys-footer {

                                display:
                                    flex;

                                justify-content:
                                    space-between;

                                color:
                                    #9CA3AF;

                                font-family:
                                    Arial,
                                    Helvetica,
                                    sans-serif !important;

                                font-size:
                                    8px;

                                margin-top:
                                    30px;

                                padding-top:
                                    8px;

                                border-top:
                                    1px solid
                                    #E5E7EB;
                            }


                            /* ===============================
                               EVITAR CORTES
                            =============================== */

                            tr {
                                page-break-inside:
                                    avoid;
                            }

                        </style>

                    `);

                }

            }

        ],


        // =====================================================
        // IDIOMA
        // =====================================================

        language: {

            search:
                'Buscar:',

            lengthMenu:
                'Mostrar _MENU_ registros',

            info:
                'Mostrando _START_ a _END_ de _TOTAL_ registros',

            infoEmpty:
                'No hay registros',

            zeroRecords:
                'No se encontraron resultados',

            paginate: {

                previous:
                    'Anterior',

                next:
                    'Siguiente'
            }

        }

    });


    // =====================================================
    // MOVER BOTONES AL CONTENEDOR
    // =====================================================

    if ($(contenedorBotones).length) {

        tablaDT
            .buttons()
            .container()
            .appendTo(
                contenedorBotones
            );
    }


    // =====================================================
    // DEVOLVER DATATABLE
    // =====================================================

    return tablaDT;
}