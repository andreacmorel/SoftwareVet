<?php
require_once '../../config/conexion.php';
require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Especies</title>
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

    <h1 class="h3 mb-4 text-gray-800">Listado de Especies</h1>
    <div class="d-flex justify-content-between mb-3">

        <a href="altaEspecie.php" class="btn btn-success" title="Agregar especie">
            <i class="fas fa-plus"></i> Agregar
        </a>

        <button class="btn btn-secondary btn-round" onclick="window.location.href='reporte_excel.php'" title="Imprmir excel">
            <i class="fas fa-file-excel"></i>
        </button>

    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <!--<th>ID</th>-->
                            <th>Nombre</th>
                            <th>Raza</th>
                            <th style="width:150px;">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $sql = $conexion->query("
                           SELECT * FROM especie;
                        ");

                        while ($row = $sql->fetch_object()) { ?>
                            <tr>
                                <!--<td><?= $row->id_especie ?></td>-->
                                    <td><?= $row->nombre_especie ?></td>
                                    <td><?= $row->raza ?></td>

                                <td class="text-center">

                                    <a href="modificarEspecie.php?id=<?= $row->id_especie ?>"
                                       class="btn btn-warning btn-circle">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <a href="eliminarEspecie.php?id=<?= $row->id_especie?>"
                                       class="btn btn-danger btn-circle"
                                       onclick="return confirm('¿Seguro que desea eliminar esta especie?');">
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
