<?php
require_once '../../settings/conexion.php';
require_once '../../php/menu.php';

$buscar = trim($_GET['buscar'] ?? '');
$whereBuscar = "";

if (!empty($buscar)) {
    $buscarSeguro = $conexion->real_escape_string($buscar);

    $whereBuscar = " AND (
        u.usuario LIKE '%$buscarSeguro%' OR
        u.email LIKE '%$buscarSeguro%' OR
        p.nombre_perfil LIKE '%$buscarSeguro%'
    )";
}

$usuarios = $conexion->query("
    SELECT u.id_usuario, u.usuario, u.email, u.estado, p.nombre_perfil
    FROM usuario u
    INNER JOIN perfil p ON u.id_perfil = p.id_perfil
    WHERE 1=1
    $whereBuscar
    ORDER BY u.id_usuario DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<title>Listado Usuarios</title>

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
    border-radius:8px;
    border:1px solid #d8c2e8;
    font-size:14px;
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

.user-icon {
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

.user-name {
    font-weight:800;
    color:#111827;
}

.user-id {
    color:#9ca3af;
    font-size:12px;
}

.dato-muted { color:#6b7280; }

.perfil-badge {
    background:#eef2ff;
    color:#4338ca;
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:800;
}

.badge-estado {
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    font-weight:800;
    display:inline-block;
    min-width:75px;
    text-align:center;
}

.badge-estado.activo {
    background:#dcfce7;
    color:#166534;
}

.badge-estado.inactivo {
    background:#e5e7eb;
    color:#374151;
}

.btn-action:hover {
    transform:scale(1.08);
}

.btn-edit {
    background:#fef3c7;
    color:#92400e;
}

.btn-delete {
    background:#fee2e2;
    color:#b91c1c;
}

.btn-toggle {
    background:#e0e7ff;
    color:#3730a3;
}

.btn-toggle:hover {
    background:#c7d2fe;
}

.inactivo-row {
    opacity: 0.6;
}
.acciones-th,
.acciones-td {
    width: 150px !important;
    min-width: 150px !important;
    max-width: 150px !important;
    text-align: center !important;
    vertical-align: middle !important;
}

.acciones-wrap {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.btn-action {
    width: 32px;
    height: 32px;
    min-width: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    margin: 0;
    text-decoration: none;
    line-height: 1;
    transition: .2s;
}

</style>
</head>

<body>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h1 class="h3 page-title">
            <i class="fas fa-users mr-2"></i> Usuarios
        </h1>
        <div class="page-subtitle">Gestión de usuarios del sistema</div>
    </div>

    <a href="create.php" class="btn btn-purple">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>
</div>

<form method="GET" class="filter-card">
    <div class="row align-items-end">

        <div class="col-md-10">
            <label>Buscar</label>
            <input 
                type="text"
                name="buscar"
                class="form-control"
                placeholder="Buscar por usuario, email o perfil"
                value="<?= htmlspecialchars($buscar) ?>"
            >
        </div>

        <div class="col-md-2">
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
    <th>Usuario</th>
    <th>Email</th>
    <th class="text-center">Perfil</th>
    <th class="text-center">Estado</th>
    <th class="text-center acciones-th">Acciones</th>
</tr>
</thead>

<tbody>

<?php if ($usuarios && $usuarios->num_rows > 0) { ?>
<?php while ($user = $usuarios->fetch_object()) { ?>
<tr class="<?= $user->estado == 0 ? 'inactivo-row' : '' ?>">

<td>
    <div class="d-flex align-items-center">
        <span class="user-icon">
            <i class="fas fa-user"></i>
        </span>

        <div>
            <div class="user-name">
                <?= htmlspecialchars($user->usuario) ?>
            </div>
            <div class="user-id">
                #<?= $user->id_usuario ?>
            </div>
        </div>
    </div>
</td>

<td class="dato-muted">
    <?= htmlspecialchars($user->email) ?>
</td>

<td class="text-center">
    <span class="perfil-badge">
        <?= htmlspecialchars($user->nombre_perfil) ?>
    </span>
</td>

<td class="text-center">
    <?php if ($user->estado == 1) { ?>
        <span class="badge-estado activo">Activo</span>
    <?php } else { ?>
        <span class="badge-estado inactivo">Inactivo</span>
    <?php } ?>
</td>

<td class="acciones-td">
    <div class="acciones-wrap">
        <a href="change_status.php?id=<?= $user->id_usuario ?>" 
           class="btn-action btn-toggle"
           title="Cambiar estado"
           onclick="return confirm('¿Seguro que desea cambiar el estado de este usuario?')">
            <i class="<?= $user->estado == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off' ?>"></i>
        </a>

        <a href="edit.php?id=<?= $user->id_usuario ?>" 
           class="btn-action btn-edit"
           title="Modificar">
            <i class="fas fa-pen"></i>
        </a>

        <a href="delete.php?id=<?= $user->id_usuario ?>"
           class="btn-action btn-delete"
           title="Eliminar"
           onclick="return confirm('¿Seguro que desea eliminar este usuario?')">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</td>

</tr>
<?php } ?>
<?php } else { ?>

<tr>
<td colspan="5" class="text-center text-muted py-4">
    <i class="fas fa-search mr-1"></i>
    No se encontraron usuarios.
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>
</div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>