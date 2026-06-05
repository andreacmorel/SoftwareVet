<?php

// Incluye la conexión a la base de datos
require_once '../../settings/conexion.php';

// Valida que el usuario tenga permisos para acceder a esta ruta
require_once '../../php/validateRoute.php';

// Incluye el menú principal del sistema
require_once '../../php/menu.php';

// Obtiene el ID de la mascota enviado por la URL y lo convierte a entero
$id = (int) $_GET['id'];

// Consulta para obtener toda la información de la mascota,
// incluyendo datos del cliente propietario y la especie
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

// Ejecuta la consulta
$result = mysqli_query($conexion, $sql);

// Obtiene los datos de la mascota en un arreglo asociativo
$mascota = mysqli_fetch_assoc($result);

// Si no encuentra la mascota, detiene la ejecución
if (!$mascota) {
    die("Mascota no encontrada");
}

// =====================================================
// FORMATEO DE DATOS PARA MOSTRAR EN LA FICHA
// =====================================================

// Si existe fecha de nacimiento y no es una fecha inválida,
// la convierte al formato dd/mm/aaaa.
// Caso contrario muestra "No registrada".
$fechaNacimiento = (!empty($mascota['fecha_nacimiento']) && $mascota['fecha_nacimiento'] != '0000-00-00')
    ? date('d/m/Y', strtotime($mascota['fecha_nacimiento']))
    : 'No registrada';

// Si existe color lo muestra protegido con htmlspecialchars,
// caso contrario muestra un texto por defecto
$color = !empty($mascota['color'])
    ? htmlspecialchars($mascota['color'])
    : 'Sin especificar';

// Si existe edad y es mayor a 0, agrega la palabra "años"
// caso contrario muestra "No registrada"
$edad = (!empty($mascota['edad']) && $mascota['edad'] > 0)
    ? htmlspecialchars($mascota['edad']) . ' años'
    : 'No registrada';

// Si existe peso y es mayor a 0, agrega la unidad "kg"
// caso contrario muestra "No registrado"
$peso = (!empty($mascota['peso']) && $mascota['peso'] > 0)
    ? htmlspecialchars($mascota['peso']) . ' kg'
    : 'No registrado';

// Si existe teléfono lo muestra protegido con htmlspecialchars
// caso contrario muestra un mensaje por defecto
$telefono = !empty($mascota['telefono'])
    ? htmlspecialchars($mascota['telefono'])
    : 'Sin teléfono';

// Si existe email lo muestra protegido con htmlspecialchars
// caso contrario muestra un mensaje por defecto
$email = !empty($mascota['email'])
    ? htmlspecialchars($mascota['email'])
    : 'Sin email';

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
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .card-header-ficha {
            background: #fbf7ff;
            border-bottom: 1px solid #eee1f6;
            padding: 18px 22px;
        }

        .card-header-ficha h6 {
            color: #52266E;
            font-weight: 800;
            margin: 0;
        }

        .dato-item {
            padding: 14px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .dato-item:last-child {
            border-bottom: none;
        }

        .dato-label {
            color: #52266E;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: .3px;
        }

        .dato-valor {
            color: #374151;
            font-size: 15px;
            font-weight: 700;
        }

        .badge-vet {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 800;
        }

        .badge-macho {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-hembra {
            background: #fce7f3;
            color: #be185d;
        }

        .badge-peso {
            background: #ecfdf5;
            color: #15803d;
        }

        .badge-especie {
            background: #ead7f7;
            color: #52266E;
        }

        .text-muted-vet {
            color: #9ca3af;
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
                        <div class="dato-valor">
                            <?= htmlspecialchars($mascota['nombre_mascota']) ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Fecha de nacimiento</div>
                        <div class="dato-valor">
                            <?= $fechaNacimiento ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Sexo</div>
                        <div class="dato-valor">
                            <?php if ($mascota['sexo'] == 'M') { ?>
                                <span class="badge-vet badge-macho">
                                    <i class="fas fa-mars mr-1"></i> Macho
                                </span>
                            <?php } elseif ($mascota['sexo'] == 'H') { ?>
                                <span class="badge-vet badge-hembra">
                                    <i class="fas fa-venus mr-1"></i> Hembra
                                </span>
                            <?php } else { ?>
                                <span class="text-muted-vet">No registrado</span>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Peso</div>
                        <div class="dato-valor">
                            <span class="badge-vet badge-peso">
                                <i class="fas fa-weight mr-1"></i>
                                <?= $peso ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Color</div>
                        <div class="dato-valor">
                            <?= $color ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Edad</div>
                        <div class="dato-valor">
                            <?= $edad ?>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Especie</div>
                        <div class="dato-valor">
                            <span class="badge-vet badge-especie">
                                <?= htmlspecialchars($mascota['nombre_especie']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="dato-item">
                        <div class="dato-label">Raza</div>
                        <div class="dato-valor">
                            <?= !empty($mascota['raza']) ? htmlspecialchars($mascota['raza']) : 'Sin especificar' ?>
                        </div>
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
                        <div class="dato-valor">
                            <?= $telefono ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dato-item">
                        <div class="dato-label">Email</div>
                        <div class="dato-valor">
                            <?= $email ?>
                        </div>
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

        <a href="print_pet_record.php?id=<?= $id ?>" target="_blank" class="btn btn-purple">
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