<?php
require_once '../settings/conexion.php';
require_once 'validateRoute.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['email'])) {
    header("Location: forgot_password.php?mensaje=error");
    exit;
}

$email = trim($_POST['email']);


$sql = "SELECT * FROM usuario WHERE email = '$email'";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    header("Location: forgot_password.php?mensaje=error");
    exit;
}

$user = mysqli_fetch_assoc($resultado);


if ($user) {

    $token = bin2hex(random_bytes(32));

    $update = "UPDATE usuario 
               SET reset_token = '$token'
               WHERE email = '$email'";

    mysqli_query($conexion, $update);

    $link = "http://localhost/SoftwareVet/php/reset_password.php?token=$token";

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = 'vetsys.softwareveterinario@gmail.com';
        $mail->Password   = 'fvzv elnl znyf herr';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom(
            'vetsys.softwareveterinario@gmail.com',
            'Software Veterinario'
        );

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = 'Recuperar contraseña - VetSys';

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

        $mail->send();

        header("Location: forgot_password.php?mensaje=ok");
        exit;

    } catch (Exception $e) {

        header("Location: forgot_password.php?mensaje=mail_error");
        exit;
    }

} else {

    header("Location: forgot_password.php?mensaje=error");
    exit;
}
?>