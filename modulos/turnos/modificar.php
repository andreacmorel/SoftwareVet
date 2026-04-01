<?php
// INCLUIMOS EL ARCHIVO DE CONEXION SQL 
require_once '../../config/conexion.php';


// TOMAMOS EL PARAMETRO ID QUE LLEGA POR URL, SE USA PARA SABER QUE TURNO ELIMINAR
$id_turno = $_GET["id"];

// CONSULTA PARA TRAER LOS DATOS DEL TURNO
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

// CONSULTA PARA TRAER LOS DATOS DEL PROFESIONAL
$profesionales = $conexion->query("
    SELECT p.id_profesional, CONCAT(per.nombre_persona, ' ', per.apellido_persona) AS nombre
    FROM profesional p
    INNER JOIN persona per ON per.id_persona = p.id_persona
");

// CONSULTA PARA TRAER LOS DATOS DE LA MASCOTA
$mascotas = $conexion->query("
    SELECT id_mascota, nombre_mascota 
    FROM mascota
");
// VERIFICA SI EL FORMULARIO SE MANDA POR METODO POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fecha = $_POST["fecha"];  //GUARDA LA FECHA ENVIADA DESDE EL FORMULARIO
    $hora = $_POST["hora"]; // GUARDA LA HORA
    $motivo = $_POST["motivo"]; // GUARDA EL MOTIVO SELECCIONADO
    $id_profesional = $_POST["id_profesional"]; //GUARDA EL PROFESIONAL SELECCIONADO EN EL SELECT
    $id_mascota = $_POST["id_mascota"]; //GUARDA LA MASCOTA SELECCIONADA EN EL SELECT 

// CONSULTA QUE SIRVE PARA EL MODIFICAR DONDE UTILIZAMOS UPDATE
    $conexion->query("
        UPDATE turnos 
        SET fecha='$fecha',
            hora='$hora',
            motivo='$motivo',
            id_profesional='$id_profesional',
            id_mascota='$id_mascota'
        WHERE id_turno = $id_turno
    ");
// REDIRECCION AL LISTADO 
    header("Location: listado.php");
    exit;
}

require_once '../../php/menu.php';

?>

<!--FORMULARIO DE MODIFICAR-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modificar Turno</title>
    
    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modificar Turno</h1>
    </div>

    <!-- Card del formulario -->
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold" style="color: #52266E !important;">Editar Turno</h5>
                </div>
                <div class="card-body">
                    <form method="POST">

                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $datos->fecha ?>" required>
                            <!-- Campo de texto donde se muestra y permite editar la fecha del turno 
                            $datos->fecha → obtiene la fecha del turno -->
                        </div>

                        <div class="form-group">
                            <label for="hora">Hora:</label>
                            <input type="time" class="form-control" id="hora" name="hora" value="<?= $datos->hora ?>" required>
                            <!-- Campo de texto donde se muestra y permite editar la hora del turno 
                            $datos->hora → obtiene la hora del turno -->
                        </div>

                        <div class="form-group">
                            <label for="motivo">Motivo:</label>
                            <input type="text" class="form-control" id="motivo" name="motivo" value="<?= $datos->motivo ?>">
                            <!-- Carga el motivo actual para poder modificarlo 
                            $datos->motivo → obtiene el motivo del turno -->
                        </div>

                        <div class="form-group">
                            <label for="id_profesional">Profesional:</label>
                            <select name="id_profesional" id="id_profesional" class="form-control" required>
                                <option value="">Seleccione un profesional</option>
                                <!--Este while recorre la lista de profesionales que trajiste de la base de datos-->
                                <?php while ($p = $profesionales->fetch_object()) { ?>
                                    <!--Crea una opción del select y le pone como valor el id del profesional-->
                                    <option value="<?= $p->id_profesional ?>" <?= ($p->id_profesional == $datos->id_profesional) ? 'selected' : '' ?>>
                                    <!--Esto sirve para que el select muestre cuál es el profesional actual del turno-->
                                        <?= $p->nombre ?>
                                        <!--Esto es lo que se muestra dentro del select-->
                                    </option>
                                <?php } ?> <!--CIERRA EL WHILE-->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_mascota">Mascota:</label>
                            <select name="id_mascota" id="id_mascota" class="form-control" required>
                                <option value="">Seleccione una mascota</option>
                                <!--Este while recorre la lista de las mascotas que trajiste de la base de datos-->
                                <?php while ($m = $mascotas->fetch_object()) { ?>
                                    <!--Crea una opción del select y le pone como valor el id de la mascota-->
                                    <option value="<?= $m->id_mascota ?>" <?= ($m->id_mascota == $datos->id_mascota) ? 'selected' : '' ?>>
                                    <!-- MARCA COMO SELECCIONADA LA MASCOTA DEL TURNO -->
                                        <?= $m->nombre_mascota ?>
                                        <!--Esto es lo que se muestra dentro del select-->
                                        <!-- MUESTRA EL NOMBRE DE LA MASCOTA -->
                                    </option>
                                <?php } ?> <!--CIERRA EL WHILE-->
                            </select>
                        </div>
                        <br>            
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="listado.php" class="btn btn-danger ml-3">
                                <i class="fas fa-times"></i> Cancelar
                            </a> 
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

</body>
</html>