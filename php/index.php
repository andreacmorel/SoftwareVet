<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../css/style-vet.css">
    <style>.alert {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-size: 14px;
}

.alert-success {
    background: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

.alert-danger {
    background: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}</style>
</head>
<body>

<div class="container">
    
    <div class="info">
        <p class="txt-1">Software Veterinario</p>
        <h2>Bienvenido a VetSys</h2>
        <hr/>
    </div>

    <form class="form" action="login.php" method="POST">

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok') { ?>
            <div class="alert alert-success">
                 Contraseña actualizada correctamente. Iniciá sesión.
            </div>
        <?php } ?>

        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger">
                ✖ <?php echo $_GET['error']; ?>
            </div>
        <?php } ?>

        <h2>Iniciar sesión</h2>

        <div class="inputs">
            <input type="text" class="box" name="usuario" placeholder="Ingrese su usuario" required>
            <br>

            <input type="password" class="box" name="clave" placeholder="Ingrese su contraseña" required>
            <br>

            <input type="submit" value="Ingresar" class="submit">

            <p style="margin-top:10px;">
                <a href="reset_password.php">¿Olvidaste tu contraseña?</a>
            </p>
        </div>

    </form>

</div>

<script>
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if(alert){
        alert.style.display = 'none';
    }
}, 4000);
</script>

</body>
</html>