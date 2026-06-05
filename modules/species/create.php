<?php

// Incluye el archivo de conexión a la base de datos.
require_once '../../settings/conexion.php';

// Valida que el usuario tenga permisos para acceder a este módulo.
require_once '../../php/validateRoute.php';

// Array donde se guardarán los errores de validación del formulario.
$erroresCampos = [];

// Verifica si el formulario fue enviado mediante el método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtiene el nombre de la especie enviado desde el formulario.
    // trim elimina espacios innecesarios al inicio y al final.
    $nombre_especie = trim($_POST['nombre_especie'] ?? '');

    // Obtiene la raza enviada desde el formulario.
    $raza = trim($_POST['raza'] ?? '');

    // Valida que el campo nombre de especie no esté vacío.
    if (empty($nombre_especie)) {
        $erroresCampos['nombre_especie'] = "El nombre de la especie es obligatorio.";

    // Valida que el nombre de especie tenga al menos 3 caracteres.
    } elseif (strlen($nombre_especie) < 3) {
        $erroresCampos['nombre_especie'] = "Debe tener al menos 3 caracteres.";
    }

    // Valida que el campo raza no esté vacío.
    if (empty($raza)) {
        $erroresCampos['raza'] = "La raza es obligatoria.";

    // Valida que la raza tenga al menos 3 caracteres.
    } elseif (strlen($raza) < 3) {
        $erroresCampos['raza'] = "Debe tener al menos 3 caracteres.";
    }

    // Valida que el nombre de especie no supere los 50 caracteres,
    // siempre que no tenga ya un error previo.
    if (empty($erroresCampos['nombre_especie']) && strlen($nombre_especie) > 50) {
        $erroresCampos['nombre_especie'] = "No puede superar los 50 caracteres.";
    }

    // Valida que la raza no supere los 50 caracteres,
    // siempre que no tenga ya un error previo.
    if (empty($erroresCampos['raza']) && strlen($raza) > 50) {
        $erroresCampos['raza'] = "No puede superar los 50 caracteres.";
    }

    // Valida que el nombre de especie solo contenga letras,
    // espacios y guiones, siempre que no tenga errores previos.
    if (empty($erroresCampos['nombre_especie']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/', $nombre_especie)) {
        $erroresCampos['nombre_especie'] = "Solo se permiten letras, espacios y guiones.";
    }

    // Valida que la raza solo contenga letras,
    // espacios y guiones, siempre que no tenga errores previos.
    if (empty($erroresCampos['raza']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/', $raza)) {
        $erroresCampos['raza'] = "Solo se permiten letras, espacios y guiones.";
    }

    // Normaliza el nombre de la especie:
    // convierte todo a minúscula y luego pone la primera letra en mayúscula.
    $nombre_especie = ucfirst(strtolower($nombre_especie));

    // Normaliza la raza con el mismo criterio.
    $raza = ucfirst(strtolower($raza));

    // Si no existen errores, se verifica que no esté duplicada la especie y raza.
    if (empty($erroresCampos)) {

        // Prepara una consulta segura para buscar si ya existe esa especie y raza.
        $stmtExiste = $conexion->prepare("
            SELECT id_especie 
            FROM especie 
            WHERE nombre_especie = ? 
            AND raza = ?
        ");

        // Vincula los valores a los signos de pregunta de la consulta.
        // "ss" indica que ambos parámetros son strings.
        $stmtExiste->bind_param("ss", $nombre_especie, $raza);

        // Ejecuta la consulta.
        $stmtExiste->execute();

        // Obtiene el resultado de la consulta.
        $resExiste = $stmtExiste->get_result();

        // Si existe al menos un registro, significa que ya está cargado.
        if ($resExiste->num_rows > 0) {
            $erroresCampos['raza'] = "Esta especie y raza ya están registradas.";
        }

        // Cierra la consulta preparada.
        $stmtExiste->close();
    }

    // Si no hay errores, se registra la nueva especie.
    if (empty($erroresCampos)) {

        // Prepara la consulta para insertar especie y raza.
        $stmt = $conexion->prepare("
            INSERT INTO especie (nombre_especie, raza) 
            VALUES (?, ?)
        ");

        // Vincula los valores enviados al INSERT.
        $stmt->bind_param("ss", $nombre_especie, $raza);

        // Ejecuta el INSERT.
        if ($stmt->execute()) {

            // Si se registra correctamente, redirige al listado con mensaje de éxito.
            header("Location: index.php?success=1");
            exit;

        } else {

            // Si ocurre un error, guarda un mensaje general.
            $erroresCampos['general'] = "Error al registrar especie.";
        }

        // Cierra la consulta preparada.
        $stmt->close();
    }
}

require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Alta Especie</title>

<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../../css/sb-admin-2.min.css" rel="stylesheet">

<style>
.page-title {
    font-weight: 800;
    color: #1f2937;
}

.page-title i {
    color: #52266E;
}

.page-subtitle {
    color: #9ca3af;
    font-size: 14px;
}

.form-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 18px rgba(0,0,0,.06);
    margin-top: 25px;
}

.form-group label {
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
    box-shadow: 0 0 0 .2rem rgba(82,38,110,.15);
}

.form-control.is-invalid {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220,38,38,.12) !important;
}

.invalid-feedback {
    display: block;
    font-size: 13px;
    font-weight: 600;
}

.btn-purple {
    background: #52266E;
    color: white;
    border-radius: 8px;
    font-weight: 600;
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

</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title">
                <i class="fas fa-dna mr-2"></i> Nueva Especie
            </h3>
            <div class="page-subtitle">Registro de especie y raza</div>
        </div>
    </div>

    <div class="form-card">

        <?php if (isset($erroresCampos['general'])) { ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erroresCampos['general']); ?>
            </div>
        <?php } ?>

        <form method="POST" id="frmEspecie">

            <div class="form-group mb-3">
                <label>Nombre de Especie <span style="color:#dc2626;">*</span></label>

                <input
                    type="text"
                    name="nombre_especie"
                    class="form-control <?php echo isset($erroresCampos['nombre_especie']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['nombre_especie'] ?? ''); ?>"
                >

                <?php if (isset($erroresCampos['nombre_especie'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['nombre_especie']); ?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group mb-3">
                <label>Raza <span style="color:#dc2626;">*</span></label>

                <input
                    type="text"
                    name="raza"
                    class="form-control <?php echo isset($erroresCampos['raza']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($_POST['raza'] ?? ''); ?>"
                >

                <?php if (isset($erroresCampos['raza'])) { ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($erroresCampos['raza']); ?>
                    </div>
                <?php } ?>
            </div>

            <hr>

            <div class="d-flex justify-content-between mt-3">
                <a href="index.php" class="btn btn-cancelar">
                    <i class="fas fa-times mr-1"></i>
                    Cancelar
                </a>

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-save"></i> Guardar Especie
                </button>
            </div>

        </form>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>

// Detecta cuando se intenta enviar el formulario de especie.
document.getElementById('frmEspecie').addEventListener('submit', function(e) {

    // Expresión regular que permite letras, acentos, espacios y guiones.
    const solo_letras = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/;

    // Variable que indica si el formulario está correcto.
    let ok = true;

    // Obtiene el campo nombre de especie.
    const especie = document.querySelector('[name="nombre_especie"]');

    // Obtiene el campo raza.
    const raza = document.querySelector('[name="raza"]');

    // Recorre los dos campos para aplicarles las mismas validaciones.
    [especie, raza].forEach(function(campo) {

        // Obtiene el valor del campo sin espacios al inicio ni al final.
        const val = campo.value.trim();

        // Variable donde se guardará el mensaje de error.
        let msg = '';

        // Valida que el campo no esté vacío.
        if (val === '') {
            msg = 'El campo es obligatorio.';

        // Valida que tenga al menos 3 caracteres.
        } else if (val.length < 3) {
            msg = 'Debe tener al menos 3 caracteres.';

        // Valida que no supere los 50 caracteres.
        } else if (val.length > 50) {
            msg = 'No puede superar los 50 caracteres.';

        // Valida que solo tenga letras, espacios y guiones.
        } else if (!solo_letras.test(val)) {
            msg = 'Solo se permiten letras, espacios y guiones.';
        }

        // Si existe algún mensaje de error.
        if (msg) {

            // Agrega la clase is-invalid para marcar el campo en rojo.
            campo.classList.add('is-invalid');

            // Busca el elemento siguiente al input para mostrar el error.
            let fb = campo.nextElementSibling;

            // Si no existe un contenedor de error, lo crea.
            if (!fb || !fb.classList.contains('invalid-feedback')) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                campo.parentNode.insertBefore(fb, campo.nextSibling);
            }

            // Coloca el mensaje de error dentro del contenedor.
            fb.textContent = msg;

            // Marca el formulario como no válido.
            ok = false;

        } else {

            // Si no hay error, quita la clase de campo inválido.
            campo.classList.remove('is-invalid');

            // Busca el contenedor de error existente.
            const fb = campo.nextElementSibling;

            // Si existe, limpia el mensaje de error.
            if (fb && fb.classList.contains('invalid-feedback')) {
                fb.textContent = '';
            }
        }
    });

    // Si hay algún error, evita que el formulario se envíe.
    if (!ok) e.preventDefault();
});

</script>

</body>
</html>