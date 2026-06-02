<?php
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

$erroresCampos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_especie = trim($_POST['nombre_especie'] ?? '');
    $raza = trim($_POST['raza'] ?? '');

    if (empty($nombre_especie)) {
        $erroresCampos['nombre_especie'] = "El nombre de la especie es obligatorio.";
    } elseif (strlen($nombre_especie) < 3) {
        $erroresCampos['nombre_especie'] = "Debe tener al menos 3 caracteres.";
    }

    if (empty($raza)) {
        $erroresCampos['raza'] = "La raza es obligatoria.";
    } elseif (strlen($raza) < 3) {
        $erroresCampos['raza'] = "Debe tener al menos 3 caracteres.";
    }

    if (empty($erroresCampos['nombre_especie']) && strlen($nombre_especie) > 50) {
        $erroresCampos['nombre_especie'] = "No puede superar los 50 caracteres.";
    }

    if (empty($erroresCampos['raza']) && strlen($raza) > 50) {
        $erroresCampos['raza'] = "No puede superar los 50 caracteres.";
    }

    if (empty($erroresCampos['nombre_especie']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/', $nombre_especie)) {
        $erroresCampos['nombre_especie'] = "Solo se permiten letras, espacios y guiones.";
    }

    if (empty($erroresCampos['raza']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/', $raza)) {
        $erroresCampos['raza'] = "Solo se permiten letras, espacios y guiones.";
    }

    $nombre_especie = ucfirst(strtolower($nombre_especie));
    $raza           = ucfirst(strtolower($raza));

    if (empty($erroresCampos)) {

        $stmtExiste = $conexion->prepare("
            SELECT id_especie 
            FROM especie 
            WHERE nombre_especie = ? 
            AND raza = ?
        ");

        $stmtExiste->bind_param("ss", $nombre_especie, $raza);
        $stmtExiste->execute();
        $resExiste = $stmtExiste->get_result();

        if ($resExiste->num_rows > 0) {
            $erroresCampos['raza'] = "Esta especie y raza ya están registradas.";
        }

        $stmtExiste->close();
    }

    if (empty($erroresCampos)) {

        $stmt = $conexion->prepare("
            INSERT INTO especie (nombre_especie, raza) 
            VALUES (?, ?)
        ");

        $stmt->bind_param("ss", $nombre_especie, $raza);

        if ($stmt->execute()) {
            header("Location: index.php?success=1");
            exit;
        } else {
            $erroresCampos['general'] = "Error al registrar especie.";
        }

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
document.getElementById('frmEspecie').addEventListener('submit', function(e) {
    const solo_letras = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/;
    let ok = true;

    const especie = document.querySelector('[name="nombre_especie"]');
    const raza    = document.querySelector('[name="raza"]');

    [especie, raza].forEach(function(campo) {
        const val = campo.value.trim();
        let msg   = '';

        if (val === '') {
            msg = 'El campo es obligatorio.';
        } else if (val.length < 3) {
            msg = 'Debe tener al menos 3 caracteres.';
        } else if (val.length > 50) {
            msg = 'No puede superar los 50 caracteres.';
        } else if (!solo_letras.test(val)) {
            msg = 'Solo se permiten letras, espacios y guiones.';
        }

        if (msg) {
            campo.classList.add('is-invalid');
            let fb = campo.nextElementSibling;
            if (!fb || !fb.classList.contains('invalid-feedback')) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                campo.parentNode.insertBefore(fb, campo.nextSibling);
            }
            fb.textContent = msg;
            ok = false;
        } else {
            campo.classList.remove('is-invalid');
            const fb = campo.nextElementSibling;
            if (fb && fb.classList.contains('invalid-feedback')) {
                fb.textContent = '';
            }
        }
    });

    if (!ok) e.preventDefault();
});
</script>


</body>
</html>