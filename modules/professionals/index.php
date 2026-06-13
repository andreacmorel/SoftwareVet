<?php

// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Valida que el usuario tenga permisos para acceder a esta ruta
require_once '../../php/validateRoute.php';

// Incluye el menú principal del sistema
require_once '../../php/menu.php';

// Obtiene el texto ingresado en el buscador
$buscar = trim($_GET['buscar'] ?? '');

// Variable donde se almacenará la condición WHERE de la consulta
$where = "WHERE c.activo = 1";

// Si el usuario escribió algo en el buscador
if (!empty($buscar)) {

    // Escapa caracteres especiales para evitar problemas en la consulta SQL
    $buscarSeguro = $conexion->real_escape_string($buscar);

    // Construye el filtro de búsqueda para nombre, apellido,
    // teléfono, correo electrónico y barrio
    $where = "WHERE pr.activo = 1 AND (
        p.nombre_persona LIKE '%$buscarSeguro%' OR
        p.apellido_persona LIKE '%$buscarSeguro%' OR
        p.telefono LIKE '%$buscarSeguro%' OR
        p.email LIKE '%$buscarSeguro%' OR
        d.barrio LIKE '%$buscarSeguro%'
)";
}

// ======================================================
// MENSAJE DE ÉXITO AL REGISTRAR UN PROFESIONAL
// ======================================================

// Verifica si llegó el parámetro success por URL
if(isset($_GET['success'])) { ?>

    <!-- Alerta personalizada de registro exitoso -->
    <div class="vet-alert-success">

        <!-- Ícono de confirmación -->
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Contenido del mensaje -->
        <div class="vet-alert-content">
            <h5>Registro exitoso</h5>
            <p>El profesional fue registrado correctamente.</p>
        </div>

    </div>

<?php } ?>

<?php

// ======================================================
// MENSAJE DE ÉXITO AL MODIFICAR UN PROFESIONAL
// ======================================================

// Verifica si llegó el parámetro updated por URL
if(isset($_GET['updated'])) { ?>

    <!-- Alerta personalizada de actualización exitosa -->
    <div class="vet-alert-success">

        <!-- Ícono de confirmación -->
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Contenido del mensaje -->
        <div class="vet-alert-content">
            <h5>Cambios guardados</h5>
            <p>La información fue actualizada correctamente.</p>
        </div>

    </div>

<?php } ?>

<?php

// ======================================================
// MENSAJE DE ÉXITO AL ELIMINAR UN PROFESIONAL
// ======================================================

