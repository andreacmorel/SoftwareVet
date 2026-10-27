<?php
/* =====================================================
VISTA PÚBLICA DEL QR - PREPARACIÓN DE DATOS
Los datos llegan desde PetController::qr()
===================================================== */

/**
 * Escapa un valor para mostrarlo de forma segura en HTML.
 *
 * Convierte caracteres especiales (<, >, ", ', &) en entidades HTML,
 * lo que previene ataques XSS si el texto contiene código malicioso.
 * Si el valor es null, devuelve una cadena vacía.
 */
function escaparQR($valor): string{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
/* ----- Datos básicos de la mascota -----
    Si algún dato no existe, se usa una cadena vacía
   para evitar errores de "undefined index". */

$nombre  = $mascota['nombre_mascota'] ?? '';
$especie = $mascota['nombre_especie'] ?? '';
$raza    = $mascota['raza'] ?? '';

/* ----- La ficha se puede mostrar públicamente? -----
    Deben cumplirse DOS condiciones al mismo tiempo:
    1. La ficha fue marcada como pública (ficha_publica = 1).
    2. El propietario dio su consentimiento para el QR (consentimiento_qr = 1).
   Si falta alguna, $publicada será false y no se deben mostrar los datos. */
$publicada = ($mascota['ficha_publica'] ?? 0) == 1
        && ($mascota['consentimiento_qr'] ?? 0) == 1;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR de mascota</title>
    <link href="../../css/qr_style.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>

<body>

<div class="qr-wrapper">
    <main class="qr-card">

        <header class="qr-header">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-paw"></i>
                </div>
                <h1 class="brand-name">VETSYS</h1>
            </div>

            <p class="brand-subtitle">
                Sistema de gestión veterinaria
            </p>
        </header>

        <section class="qr-body">

            <div class="pet-summary">
                <h2 class="pet-name">
                    <?= escaparQR($nombre) ?>
                </h2>

                <p class="pet-meta">
                    <?= escaparQR($especie) ?>
                    <?php if (!empty($raza)): ?>
                        · <?= escaparQR($raza) ?>
                    <?php endif; ?>
                </p>

                <?php if ($publicada): ?>
                    <div class="status-badge status-active">
                        <i class="fas fa-check-circle"></i>
                        Ficha pública habilitada
                    </div>
                <?php else: ?>
                    <div class="status-badge status-inactive">
                        <i class="fas fa-exclamation-triangle"></i>
                        Ficha pública no habilitada
                    </div>
                <?php endif; ?>
            </div>

            <div class="qr-box">
                <img
                    src="<?= escaparQR($imagenQR) ?>"
                    alt="Código QR de <?= escaparQR($nombre) ?>"
                    class="qr-image"
                >
            </div>

            <p class="qr-text">
                Escaneá este código para consultar la ficha
                de identificación de la mascota desde un celular.
            </p>

            <div class="qr-footer no-print">
                <button
                    type="button"
                    class="btn-print"
                    onclick="window.print()"
                >
                    <i class="fas fa-print"></i>
                    Imprimir código QR
                </button>

                <div class="print-note">
                    Recomendado para collar, chapa o ficha de identificación.
                </div>
            </div>

        </section>

    </main>
</div>

</body>
</html>