<?php

// Incluye la conexión a la base de datos.
require_once '../settings/conexion.php';

// Incluye las clases necesarias de PHPMailer.
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

// Importa las clases PHPMailer y Exception para poder usarlas.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica que el formulario haya enviado el email.
if (!isset($_POST['email'])) {

    // Si no se recibió email, redirige con mensaje de error.
    header("Location: forgot_password.php?mensaje=error");
    exit;
}

// Obtiene el email enviado y elimina espacios innecesarios.
$email = trim($_POST['email']);


// Busca en la base de datos si existe un usuario con ese email.
$sql = "SELECT * FROM usuario WHERE email = '$email'";
$resultado = mysqli_query($conexion, $sql);

// Si la consulta falla, redirige con mensaje de error.
if (!$resultado) {
    header("Location: forgot_password.php?mensaje=error");
    exit;
}

// Obtiene los datos del usuario encontrado.
$user = mysqli_fetch_assoc($resultado);


// Si existe un usuario con ese email.
if ($user) {

    // Genera un token seguro y único para recuperar la contraseña.
    $token = bin2hex(random_bytes(32));

    // Guarda el token generado en la tabla usuario.
    $update = "UPDATE usuario SET reset_token = '$token'
                WHERE email = '$email'";

    mysqli_query($conexion, $update);

    // Arma el enlace que recibirá el usuario para cambiar su contraseña.
    $link = "http://localhost/SoftwareVet/php/reset_password.php?token=$token";

    // Crea una nueva instancia de PHPMailer.
    $mail = new PHPMailer(true);

    try {

        // Configura el envío por SMTP.
        $mail->isSMTP();

        // Servidor SMTP de Gmail.
        $mail->Host       = 'smtp.gmail.com';

        // Activa autenticación SMTP.
        $mail->SMTPAuth   = true;

        // Cuenta de Gmail utilizada para enviar el correo.
        $mail->Username   = 'vetsys.softwareveterinario@gmail.com';

        // Contraseña de aplicación de Gmail.
        $mail->Password   = 'fvzv elnl znyf herr';

        // Tipo de seguridad del envío.
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // Puerto usado por Gmail para STARTTLS.
        $mail->Port       = 587;

        // Define la codificación para permitir acentos y caracteres especiales.
        $mail->CharSet = 'UTF-8';

        // Define el remitente del correo.
        $mail->setFrom(
            'vetsys.softwareveterinario@gmail.com',
            'Software Veterinario'
        );

        // Agrega como destinatario el email ingresado por el usuario.
        $mail->addAddress($email);

        // Indica que el contenido del correo será HTML.
        $mail->isHTML(true);

        // Asunto del correo.
        $mail->Subject = 'Recuperar contraseña - VetSys';

        // Cuerpo del correo con diseño HTML.
        $mail->Body = '
        <div style="
            background:#edf1f5;
            padding:40px;
            font-family:Arial,sans-serif;
        ">

            <div style="
                max-width:620px;
                margin:auto;
                background:#ffffff;
                border-radius:22px;
                overflow:hidden;
                box-shadow:0 10px 30px rgba(0,0,0,.12);
            ">

                <!-- HEADER -->
                <div style="
                    background:#52266E;
                    padding:40px;
                    text-align:center;
                    color:white;
                ">

                    <h1 style="
                        margin:0;
                        font-size:40px;
                        font-weight:bold;
                    ">
                        VetSys
                    </h1>

                    <p style="
                        margin-top:10px;
                        font-size:15px;
                        opacity:.9;
                    ">
                        Software Veterinario
                    </p>

                </div>

                <!-- CONTENIDO -->
                <div style="
                    padding:50px;
                    color:#333;
                ">

                    <h2 style="
                        color:#52266E;
                        margin-top:0;
                        font-size:30px;
                    ">
                        Recuperar contraseña
                    </h2>

                    <p style="
                        font-size:16px;
                        line-height:1.7;
                    ">
                        Hola, solicitaste cambiar tu contraseña.
                    </p>

                    <p style="
                        font-size:16px;
                        line-height:1.7;
                    ">
                        Hacé clic en el siguiente botón para restablecerla:
                    </p>

                    <div style="
                        text-align:center;
                        margin:40px 0;
                    ">

                        <a href="'.$link.'" style="
                            background:#52266E;
                            color:white;
                            text-decoration:none;
                            padding:16px 35px;
                            border-radius:14px;
                            display:inline-block;
                            font-weight:bold;
                            font-size:16px;
                        ">
                            Restablecer contraseña
                        </a>

                    </div>

                    <p style="
                        color:#777;
                        font-size:14px;
                        line-height:1.6;
                    ">
                        Si no solicitaste este cambio, podés ignorar este mensaje.
                    </p>

                </div>

                <!-- FOOTER -->
                <div style="
                    background:#f4f2f7;
                    padding:25px;
                    text-align:center;
                    color:#777;
                    font-size:13px;
                ">
                    © '.date("Y").' VetSys - Software Veterinario
                </div>

            </div>

        </div>
        ';

        // Envía el correo de recuperación.
        $mail->send();

        // Si se envió correctamente, redirige con mensaje OK.
        header("Location: forgot_password.php?mensaje=ok");
        exit;

    } catch (Exception $e) {

        // Si ocurre un error al enviar el correo, redirige con error de mail.
        header("Location: forgot_password.php?mensaje=mail_error");
        exit;
    }

} else {

    // Si no existe un usuario con ese email, redirige con error.
    header("Location: forgot_password.php?mensaje=error");
    exit;
}

?>