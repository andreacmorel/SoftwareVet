<?php

require_once __DIR__ . '/../../settings/conexion.php';
require_once '../../app/menu.php';

$modulo = $_GET['modulo'] ?? '';

$where = "";

if (!empty($modulo)) {
    $moduloSeguro = $conexion->real_escape_string($modulo);
    $where = "WHERE a.modulo = '$moduloSeguro'";
}

// Trae auditorías
$sql = "SELECT 
            a.*,
            CONCAT(u.nombre, ' ', u.apellido) AS nombre_usuario,
            m.nombre_mascota
        FROM auditoria a
        INNER JOIN usuario u 
            ON a.id_usuario = u.id_usuario
        LEFT JOIN mascota m
            ON a.modulo = 'Mascotas'
            AND a.id_registro = m.id_mascota
        $where
        ORDER BY a.fecha DESC";
        
$resultado = mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Auditoría</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/SoftwareVet/css/index_style.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-clipboard-list mr-2"></i>
                Auditoría
            </h1>

            <div class="page-subtitle">
                Historial de cambios realizados en el sistema
            </div>
        </div>

    </div>


    <!-- FILTRO -->
    <form method="GET" class="filter-card">

        <div class="row align-items-end">

            <div class="col-md-4">

                <label>Módulo</label>

                <select name="modulo" class="form-control">

                    <option value="">Todos</option>

                    <option value="Mascotas"
                        <?= ($modulo == 'Mascotas') ? 'selected' : '' ?>>
                        Mascotas
                    </option>

                    <option value="Turnos"
                        <?= ($modulo == 'Turnos') ? 'selected' : '' ?>>
                        Turnos
                    </option>

                </select>

            </div>


            <div class="col-md-2">

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-filter"></i>
                </button>

            </div>

        </div>

    </form>


    <!-- TABLA -->
    <div class="table-card">

        <div class="table-responsive">

            <table class="table table-hover" width="100%">

                <thead>

                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Módulo</th>
                        <th>Acción</th>
                        <th>Registro</th>
                        <th>Antes</th>
                        <th>Después</th>
                    </tr>

                </thead>

                <tbody>

                <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>

                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>

                        <tr>

                            <!-- FECHA -->
                            <td>

                                <div class="d-flex align-items-center">

                                    <span class="turno-icon">
                                        <i class="fas fa-clock"></i>
                                    </span>

                                    <div>

                                        <div class="turno-date">
                                            <?= date('d/m/Y', strtotime($fila['fecha'])) ?>
                                        </div>

                                        <div class="turno-hour">
                                            <?= date('H:i', strtotime($fila['fecha'])) ?> hs
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <!-- USUARIO -->
                            <td>

                                <div class="user-name">
                                    <?= htmlspecialchars($fila['nombre_usuario']) ?>
                                </div>

                            </td>


                            <!-- MODULO -->
                            <td>

                                <span class="badge-total">

                                    <?php if ($fila['modulo'] == 'Mascotas'): ?>

                                        <i class="fas fa-paw mr-1"></i>

                                    <?php elseif ($fila['modulo'] == 'Turnos'): ?>

                                        <i class="fas fa-calendar-check mr-1"></i>

                                    <?php endif; ?>

                                    <?= htmlspecialchars($fila['modulo']) ?>

                                </span>

                            </td>


                            <!-- ACCION -->
                            <td>

                                <strong>
                                    <?= htmlspecialchars($fila['accion']) ?>
                                </strong>

                            </td>


                            <!-- REGISTRO -->
                            <td>

                                <?php if ($fila['modulo'] == 'Mascotas'): ?>

                                    <div class="pet-name">
                                        <?= htmlspecialchars($fila['nombre_mascota']) ?>
                                    </div>

                                <?php else: ?>

                                    <div class="dato-valor">
                                        Turno #<?= htmlspecialchars($fila['id_registro']) ?>
                                    </div>

                                <?php endif; ?>

                            </td>


                            <!-- ANTES -->
                            <td class="dato-muted">
                                <?= htmlspecialchars($fila['datos_anteriores']) ?>
                            </td>


                            <!-- DESPUES -->
                            <td class="dato-muted">
                                <?= htmlspecialchars($fila['datos_nuevos']) ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>


                <?php else: ?>

                    <tr>

                        <td colspan="7" class="text-center text-muted py-4">

                            <i class="fas fa-search mr-1"></i>

                            No se encontraron registros de auditoría.

                        </td>

                    </tr>

                <?php endif; ?>

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