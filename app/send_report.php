
<?php

// CONEXIÓN Y CONFIGURACIÓN
require_once __DIR__ . '/../settings/conexion.php';

// PHPMailer
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';
require_once __DIR__ . '/../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

// RESPUESTA JSON
header('Content-Type: application/json; charset=utf-8');

// SOLO PERMITIMOS PETICIONES POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido.']);
    exit;
}

// EL USUARIO DEBE TENER UNA SESIÓN ACTIVA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'Debés iniciar sesión.']);
    exit;
}

// VALIDAR CORREO
$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'mensaje' => 'Correo electrónico inválido.']);
    exit;
}

// VALIDAR ARCHIVOS ADJUNTOS
$archivos = [
    'pdf' => [
        'nombre' => 'VetSys_Reporte_General.pdf',
        'tipo' => 'application/pdf'
    ],
    'excel' => [
        'nombre' => 'VetSys_Reporte_General.xlsx',
        'tipo' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ]
];

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['MAIL_USERNAME'];
    $mail->Password = $_ENV['MAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int) $_ENV['MAIL_PORT'];
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        $_ENV['MAIL_FROM_ADDRESS'],
        $_ENV['MAIL_FROM_NAME']
    );

    $mail->addAddress($email);

    $adjuntos = 0;

    foreach ($archivos as $campo => $config) {

        if (!isset($_FILES[$campo])) {
            continue;
        }

        $archivo = $_FILES[$campo];

        if ($archivo['error'] !== UPLOAD_ERR_OK ||
            $archivo['size'] <= 0 ||
            $archivo['size'] > 10 * 1024 * 1024) {
            throw new RuntimeException('Archivo adjunto inválido.');
        }

        // Verificar formato real del archivo
        $contenido = file_get_contents($archivo['tmp_name']);

        $valido = $campo === 'pdf'
            ? strncmp($contenido, '%PDF-', 5) === 0
            : strncmp($contenido, 'PK', 2) === 0;

        if (!$valido) {
            throw new RuntimeException('Formato de archivo inválido.');
        }

        $mail->addAttachment(
            $archivo['tmp_name'],
            $config['nombre']
        );

        $adjuntos++;
    }

    if ($adjuntos === 0) {
        throw new RuntimeException('No se adjuntaron reportes.');
    }

    $mail->isHTML(true);
    $mail->Subject = 'Reportes generales - VetSys';

    $mail->Body = '
        <div style="font-family:Arial,sans-serif;padding:25px;">
            <h2 style="color:#52266E;">VetSys</h2>
            <p>Software Veterinario</p>
            <p>Se adjuntan los reportes generales del sistema.</p>
            <p>Saludos,<br>Equipo VetSys.</p>
        </div>
    ';

    $mail->AltBody =
        'Se adjuntan los reportes generales del sistema VetSys.';

    $mail->send();

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Reportes enviados correctamente.'
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    error_log('VetSys - Error al enviar reporte: ' . $e->getMessage());

    echo json_encode([
        'ok' => false,
        'mensaje' => 'No se pudo enviar el correo. Verificá los archivos y la configuración.'
    ]);
}
