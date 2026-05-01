<?php
require_once '../../config/conexion.php';
require_once '../../php/menu.php';

$id = $_GET['id'];

$sql = "SELECT 
            m.*,
            e.nombre_especie,
            e.raza,
            p.nombre_persona,
            p.apellido_persona,
            p.telefono,
            p.email
        FROM mascota m
        INNER JOIN cliente c ON m.id_cliente = c.id_cliente
        INNER JOIN persona p ON c.id_persona = p.id_persona
        INNER JOIN especie e ON m.id_especie = e.id_especie
        WHERE m.id_mascota = $id";

$result = mysqli_query($conexion, $sql);
$mascota = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ficha Mascota</title>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Ficha de Mascota</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background-color:#52266E;">
            <h6 class="m-0 font-weight-bold text-white">Datos de la Mascota</h6>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <p><b>Nombre:</b> <?php echo $mascota['nombre_mascota']; ?></p>
                    <p><b>Fecha de nacimiento:</b> <?php echo $mascota['fecha_nacimiento']; ?></p>
                    <p><b>Sexo:</b> <?php echo $mascota['sexo']; ?></p>
                    <p><b>Peso:</b> <?php echo $mascota['peso']; ?> kg</p>
                </div>

                <div class="col-md-6">
                    <p><b>Color:</b> <?php echo $mascota['color']; ?></p>
                    <p><b>Edad:</b> <?php echo $mascota['edad']; ?></p>
                    <p><b>Especie:</b> <?php echo $mascota['nombre_especie']; ?></p>
                    <p><b>Raza:</b> <?php echo $mascota['raza']; ?></p>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background-color:#52266E;">
            <h6 class="m-0 font-weight-bold text-white">Datos del Dueño</h6>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <p><b>Nombre:</b> <?php echo $mascota['nombre_persona'] . " " . $mascota['apellido_persona']; ?></p>
                    <p><b>Teléfono:</b> <?php echo $mascota['telefono']; ?></p>
                </div>

                <div class="col-md-6">
                    <p><b>Email:</b> <?php echo $mascota['email']; ?></p>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end" style="gap: 12px;">
        <a href="listadoMascota.php" class="btn btn-secondary">
            Volver
        </a>

        <a href="fichaMascotaImprimir.php?id=<?php echo $id; ?>" target="_blank" class="btn btn-primary" title="Imprimir ficha">
        <i class="fas fa-print mr-1"></i> Imprimir
        </a>
    </div>

</div>

</body>
</html>