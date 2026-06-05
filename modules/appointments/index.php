<?php
// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Valida que el usuario tenga permiso para acceder a esta ruta
require_once '../../php/validateRoute.php';

// Incluye el menú principal del sistema
require_once '../../php/menu.php';

// Obtiene los filtros enviados por GET
$filtro_profesional = (int)($_GET['profesional'] ?? 0);
$filtro_estado      = $_GET['estado'] ?? '';
$filtro_fecha_desde = $_GET['fecha_desde'] ?? '';
$filtro_fecha_hasta = $_GET['fecha_hasta'] ?? '';

// Lista de estados permitidos para evitar valores incorrectos
$estados_validos = ['pendiente', 'confirmado', 'en_atencion', 'completado', 'cancelado'];

// Arrays para armar la consulta preparada dinámicamente
$where = [];
$params = [];
$types = '';

// Filtro por profesional
if ($filtro_profesional) {
    $where[] = "t.id_profesional = ?";
    $params[] = $filtro_profesional;
    $types .= 'i';
}

// Filtro por estado, validando que sea un estado permitido
if ($filtro_estado && in_array($filtro_estado, $estados_validos)) {
    $where[] = "t.estado = ?";
    $params[] = $filtro_estado;
    $types .= 's';
}

// Filtro por fecha desde
if ($filtro_fecha_desde) {
    $where[] = "t.fecha >= ?";
    $params[] = $filtro_fecha_desde;
    $types .= 's';
}

// Filtro por fecha hasta
if ($filtro_fecha_hasta) {
    $where[] = "t.fecha <= ?";
    $params[] = $filtro_fecha_hasta;
    $types .= 's';
}

// Arma el WHERE final solo si existen filtros
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Consulta principal del listado de turnos
$sql = "
    SELECT 
        t.id_turno, 
        t.fecha, 
        t.hora, 
        t.motivo, 
        t.estado,
        CONCAT(per.apellido_persona, ', ', per.nombre_persona) AS profesional,
        m.nombre_mascota AS mascota,
        CONCAT(pc.apellido_persona, ', ', pc.nombre_persona) AS duenio
    FROM turnos t
    INNER JOIN profesional p ON t.id_profesional = p.id_profesional
    INNER JOIN persona per ON p.id_persona = per.id_persona
    INNER JOIN mascota m ON t.id_mascota = m.id_mascota
    INNER JOIN cliente c ON m.id_cliente = c.id_cliente
    INNER JOIN persona pc ON c.id_persona = pc.id_persona
    $whereSQL
    ORDER BY t.fecha DESC, t.hora DESC
";

// Si hay filtros, ejecuta la consulta como preparada
if ($params) {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    // Si no hay filtros, ejecuta la consulta directamente
    $result = $conexion->query($sql);
}

