<?php

// Incluye la conexión a la base de datos.
require_once '../../settings/conexion.php';

// Valida que el usuario tenga permisos para acceder a esta ruta.
require_once '../../php/validateRoute.php';

// Array donde se guardan los errores de validación del formulario.
$erroresCampos = [];

// Verifica que se haya recibido un ID por la URL.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no válido.");
}

// Convierte el ID recibido a entero para trabajar con un valor seguro.
$id_usuario = (int) $_GET['id'];

// Busca los datos del usuario que se quiere modificar.
$usuarioEditar = $conexion->query("
    SELECT *
    FROM usuario
    WHERE id_usuario = $id_usuario
")->fetch_object();

if (!$usuarioEditar) {
    die("Usuario no encontrado.");
}

/*
| Verifica si el usuario que se está editando es el mismo usuario
| que inició sesión.
| Esto sirve para impedir que un administrador cambie su propio perfil.
*/
$esMiUsuario = isset($_SESSION['id_usuario']) && ((int)$_SESSION['id_usuario'] === (int)$id_usuario);

// Consulta los perfiles activos para mostrarlos en el select del formulario.
$perfiles = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE estado = 1
    ORDER BY nombre_perfil
");

// Verifica si se presionó el botón de actualizar.
if (!empty($_POST['btnActualizar'])) {

    // Obtiene y limpia los datos enviados desde el formulario.
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');

    /*
    | Si el usuario está editando su propia cuenta, NO se toma el perfil enviado
    | por POST. Se conserva el perfil actual de la base de datos.
    | Si está editando a otro usuario, sí puede cambiar el perfil desde el select.
    */
    if ($esMiUsuario) {
        $id_perfil = (int)$usuarioEditar->id_perfil;
    } else {
        $id_perfil = (int)($_POST['id_perfil'] ?? 0);
    }

    $clave = $_POST['clave'] ?? '';
    $confirmar_clave = $_POST['confirmar_clave'] ?? '';

    // Valida que el usuario no esté vacío y tenga una longitud correcta.
    if (empty($usuario)) {
        $erroresCampos['usuario'] = "El usuario es obligatorio.";
    } elseif (strlen($usuario) < 3) {
        $erroresCampos['usuario'] = "Debe tener al menos 3 caracteres.";
    } elseif (strlen($usuario) > 30) {
        $erroresCampos['usuario'] = "No puede superar los 30 caracteres.";
    }

    // Valida que el email no esté vacío y tenga formato correcto.
    if (empty($email)) {
        $erroresCampos['email'] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroresCampos['email'] = "Ingrese un email válido.";
    }

    /*
    | La validación del perfil solo se aplica cuando se edita a otro usuario.
    | Si estoy editando mi propia cuenta, el perfil queda bloqueado y se mantiene igual.
    */
    if (!$esMiUsuario && empty($id_perfil)) {
        $erroresCampos['id_perfil'] = "Seleccione un perfil.";
    }

    // Valida la contraseña solo si el usuario escribió una nueva.
    if (!empty($clave) || !empty($confirmar_clave)) {

        if (strlen($clave) < 6) {
            $erroresCampos['clave'] = "La contraseña debe tener al menos 6 caracteres.";
        }

        if ($clave !== $confirmar_clave) {
            $erroresCampos['confirmar_clave'] = "Las contraseñas no coinciden.";
        }
    }

    // Si no existen errores, valida que no haya usuario o email duplicado.
    if (empty($erroresCampos)) {

        $usuarioSeguro = $conexion->real_escape_string($usuario);
        $emailSeguro = $conexion->real_escape_string($email);

        // Busca si existe otro usuario con el mismo usuario o email.
        // Se excluye el propio usuario que se está editando.
        $validarDuplicado = $conexion->query("
            SELECT id_usuario
            FROM usuario
            WHERE (usuario = '$usuarioSeguro' OR email = '$emailSeguro')
            AND id_usuario != $id_usuario
        ");

        if ($validarDuplicado && $validarDuplicado->num_rows > 0) {
            $erroresCampos['usuario'] = "El usuario o email ya se encuentra registrado.";
        }
    }

    // Si no hay errores, actualiza el usuario.
    if (empty($erroresCampos)) {

        if (!empty($clave)) {

            $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

            /*
            | Se actualiza el perfil usando $id_perfil.
            | Si es mi propio usuario, ese valor ya fue conservado desde la BD.
            */
            $conexion->query("
                UPDATE usuario
                SET usuario = '$usuarioSeguro',
                    email = '$emailSeguro',
                    clave = '$clave_hash',
                    id_perfil = $id_perfil
                WHERE id_usuario = $id_usuario
            ");

        } else {

            $conexion->query("
                UPDATE usuario
                SET usuario = '$usuarioSeguro',
                    email = '$emailSeguro',
                    id_perfil = $id_perfil
                WHERE id_usuario = $id_usuario
            ");
        }

        header("Location: index.php?updated=1");
        exit;
    }

    // Si hubo errores, conserva los datos ingresados en el formulario.
    $usuarioEditar->usuario = $usuario;
    $usuarioEditar->email = $email;
    $usuarioEditar->id_perfil = $id_perfil;
}

// Carga el menú principal del sistema.
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

select.form-control.is-invalid {
    background-image: none !important;
}

.help-text {
    color: #9ca3af;
    font-size: 13px;
    margin-top: 5px;
}

/* Estilo para mostrar una aclaración cuando el perfil está bloqueado. */
.alert-info-vet {
    background: #eef6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 13px;
    font-weight: 700;
    margin-top: 8px;
}
</style>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-user-edit mr-2"></i> Editar Usuario
    </h1>

    <div class="subtitulo">
        Modificá los datos de acceso del usuario.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos del Usuario
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erroresCampos['general']) ?>
                </div>
            <?php } ?>

            <form method="POST" novalidate>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Usuario <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="usuario"
                            class="form-control <?= isset($erroresCampos['usuario']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($usuarioEditar->usuario ?? '') ?>">

                        <?php if(isset($erroresCampos['usuario'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['usuario']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Email <span style="color:#dc2626;">*</span></label>

                        <input type="email" name="email"
                            class="form-control <?= isset($erroresCampos['email']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($usuarioEditar->email ?? '') ?>">

                        <?php if(isset($erroresCampos['email'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['email']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nueva contraseña</label>

                        <input type="password" name="clave"
                            class="form-control <?= isset($erroresCampos['clave']) ? 'is-invalid' : '' ?>">

                        <?php if(isset($erroresCampos['clave'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['clave']) ?></div>
                        <?php } ?>

                        <div class="help-text">
                            Dejar vacío si no desea cambiarla.
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Confirmar nueva contraseña</label>

                        <input type="password" name="confirmar_clave"
                            class="form-control <?= isset($erroresCampos['confirmar_clave']) ? 'is-invalid' : '' ?>">

                        <?php if(isset($erroresCampos['confirmar_clave'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['confirmar_clave']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Perfil <span style="color:#dc2626;">*</span></label>

                    <!--
                    | CAMBIO AGREGADO:
                    | Si el usuario está editando su propia cuenta,
                    | el select aparece bloqueado para impedir que cambie su propio rol.
                    -->
                    <select name="id_perfil"
                        class="form-control <?= isset($erroresCampos['id_perfil']) ? 'is-invalid' : '' ?>"
                        <?= $esMiUsuario ? 'disabled' : '' ?>>

                        <option value="">Seleccione un perfil</option>

                        <?php while ($perfil = $perfiles->fetch_object()) { ?>
                            <option value="<?= $perfil->id_perfil ?>"
                                <?= (($usuarioEditar->id_perfil ?? '') == $perfil->id_perfil) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($perfil->nombre_perfil) ?>
                            </option>
                        <?php } ?>

                    </select>

                    <!--
                    | Como el select disabled no se envía por POST,
                    | el PHP conserva el perfil original desde la base de datos.
                    | Este mensaje explica al usuario por qué no puede modificarlo.
                    -->
                    <?php if ($esMiUsuario) { ?>
                        <div class="alert-info-vet">
                            <i class="fas fa-info-circle mr-1"></i>
                            Por seguridad, no puede modificar el perfil de su propia cuenta.
                        </div>
                    <?php } ?>

                    <?php if(isset($erroresCampos['id_perfil'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_perfil']) ?></div>
                    <?php } ?>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" name="btnActualizar" value="1" class="btn btn-purple">
                        <i class="fas fa-save mr-1"></i>
                        Guardar cambios
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