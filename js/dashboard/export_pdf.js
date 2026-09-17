function exportarTurnosEstadoPDF() {

    /* =====================================================
    OBTENER GRÁFICO
    ===================================================== */

    const canvas =
        document.getElementById('graficoTurnosEstado');


    /* =====================================================
    CREAR UNA COPIA DEL GRÁFICO EN MAYOR RESOLUCIÓN
    ==================================================== */

    const escala = 3;

    const canvasHD =
        document.createElement('canvas');

    canvasHD.width =
        canvas.width * escala;

    canvasHD.height =
        canvas.height * escala;


    const ctxHD =
        canvasHD.getContext('2d');


    // Fondo blanco
    ctxHD.fillStyle = '#ffffff';

    ctxHD.fillRect(
        0,
        0,
        canvasHD.width,
        canvasHD.height
    );


    // Copia el gráfico original aumentando la resolución
    ctxHD.drawImage(
        canvas,
        0,
        0,
        canvasHD.width,
        canvasHD.height
    );


    // Convierte el gráfico HD en una imagen PNG
    const imagen =
        canvasHD.toDataURL('image/png', 1.0);



    /* =====================================================
    CREAR PDF
    ===================================================== */

    const { jsPDF } = window.jspdf;

    const pdf =
        new jsPDF();



    /* =====================================================
    ENCABEZADO VETSYS
    ===================================================== */

    pdf.setFillColor(
        82,
        38,
        110
    );


    pdf.rect(
        0,
        0,
        210,
        28,
        'F'
    );


    // Nombre del sistema
    pdf.setTextColor(
        255,
        255,
        255
    );


    pdf.setFontSize(18);

    pdf.setFont(
        undefined,
        'bold'
    );


    pdf.text(
        'VetSys',
        15,
        12
    );


    // Subtítulo
    pdf.setFontSize(9);

    pdf.setFont(
        undefined,
        'normal'
    );


    pdf.text(
        'Software Veterinario',
        15,
        19
    );


    // Reporte
    pdf.setFontSize(10);

    pdf.setFont(
        undefined,
        'bold'
    );


    pdf.text(
        'REPORTE',
        195,
        14,
        {
            align: 'right'
        }
    );



    /* =====================================================
       TÍTULO DEL REPORTE
    ===================================================== */

    pdf.setTextColor(
        82,
        38,
        110
    );


    pdf.setFontSize(16);

    pdf.setFont(
        undefined,
        'bold'
    );


    pdf.text(
        'Turnos por estado',
        15,
        42
    );


    // Descripción
    pdf.setTextColor(
        110,
        110,
        110
    );


    pdf.setFontSize(9);

    pdf.setFont(
        undefined,
        'normal'
    );


    pdf.text(
        'Resumen estadístico de los turnos registrados en el sistema.',
        15,
        49
    );



    /* =====================================================
       FECHA
    ===================================================== */

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



    /* =====================================================
       GRÁFICO
    ===================================================== */

    pdf.addImage(
        imagen,
        'PNG',

        // Posición
        45,
        57,

        // Tamaño
        120,
        78
    );



    /* =====================================================
       TABLA DE DATOS
    ===================================================== */

    pdf.autoTable({

        startY: 143,

        head: [

            [
                'Estado',
                'Cantidad'
            ]

        ],

        body: [
                //ashboardData centraliza los datos obtenidos
                // con PHP para que puedan ser utilizados por los archivos JavaScript externos del dashboard.
            [
                'Pendiente',
                dashboardData.turnosEstado.pendientes
            ],

            [
                'Confirmado',
                dashboardData.turnosEstado.confirmados
            ],

            [
                'En atención',
                dashboardData.turnosEstado.enAtencion
            ],

            [
                'Completado',
                dashboardData.turnosEstado.completados
            ],

            [
                'Cancelado',
                dashboardData.turnosEstado.cancelados
            ]

        ],

        theme: 'grid',


        /* ENCABEZADO DE LA TABLA */

        headStyles: {

            fillColor: [
                82,
                38,
                110
            ],

            textColor: [
                255,
                255,
                255
            ],

            fontStyle: 'bold',

            fontSize: 9

        },


        /* CELDAS */

        styles: {

            fontSize: 9,

            cellPadding: 3,

            lineColor: [
                210,
                210,
                210
            ],

            lineWidth: 0.2

        },


        /* COLUMNA CANTIDAD */

        columnStyles: {

            1: {

                halign: 'center'

            }

        },


        /* MÁRGENES */

        margin: {

            left: 25,

            right: 25

        }

    });



    /* =====================================================
       FOOTER
    ===================================================== */

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



    /* =====================================================
    DESCARGAR PDF
    ===================================================== */

    pdf.save(
        'VetSys_Turnos_por_estado.pdf'
    );

}

