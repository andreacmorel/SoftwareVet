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
                dashboardData.turnosEstado.pendientes,
                dashboardData.turnosEstado.confirmados,
                dashboardData.turnosEstado.enAtencion,
                dashboardData.turnosEstado.completados,
                dashboardData.turnosEstado.cancelados
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


const ctxTurnosMes = document.getElementById('graficoTurnosMes');
// busca un elemento del html que contenga id "graficoTurnosMe"
// que lo encontramos arriba  en el html donde dice canva id
// y creamos una constante ctxTurnosMes donde guardamos ese elemento
// osea donde queremos colocar el grafico 

new Chart(ctxTurnosMes, { //creamos el grafico, aca comienza toda la config del grafico q queremos

    type: 'line', //definimos tipo de grafico line: grafico de linea

    data: { // informacion que muestra el grafico

        labels: dashboardData.mesesTurnos,
        //json_encode convierte una estructura PHP en un formato que javascript puede interpretar

        datasets: [{

            label: 'Cantidad de turnos',

            data: dashboardData.cantidadTurnosMes,
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

const ctxHistoriasMes =
    document.getElementById('graficoHistoriasMes');

new Chart(ctxHistoriasMes, {

    type: 'line',

    data: {

        labels: dashboardData.mesesHistorias,

        datasets: [{

            label: 'Historias clínicas',

            data: dashboardData.cantidadHistoriasMes,

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

const ctxMascotasHistorias =
    document.getElementById('graficoMascotasHistorias');

// Cantidades de registros clínicos de cada mascota
const cantidadesMascotas =
    dashboardData.cantidadHistoriasMascotas;

new Chart(ctxMascotasHistorias, {

    type: 'bar',

    data: {

        labels:
            dashboardData.nombresMascotasHistorias,

        datasets: [{

            label: 'Registros clínicos',

            data: cantidadesMascotas,

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
            },

            tooltip: {

                callbacks: {

                    label: function(context) {

                        const cantidad = context.raw;

                        return cantidad + 
                            (cantidad == 1
                                ? ' registro clínico'
                                : ' registros clínicos');
                    }

                }

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
                },

                ticks: {

                    // Genera dos líneas:
                    // Nombre
                    // Cantidad de registros
                    callback: function(value) {

                        const nombre =
                            this.getLabelForValue(value);

                        const cantidad =
                            cantidadesMascotas[value];

                        const textoCantidad =
                            cantidad == 1
                                ? '1 registro'
                                : cantidad + ' registros';

                        return [
                            nombre,
                            textoCantidad
                        ];

                    },

                    padding: 10

                }

            }

        }

    }

});

