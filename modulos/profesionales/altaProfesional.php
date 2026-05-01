<?php
require_once '../../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre_persona'];
    $apellido = $_POST['apellido_persona'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $calle = $_POST['calle'];
    $numero_calle = $_POST['numero_calle'];
    $barrio = $_POST['barrio'];
    $manzana = $_POST['manzana'];

    $sqlPersona = "
        INSERT INTO persona (nombre_persona, apellido_persona, telefono, email)
        VALUES ('$nombre', '$apellido', '$telefono', '$email')
    ";

    $resPersona = mysqli_query($conexion, $sqlPersona);

    if (!$resPersona) {
        die("Error al guardar persona: " . mysqli_error($conexion));
    }

    $id_persona = mysqli_insert_id($conexion);

    $sqlProfesional = "
        INSERT INTO profesional (id_persona)
        VALUES ('$id_persona')
    ";

    $resProfesional = mysqli_query($conexion, $sqlProfesional);

    if (!$resProfesional) {
        die("Error al guardar profesional: " . mysqli_error($conexion));
    }

    $id_profesional = mysqli_insert_id($conexion);

    if (!empty($calle) || !empty($numero_calle) || !empty($barrio) || !empty($manzana)) {

        $sqlDomicilio = "
            INSERT INTO domicilio (calle, numero_calle, barrio, manzana, id_profesional)
            VALUES ('$calle', '$numero_calle', '$barrio', '$manzana', '$id_profesional')
        ";

        $resDomicilio = mysqli_query($conexion, $sqlDomicilio);

        if (!$resDomicilio) {
            die("Error al guardar domicilio: " . mysqli_error($conexion));
        }
    }

    header("Location: listadoProfesional.php");
    exit;
}

require_once '../../php/menu.php';
?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Registro de Profesional</h1>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary" style="color:#52266E !important;">
                Nuevo Profesional
            </h5>
        </div>

        <div class="card-body">

            <form method="POST">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre_persona" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellido</label>
                        <input type="text" name="apellido_persona" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Correo</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>

                <hr>

                <h5 class="font-weight-bold mb-3" style="color:#52266E;">Domicilio</h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Calle</label>
                        <input type="text" name="calle" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Número</label>
                        <input type="text" name="numero_calle" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Barrio</label>
                        <input type="text" name="barrio" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Manzana</label>
                        <input type="text" name="manzana" class="form-control">
                    </div>
                </div>

                <br>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success px-4">
                        Guardar
                    </button>

                    <a href="listadoProfesional.php" class="btn btn-danger">
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