// Consulta para cargar profesionales en el filtro del listado
$resProfiltro = $conexion->query("
    SELECT 
        p.id_profesional, 
        CONCAT(per.apellido_persona, ', ', per.nombre_persona) AS nombre
    FROM profesional p
    INNER JOIN persona per ON p.id_persona = per.id_persona
    ORDER BY per.apellido_persona
");

// Mensaje de éxito cuando se registra un turno
if(isset($_GET['success'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Turno registrado</h5>
            <p>El turno fue registrado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
// Mensaje de éxito cuando se actualiza un turno
if(isset($_GET['updated'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Turno actualizado</h5>
            <p>Los datos del turno fueron actualizados correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
// Mensaje de éxito cuando se actualiza el estado de un turno
if(isset($_GET['status'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Estado actualizado</h5>
            <p>El estado del turno fue actualizado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
// Mensaje de éxito cuando se elimina un turno
if(isset($_GET['deleted'])) { ?>
    <div class="vet-alert-success">
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Turno eliminado</h5>
            <p>El turno fue eliminado correctamente.</p>
        </div>
    </div>
<?php } ?>

<?php
// Mensaje de error cuando se intenta modificar un turno cancelado o completado
if(isset($_GET['error']) && $_GET['error'] == 'estado') { ?>
    <div class="vet-alert-error">
        <div class="vet-alert-error-icon">
            <i class="fas fa-exclamation"></i>
        </div>

        <div class="vet-alert-content">
            <h5>Acción no permitida</h5>
            <p>No se puede modificar un turno cancelado o completado.</p>
        </div>
    </div>
<?php }

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Listado de Turnos</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>
.page-title {
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 2px;
}

.page-title i {
    color: #52266E;
}

.page-subtitle {
    color: #9ca3af;
    font-size: 14px;
}

.btn-purple {
    background: #52266E;
    color: white;
    border-radius: 8px;
    font-weight: 600;
}

.btn-purple:hover {
    background: #3f1d55;
    color: white;
}

.filter-card {
    background: white;
    border-radius: 15px;
    padding: 18px 20px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
    margin-top: 25px;
    margin-bottom: 25px;
}

.filter-card label {
    color: #52266E;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}

.filter-card .form-control {
    border-radius: 8px;
    border: 1px solid #d8c2e8;
    font-size: 14px;
}

.table-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

thead th {
    background: #fbf7ff !important;
    color: #52266E !important;
    font-size: 12px;
    text-transform: uppercase;
    border-bottom: 2px solid #eee1f6 !important;
    font-weight: 800;
}

tbody td {
    vertical-align: middle !important;
    font-size: 14px;
    color: #374151;
}

tbody tr:hover {
    background: #fcf8ff;
}

.turno-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #f0e6f6;
    color: #52266E;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
}

.turno-date {
    font-weight: 800;
    color: #111827;
}

.turno-hour {
    color: #9ca3af;
    font-size: 12px;
}

.dato-muted {
    color: #6b7280;
}

.btn-action {
    width: 31px;
    height: 31px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    margin: 0 2px;
    text-decoration: none;
}

.btn-action:hover {
    text-decoration: none;
    transform: scale(1.08);
}

.btn-edit {
    background: #fef3c7;
    color: #92400e;
}

.btn-delete {
    background: #fee2e2;
    color: #b91c1c;
}

.estado-select {
    width: 140px;
    border: none;
    border-radius: 50px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    text-align: center;
    box-shadow: none;
    transition: all .2s ease;
}

.estado-select:hover {
    filter: brightness(0.95);
}

.estado-pendiente {
    background: #fff4cc;
    color: #9a6a00;
}

.estado-confirmado {
    background: #dff8ff;
    color: #087990;
}

.estado-en_atencion {
    background: #e7ecff;
    color: #2944a3;
}

.estado-completado {
    background: #dcfce7;
    color: #15803d;
}

.estado-cancelado {
    background: #ffe4e6;
    color: #be123c;
}

.vet-alert-success,
.vet-alert-error {
    width:100%;
    border-radius:16px;
    padding:18px 22px;
    display:flex;
    align-items:center;
    gap:16px;
    margin-bottom:25px;
    animation:fadeIn .35s ease;
}

.vet-alert-success {
    background:linear-gradient(135deg,#f6fffa,#eefcf4);
    border:1px solid #d7f3e3;
    box-shadow:0 6px 18px rgba(25,135,84,.08);
}

.vet-alert-error {
    background:linear-gradient(135deg,#fff5f5,#fef2f2);
    border:1px solid #fecaca;
    box-shadow:0 6px 18px rgba(220,38,38,.08);
}

.vet-alert-icon,
.vet-alert-error-icon {
    width:48px;
    height:48px;
    min-width:48px;
    border-radius:50%;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
}

.vet-alert-icon {
    background:#198754;
    box-shadow:0 4px 10px rgba(25,135,84,.25);
}

.vet-alert-error-icon {
    background:#dc2626;
    box-shadow:0 4px 10px rgba(220,38,38,.25);
}

.vet-alert-content h5 {
    margin:0;
    font-size:15px;
    font-weight:800;
}

.vet-alert-success .vet-alert-content h5 {
    color:#166534;
}

.vet-alert-error .vet-alert-content h5 {
    color:#991b1b;
}

.vet-alert-content p {
    margin:3px 0 0;
    color:#4b5563;
    font-size:14px;
}

@keyframes fadeIn {
    from {
        opacity:0;
        transform:translateY(-8px);
    }
    to {
        opacity:1;
        transform:translateY(0);
    }
}
</style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-calendar-check mr-2"></i> Turnos
            </h1>
            <div class="page-subtitle">Gestión del registro de turnos</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nuevo Turno
            </a>

            <a href="reporte_excel.php" class="btn btn-success ml-2" title="Exportar a Excel">
                <i class="fas fa-file-excel"></i>
            </a>
        </div>
    </div>

    <form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-3">
                <label>Profesional</label>
                <select name="profesional" class="form-control">
                    <option value="">Todos</option>

                    <?php while ($pf = $resProfiltro->fetch_assoc()) { ?>
                        <option value="<?= $pf['id_profesional'] ?>"
                            <?= ($filtro_profesional == $pf['id_profesional']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pf['nombre']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendiente" <?= $filtro_estado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="confirmado" <?= $filtro_estado === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                    <option value="en_atencion" <?= $filtro_estado === 'en_atencion' ? 'selected' : '' ?>>En atención</option>
                    <option value="completado" <?= $filtro_estado === 'completado' ? 'selected' : '' ?>>Completado</option>
                    <option value="cancelado" <?= $filtro_estado === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Desde</label>
                <input 
                    type="date" 
                    name="fecha_desde" 
                    class="form-control"
                    value="<?= htmlspecialchars($filtro_fecha_desde) ?>"
                >
            </div>

            <div class="col-md-2">
                <label>Hasta</label>
                <input 
                    type="date" 
                    name="fecha_hasta" 
                    class="form-control"
                    value="<?= htmlspecialchars($filtro_fecha_hasta) ?>"
                >
            </div>

            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-filter"></i>
                </button>

                <a href="index.php" class="btn btn-secondary ml-2">
                    <i class="fas fa-times"></i>
                </a>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Mascota</th>
                        <th>Dueño</th>
                        <th>Profesional</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th class="text-center" style="width:130px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && $result->num_rows > 0) { ?>
                        <?php while ($t = $result->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="turno-icon">
                                            <i class="fas fa-calendar-day"></i>
                                        </span>

                                        <div>
                                            <div class="turno-date">
                                                <?= date('d/m/Y', strtotime($t->fecha)) ?>
                                            </div>

                                            <div class="turno-hour">
                                                <?= substr($t->hora, 0, 5) ?> hs
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted">
                                    <strong><?= htmlspecialchars($t->mascota) ?></strong>
                                </td>

                                <td class="dato-muted">
                                    <?= htmlspecialchars($t->duenio) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= htmlspecialchars($t->profesional) ?>
                                </td>

                                <td class="dato-muted">
                                    <?= !empty($t->motivo) ? htmlspecialchars($t->motivo) : '—' ?>
                                </td>

                                <td>
                                    <?php if ($t->estado === 'completado' || $t->estado === 'cancelado') { ?>

                                        <span class="estado-select estado-<?= htmlspecialchars($t->estado) ?>">
                                            <?= $t->estado === 'completado' ? 'Completado' : 'Cancelado' ?>
                                        </span>

                                    <?php } else { ?>

                                        <form action="change_status.php" method="POST" style="margin:0;">
                                            <input type="hidden" name="id_turno" value="<?= $t->id_turno ?>">

                                            <select 
                                                name="estado"
                                                class="form-control form-control-sm estado-select estado-<?= htmlspecialchars($t->estado) ?>"
                                                onchange="this.form.submit()"
                                            >
                                                <option value="pendiente" <?= $t->estado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                <option value="confirmado" <?= $t->estado === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                                                <option value="en_atencion" <?= $t->estado === 'en_atencion' ? 'selected' : '' ?>>En atención</option>
                                                <option value="completado" <?= $t->estado === 'completado' ? 'selected' : '' ?>>Completado</option>
                                                <option value="cancelado" <?= $t->estado === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                            </select>
                                        </form>

                                    <?php } ?>
                                </td>

                                <td class="text-center">

                                    <?php if ($t->estado !== 'cancelado' && $t->estado !== 'completado') { ?>
                                        <a 
                                            href="edit.php?id=<?= $t->id_turno ?>"
                                            class="btn-action btn-edit" 
                                            title="Modificar / Reprogramar"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    <?php } ?>

                                    <button 
                                        type="button"
                                        class="btn-action btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalEliminarTurno"
                                        data-id="<?= $t->id_turno ?>"
                                        data-nombre="<?= htmlspecialchars($t->mascota . ' - ' . date('d/m/Y', strtotime($t->fecha)) . ' ' . substr($t->hora, 0, 5)) ?>"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron turnos.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="modalEliminarTurno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px; overflow:hidden; border:none;">

            <div style="background:#52266E; color:white; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
                <h5 style="margin:0; font-weight:700;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirmar eliminación
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <div class="text-center p-4">
                <i class="fas fa-calendar-times fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar el turno?</p>

                <h5 id="nombreTurnoEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarTurno" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Sí, eliminar
                </a>
            </div>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
$('#modalEliminarTurno').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);
    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreTurnoEliminar').text(nombre);
    $('#btnConfirmarEliminarTurno').attr('href', 'delete.php?id=' + id);
});

setTimeout(() => {

    const alerta = document.querySelector('.vet-alert-success, .vet-alert-error');

    if(alerta){

        alerta.style.transition = '.4s';
        alerta.style.opacity = '0';
        alerta.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            alerta.remove();
        }, 400);
    }

}, 3500);
</script>

</body>
</html>