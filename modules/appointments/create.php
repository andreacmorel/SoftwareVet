<?php

require_once '../../settings/conexion.php';

$sqlProfesionales = "SELECT p.id_profesional, per.nombre_persona, per.apellido_persona
                     FROM profesional p
                     INNER JOIN persona per ON p.id_persona = per.id_persona";

$resProfesionales = mysqli_query($conexion, $sqlProfesionales);

$sqlMascotas = "SELECT id_mascota, nombre_mascota FROM mascota";

$resMascotas = mysqli_query($conexion, $sqlMascotas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];
    $id_profesional = $_POST['id_profesional'];
    $id_mascota = $_POST['id_mascota'];

    $sqlInsert = "
        INSERT INTO turnos (fecha, hora, motivo, id_profesional, id_mascota)
        VALUES ('$fecha', '$hora', '$motivo', '$id_profesional', '$id_mascota')
    ";

    mysqli_query($conexion, $sqlInsert);

    header("Location: index.php");
    exit;
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Turnos</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/SoftwareVet/css/style_system.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-calendar-plus mr-2"></i>Registro de Turnos
    </h1>

    <div class="subtitulo-pagina">Completá los datos para registrar un nuevo turno.</div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>Nuevo Turno
            </h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <h5 class="section-title">
                    <i class="fas fa-calendar-check mr-2"></i>Datos del turno
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha"  min="<?= date('Y-m-d') ?>" required>
                    </div>  


                    <div class="form-group col-md-6">
                        <label for="hora">Hora</label>
                        <input type="time" class="form-control" id="hora" name="hora" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="motivo">Motivo</label>
                    <input type="text" class="form-control" id="motivo" name="motivo" required>
                </div>

                <hr>

                <h5 class="section-title">
                    <i class="fas fa-user-md mr-2"></i>
                    Profesional y paciente
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="id_profesional">Profesional</label>
                        <select name="id_profesional" id="id_profesional" class="form-control" required>
                            <option value="">Seleccione un profesional</option>

                            <?php while($p = mysqli_fetch_assoc($resProfesionales)) { ?>
                                <option value="<?php echo $p['id_profesional']; ?>">
                                    <?php echo $p['apellido_persona'] . ", " . $p['nombre_persona']; ?>
                                </option>
                            <?php } ?>

                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="id_mascota">Mascota</label>
                        <select name="id_mascota" id="id_mascota" class="form-control" required>
                            <option value="">Seleccione una mascota</option>

                            <?php while($m = mysqli_fetch_assoc($resMascotas)) { ?>
                                <option value="<?php echo $m['id_mascota']; ?>">
                                    <?php echo $m['nombre_mascota']; ?>
                                </option>
                            <?php } ?>

                        </select>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-purple">
                        <i class="fas fa-save mr-1"></i>
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>