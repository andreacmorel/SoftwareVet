<?php
require '../config/conexion.php';

if (!isset($_GET['token'])) {
    die("Token no válido");
}

$token = $_GET['token'];

$sql = "SELECT * FROM usuario WHERE reset_token='$token'";
$resultado = mysqli_query($conexion, $sql);
$user = mysqli_fetch_assoc($resultado);

if (!$user) {
    die("Token inválido");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="../css/style-vet.css">
    <style>.alert {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
}

.alert-success {
    background: #d1e7dd;
    color: #0f5132;
}

.alert-danger {
    background: #f8d7da;
    color: #842029;
}</style>
</head>
<body>

<div class="container">
    <div class="info">
        <p class="txt-1">Software Veterinario</p>
        <h2>Nueva contraseña</h2>
        <hr/>
        <p class="txt-2">Ingresá tu nueva contraseña</p>
    </div>

    <form class="form" action="guardar.php" method="POST">
        <h2>Restablecer</h2>

        <?php if (isset($_GET['mensaje'])) { ?>

            <?php if ($_GET['mensaje'] == 'error') { ?>
                <div class="alert alert-danger">
                    Las contraseñas no coinciden
                </div>
            <?php } ?>

            <?php if ($_GET['mensaje'] == 'ok') { ?>
                <div class="alert alert-success">
                    Contraseña actualizada correctamente
                </div>
            <?php } ?>

        <?php } ?>

        <div class="inputs">
            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <input type="password" class="box" name="password" placeholder="Nueva contraseña" required>
            <br>

            <input type="password" class="box" name="password2" placeholder="Confirmar contraseña" required>
            <br>

            <input type="submit" value="Guardar" class="submit">
        </div>

        <p style="text-align:center; margin-top:10px;">
            <a href="index.php">Volver al login</a>
        </p>

    </form>
</div>

</body>
</html>