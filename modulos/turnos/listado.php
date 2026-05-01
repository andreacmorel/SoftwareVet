<?php
require_once '../../config/conexion.php';
require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Turnos</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
    thead th {
        background-color: #52266E !important;
        color: white !important;
    }
    
   
</style>

</head>

<body>
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">Listado de Turnos</h1>

        <div class="d-flex justify-content-between mb-3">
        <a href="alta.php" class="btn btn-success" title="Agregar turno">
            <i class="fas fa-plus"></i> Agregar
        </a>

        <button class="btn btn-secondary btn-round" onclick="window.location.href='reporte_excel.php'" title="Imprimir excel">
    <i class="fas fa-file-excel"></i> 
    </div>

        <div class="card shadow mb-4">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                              <!--  <th>ID</th>-->
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Motivo</th>
                                <th>Profesional</th>
                                <th>Mascota</th>
                                <th style="width:150px;">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sql = $conexion->query("
                                SELECT t.id_turno, t.fecha, t.hora, t.motivo,
                                CONCAT(per.nombre_persona, ' ', per.apellido_persona) AS profesional,
                                m.nombre_mascota AS mascota
                                FROM turnos t
                                INNER JOIN profesional p ON t.id_profesional = p.id_profesional
                                INNER JOIN persona per ON p.id_persona = per.id_persona
                                INNER JOIN mascota m ON t.id_mascota = m.id_mascota
                                ORDER BY t.fecha DESC, t.hora DESC
                            ");

                            while ($datos = $sql->fetch_object()) { ?>
                                <tr>
                                  <!--  <td><?= $datos->id_turno ?></td>-->
                                    <td><?= $datos->fecha ?></td>
                                    <td><?= $datos->hora ?></td>
                                    <td><?= $datos->motivo ?></td>
                                    <td><?= $datos->profesional ?></td>
                                    <td><?= $datos->mascota ?></td>

                                    <td>
                                        <a href="modificar.php?id=<?= $datos->id_turno ?>" 
                                           class="btn btn-warning btn-circle ">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <a href="eliminar.php?id=<?= $datos->id_turno ?>" 
                                           class="btn btn-danger btn-circle"
                                           onclick="return confirm('¿Seguro que desea eliminar este turno?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        

                    </table>
                </div>

            </div>
        </div>

    </div>

    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>
