<?php
require_once '../../config/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cliente no válido.");
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre_persona'];
    $apellido = $_POST['apellido_persona'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $calle = $_POST['calle'];
    $numero_calle = $_POST['numero_calle'];
    $barrio = $_POST['barrio'];
    $manzana = $_POST['manzana'];

    $sqlBuscar = "SELECT id_persona FROM cliente WHERE id_cliente = '$id'";
    $resBuscar = mysqli_query($conexion, $sqlBuscar);

    if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
        die("Cliente no encontrado.");
    }

    $cliente = mysqli_fetch_assoc($resBuscar);
    $id_persona = $cliente['id_persona'];

    $sqlPersona = "
        UPDATE persona SET
        nombre_persona = '$nombre',
        apellido_persona = '$apellido',
        telefono = '$telefono',
        email = '$email'
        WHERE id_persona = '$id_persona'
    ";

    if (!mysqli_query($conexion, $sqlPersona)) {
        die("Error al modificar persona: " . mysqli_error($conexion));
    }

    $sqlDomicilioExiste = "SELECT id_domicilio FROM domicilio WHERE id_cliente = '$id'";
    $resDomicilioExiste = mysqli_query($conexion, $sqlDomicilioExiste);

    if (!$resDomicilioExiste) {
        die("Error al buscar domicilio: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($resDomicilioExiste) > 0) {

        $domicilio = mysqli_fetch_assoc($resDomicilioExiste);
        $id_domicilio = $domicilio['id_domicilio'];

        $sqlDomicilio = "
            UPDATE domicilio SET
            calle = '$calle',
            numero_calle = '$numero_calle',
            barrio = '$barrio',
            manzana = '$manzana'
            WHERE id_domicilio = '$id_domicilio'
        ";

    } else {

        $sqlDomicilio = "
            INSERT INTO domicilio (calle, numero_calle, barrio, manzana, id_cliente)
            VALUES ('$calle', '$numero_calle', '$barrio', '$manzana', '$id')
        ";
    }

    if (!mysqli_query($conexion, $sqlDomicilio)) {
        die("Error al modificar domicilio: " . mysqli_error($conexion));
    }

    header("Location: listadoCliente.php");
    exit;
}

$sql = "
    SELECT 
    c.id_cliente,
    c.id_persona,
    p.nombre_persona,
    p.apellido_persona,
    p.telefono,
    p.email,
    d.calle,
    d.numero_calle,
    d.barrio,
    d.manzana
    FROM cliente c
    INNER JOIN persona p ON c.id_persona = p.id_persona
    LEFT JOIN domicilio d ON d.id_cliente = c.id_cliente
    WHERE c.id_cliente = '$id'
";

$res = mysqli_query($conexion, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    die("Cliente no encontrado.");
}

$row = mysqli_fetch_assoc($res);

require_once '../../php/menu.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Modificar Cliente</h1>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary" style="color:#52266E !important;">
                Datos del Cliente
            </h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre_persona" class="form-control"
                               value="<?= htmlspecialchars($row['nombre_persona']) ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellido</label>
                        <input type="text" name="apellido_persona" class="form-control"
                               value="<?= htmlspecialchars($row['apellido_persona']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="<?= htmlspecialchars($row['telefono'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Correo</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($row['email'] ?? '') ?>">
                    </div>
                </div>

                <br>

                <h5 class="font-weight-bold mb-3" style="color:#52266E;">Domicilio</h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Calle</label>
                        <input type="text" name="calle" class="form-control"
                               value="<?= htmlspecialchars($row['calle'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Número</label>
                        <input type="text" name="numero_calle" class="form-control"
                               value="<?= htmlspecialchars($row['numero_calle'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Barrio</label>
                        <input type="text" name="barrio" class="form-control"
                               value="<?= htmlspecialchars($row['barrio'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Manzana</label>
                        <input type="text" name="manzana" class="form-control"
                               value="<?= htmlspecialchars($row['manzana'] ?? '') ?>">
                    </div>
                </div>

                <br>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success px-4">
                        Guardar
                    </button>

                    <a href="listadoCliente.php" class="btn btn-danger">
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