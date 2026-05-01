<?php
require_once '../../config/conexion.php';

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
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <title>Imprimir Ficha Mascota</title>

<style>
    .dato {
    color: #000;
    font-size: 15px;
}

.dato b {
    color: #000;
} 
@media print {
    body {
        background: white !important;
    }

    .acciones {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: none !important;
    }

    .container {
        margin-top: 0 !important;
    }
    
}
</style>
</head>

<body style="background:#f8f9fc;">

<div class="container mt-5 mb-5">

    <div class="card shadow">
        <div class="card-body p-5">

            <div class="d-flex justify-content-between align-items-center">
                <img src="../../img/logoFicha.png" style="width:75px;">

                <div class="text-right">
                    <h2 style="color:#52266E; margin:0;"><b>VetSys</b></h2>
                    <p style="margin:0;">Software Veterinario</p>
                </div>
            </div>

            <hr style="border-top:3px solid #52266E;">

            <h4 style="color:#52266E;"><b>Datos de la Mascota</b></h4>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <p class="dato"><b>Nombre:</b> <?php echo $mascota['nombre_mascota']; ?></p>
                    <p class="dato"><b>Fecha de nacimiento:</b> <?php echo $mascota['fecha_nacimiento']; ?></p>
                    <p class="dato"><b>Sexo:</b> <?php echo $mascota['sexo']; ?></p>
                    <p class="dato"><b>Peso:</b> <?php echo $mascota['peso']; ?> kg</p>
                </div>

                <div class="col-md-6">
                    <p class="dato"><b>Color:</b> <?php echo $mascota['color']; ?></p>
                    <p class="dato"><b>Edad:</b> <?php echo $mascota['edad']; ?></p>
                    <p class="dato"><b>Especie:</b> <?php echo $mascota['nombre_especie']; ?></p>
                    <p class="dato"><b>Raza:</b> <?php echo $mascota['raza']; ?></p>
                </div>
            </div>

            <br>

            <h4 style="color:#52266E;"><b>Datos del Dueño</b></h4>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <p class="dato"><b>Nombre:</b> <?php echo $mascota['nombre_persona'] . " " . $mascota['apellido_persona']; ?></p>
                    <p class="dato"><b>Teléfono:</b> <?php echo $mascota['telefono']; ?></p>
                </div>

                <div class="col-md-6">
                    <p class="dato"><b>Email:</b> <?php echo $mascota['email']; ?></p>
                </div>
            </div>

            <div class="text-right mt-4 acciones">
                <a href="fichaMascota.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                    Volver
                </a>

                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimir
                </button> 
            </div>

        </div>
    </div>
</div>

</body>
</html>