<?php
require_once '../../config/conexion.php';

if ($_POST) {
$nombre_especie = $_POST['nombre_especie'];
$raza = $_POST['raza'];

$sql = "INSERT INTO especie (nombre_especie, raza) 
        VALUES ('$nombre_especie', '$raza')";

if (mysqli_query($conexion, $sql)) {
    echo "Especie registrada correctamente";
} else {
    echo "Error al registrar: " . mysqli_error($conexion);
}
}
$sqlEspecies = "SELECT id_especie, nombre_especie, raza FROM especie";
$resEspecies = mysqli_query($conexion, $sqlEspecies);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Especie</title>
    <link href="public/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Registro</h4>
                </div>

                <div class="card-body">

                    <form action="alta.php" method="POST">

                        <div class="form-group mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Raza</label>
                            <input type="text" name="raza" class="form-control" required>
                        </div>


                        <button type="submit" class="btn btn-primary w-100">Guardar</button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
