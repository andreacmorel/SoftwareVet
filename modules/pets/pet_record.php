<?php
require_once '../../settings/conexion.php';
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
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Ficha Mascota</title>

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

        .card-ficha {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .card-header-ficha {
            background: #fbf7ff;
            border-bottom: 1px solid #eee1f6;
            padding: 16px 22px;
        }

        .card-header-ficha h6 {
            color: #52266E;
            font-weight: 800;
            margin: 0;
        }

        .dato-item {
            padding: 10px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .dato-item:last-child {
            border-bottom: none;
        }

        .dato-label {
            color: #52266E;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .dato-valor {
            color: #374151;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-purple {
            background: #52266E;
            color: white;
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 18px;
        }

        .btn-purple:hover {
            background: #3f1d55;
            color: white;
        }

        .btn-volver {
            background: #e5e7eb;
            color: #374151;
            border-radius: 8px;
            font-weight: 700;
            padding: 8px 18px;
        }

        .btn-volver:hover {
            background: #d1d5db;
            color: #111827;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-paw mr-2"></i>
        Ficha de Mascota
    </h1>

    <div class="subtitulo-pagina">
        Información completa del paciente y su propietario.
    </div>

    <div class="card card-ficha mb-4">
        <div class="card-header-ficha">
            <h6>
                <i class="fas fa-dog mr-2"></i>
                Datos de la Mascota
            </h6>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Nombre</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['nombre_mascota']) ?></div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Fecha de nacimiento</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['fecha_nacimiento']) ?></div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Sexo</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['sexo']) ?></div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Peso</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['peso']) ?> kg</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Color</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['color']) ?></div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Edad</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['edad']) ?></div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Especie</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['nombre_especie']) ?></div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Raza</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['raza']) ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-ficha mb-4">
        <div class="card-header-ficha">
            <h6>
                <i class="fas fa-user mr-2"></i>
                Datos del Dueño
            </h6>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Nombre completo</div>
                        <div class="dato-valor">
                            <?= htmlspecialchars($mascota['nombre_persona'] . " " . $mascota['apellido_persona']) ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Teléfono</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['telefono']) ?></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Email</div>
                        <div class="dato-valor"><?= htmlspecialchars($mascota['email']) ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end" style="gap: 12px;">
        <a href="index.php" class="btn btn-volver">
            <i class="fas fa-arrow-left mr-1"></i>
            Volver
        </a>

        <a href="print_pet_record.php?id=<?= $id ?>" target="_blank" class="btn btn-purple" title="Imprimir ficha">
            <i class="fas fa-print mr-1"></i>
            Imprimir
        </a>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>