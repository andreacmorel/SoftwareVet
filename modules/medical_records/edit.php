<?php
// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Incluye la validación de acceso según la ruta/perfil
require_once '../../php/validateRoute.php';

// Obtiene el ID de la historia clínica desde la URL y lo convierte a entero
$id = (int)($_GET['id'] ?? 0);

// Si el ID no es válido, redirige al listado
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Consulta las mascotas disponibles para cargarlas en el select
$rMas = $conexion->query("
    SELECT m.id_mascota, m.nombre_mascota, p.apellido_persona, p.nombre_persona
    FROM mascota m
    INNER JOIN cliente c ON m.id_cliente = c.id_cliente
    INNER JOIN persona p ON c.id_persona = p.id_persona
    ORDER BY m.nombre_mascota ASC
");

// Array donde se guardarán las mascotas
$mascotas = [];

// Recorre el resultado y guarda cada mascota dentro del array
while ($rm = $rMas->fetch_assoc()) {
    $mascotas[] = $rm;
}

// Array donde se guardarán los errores generales
$errors = [];

// Prepara la consulta para buscar la historia clínica por ID
$stmt = $conexion->prepare("
    SELECT id_historia_clinica, fecha, descripcion, observacion, id_mascota
    FROM historia_clinica
    WHERE id_historia_clinica = ?
");

// Vincula el ID recibido a la consulta preparada
$stmt->bind_param("i", $id);

// Ejecuta la consulta
$stmt->execute();

// Obtiene los datos de la historia clínica
$historia = $stmt->get_result()->fetch_assoc();

// Cierra la consulta preparada
$stmt->close();

// Si no encuentra la historia clínica, redirige al listado
if (!$historia) {
    header("Location: index.php");
    exit;
}

// Verifica si el formulario fue enviado por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibe los datos enviados desde el formulario
    $idMascota   = (int)($_POST['id_mascota'] ?? 0);
    $fecha       = trim($_POST['fecha'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $observacion = trim($_POST['observacion'] ?? '');

    // Valida que se haya seleccionado una mascota
    if ($idMascota <= 0) {
        $errors[] = 'Debe seleccionar una mascota.';
    }

    // Valida que la fecha no esté vacía
    if ($fecha === '') {
        $errors[] = 'La fecha es obligatoria.';
    }

    // Si no hay errores, modifica la historia clínica
    if (empty($errors)) {

        // Prepara la consulta UPDATE
        $stmt = $conexion->prepare("
            UPDATE historia_clinica
            SET fecha = ?, descripcion = ?, observacion = ?, id_mascota = ?
            WHERE id_historia_clinica = ?
        ");

        // Vincula los datos a la consulta preparada
        $stmt->bind_param("sssii", $fecha, $descripcion, $observacion, $idMascota, $id);

        // Ejecuta la actualización
        $stmt->execute();

        // Cierra la consulta
        $stmt->close();
        //$stmt significa statement (sentencia o consulta preparada).
        //Es una variable que guarda una consulta SQL preparada para ejecutarla de forma segura.

        // Redirige al listado con mensaje de modificación exitosa
        header("Location: index.php?ok=modificado");
        exit;
    }

    // Si hubo errores, conserva los datos ingresados en el formulario
    $historia['id_mascota'] = $idMascota;
    $historia['fecha'] = $fecha;
    $historia['descripcion'] = $descripcion;
    $historia['observacion'] = $observacion;
}

// Incluye el menú principal del sistema
require_once '../../php/menu.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificar Historia Clínica</title>

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

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            margin-top: 25px;
        }

        .section-title {
            color: #52266E;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 18px;
            border-bottom: 1px solid #eee1f6;
            padding-bottom: 8px;
        }

        .form-group label {
            color: #52266E;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d8c2e8;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #52266E;
            box-shadow: 0 0 0 .2rem rgba(82, 38, 110, .15);
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

        .btn-light-pro {
            background: #f8f9fc;
            color: #6b7280;
            border-radius: 8px;
            font-weight: 600;
        }

        .alert-pro {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 10px;
            padding: 12px 15px;
            font-weight: 600;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-notes-medical mr-2"></i> Modificar Historia Clínica
            </h1>
            <div class="page-subtitle">Editar datos clínicos registrados</div>
        </div>
        <a href="index.php" class="btn btn-light-pro">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">
        <?php if (!empty($errors)) { ?>
            <div class="alert-pro">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Revisá los siguientes campos:
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $e) { ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form method="POST">
            <div class="section-title">
                <i class="fas fa-paw mr-1"></i> Datos de la consulta
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Mascota</label>
                        <select name="id_mascota" class="form-control" required>
                            <option value="">Seleccione una mascota</option>

                            <?php foreach ($mascotas as $m) { ?>
                                <option value="<?= $m['id_mascota'] ?>"
                                    <?= $historia['id_mascota'] == $m['id_mascota'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nombre_mascota'] . ' - ' . $m['apellido_persona'] . ', ' . $m['nombre_persona']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control"value="<?= htmlspecialchars($historia['fecha']) ?>"required>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                <i class="fas fa-clipboard-list mr-1"></i> Notas clínicas
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"
                ><?= htmlspecialchars($historia['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Observación</label>
                <textarea name="observacion" class="form-control" rows="3"
                ><?= htmlspecialchars($historia['observacion'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="index.php" class="btn btn-light-pro mr-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
            </div>

        </form>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>