<?php
require_once __DIR__ . '/../../config/conexion.php';

$nombre = $_POST['nombre'];
$fecha = $_POST['fecha_nacimiento'];
$sexo = $_POST['sexo'];
$peso = $_POST['peso'];
$color = $_POST['color'];   
$id_especie = $_POST['id_especie'];
$id_cliente = $_POST['id_cliente'];

    $sql = "INSERT INTO mascota 
            (nombre_mascota, fecha_nacimiento, sexo, peso, color, id_especie, id_cliente)
            VALUES 
            ('$nombre', '$fecha', '$sexo', '$peso', '$color', '$id_especie', '$id_cliente')";

    if (mysqli_query($conexion, $sql)) {
        echo "Mascota registrada correctamente";
    } else {
        echo "Error al registrar: " . mysqli_error($conexion);
    }


$sqlClientes = "SELECT cliente.id_cliente, persona.nombre, persona.apellido FROM cliente
               INNER JOIN persona ON persona.id_persona = cliente.id_persona";
               
$resClientes = mysqli_query($conexion, $sqlClientes);

$sqlEspecies = "SELECT id_especie, nombre_especie, raza FROM especie";
$resEspecies = mysqli_query($conexion, $sqlEspecies);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Mascota</title>
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
                            <label>Fecha Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Sexo</label>
                            <input type="text" name="sexo" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Peso</label>
                            <input type="text" name="peso" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Color</label>
                            <input type="text" name="color" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Especie</label>
                            <select name="id_especie" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php while ($row = mysqli_fetch_assoc($resEspecies)): ?>
                                    <option value="<?= $row['id_especie'] ?>">
                                        <?= $row['nombre_especie'] ?> - <?= $row['raza'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Cliente</label>
                            <select name="id_cliente" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php while ($row = mysqli_fetch_assoc($resClientes)): ?>
                                    <option value="<?= $row['id_cliente'] ?>">
                                        <?= $row['nombre'] ?> <?= $row['apellido'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
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
