<?php
require_once '../../settings/conexion.php';

$mensaje = "";

if ($_POST) {
    $nombre_especie = $_POST['nombre_especie'] ?? '';
    $raza = $_POST['raza'] ?? '';

    if (!empty($nombre_especie) && !empty($raza)) {

        $stmt = $conexion->prepare("
            INSERT INTO especie (nombre_especie, raza) 
            VALUES (?, ?)
        ");
        $stmt->bind_param("ss", $nombre_especie, $raza);

        if ($stmt->execute()) {
            $mensaje = "ok";
        } else {
            $mensaje = "error";
        }

        $stmt->close();
    }
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Alta Especie</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>

.page-title {
    font-weight: 800;
    color: #1f2937;
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

.form-group label {
    color: #52266E;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #d8c2e8;
}

.form-control:focus {
    border-color: #52266E;
    box-shadow: 0 0 0 .2rem rgba(82,38,110,.15);
}

.btn-purple {
    background: #52266E;
    color: white;
    border-radius: 8px;
    font-weight: 600;
}

.btn-purple:hover {
    background: #3f1d55;
}

.alert-ok {
    background: #dcfce7;
    color: #166534;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}

</style>

</head>

<body>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title">
            <i class="fas fa-dna mr-2"></i> Nueva Especie
        </h3>
        <div class="page-subtitle">Registro de especie y raza</div>
    </div>

    
</div>

<div class="form-card">

<?php if ($mensaje == "ok") { ?>
    <div class="alert-ok">
        <i class="fas fa-check-circle"></i> Especie registrada correctamente
    </div>
<?php } ?>

<?php if ($mensaje == "error") { ?>
    <div class="alert-error">
        <i class="fas fa-times-circle"></i> Error al registrar
    </div>
<?php } ?>

<form method="POST">

    <div class="form-group mb-3">
        <label>Nombre de Especie</label>
        <input type="text" name="nombre_especie" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label>Raza</label>
        <input type="text" name="raza" class="form-control" required>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-purple">
            <i class="fas fa-save"></i> Guardar Especie
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