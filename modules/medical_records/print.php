<?php
// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Verifica si se pidió generar PDF mediante el parámetro ?pdf
$generarPDF = isset($_GET['pdf']);

// Si se solicitó PDF, carga Dompdf y activa el buffer de salida
if ($generarPDF) {
    require_once '../../vendor/autoload.php';
    ob_start();
}

// Obtiene el ID de la historia clínica desde la URL
$id = (int)($_GET['id'] ?? 0);

// Valida que el ID sea correcto
if ($id <= 0) {
    die("ID de historia clínica no válido.");
}

// Consulta preparada para obtener los datos de la historia clínica,
// mascota, especie y propietario
$stmt = $conexion->prepare("
    SELECT 
        h.id_historia_clinica,
        h.fecha,
        h.descripcion,
        h.observacion,
        m.id_mascota,
        m.nombre_mascota,
        m.sexo,
        m.peso,
        m.color,
        m.edad,
        e.nombre_especie,
        e.raza,
        p.nombre_persona,
        p.apellido_persona,
        p.telefono,
        p.email
    FROM historia_clinica h
    INNER JOIN mascota m ON h.id_mascota = m.id_mascota
    LEFT JOIN especie e ON m.id_especie = e.id_especie
    INNER JOIN cliente c ON m.id_cliente = c.id_cliente
    INNER JOIN persona p ON c.id_persona = p.id_persona
    WHERE h.id_historia_clinica = ?
");

// Vincula el ID a la consulta preparada
$stmt->bind_param("i", $id);

// Ejecuta la consulta
$stmt->execute();

// Obtiene el resultado
$res = $stmt->get_result();

// Si no encuentra la historia clínica, corta la ejecución
if ($res->num_rows == 0) {
    die("Historia clínica no encontrada.");
}

// Guarda los datos encontrados en un array asociativo
$hc = $res->fetch_assoc();

// Cierra la consulta preparada
$stmt->close();

// Consulta preparada para obtener los tratamientos asociados a la historia clínica
$stmtTrat = $conexion->prepare("
    SELECT t.duracion, t.dosis, t.descripcion
    FROM detalle_historia_clinica dh
    INNER JOIN tratamientos t ON dh.id_tratamiento = t.id_tratamiento
    WHERE dh.id_historia_clinica = ?
");

// Vincula el ID de historia clínica a la consulta de tratamientos
$stmtTrat->bind_param("i", $id);

// Ejecuta la consulta de tratamientos
$stmtTrat->execute();

// Obtiene los tratamientos encontrados
$tratamientos = $stmtTrat->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historia Clínica</title>

<style>
/* Estilos generales del documento */
body{
    font-family: DejaVu Sans, Arial, sans-serif;
    color:#1f2937;
    margin:20px 28px;
    font-size:13px;
}

/* Encabezado principal */
.header{
    border-bottom:2px solid #52266E;
    padding-bottom:12px;
    margin-bottom:18px;
}

/* Contenedor superior del encabezado */
.header-top{
    position:relative;
}

/* Información pequeña del sistema y fecha */
.header_one{
    font-size:11px;
    color:#6b7280;
    line-height:1.5;
    margin-bottom:8px;
}

/* Título principal del documento */
.logo-title{
    font-family: Georgia, serif;
    font-size:24px;
    font-weight:700;
    color:#52266E;
    text-transform:uppercase;
    letter-spacing:.5px;
    text-align:center;
}

/* Código de historia clínica ubicado arriba a la derecha */
.badge-hc{
    position:absolute;
    top:0;
    right:0;
    color:#52266E;
    font-size:12px;
    font-weight:700;
}

/* Secciones del documento */
.section{
    margin-top:13px;
    page-break-inside:avoid;
}

/* Títulos de cada sección */
.section h3{
    color:#52266E;
    font-size:13px;
    text-transform:uppercase;
    border-bottom:1px solid #eee1f6;
    padding-bottom:5px;
    margin-bottom:8px;
}

/* Grilla para ordenar los datos en dos columnas */
.grid{
    display:grid;
    grid-template-columns:repeat(2, 1fr);
    gap:8px 22px;
}

/* Cada dato individual */
.item{
    font-size:13px;
}

/* Etiquetas en negrita */
.label{
    font-weight:800;
    color:#374151;
}

/* Caja para textos largos como descripción u observación */
.box{
    border:1px solid #eee1f6;
    border-radius:10px;
    padding:10px;
    background:#fbf7ff;
    font-size:13px;
    min-height:auto;
}

/* Tabla de tratamientos */
.table{
    width:100%;
    border-collapse:collapse;
    margin-top:8px;
}

/* Encabezado de la tabla */
.table th{
    background:#f3e8ff;
    color:#52266E;
    text-align:left;
    padding:8px;
    font-size:11px;
    text-transform:uppercase;
    border:1px solid #e7d7f5;
}

/* Celdas de la tabla */
.table td{
    padding:8px;
    border:1px solid #eee1f6;
    font-size:12px;
}

/* Sección de tratamientos */
.tratamientos-section{
    page-break-inside:avoid;
    margin-top:13px;
}

/* Mensaje cuando no hay tratamientos */
.no-tratamientos{
    background:#fbf7ff;
    border:1px dashed #d8c2e8;
    color:#52266E;
    padding:12px;
    border-radius:10px;
    text-align:center;
    font-size:13px;
    font-weight:600;
}

/* Pie del documento */
.footer{
    margin-top:30px;
    display:flex;
    justify-content:space-between;
    font-size:11px;
    color:#6b7280;
}

/* Ajustes cuando se imprime */
@media print{
    body{
        margin:20px;
    }
}
</style>
</head>

<body>

<!-- Encabezado del documento -->
<div class="header">
    <div class="header-top">

        <!-- Código formateado de historia clínica -->
        <div class="badge-hc">
            HC-<?= str_pad($hc['id_historia_clinica'], 5, '0', STR_PAD_LEFT) ?>
        </div>

        <!-- Datos del sistema y fecha de emisión -->
        <div class="header_one">
            <div>VetSys - Software Veterinario</div>
            <div>Fecha: <?= date('d/m/Y H:i') ?></div>
        </div>

        <!-- Título del documento -->
        <div class="logo-title">
            Historia Clínica
        </div>

    </div>
</div>

<!-- Datos generales de la consulta -->
<div class="section">
    <h3>Datos de la consulta</h3>
    <div class="grid">
        <div class="item"><span class="label">Fecha:</span> <?= date('d/m/Y', strtotime($hc['fecha'])) ?></div>
        <div class="item"><span class="label">Código mascota:</span> M-<?= str_pad($hc['id_mascota'], 4, '0', STR_PAD_LEFT) ?></div>
    </div>
</div>

<!-- Datos de la mascota -->
<div class="section">
    <h3>Datos de la mascota</h3>
    <div class="grid">
        <div class="item"><span class="label">Nombre:</span> <?= htmlspecialchars($hc['nombre_mascota']) ?></div>
        <div class="item"><span class="label">Especie/Raza:</span> <?= htmlspecialchars(($hc['nombre_especie'] ?? '—') . ' - ' . ($hc['raza'] ?? '—')) ?></div>
        <div class="item"><span class="label">Sexo:</span> <?= $hc['sexo'] == 'M' ? 'Macho' : ($hc['sexo'] == 'H' ? 'Hembra' : '—') ?></div>
        <div class="item"><span class="label">Peso:</span> <?= !empty($hc['peso']) ? htmlspecialchars($hc['peso']) . ' kg' : '—' ?></div>
        <div class="item"><span class="label">Edad:</span> <?= !empty($hc['edad']) ? htmlspecialchars($hc['edad']) : '—' ?></div>
        <div class="item"><span class="label">Color:</span> <?= !empty($hc['color']) ? htmlspecialchars($hc['color']) : '—' ?></div>
    </div>
</div>

<!-- Datos del propietario -->
<div class="section">
    <h3>Datos del propietario</h3>
    <div class="grid">
        <div class="item"><span class="label">Cliente:</span> <?= htmlspecialchars($hc['apellido_persona'] . ', ' . $hc['nombre_persona']) ?></div>
        <div class="item"><span class="label">Teléfono:</span> <?= !empty($hc['telefono']) ? htmlspecialchars($hc['telefono']) : '—' ?></div>
        <div class="item"><span class="label">Email:</span> <?= !empty($hc['email']) ? htmlspecialchars($hc['email']) : '—' ?></div>
    </div>
</div>

<!-- Descripción clínica -->
<div class="section">
    <h3>Descripción clínica</h3>
    <div class="box">
        <?= nl2br(htmlspecialchars($hc['descripcion'])) ?>
    </div>
</div>

<!-- Observación -->
<div class="section">
    <h3>Observación</h3>
    <div class="box">
        <?= !empty($hc['observacion']) ? nl2br(htmlspecialchars($hc['observacion'])) : '—' ?>
    </div>
</div>

<!-- Tratamientos asociados -->
<div class="section">
    <h3>Tratamientos</h3>

    <!-- Si existen tratamientos, los muestra en una tabla -->
    <?php if ($tratamientos && $tratamientos->num_rows > 0) { ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Duración</th>
                    <th>Dosis</th>
                    <th>Descripción</th>
                </tr>
            </thead>

            <tbody>
                <!-- Recorre cada tratamiento encontrado -->
                <?php while ($t = $tratamientos->fetch_assoc()) { ?>
                    <tr>
                        <td><?= !empty($t['duracion']) ? htmlspecialchars($t['duracion']) : '—' ?></td>
                        <td><?= !empty($t['dosis']) ? htmlspecialchars($t['dosis']) : '—' ?></td>
                        <td><?= !empty($t['descripcion']) ? htmlspecialchars($t['descripcion']) : '—' ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <!-- Si no hay tratamientos, muestra un guion -->
        <div class="box">
            —
        </div>

    <?php } ?>

</div>
</body>
</html>

<?php
// Cierra la consulta de tratamientos
$stmtTrat->close();

// Si se solicitó generar PDF
if ($generarPDF) {

    // Obtiene todo el HTML generado anteriormente
    $html = ob_get_clean();

    // Crea las opciones de Dompdf
    $options = new Dompdf\Options();

    // Permite cargar recursos remotos si fuera necesario
    $options->set('isRemoteEnabled', true);

    // Crea una instancia de Dompdf
    $dompdf = new Dompdf\Dompdf($options);

    // Carga el HTML que se convertirá a PDF
    $dompdf->loadHtml($html);

    // Define el tamaño y orientación del papel
    $dompdf->setPaper('A4', 'portrait');

    // Renderiza/genera el PDF
    $dompdf->render();

    // Envía el PDF al navegador para descargarlo
    $dompdf->stream(
        'Historia_Clinica_HC_' . str_pad($hc['id_historia_clinica'], 5, '0', STR_PAD_LEFT) . '.pdf',
        ['Attachment' => true]
    );

    // Finaliza la ejecución para no imprimir contenido extra
    exit;
}
?>