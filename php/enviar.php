<?php
require '../config/conexion.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['email'])) {
    header("Location: olvide.php?mensaje=error");
    exit;
}

$email = trim($_POST['email']);

$sql = "SELECT * FROM usuario WHERE email = '$email'";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    header("Location: olvide.php?mensaje=error");
    exit;
}

$user = mysqli_fetch_assoc($resultado);

if ($user) {

    $token = bin2hex(random_bytes(32));

    $update = "UPDATE usuario 
               SET reset_token = '$token' 
               WHERE email = '$email'";

    mysqli_query($conexion, $update);

    $link = "http://localhost/SoftwareVet/php/reset.php?token=$token";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'andreamorelucp@gmail.com';
        $mail->Password = 'axmo yhtu xwum tkgb';
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('TU_GMAIL@gmail.com', 'Software Veterinario');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperar contraseña - Software Veterinario';

        $mail->Body = "
            <h2>Recuperar contraseña</h2>
            <p>Hola, solicitaste cambiar tu contraseña.</p>
            <p>Hacé clic en el siguiente enlace:</p>
            <a href='$link'>Restablecer contraseña</a>
            <br><br>
            <p>Si no solicitaste esto, ignorá este mensaje.</p>
        ";

        $mail->send();

        header("Location: olvide.php?mensaje=ok");
        exit;

    } catch (Exception $e) {
        header("Location: olvide.php?mensaje=mail_error");
        exit;
    }

} else {
    header("Location: olvide.php?mensaje=error");
    exit;
}
?>