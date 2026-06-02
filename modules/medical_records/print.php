<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$generarPDF = isset($_GET['pdf']);

if ($generarPDF) {
    require_once '../../vendor/autoload.php';
    ob_start();
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID de historia clínica no válido.");
}

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

$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("Historia clínica no encontrada.");
}

$hc = $res->fetch_assoc();
$stmt->close();

$stmtTrat = $conexion->prepare("
    SELECT t.duracion, t.dosis, t.descripcion
    FROM detalle_historia_clinica dh
    INNER JOIN tratamientos t ON dh.id_tratamiento = t.id_tratamiento
    WHERE dh.id_historia_clinica = ?
");

$stmtTrat->bind_param("i", $id);
$stmtTrat->execute();
$tratamientos = $stmtTrat->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historia Clínica</title>

<style>
body{
    font-family: DejaVu Sans, Arial, sans-serif;
    color:#1f2937;
    margin:20px 28px;
    font-size:13px;
}

.header{
    border-bottom:2px solid #52266E;
    padding-bottom:12px;
    margin-bottom:18px;
}

.header-top{
    position:relative;
}

.header_one{
    font-size:11px;
    color:#6b7280;
    line-height:1.5;
    margin-bottom:8px;
}

.logo-title{
    font-family: Georgia, serif;
    font-size:24px;
    font-weight:700;
    color:#52266E;
    text-transform:uppercase;
    letter-spacing:.5px;
    text-align:center;
}

.badge-hc{
    position:absolute;
    top:0;
    right:0;
    color:#52266E;
    font-size:12px;
    font-weight:700;
}

.section{
    margin-top:13px;
    page-break-inside:avoid;
}

.section h3{
    color:#52266E;
    font-size:13px;
    text-transform:uppercase;
    border-bottom:1px solid #eee1f6;
    padding-bottom:5px;
    margin-bottom:8px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(2, 1fr);
    gap:8px 22px;
}

.item{
    font-size:13px;
}

.label{
    font-weight:800;
    color:#374151;
}

.box{
    border:1px solid #eee1f6;
    border-radius:10px;
    padding:10px;
    background:#fbf7ff;
    font-size:13px;
    min-height:auto;
}

.table{
    width:100%;
    border-collapse:collapse;
    margin-top:8px;
}

.table th{
    background:#f3e8ff;
    color:#52266E;
    text-align:left;
    padding:8px;
    font-size:11px;
    text-transform:uppercase;
    border:1px solid #e7d7f5;
}

.table td{
    padding:8px;
    border:1px solid #eee1f6;
    font-size:12px;
}

.tratamientos-section{
    page-break-inside:avoid;
    margin-top:13px;
}

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

.footer{
    margin-top:30px;
    display:flex;
    justify-content:space-between;
    font-size:11px;
    color:#6b7280;
}

@media print{
    body{
        margin:20px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="header-top">

        <div class="badge-hc">
            HC-<?= str_pad($hc['id_historia_clinica'], 5, '0', STR_PAD_LEFT) ?>
        </div>

        <div class="header_one">
            <div>VetSys - Software Veterinario</div>
            <div>Fecha: <?= date('d/m/Y H:i') ?></div>
        </div>

        <div class="logo-title">
            Historia Clínica
        </div>

    </div>
</div>

<div class="section">
    <h3>Datos de la consulta</h3>
    <div class="grid">
        <div class="item"><span class="label">Fecha:</span> <?= date('d/m/Y', strtotime($hc['fecha'])) ?></div>
        <div class="item"><span class="label">Código mascota:</span> M-<?= str_pad($hc['id_mascota'], 4, '0', STR_PAD_LEFT) ?></div>
    </div>
</div>

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

<div class="section">
    <h3>Datos del propietario</h3>
    <div class="grid">
        <div class="item"><span class="label">Cliente:</span> <?= htmlspecialchars($hc['apellido_persona'] . ', ' . $hc['nombre_persona']) ?></div>
        <div class="item"><span class="label">Teléfono:</span> <?= !empty($hc['telefono']) ? htmlspecialchars($hc['telefono']) : '—' ?></div>
        <div class="item"><span class="label">Email:</span> <?= !empty($hc['email']) ? htmlspecialchars($hc['email']) : '—' ?></div>
    </div>
</div>

<div class="section">
    <h3>Descripción clínica</h3>
    <div class="box">
        <?= nl2br(htmlspecialchars($hc['descripcion'])) ?>
    </div>
</div>

<div class="section">
    <h3>Observación</h3>
    <div class="box">
        <?= !empty($hc['observacion']) ? nl2br(htmlspecialchars($hc['observacion'])) : '—' ?>
    </div>
</div>

<div class="section">
    <h3>Tratamientos</h3>

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

        <div class="box">
            —
        </div>

    <?php } ?>

</div>
</body>
</html>

<?php
$stmtTrat->close();

if ($generarPDF) {
    $html = ob_get_clean();

    $options = new Dompdf\Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream(
        'Historia_Clinica_HC_' . str_pad($hc['id_historia_clinica'], 5, '0', STR_PAD_LEFT) . '.pdf',
        ['Attachment' => true]
    );

    exit;
}
?>