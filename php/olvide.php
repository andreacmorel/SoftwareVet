<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../css/style-vet.css">
    <style>
        .alert {
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
} </style>
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

            <?php if (isset($_GET['mensaje'])) { ?>

                <?php if ($_GET['mensaje'] == 'ok') { ?>
                    <div class="alert alert-success">
                        Se envió el link de recuperación a tu correo
                    </div>
                <?php } ?>

                <?php if ($_GET['mensaje'] == 'error') { ?>
                    <div class="alert alert-danger">
                         El email no existe
                    </div>
                <?php } ?>

                <?php if ($_GET['mensaje'] == 'mail_error') { ?>
                    <div class="alert alert-danger">
                         No se pudo enviar el correo
                    </div>
                <?php } ?>

            <?php } ?>

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