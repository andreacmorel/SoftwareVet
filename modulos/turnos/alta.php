<?php
 // CONECTA CON LA BASE DE DATOS USANDO EL ARCHIVO CONEXIÓN
require_once __DIR__ . '/../../config/conexion.php';


// CONSULTA PARA TRAER PROFESIONALES
// Trae el id del profesional y sus nombres desde la tabla persona
$sqlProfesionales = "SELECT p.id_profesional, per.nombre_persona, per.apellido_persona
                     FROM profesional p
                     INNER JOIN persona per ON p.id_persona = per.id_persona";

// Ejecuta la consulta y guarda el resultado
$resProfesionales = mysqli_query($conexion, $sqlProfesionales);

// CONSULTA PARA TRAER MASCOTAS
$sqlMascotas = "SELECT id_mascota, nombre_mascota FROM mascota";

// Ejecuta la consulta y guarda el resultado
$resMascotas = mysqli_query($conexion, $sqlMascotas);


// CONSULTA PARA INSERTAR DATOS
// Se ejecuta solo cuando el usuario hace clic en "Guardar"

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibe los valores enviados desde el formulario
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];
    $id_profesional = $_POST['id_profesional'];
    $id_mascota = $_POST['id_mascota'];


    // CONSULTA SQ PARA GUARDAR EL TURNO
    $sqlInsert = "
        INSERT INTO turnos (fecha, hora, motivo, id_profesional, id_mascota)
        VALUES ('$fecha', '$hora', '$motivo', '$id_profesional', '$id_mascota')
    ";

    // Ejecutamos la consulta para realizar el alta
    mysqli_query($conexion, $sqlInsert);

    // Redirige al listado cuando se guarda correctamente
    header("Location: listado.php");
    exit;
 


}
require_once '../../php/menu.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro de Turnos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Registro de Turnos</h1>
    </div>

   <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary" style="color: #52266E !important;">Nuevo Turno</h5>
                </div>
            <div class="card-body">
                    <form method="POST"> <!-- Formulario que envía los datos por método POST -->

                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>

                        <div class="form-group">
                            <label for="hora">Hora:</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>

                        <div class="form-group">
                            <label for="motivo">Motivo:</label>
                            <input type="text" class="form-control" id="motivo" name="motivo" required>
                        </div>

                        <div class="form-group">
                            <label for="id_profesional">Profesional:</label>
                            <select name="id_profesional" id="id_profesional" class="form-control" required>
                                <option value="">Seleccione un profesional</option>
                                <!-- Recorre todos los profesionales y crea las opciones -->
                                <?php while($p = mysqli_fetch_assoc($resProfesionales)) { ?>
                                      <!-- Creamos una opción <option> dentro del <select>.
                                           El value del option será el ID del profesional.
                                          Este valor es el que se enviará por POST al formulario.-->
                                    <option value="<?php echo $p['id_profesional']; ?>">
                                        <!--Mostramos el nombre completo del profesional.
                                        Concatenamos nombre_persona + apellido_persona.
                                        Esto es lo que el usuario ve en el menú desplegable.-->
                                        <?php echo $p['apellido_persona'] . "," . $p['nombre_persona']; ?>
                                    </option>
                                <?php } ?> <!--CIERRA EL WHILE-->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_mascota">Mascota:</label>
                            <select name="id_mascota" id="id_mascota" class="form-control" required>
                                <option value="">Seleccione una mascota</option>
                                     <!-- Abrimos el while que recorrerá el resultado de la consulta de mascotas. 
                                      mysqli_fetch_assoc() devuelve cada fila como un array asociativo. 
                                      Cada vuelta del while genera una <option> distinta.-->
                                <?php while($m = mysqli_fetch_assoc($resMascotas)) { ?>
                                    <!-- Cada <option> representa una mascota.
                                    value="id_mascota": es el ID que se guardará en la base de datos.
                                    Entre las etiquetas mostramos el nombre visible de la mascota.-->
                                    <option value="<?php echo $m['id_mascota']; ?>">
                                        <?php echo $m['nombre_mascota']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <br>

                        <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="listado.php" class="btn btn-danger ml-3">
                            <i class="fas fa-times"></i> Cancelar
                        </a> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SB Admin 2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

</body>
</html>