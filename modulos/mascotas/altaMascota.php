<?php
require_once '../../config/conexion.php';

$sqlClientes = "
SELECT c.id_cliente, p.nombre_persona, p.apellido_persona
FROM cliente c
INNER JOIN persona p ON c.id_persona = p.id_persona
";
$resClientes = mysqli_query($conexion, $sqlClientes);

if (!$resClientes) {
    die("Error en clientes: " . mysqli_error($conexion));
}

$sqlEspecies = "SELECT id_especie, nombre_especie, raza FROM especie";
$resEspecies = mysqli_query($conexion, $sqlEspecies);

if (!$resEspecies) {
    die("Error en especies: " . mysqli_error($conexion));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre_mascota'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $peso = $_POST['peso'];
    $color = $_POST['color'];
    $edad = $_POST['edad'];
    $id_especie = $_POST['id_especie'];
    $id_cliente = $_POST['id_cliente'];

    $sqlInsert = "
        INSERT INTO mascota 
        (nombre_mascota, fecha_nacimiento, sexo, peso, color, edad, id_especie, id_cliente)
        VALUES 
        ('$nombre', '$fecha_nacimiento', '$sexo', '$peso', '$color', '$edad', '$id_especie', '$id_cliente')
    ";

    $resultado = mysqli_query($conexion, $sqlInsert);

    if (!$resultado) {
        die("Error al guardar mascota: " . mysqli_error($conexion));
    }

    header("Location: listadoMascota.php");
    exit;
}

require_once '../../php/menu.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Registro de Mascota</h1>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary" style="color: #52266E !important;">
                Nueva Mascota
            </h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre_mascota" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sexo</label>
                        <select name="sexo" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="M">Macho</option>
                            <option value="H">Hembra</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso (kg)</label>
                        <input type="number" step="0.01" min="0" name="peso" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edad</label>
                        <input type="number" min="0" name="edad" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Especie / Raza</label>
                        <select name="id_especie" class="form-control" required>
                            <option value="">Seleccione una especie y raza</option>

                            <?php while($e = mysqli_fetch_assoc($resEspecies)) { ?>
                                <option value="<?php echo $e['id_especie']; ?>">
                                    <?php echo $e['nombre_especie'] . " - " . $e['raza']; ?>
                                </option>
                            <?php } ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control" required>
                        <option value="">Seleccione un cliente</option>

                        <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>
                            <option value="<?php echo $c['id_cliente']; ?>">
                                <?php echo $c['apellido_persona'] . ", " . $c['nombre_persona']; ?>
                            </option>
                        <?php } ?>

                    </select>
                </div>

                <br>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success px-4">
                        Guardar
                    </button>

                    <a href="listadoMascota.php" class="btn btn-danger">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

</body>
</html>