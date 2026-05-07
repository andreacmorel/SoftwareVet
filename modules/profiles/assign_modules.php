<?php
require_once '../../settings/conexion.php';
require_once '../../php/menu.php';

$id_perfil = (int)($_GET['id'] ?? 0);

$perfil = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE id_perfil = $id_perfil
")->fetch_object();

if (!$perfil) {
    echo "<script>alert('Perfil no encontrado'); window.location.href='listadoPerfil.php';</script>";
    exit;
}

if (!empty($_POST['btnGuardar'])) {

    $conexion->query("
        DELETE FROM perfil_modulo
        WHERE id_perfil = $id_perfil
    ");

    if (!empty($_POST['modulos'])) {
        foreach ($_POST['modulos'] as $id_modulo) {
            $id_modulo = (int)$id_modulo;

            $conexion->query("
                INSERT INTO perfil_modulo (id_perfil, id_modulo)
                VALUES ($id_perfil, $id_modulo)
            ");
        }
    }

    echo "<script>
        alert('Módulos asignados correctamente');
        window.location.href='index.php';
    </script>";
    exit;
}

$modulos = $conexion->query("
    SELECT id_modulo, nombre_modulo, ruta
    FROM modulo
    WHERE estado = 1
    ORDER BY nombre_modulo
");

$asignados = [];

$resAsignados = $conexion->query("
    SELECT id_modulo
    FROM perfil_modulo
    WHERE id_perfil = $id_perfil
");

while ($row = $resAsignados->fetch_object()) {
    $asignados[] = $row->id_modulo;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Asignar Módulos</title>

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

        .perfil-badge {
            display: inline-block;
            background: #f0e6f6;
            color: #52266E;
            font-weight: 800;
            font-size: 15px;
            padding: 6px 16px;
            border-radius: 20px;
            margin-left: 8px;
        }

        .modulos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 14px;
            margin-top: 20px;
        }

        .modulo-item {
            background: white;
            border: 2px solid #eee1f6;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            transition: all .2s;
        }

        .modulo-item:hover {
            border-color: #52266E;
            background: #faf5ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(82,38,110,.1);
        }

        .modulo-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            margin-top: 2px;
            accent-color: #52266E;
            cursor: pointer;
        }

        .modulo-item.checked {
            border-color: #52266E;
            background: #faf5ff;
        }

        .modulo-nombre {
            font-weight: 700;
            color: #1f2937;
            font-size: 14px;
        }

        .modulo-ruta {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 3px;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            padding: 28px 30px;
        }

        .section-label {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: #52266E;
            margin-bottom: 4px;
        }

        .btn-guardar {
            background: #52266E;
            color: white;
            border-radius: 8px;
            font-weight: 700;
            padding: 10px 24px;
            border: none;
            transition: background .2s;
        }

        .btn-guardar:hover {
            background: #3f1d55;
            color: white;
        }

        .btn-cancelar {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            background: white;
        }

        .btn-cancelar:hover {
            background: #f9fafb;
            color: #374151;
        }

        .actions-bar {
            display: flex;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-lock mr-2"></i> Asignar Módulos
            </h1>
            <div class="page-subtitle">Seleccioná los módulos que tendrá acceso este perfil</div>
        </div>
    </div>

    <div class="form-card">

        <div class="section-label">Perfil seleccionado</div>
        <div class="mb-4">
            <span class="perfil-badge">
                <i class="fas fa-user-shield mr-1"></i>
                <?= htmlspecialchars($perfil->nombre_perfil) ?>
            </span>
        </div>

        <div class="section-label">Módulos disponibles</div>

        <form method="POST">

            <div class="modulos-grid">
                <?php while ($modulo = $modulos->fetch_object()) {
                    $checked = in_array($modulo->id_modulo, $asignados);
                ?>
                    <label class="modulo-item <?= $checked ? 'checked' : '' ?>" for="modulo<?= $modulo->id_modulo ?>">
                        <input type="checkbox"
                               name="modulos[]"
                               value="<?= $modulo->id_modulo ?>"
                               id="modulo<?= $modulo->id_modulo ?>"
                               <?= $checked ? 'checked' : '' ?>>

                        <div>
                            <div class="modulo-nombre">
                                <?= htmlspecialchars($modulo->nombre_modulo) ?>
                            </div>
                         <!--   <div class="modulo-ruta">
                                <i class="fas fa-link mr-1"></i>
                                <?= htmlspecialchars($modulo->ruta) ?>
                            </div>-->
                        </div>
                    </label>
                <?php } ?>
            </div>

            <div class="actions-bar">
                 <a href="index.php" class="btn btn-cancelar">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                
                <button type="submit" name="btnGuardar" value="1" class="btn btn-guardar">
                    <i class="fas fa-save mr-1"></i> Guardar asignación
                </button>
            </div>

        </form>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
    document.querySelectorAll('.modulo-item input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            this.closest('.modulo-item').classList.toggle('checked', this.checked);
        });
    });
</script>

</body>
</html>