function exportarTurnosMesPDF() {

    // Obtiene el gráfico de Turnos por mes
    const canvas = document.getElementById('graficoTurnosMes');

    // Convierte el gráfico en una imagen
    const imagen = canvas.toDataURL('image/png', 1.0);

    // Crea el PDF
    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF();


    // ==========================================
    // ENCABEZADO VETSYS
    // ==========================================

    pdf.setFillColor(82, 38, 110);
    pdf.rect(0, 0, 210, 28, 'F');

    pdf.setTextColor(255, 255, 255);
    pdf.setFontSize(18);
    pdf.setFont(undefined, 'bold');

    pdf.text('VetSys', 15, 12);

    pdf.setFontSize(9);
    pdf.setFont(undefined, 'normal');

    pdf.text('Software Veterinario', 15, 19);

    pdf.setFontSize(10);
    pdf.setFont(undefined, 'bold');

    pdf.text(
        'REPORTE',
        195,
        14,
        { align: 'right' }
    );


    // ==========================================
    // TÍTULO
    // ==========================================

    pdf.setTextColor(82, 38, 110);
    pdf.setFontSize(16);
    pdf.setFont(undefined, 'bold');

    pdf.text(
        'Turnos por mes',
        15,
        42
    );


    // Descripción
    pdf.setTextColor(110, 110, 110);
    pdf.setFontSize(9);
    pdf.setFont(undefined, 'normal');

    pdf.text(
        'Resumen estadístico de los turnos registrados por mes.',
        15,
        49
    );


    // ==========================================
    // FECHA
    // ==========================================

    const fechaActual =
        new Date().toLocaleDateString('es-AR');

    pdf.setFontSize(8);

    pdf.text(
        'Generado: ' + fechaActual,
        195,
        42,
        { align: 'right' }
    );


    // ==========================================
    // GRÁFICO
    // ==========================================

    pdf.addImage(
        imagen,
        'PNG',
        35,
        57,
        140,
        78
    );


    // ==========================================
    // DATOS DE LA TABLA
    // ==========================================

    const meses =
        dashboardData.mesesTurnos;

    const cantidades =
        dashboardData.cantidadTurnosMes;

    const datosTabla = [];

    for (let i = 0; i < meses.length; i++) {

        datosTabla.push([
            meses[i],
            cantidades[i]
        ]);

    }


    // ==========================================
    // TABLA
    // ==========================================

    pdf.autoTable({

        startY: 143,

        head: [
            ['Mes', 'Cantidad']
        ],

        body: datosTabla,

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


    // ==========================================
    // FOOTER
    // ==========================================

    pdf.setDrawColor(220, 220, 220);

    pdf.line(
        15,
        280,
        195,
        280
    );

    pdf.setTextColor(130, 130, 130);
    pdf.setFontSize(8);
    pdf.setFont(undefined, 'normal');

    pdf.text(
        '© 2026 VetSys - Software Veterinario',
        15,
        287
    );


    // ==========================================
    // DESCARGAR
    // ==========================================

    pdf.save('VetSys_Turnos_por_mes.pdf');

}
function exportarHistoriasMesPDF() {

    // ==========================================
    // OBTENER GRÁFICO
    // ==========================================

    const canvas =
        document.getElementById('graficoHistoriasMes');

    const imagen =
        canvas.toDataURL('image/png', 1.0);


    // ==========================================
    // CREAR PDF
    // ==========================================

    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF();


    // ==========================================
    // ENCABEZADO VETSYS
    // ==========================================

    pdf.setFillColor(82, 38, 110);
    pdf.rect(0, 0, 210, 28, 'F');

    pdf.setTextColor(255, 255, 255);
    pdf.setFontSize(18);
    pdf.setFont(undefined, 'bold');

    pdf.text('VetSys', 15, 12);

    pdf.setFontSize(9);
    pdf.setFont(undefined, 'normal');

    pdf.text('Software Veterinario', 15, 19);

    pdf.setFontSize(10);
    pdf.setFont(undefined, 'bold');

    pdf.text(
        'REPORTE',
        195,
        14,
        { align: 'right' }
    );


    // ==========================================
    // TÍTULO
    // ==========================================

    pdf.setTextColor(82, 38, 110);
    pdf.setFontSize(16);
    pdf.setFont(undefined, 'bold');

    pdf.text(
        'Historias clínicas por mes',
        15,
        42
    );


    // Descripción
    pdf.setTextColor(110, 110, 110);
    pdf.setFontSize(9);
    pdf.setFont(undefined, 'normal');

    pdf.text(
        'Resumen estadístico de las historias clínicas registradas por mes.',
        15,
        49
    );


    // ==========================================
    // FECHA
    // ==========================================

    const fechaActual =
        new Date().toLocaleDateString('es-AR');

    pdf.setFontSize(8);

    pdf.text(
        'Generado: ' + fechaActual,
        195,
        42,
        { align: 'right' }
    );


    // ==========================================
    // GRÁFICO
    // ==========================================

    pdf.addImage(
        imagen,
        'PNG',
        35,
        57,
        140,
        78
    );


    // ==========================================
    // DATOS PARA LA TABLA
    // ==========================================

    const meses =
        dashboardData.mesesHistorias;

    const cantidades =
        dashboardData.cantidadHistoriasMes;

    const datosTabla = [];

    for (let i = 0; i < meses.length; i++) {

        datosTabla.push([
            meses[i],
            cantidades[i]
        ]);

    }


    // ==========================================
    // TABLA
    // ==========================================

    pdf.autoTable({

        startY: 143,

        head: [
            ['Mes', 'Cantidad']
        ],

        body: datosTabla,

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


    // ==========================================
    // FOOTER
    // ==========================================

    pdf.setDrawColor(220, 220, 220);

    pdf.line(
        15,
        280,
        195,
        280
    );

    pdf.setTextColor(130, 130, 130);
    pdf.setFontSize(8);
    pdf.setFont(undefined, 'normal');

    pdf.text(
        '© 2026 VetSys - Software Veterinario',
        15,
        287
    );


    // ==========================================
    // DESCARGAR
    // ==========================================

    pdf.save(
        'VetSys_Historias_clinicas_por_mes.pdf'
    );

}

function exportarHistoriasMesPDF() {

    // ==========================================
    // OBTENER GRÁFICO
    // ==========================================

    const canvas =
        document.getElementById('graficoHistoriasMes');

    const imagen =
        canvas.toDataURL('image/png', 1.0);


    // ==========================================
    // CREAR PDF
    // ==========================================

    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF();


    // ==========================================
    // ENCABEZADO VETSYS
    // ==========================================

    pdf.setFillColor(82, 38, 110);
    pdf.rect(0, 0, 210, 28, 'F');

    pdf.setTextColor(255, 255, 255);
    pdf.setFontSize(18);
    pdf.setFont(undefined, 'bold');

    pdf.text('VetSys', 15, 12);

    pdf.setFontSize(9);
    pdf.setFont(undefined, 'normal');

    pdf.text('Software Veterinario', 15, 19);

    pdf.setFontSize(10);
    pdf.setFont(undefined, 'bold');

    pdf.text(
        'REPORTE',
        195,
        14,
        { align: 'right' }
    );


    // ==========================================
    // TÍTULO
    // ==========================================

    pdf.setTextColor(82, 38, 110);
    pdf.setFontSize(16);
    pdf.setFont(undefined, 'bold');

    pdf.text(
        'Historias clínicas por mes',
        15,
        42
    );


    // Descripción
    pdf.setTextColor(110, 110, 110);
    pdf.setFontSize(9);
    pdf.setFont(undefined, 'normal');

    pdf.text(
        'Resumen estadístico de las historias clínicas registradas por mes.',
        15,
        49
    );


    // ==========================================
    // FECHA
    // ==========================================

    const fechaActual =
        new Date().toLocaleDateString('es-AR');

    pdf.setFontSize(8);

    pdf.text(
        'Generado: ' + fechaActual,
        195,
        42,
        { align: 'right' }
    );


    // ==========================================
    // GRÁFICO
    // ==========================================

    pdf.addImage(
        imagen,
        'PNG',
        35,
        57,
        140,
        78
    );


    // ==========================================
    // DATOS PARA LA TABLA
    // ==========================================

    const meses =
        dashboardData.mesesHistorias;

    const cantidades =
        dashboardData.cantidadHistoriasMes;

    const datosTabla = [];

    for (let i = 0; i < meses.length; i++) {

        datosTabla.push([
            meses[i],
            cantidades[i]
        ]);

    }


    // ==========================================
    // TABLA
    // ==========================================

    pdf.autoTable({

        startY: 143,

        head: [
            ['Mes', 'Cantidad']
        ],

        body: datosTabla,

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


    // ==========================================
    // FOOTER
    // ==========================================

    pdf.setDrawColor(220, 220, 220);

    pdf.line(
        15,
        280,
        195,
        280
    );

    pdf.setTextColor(130, 130, 130);
    pdf.setFontSize(8);
    pdf.setFont(undefined, 'normal');

    pdf.text(
        '© 2026 VetSys - Software Veterinario',
        15,
        287
    );


    // ==========================================
    // DESCARGAR
    // ==========================================

    pdf.save(
        'VetSys_Historias_clinicas_por_mes.pdf'
    );

}

function exportarMascotasHistoriasPDF() {

    // ==========================================
    // OBTENER EL GRÁFICO
    // ==========================================

    const canvas =
        document.getElementById('graficoMascotasHistorias');

    // Convierte el gráfico en una imagen
    const imagen =
        canvas.toDataURL('image/png', 1.0);


    // ==========================================
    // CREAR PDF
    // ==========================================

    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF();


    // ==========================================
    // ENCABEZADO VETSYS
    // ==========================================

    pdf.setFillColor(82, 38, 110);

    pdf.rect(
        0,
        0,
        210,
        28,
        'F'
    );


    // Nombre VetSys
    pdf.setTextColor(255, 255, 255);

    pdf.setFontSize(18);

    pdf.setFont(
        undefined,
        'bold'
    );

    pdf.text(
        'VetSys',
        15,
        12
    );


    // Subtítulo
    pdf.setFontSize(9);

    pdf.setFont(
        undefined,
        'normal'
    );

    pdf.text(
        'Software Veterinario',
        15,
        19
    );


    // Reporte
    pdf.setFontSize(10);

    pdf.setFont(
        undefined,
        'bold'
    );

    pdf.text(
        'REPORTE',
        195,
        14,
        {
            align: 'right'
        }
    );


    // ==========================================
    // TÍTULO
    // ==========================================

    pdf.setTextColor(82, 38, 110);

    pdf.setFontSize(16);

    pdf.setFont(
        undefined,
        'bold'
    );

    pdf.text(
        'Mascotas con más registros clínicos',
        15,
        42
    );


    // ==========================================
    // DESCRIPCIÓN
    // ==========================================

    pdf.setTextColor(110, 110, 110);

    pdf.setFontSize(9);

    pdf.setFont(
        undefined,
        'normal'
    );

    pdf.text(
        'Mascotas con mayor cantidad de registros clínicos en el sistema.',
        15,
        49
    );


    // ==========================================
    // FECHA
    // ==========================================

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


    // ==========================================
    // GRÁFICO
    // ==========================================

    pdf.addImage(
        imagen,
        'PNG',
        35,
        57,
        140,
        78
    );


    // ==========================================
    // OBTENER DATOS
    // ==========================================

    const mascotas =
        dashboardData.nombresMascotasHistorias;

    const cantidades =
        dashboardData.cantidadHistoriasMascotas;


    // ==========================================
    // PREPARAR TABLA
    // ==========================================

    const datosTabla = [];

    for (let i = 0; i < mascotas.length; i++) {

        datosTabla.push([
            mascotas[i],
            cantidades[i]
        ]);

    }


    // ==========================================
    // TABLA
    // ==========================================

    pdf.autoTable({

        startY: 143,

        head: [
            [
                'Mascota',
                'Registros clínicos'
            ]
        ],

        body: datosTabla,

        theme: 'grid',

        headStyles: {

            fillColor: [82, 38, 110],

            textColor: [255, 255, 255],

            fontStyle: 'bold',

            fontSize: 9

        },

        styles: {

            fontSize: 9,

            cellPadding: 3,

            lineColor: [210, 210, 210],

            lineWidth: 0.2

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


    // ==========================================
    // PIE DE PÁGINA
    // ==========================================

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


    // ==========================================
    // DESCARGAR PDF
    // ==========================================

    pdf.save(
        'VetSys_Mascotas_registros_clinicos.pdf'
    );

}