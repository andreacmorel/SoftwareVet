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
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="style-vet.css">
</head>
<body>
<div class="container">
    <div class="info">
        <p class="txt-1">Software Veterinario</p>
        <h2>Nueva contraseña</h2>
        <hr/>
    </div>

    <form class="form" action="guardar.php" method="POST">
        <h2>Restablecer</h2>

        <div class="inputs">
            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <input type="password" class="box" name="password" placeholder="Nueva contraseña" required>
            <br>

            <input type="submit" value="Guardar" class="submit">
        </div>
    </form>
</div>
</body>
</html>