
/* =========================================================
   VETSYS - EXPORTACIÓN DE GRÁFICOS A EXCEL
========================================================= */


/* =========================================================
   FUNCIÓN PARA CREAR EL ARCHIVO EXCEL
========================================================= */

async function crearExcel(titulo, encabezados, datos, idGrafico, nombreArchivo) {

    // CREAR LIBRO Y HOJA
    const libro = new ExcelJS.Workbook();
    const hoja = libro.addWorksheet('Reporte VetSys');

    // ENCABEZADO
    hoja.addRow(['VETSYS - SOFTWARE VETERINARIO']);
    hoja.addRow(['Reporte: ' + titulo]);
    hoja.addRow([
        'Fecha de generación:',
        new Date().toLocaleDateString('es-AR')
    ]);
    hoja.addRow([]);

    // ENCABEZADOS DE LA TABLA
    hoja.addRow(encabezados);

    // AGREGAR LOS DATOS
    datos.forEach(function(fila) {
        hoja.addRow(fila);
    });

    // ANCHO DE COLUMNAS
    hoja.getColumn('A').width = 32;
    hoja.getColumn('B').width = 22;

    // COMBINAR TÍTULOS
    hoja.mergeCells('A1:B1');
    hoja.mergeCells('A2:B2');

    // ESTILO DEL TÍTULO
    hoja.getCell('A1').font = {
        bold: true,
        size: 16,
        color: { argb: 'FF52266E' }
    };

    hoja.getCell('A2').font = {
        bold: true,
        size: 12,
        color: { argb: 'FF52266E' }
    };

    // ENCABEZADO DE LA TABLA
    const encabezadoTabla = hoja.getRow(5);

    encabezadoTabla.font = {
        bold: true,
        color: { argb: 'FFFFFFFF' }
    };

    encabezadoTabla.fill = {
        type: 'pattern',
        pattern: 'solid',
        fgColor: { argb: 'FF52266E' }
    };

    // OBTENER EL GRÁFICO DE CHART.JS
    const canvas = document.getElementById(idGrafico);

    if (!canvas) {
        alert('No se encontró el gráfico para exportar.');
        return;
    }

    // CONVERTIR EL GRÁFICO EN IMAGEN
    const imagen = canvas.toDataURL('image/png');

    // AGREGAR IMAGEN AL LIBRO
    const imagenId = libro.addImage({
        base64: imagen,
        extension: 'png'
    });

    // UBICAR EL GRÁFICO A LA DERECHA DE LA TABLA
    hoja.addImage(imagenId, {
        tl: { col: 3, row: 4 },
        ext: { width: 540, height: 320 }
    });

    // GENERAR EL EXCEL
    const archivo = await libro.xlsx.writeBuffer();

    // DESCARGAR
    saveAs(
        new Blob([archivo], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        }),
        nombreArchivo
    );
}


/* =========================================================
   1. TURNOS POR ESTADO
========================================================= */

async function exportarTurnosEstadoExcel() {

    const datos = [
        ['Pendiente', Number(dashboardData.turnosEstado.pendientes)],
        ['Confirmado', Number(dashboardData.turnosEstado.confirmados)],
        ['En atención', Number(dashboardData.turnosEstado.enAtencion)],
        ['Completado', Number(dashboardData.turnosEstado.completados)],
        ['Cancelado', Number(dashboardData.turnosEstado.cancelados)]
    ];

    await crearExcel(
        'Turnos por estado',
        ['Estado', 'Cantidad'],
        datos,
        'graficoTurnosEstado',
        'VetSys_Turnos_por_estado.xlsx'
    );
}


/* =========================================================
   2. TURNOS POR MES
========================================================= */

async function exportarTurnosMesExcel() {

    const datos = [];

    const meses = dashboardData.mesesTurnos;
    const cantidades = dashboardData.cantidadTurnosMes;

    for (let i = 0; i < meses.length; i++) {

        datos.push([
            meses[i],
            Number(cantidades[i])
        ]);

    }

    await crearExcel(
        'Turnos por mes',
        ['Mes', 'Cantidad'],
        datos,
        'graficoTurnosMes',
        'VetSys_Turnos_por_mes.xlsx'
    );
}


/* =========================================================
   3. HISTORIAS CLÍNICAS POR MES
========================================================= */

async function exportarHistoriasMesExcel() {

    const datos = [];

    const meses = dashboardData.mesesHistorias;
    const cantidades = dashboardData.cantidadHistoriasMes;

    for (let i = 0; i < meses.length; i++) {

        datos.push([
            meses[i],
            Number(cantidades[i])
        ]);

    }

    await crearExcel(
        'Historias clínicas por mes',
        ['Mes', 'Cantidad'],
        datos,
        'graficoHistoriasMes',
        'VetSys_Historias_clinicas_por_mes.xlsx'
    );
}


/* =========================================================
   4. MASCOTAS CON MÁS REGISTROS CLÍNICOS
========================================================= */

async function exportarMascotasHistoriasExcel() {

    const datos = [];

    const mascotas = dashboardData.nombresMascotasHistorias;
    const cantidades = dashboardData.cantidadHistoriasMascotas;

    for (let i = 0; i < mascotas.length; i++) {

        datos.push([
            mascotas[i],
            Number(cantidades[i])
        ]);

    }

    await crearExcel(
        'Mascotas con más registros clínicos',
        ['Mascota', 'Registros clínicos'],
        datos,
        'graficoMascotasHistorias',
        'VetSys_Mascotas_registros_clinicos.xlsx'
    );
}
