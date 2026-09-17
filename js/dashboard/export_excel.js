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
        [dashboardData.turnosEstado.pendientes],
        [dashboardData.turnosEstado.confirmados],
        [dashboardData.turnosEstado.enAtencion],
        [dashboardData.turnosEstado.completados],
        [dashboardData.turnosEstado.cancelados]

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


function exportarTurnosMesExcel() {

    // ==========================================
    // OBTENER LOS DATOS DEL GRÁFICO
    // ==========================================

    const meses = 
        dashboardData.mesesTurnos;

    const cantidades =
        dashboardData.cantidadTurnosMes;


    // ==========================================
    // DATOS QUE VAN AL EXCEL
    // ==========================================

    const datos = [

        ['VETSYS - SOFTWARE VETERINARIO', ''],

        ['Reporte: Turnos por mes', ''],

        [
            'Fecha de generación:',
            new Date().toLocaleDateString('es-AR')
        ],

        ['', ''],

        ['Mes', 'Cantidad']

    ];


    // ==========================================
    // AGREGAR CADA MES
    // ==========================================

    for (let i = 0; i < meses.length; i++) {

        datos.push([
            meses[i],
            cantidades[i]
        ]);

    }


    // ==========================================
    // CREAR LA HOJA
    // ==========================================

    const hoja =
        XLSX.utils.aoa_to_sheet(datos);


    // ==========================================
    // ANCHO DE LAS COLUMNAS
    // ==========================================

    hoja['!cols'] = [

        { wch: 30 },

        { wch: 18 }

    ];


    // ==========================================
    // COMBINAR CELDAS DEL ENCABEZADO
    // ==========================================

    hoja['!merges'] = [

        // VETSYS - SOFTWARE VETERINARIO
        {
            s: { r: 0, c: 0 },
            e: { r: 0, c: 1 }
        },

        // Reporte: Turnos por mes
        {
            s: { r: 1, c: 0 },
            e: { r: 1, c: 1 }
        }

    ];


    // ==========================================
    // CREAR EL LIBRO DE EXCEL
    // ==========================================

    const libro =
        XLSX.utils.book_new();


    // ==========================================
    // AGREGAR LA HOJA AL LIBRO
    // ==========================================

    XLSX.utils.book_append_sheet(
        libro,
        hoja,
        'Turnos por mes'
    );


    // ==========================================
    // DESCARGAR EL ARCHIVO
    // ==========================================

    XLSX.writeFile(
        libro,
        'VetSys_Turnos_por_mes.xlsx'
    );

}


function exportarHistoriasMesExcel() {

    // Obtiene los meses
    const meses =
        dashboardData.mesesHistorias;

    // Obtiene la cantidad de historias clínicas
    const cantidades =
        dashboardData.cantidadHistoriasMes;


    // Datos que tendrá el Excel
    const datos = [

        ['VETSYS - SOFTWARE VETERINARIO', ''],

        ['Reporte: Historias clínicas por mes', ''],

        [
            'Fecha de generación:',
            new Date().toLocaleDateString('es-AR')
        ],

        ['', ''],

        ['Mes', 'Cantidad']

    ];


    // Agrega cada mes con su cantidad
    for (let i = 0; i < meses.length; i++) {

        datos.push([
            meses[i],
            cantidades[i]
        ]);

    }


    // Crea la hoja de Excel
    const hoja =
        XLSX.utils.aoa_to_sheet(datos);


    // Ancho de columnas
    hoja['!cols'] = [

        { wch: 30 },

        { wch: 18 }

    ];


    // Combina las celdas de los títulos
    hoja['!merges'] = [

        {
            s: { r: 0, c: 0 },
            e: { r: 0, c: 1 }
        },

        {
            s: { r: 1, c: 0 },
            e: { r: 1, c: 1 }
        }

    ];


    // Crea el libro
    const libro =
        XLSX.utils.book_new();


    // Agrega la hoja
    XLSX.utils.book_append_sheet(
        libro,
        hoja,
        'Historias por mes'
    );


    // Descarga el archivo
    XLSX.writeFile(
        libro,
        'VetSys_Historias_clinicas_por_mes.xlsx'
    );

}



function exportarMascotasHistoriasExcel() {

    // Obtiene los nombres de las mascotas
    const mascotas =
        dashboardData.nombresMascotasHistorias;

    // Obtiene la cantidad de registros clínicos
    const cantidades =
        dashboardData.cantidadHistoriasMascotas;


    // Datos del Excel
    const datos = [

        ['VETSYS - SOFTWARE VETERINARIO', ''],

        ['Reporte: Mascotas con más registros clínicos', ''],

        [
            'Fecha de generación:',
            new Date().toLocaleDateString('es-AR')
        ],

        ['', ''],

        ['Mascota', 'Registros clínicos']

    ];


    // Agrega cada mascota con su cantidad
    for (let i = 0; i < mascotas.length; i++) {

        datos.push([
            mascotas[i],
            cantidades[i]
        ]);

    }


    // Crea la hoja
    const hoja =
        XLSX.utils.aoa_to_sheet(datos);


    // Ancho de columnas
    hoja['!cols'] = [

        { wch: 30 },

        { wch: 22 }

    ];


    // Combina las celdas de los títulos
    hoja['!merges'] = [

        {
            s: { r: 0, c: 0 },
            e: { r: 0, c: 1 }
        },

        {
            s: { r: 1, c: 0 },
            e: { r: 1, c: 1 }
        }

    ];


    // Crea el libro
    const libro =
        XLSX.utils.book_new();


    // Agrega la hoja
    XLSX.utils.book_append_sheet(
        libro,
        hoja,
        'Mascotas'
    );


    // Descarga el archivo
    XLSX.writeFile(
        libro,
        'VetSys_Mascotas_registros_clinicos.xlsx'
    );

}

