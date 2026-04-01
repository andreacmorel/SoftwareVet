<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="style-vet.css">
</head>
<body>
    <div class="container">
        <div class="info">
            <p class="txt-1">Software Veterinario</p>
            <h2>Recuperar contraseña</h2>
            <hr/>
            <p class="txt-2">Ingresá tu email para recibir el link</p>
        </div>

        <form class="form" action="enviar.php" method="POST">
            <h2>Recuperación</h2>

            <?php
            if (isset($_GET['mensaje'])) { ?>
                <p class="error">
                    <?php echo $_GET['mensaje']; ?>
                </p>
            <?php }
            ?>

            <div class="inputs">
                <input type="email" class="box" name="email" placeholder="Ingrese su email" required>
                <br>

                <input type="submit" value="Enviar" class="submit">

                <p style="margin-top:10px; text-align:center;">
                    <a href="index.php">Volver al login</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>