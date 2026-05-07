<?php
require_once '../../settings/conexion.php';

$id_turno = $_GET["id"];

$sql = $conexion->query("
    SELECT 
        t.id_turno,
        t.fecha,
        t.hora,
        t.motivo,
        t.id_profesional,
        t.id_mascota
    FROM turnos t
    WHERE t.id_turno = $id_turno
");

$datos = $sql->fetch_object();

$profesionales = $conexion->query("
    SELECT p.id_profesional, CONCAT(per.nombre_persona, ' ', per.apellido_persona) AS nombre
    FROM profesional p
    INNER JOIN persona per ON per.id_persona = p.id_persona
");

$mascotas = $conexion->query("
    SELECT id_mascota, nombre_mascota 
    FROM mascota
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    $motivo = $_POST["motivo"];
    $id_profesional = $_POST["id_profesional"];
    $id_mascota = $_POST["id_mascota"];

    $conexion->query("
        UPDATE turnos 
        SET fecha='$fecha',
            hora='$hora',
            motivo='$motivo',
            id_profesional='$id_profesional',
            id_mascota='$id_mascota'
        WHERE id_turno = $id_turno
    ");

    header("Location: index.php");
    exit;
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificar Turno</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .titulo-pagina {
            font-weight: 800;
            color: #1f2937;
        }

        .titulo-pagina i {
            color: #52266E;
        }

        .subtitulo-pagina {
            color: #9ca3af;
            font-size: 14px;
            margin-top: -8px;
            margin-bottom: 25px;
        }

        .card-form {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .card-header-form {
            background: #fbf7ff;
            border-bottom: 1px solid #eee1f6;
            padding: 18px 22px;
        }

        .card-header-form h5 {
            color: #52266E;
            font-weight: 800;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        label {
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
            box-shadow: 0 0 0 3px rgba(82,38,110,.12);
        }

        .section-title {
            color: #52266E;
            font-weight: 800;
            font-size: 15px;
            margin-bottom: 18px;
        }

        .btn-purple {
            background: #52266E;
            color: white;
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 22px;
        }

        .btn-purple:hover {
            background: #3f1d55;
            color: white;
        }

        .btn-cancelar {
            background: #e5e7eb;
            color: #374151;
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 22px;
        }

        .btn-cancelar:hover {
            background: #d1d5db;
            color: #111827;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-calendar-edit mr-2"></i>
        Modificar Turno
    </h1>

    <div class="subtitulo-pagina">
        Actualizá los datos del turno seleccionado.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Editar Turno
            </h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <h5 class="section-title">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Datos del turno
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="fecha">Fecha</label>
                        <input type="date"
                               class="form-control"
                               id="fecha"
                               name="fecha"
                               value="<?= htmlspecialchars($datos->fecha) ?>"
                               required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="hora">Hora</label>
                        <input type="time"
                               class="form-control"
                               id="hora"
                               name="hora"
                               value="<?= htmlspecialchars($datos->hora) ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="motivo">Motivo</label>
                    <input type="text"
                           class="form-control"
                           id="motivo"
                           name="motivo"
                           value="<?= htmlspecialchars($datos->motivo) ?>">
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

                            <?php while ($p = $profesionales->fetch_object()) { ?>
                                <option value="<?= $p->id_profesional ?>"
                                    <?= ($p->id_profesional == $datos->id_profesional) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p->nombre) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="id_mascota">Mascota</label>
                        <select name="id_mascota" id="id_mascota" class="form-control" required>
                            <option value="">Seleccione una mascota</option>

                            <?php while ($m = $mascotas->fetch_object()) { ?>
                                <option value="<?= $m->id_mascota ?>"
                                    <?= ($m->id_mascota == $datos->id_mascota) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m->nombre_mascota) ?>
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
                        Guardar cambios
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