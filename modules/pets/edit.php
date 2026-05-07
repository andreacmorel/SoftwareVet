<?php
require_once '../../settings/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de mascota no válido.");
}

$id = $_GET['id'];

$sqlClientes = "
SELECT c.id_cliente, p.nombre_persona, p.apellido_persona
FROM cliente c
INNER JOIN persona p ON c.id_persona = p.id_persona
";
$resClientes = mysqli_query($conexion, $sqlClientes);

$sqlEspecies = "SELECT id_especie, nombre_especie, raza FROM especie";
$resEspecies = mysqli_query($conexion, $sqlEspecies);

$sqlMascota = "SELECT * FROM mascota WHERE id_mascota = '$id'";
$resMascota = mysqli_query($conexion, $sqlMascota);
$mascota = mysqli_fetch_assoc($resMascota);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre_mascota'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $peso = $_POST['peso'];
    $color = $_POST['color'];
    $edad = $_POST['edad'];
    $id_especie = $_POST['id_especie'];
    $id_cliente = $_POST['id_cliente'];

    $sqlUpdate = "
        UPDATE mascota SET
        nombre_mascota = '$nombre',
        fecha_nacimiento = '$fecha_nacimiento',
        sexo = '$sexo',
        peso = '$peso',
        color = '$color',
        edad = '$edad',
        id_especie = '$id_especie',
        id_cliente = '$id_cliente'
        WHERE id_mascota = '$id'
    ";

    mysqli_query($conexion, $sqlUpdate);

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

.subtitulo {
    color: #9ca3af;
    font-size: 14px;
    margin-bottom: 25px;
}

.card-form {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 18px rgba(0,0,0,.06);
}

.card-header-form {
    background: #fbf7ff;
    border-bottom: 1px solid #eee1f6;
    padding: 18px;
}

.card-header-form h5 {
    color: #52266E;
    font-weight: 800;
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
}

.form-control:focus {
    border-color: #52266E;
    box-shadow: 0 0 0 3px rgba(82,38,110,.1);
}

.btn-purple {
    background: #52266E;
    color: white;
    border-radius: 8px;
    font-weight: 700;
}

.btn-purple:hover {
    background: #3f1d55;
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
        <i class="fas fa-pen mr-2"></i> Editar Mascota
    </h1>

    <div class="subtitulo">
        Modificá los datos del paciente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5><i class="fas fa-edit mr-2"></i> Datos de la Mascota</h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre_mascota" class="form-control"
                               value="<?= $mascota['nombre_mascota'] ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control"
                               value="<?= $mascota['fecha_nacimiento'] ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sexo</label>
                        <select name="sexo" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="M" <?= $mascota['sexo']=='M'?'selected':'' ?>>Macho</option>
                            <option value="H" <?= $mascota['sexo']=='H'?'selected':'' ?>>Hembra</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso</label>
                        <input type="number" step="0.01" name="peso" class="form-control"
                               value="<?= $mascota['peso'] ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control"
                               value="<?= $mascota['color'] ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edad</label>
                        <input type="number" name="edad" class="form-control"
                               value="<?= $mascota['edad'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Especie / Raza</label>
                    <select name="id_especie" class="form-control" required>
                        <?php while($e = mysqli_fetch_assoc($resEspecies)) { ?>
                            <option value="<?= $e['id_especie'] ?>"
                                <?= $mascota['id_especie']==$e['id_especie']?'selected':'' ?>>
                                <?= $e['nombre_especie']." - ".$e['raza'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-control" required>
                        <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>
                            <option value="<?= $c['id_cliente'] ?>"
                                <?= $mascota['id_cliente']==$c['id_cliente']?'selected':'' ?>>
                                <?= $c['apellido_persona'].", ".$c['nombre_persona'] ?>
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
                        <i class="fas fa-save mr-1"></i> Guardar cambios
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>