// Verifica si llegó el parámetro deleted por URL
if(isset($_GET['deleted'])) { ?>

    <!-- Alerta personalizada de eliminación exitosa -->
    <div class="vet-alert-success">

        <!-- Ícono de confirmación -->
        <div class="vet-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Contenido del mensaje -->
        <div class="vet-alert-content">
            <h5>Registro eliminado</h5>
            <p>El profesional fue eliminado correctamente.</p>
        </div>

    </div>

<?php } ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Profesionales</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .page-title { font-weight:800; color:#1f2937; margin-bottom:2px; }
        .page-title i { color:#52266E; }
        .page-subtitle { color:#9ca3af; font-size:14px; }

        .btn-purple {
            background:#52266E;
            color:white;
            border-radius:8px;
            font-weight:600;
        }

        .btn-purple:hover {
            background:#3f1d55;
            color:white;
        }

        .filter-card {
            background:white;
            border-radius:15px;
            padding:18px 20px;
            box-shadow:0 4px 18px rgba(0,0,0,.06);
            margin-top:25px;
            margin-bottom:25px;
        }

        .filter-card label {
            color:#52266E;
            font-size:12px;
            font-weight:800;
            text-transform:uppercase;
        }

        .filter-card .form-control {
            border-radius:8px 0 0 8px;
            border:1px solid #d8c2e8;
            font-size:14px;
        }

        .btn-filter {
            border-radius:0 8px 8px 0;
            padding:7px 14px;
        }

        .table-card {
            background:white;
            border-radius:15px;
            box-shadow:0 4px 18px rgba(0,0,0,.06);
            overflow:hidden;
        }

        .table { margin-bottom:0; }

        thead th {
            background:#fbf7ff !important;
            color:#52266E !important;
            font-size:12px;
            text-transform:uppercase;
            border-bottom:2px solid #eee1f6 !important;
            font-weight:800;
        }

        tbody td {
            vertical-align:middle !important;
            font-size:14px;
            color:#374151;
        }

        tbody tr:hover { background:#fcf8ff; }

        .prof-icon {
            width:34px;
            height:34px;
            border-radius:8px;
            background:#f0e6f6;
            color:#52266E;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            margin-right:10px;
        }

        .prof-name {
            font-weight:800;
            color:#111827;
        }

        .prof-id {
            color:#9ca3af;
            font-size:12px;
        }

        .dato-muted { color:#6b7280; }

        .btn-action {
            width:31px;
            height:31px;
            border-radius:8px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border:none;
            margin:0 2px;
        }

        .btn-edit {
            background:#fef3c7;
            color:#92400e;
        }

        .btn-delete {
            background:#fee2e2;
            color:#b91c1c;
        }
        .vet-alert-success{
    width:100%;
    background:linear-gradient(135deg,#f6fffa,#eefcf4);
    border:1px solid #d7f3e3;
    border-radius:16px;
    padding:18px 22px;
    display:flex;
    align-items:center;
    gap:16px;
    box-shadow:0 6px 18px rgba(25,135,84,.08);
    margin-bottom:25px;
    animation:fadeIn .35s ease;
}

.vet-alert-icon{
    width:48px;
    height:48px;
    min-width:48px;
    border-radius:50%;
    background:#198754;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    box-shadow:0 4px 10px rgba(25,135,84,.25);
}

.vet-alert-content h5{
    margin:0;
    font-size:15px;
    font-weight:800;
    color:#166534;
}

.vet-alert-content p{
    margin:3px 0 0;
    color:#4b5563;
    font-size:14px;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-8px);
    }
    to{
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
                <i class="fas fa-user-md mr-2"></i> Profesionales
            </h1>
            <div class="page-subtitle">Gestión del registro de profesionales</div>
        </div>

        <div class="d-flex align-items-center">
            <a href="create.php" class="btn btn-purple">
                <i class="fas fa-plus"></i> Nuevo Profesional
            </a>

            <button class="btn btn-success ml-2"
                    onclick="window.location.href='reporte_excel.php'"
                    title="Exportar a Excel">
                <i class="fas fa-file-excel"></i>
            </button>
        </div>
    </div>

<form method="GET" class="filter-card">
        <div class="row align-items-end">

            <div class="col-md-10">
                <label>Buscar</label>
                <input type="text" name="buscar" class="form-control"
                    placeholder="Buscar por nombre, apellido, teléfono, email o barrio"
                    value="<?= htmlspecialchars($buscar) ?>">
            </div>

            <div class="col-md-2 ">
                <button type="submit" class="btn btn-purple">
                <i class="fas fa-filter"></i>
            </button>
            </div>

        </div>
    </form>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Profesional</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Calle</th>
                        <th>Número</th>
                        <th>Barrio</th>
                        <th>Manzana</th>
                        <th class="text-center" style="width:120px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $sql = $conexion->query("
                        SELECT 
                            c.id_profesional,
                            p.nombre_persona,
                            p.apellido_persona,
                            p.telefono,
                            p.email,
                            d.calle,
                            d.numero_calle,
                            d.barrio,
                            d.manzana
                        FROM profesional c
                        INNER JOIN persona p ON c.id_persona = p.id_persona
                        LEFT JOIN domicilio d ON d.id_profesional = c.id_profesional
                        $where
                        ORDER BY c.id_profesional DESC
                    ");

                    if ($sql->num_rows > 0) {
                        while ($row = $sql->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="prof-icon">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                        <div>
                                            <div class="prof-name">
                                                <?= htmlspecialchars($row->nombre_persona . ' ' . $row->apellido_persona) ?>
                                            </div>
                                            <div class="prof-id">#<?= $row->id_profesional ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="dato-muted"><?= !empty($row->telefono) ? htmlspecialchars($row->telefono) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->email) ? htmlspecialchars($row->email) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->calle) ? htmlspecialchars($row->calle) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->numero_calle) ? htmlspecialchars($row->numero_calle) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->barrio) ? htmlspecialchars($row->barrio) : '—' ?></td>
                                <td class="dato-muted"><?= !empty($row->manzana) ? htmlspecialchars($row->manzana) : '—' ?></td>

                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row->id_profesional ?>"
                                    class="btn-action btn-edit" title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button type="button"
                                            class="btn-action btn-delete"
                                            data-toggle="modal"
                                            data-target="#modalEliminarProfesional"
                                            data-id="<?= $row->id_profesional ?>"
                                            data-nombre="<?= htmlspecialchars($row->nombre_persona . ' ' . $row->apellido_persona) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron profesionales.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="modalEliminarProfesional" tabindex="-1">
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
                <i class="fas fa-user-md fa-3x mb-3" style="color:#d8c2e8;"></i>

                <p class="mb-1">¿Estás seguro de eliminar a</p>

                <h5 id="nombreProfesionalEliminar" style="color:#52266E; font-weight:800;"></h5>

                <p class="mt-3" style="font-size:14px; color:#6b7280;">
                    <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                    Esta acción es <b>irreversible</b>.
                </p>
            </div>

            <div class="d-flex justify-content-end p-3" style="gap:10px; border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>

                <a href="#" id="btnConfirmarEliminarProfesional" class="btn btn-danger">
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
$('#modalEliminarProfesional').on('show.bs.modal', function (event) {
    var boton = $(event.relatedTarget);

    var id = boton.data('id');
    var nombre = boton.data('nombre');

    $('#nombreProfesionalEliminar').text(nombre);
    $('#btnConfirmarEliminarProfesional').attr('href', 'delete.php?id=' + id);
});
</script>
<script>

setTimeout(() => {

    const alerta = document.querySelector('.vet-alert-success');

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