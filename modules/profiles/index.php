<?php
require_once '../../settings/conexion.php';
require_once '../../php/menu.php';

$buscar = trim($_GET['buscar'] ?? '');
$whereBuscar = "";

if (!empty($buscar)) {
    $buscarSeguro = $conexion->real_escape_string($buscar);

    $whereBuscar = " AND nombre_perfil LIKE '%$buscarSeguro%'";
}

$sql = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE estado = 1
    $whereBuscar
    ORDER BY id_perfil DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Perfiles</title>

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

        .perfil-icon {
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

        .perfil-name {
            font-weight:800;
            color:#111827;
        }

        .perfil-id {
            color:#9ca3af;
            font-size:12px;
        }

        .btn-action {
            width:31px;
            height:31px;
            border-radius:8px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border:none;
            margin:0 2px;
            text-decoration:none;
        }

        .btn-modulos {
            background:#e8f4f8;
            color:#0c7f9e;
        }

        .btn-edit {
            background:#fef3c7;
            color:#92400e;
        }

        .btn-delete {
            background:#fee2e2;
            color:#b91c1c;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-user-tag mr-2"></i> Perfiles
            </h1>
            <div class="page-subtitle">Gestión de perfiles y permisos del sistema</div>
        </div>

        <a href="create.php" class="btn btn-purple">
            <i class="fas fa-plus"></i> Nuevo Perfil
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
                    placeholder="Buscar por nombre de perfil"
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
                        <th>Perfil</th>
                        <th class="text-center" style="width:150px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($sql && $sql->num_rows > 0) { ?>
                        <?php while ($row = $sql->fetch_object()) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="perfil-icon">
                                            <i class="fas fa-user-tag"></i>
                                        </span>

                                        <div>
                                            <div class="perfil-name">
                                                <?= htmlspecialchars($row->nombre_perfil) ?>
                                            </div>
                                            <div class="perfil-id">
                                                #<?= $row->id_perfil ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <a href="assign_modules.php?id=<?= $row->id_perfil ?>"
                                       class="btn-action btn-modulos"
                                       title="Asignar módulos">
                                        <i class="fas fa-lock"></i>
                                    </a>

                                    <a href="edit.php?id=<?= $row->id_perfil ?>"
                                       class="btn-action btn-edit"
                                       title="Modificar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <a href="delete.php?id=<?= $row->id_perfil ?>"
                                       class="btn-action btn-delete"
                                       title="Eliminar"
                                       onclick="return confirm('¿Seguro que desea dar de baja este perfil?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i>
                                No se encontraron perfiles.
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