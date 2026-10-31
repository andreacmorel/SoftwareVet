document.getElementById('formEnviarReportes')
    .addEventListener('submit', async function(evento) {

        evento.preventDefault();

        const correo = document.getElementById('correoReportes').value.trim();
        const token = document.getElementById('csrfReportes').value;
        const boton = document.getElementById('btnEnviarReportes');
        const mensaje = document.getElementById('mensajeEnvioReportes');

        boton.disabled = true;
        mensaje.textContent = 'Preparando los informes...';
        mensaje.className = 'mt-3 text-muted';

        try {

            // OBTIENE LOS ARCHIVOS SIN DESCARGARLOS
            const pdf = exportarTodosPDF(false);
            const excel = await exportarTodosExcel(false);

            if (!(pdf instanceof Blob) || !(excel instanceof Blob)) {
                throw new Error(
                    'Las funciones de exportación deben devolver archivos Blob.'
                );
            }

            // PREPARA DATOS PARA ENVIAR A PHP
            const datos = new FormData();

            datos.append('email', correo);
            datos.append('csrf_token', token);
            datos.append('pdf', pdf, 'VetSys_Reporte_General.pdf');
            datos.append('excel', excel, 'VetSys_Reporte_General.xlsx');

            mensaje.textContent = 'Enviando reportes por correo...';

            // LOS ENVIA AL SERVIDOR 
            const respuesta = await fetch(
                '/SoftwareVet/app/send_report.php',
                {
                    method: 'POST',
                    body: datos,
                    credentials: 'same-origin'
                }
            );

            const resultado = await respuesta.json();

            if (!respuesta.ok || !resultado.ok) {
                throw new Error(
                    resultado.mensaje || 'No se pudo enviar el correo.'
                );
            }

            mensaje.className = 'mt-3 text-success';
            mensaje.textContent = resultado.mensaje;

        } catch (error) {

            mensaje.className = 'mt-3 text-danger';
            mensaje.textContent = error.message;

        } finally {

            boton.disabled = false;

        }

    });