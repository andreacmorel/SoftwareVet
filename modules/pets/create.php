<?php
require_once '../../settings/conexion.php';

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

    header("Location: index.php");
    exit;
}

require_once '../../php/menu.php';
?>

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

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-paw mr-2"></i>
        Registro de Mascota
    </h1>

    <div class="subtitulo-pagina">
        Completá los datos para registrar un nuevo paciente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
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

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

</body>
</html>