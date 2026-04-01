<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../css/style-vet.css">
</head>
<body>
    <div class="container">
        <div class="info">
        <p class="txt-1">Software Veterinario</p>
        <h2>Bienvenido a VetSys </h2>
        <hr/>

        <p class="txt-2"></p>
        </div>

    <form class="form" action="IniciarSesion.php" method="POST">
        <h2>Iniciar sesión</h2>
        <?php
            if (isset($_GET['error'])) { ?> 
            <p class="error">
                <?php 
                echo $_GET['error'];
                ?>
                </p>
        <?php 
        }
        ?>
        
    <div class="inputs">
        <input type="text" class="box"  name="usuario" placeholder="Ingrese su usuario">
        <br>
        <input type="password" class="box" name="clave" placeholder="Ingrese su contraseña">
        <br>
        <input type="submit" value="ingresar" class="submit">
        <p style="margin-top:10px;">
        <a href="olvide.php">¿Olvidaste tu contraseña?</a>
        </p>
    </div>
    </form>
    </div>
</body>
</html>
