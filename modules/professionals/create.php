<?php
require_once '../../settings/conexion.php';

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

    .section-title {
        color: #52266E;
        font-weight: 800;
        font-size: 15px;
        margin-bottom: 18px;
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
        <i class="fas fa-user-md mr-2"></i>
        Registro de Profesional
    </h1>

    <div class="subtitulo-pagina">
        Completá los datos para registrar un nuevo profesional.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-plus-circle mr-2"></i>
                Nuevo Profesional
            </h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <h5 class="section-title">
                    <i class="fas fa-user-md mr-2"></i>
                    Datos personales
                </h5>

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

                <h5 class="section-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Domicilio
                </h5>

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