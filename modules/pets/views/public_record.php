<?php
/* ===== Helpers ===== */

// Escapa un valor para HTML; si viene vacío muestra "Sin especificar".
function mostrarDato($valor): string
{
    $texto = (string)($valor ?? '');

    return htmlspecialchars(
        $texto === '' ? 'Sin especificar' : $texto,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Convierte un teléfono argentino al formato de WhatsApp (549 + 10 dígitos).
// Devuelve null si no se puede interpretar.
function normalizarWhatsApp(string $telefono): ?string
{
    $n = preg_replace('/\D/', '', $telefono);

    // Quita el prefijo de país (54) y/o el 0 de discado.
    $n = preg_replace('/^(54)?0?/', '', $n);

    // Quita el 9 de celular si ya venía incluido.
    if (strlen($n) === 11 && $n[0] === '9') {
        $n = substr($n, 1);
    }

    // Quita el "15": código de área (2-4) + 15 + número (6-8).
    if (strlen($n) !== 10 && preg_match('/^(\d{2,4})15(\d{6,8})$/', $n, $m)) {
        $n = $m[1] . $m[2];
    }

    return strlen($n) === 10 ? '549' . $n : null;
}

/* ===== Datos de la mascota ===== */

$nombre = $mascota['nombre_mascota'] ?? '';

$sexo = ['M' => 'Macho', 'H' => 'Hembra'][$mascota['sexo'] ?? ''] ?? 'Sin especificar';

/* ===== Contacto autorizado ===== */

$telefono = !empty($mascota['mostrar_telefono']) ? ($mascota['telefono'] ?? null) : null;

$mostrarUbicacion = !empty($mascota['mostrar_domicilio']) && !empty($urlMaps);

$numeroWhatsApp = $telefono ? normalizarWhatsApp((string)$telefono) : null;
$urlWhatsApp    = null;

if ($numeroWhatsApp) {
    $mensaje = "Hola, encontré a $nombre. Escaneé su código QR de VetSys "
            . "y quisiera comunicarme para devolver a tu mascota.";

    $urlWhatsApp = "https://wa.me/$numeroWhatsApp?text=" . rawurlencode($mensaje);
}

/* ===== Descripción (especie · raza · edad) ===== */

$edad = $mascota['edad'] ?? '';
$edadTexto = $edad !== '' ? trim("$edad " . ($mascota['unidad_edad'] ?? '')) : '';

$descripcion = implode(' · ', array_filter([
    $mascota['nombre_especie'] ?? '',
    $mascota['raza'] ?? '',
    $edadTexto,
]));

/* ===== Condiciones y cuidados especiales ===== */

$guardadas   = (string)($mascota['condicion_especial'] ?? '');
$condiciones = json_decode($guardadas, true);

if (!is_array($condiciones)) {
    $condiciones = $guardadas !== '' ? [$guardadas] : [];
}

// Reemplaza "Otra" por la condición que escribió el usuario.
if (!empty($mascota['condicion_otra'])) {
    $condiciones = array_map(
        fn($c) => $c === 'Otra' ? $mascota['condicion_otra'] : $c,
        $condiciones
    );
}

$cuidados = trim((string)($mascota['cuidados_especiales'] ?? ''));

$estadoIdentificacion = 'Mascota identificada';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="theme-color" content="#52266E">
    <link href="/SoftwareVet/css/public_record_style.css" rel="stylesheet">
    <link href="/SoftwareVet/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <title>Ficha de <?= mostrarDato($nombre) ?> | VetSys</title>
</head>

<body>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="theme-color" content="#52266E">
    <link href="/SoftwareVet/css/public_record_style.css" rel="stylesheet">
    <link href="/SoftwareVet/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <title>Ficha de <?= mostrarDato($nombre) ?> | VetSys</title>
</head>

<body>

<div class="pagina-ficha">

    <div class="barra-superior">
        <div class="logo-vetsys" aria-hidden="true">
            <svg class="icono" viewBox="0 0 24 24">
                <circle cx="5.5" cy="11" r="1.8"/>
                <circle cx="9.5" cy="6.5" r="1.8"/>
                <circle cx="14.5" cy="6.5" r="1.8"/>
                <circle cx="18.5" cy="11" r="1.8"/>
                <path d="M12 12.5c-2.6 0-5 2.6-5 4.8 0 1.6 1.4 2.2 2.8 2.2 1 0 1.6-.4 2.2-.4s1.2.4 2.2.4c1.4 0 2.8-.6 2.8-2.2 0-2.2-2.4-4.8-5-4.8z"/>
            </svg>
        </div>

        <div>
            <h1>VETSYS</h1>
            <p>Ficha pública de identificación</p>
        </div>
    </div>

    <main class="tarjeta-ficha">

        <div class="encabezado-mascota">

            <?php if (!empty($mascota['foto'])): ?>
                <div class="foto-mascota"
                    style="position:relative;z-index:1;width:100%;aspect-ratio:4/3;margin-bottom:18px;border-radius:20px;overflow:hidden;border:3px solid rgba(255,255,255,.35);">
                    <img
                        src="/SoftwareVet/<?= htmlspecialchars(ltrim($mascota['foto'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                        alt="Fotografía de <?= mostrarDato($nombre) ?>"
                        style="display:block;width:100%;height:100%;max-width:100%;object-fit:cover;object-position:center 30%;"
                    >
                </div>
            <?php endif; ?>

            <div class="estado-mascota">
                <svg class="icono" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9.5"/>
                    <path d="M8 12.5l3 3 5-6"/>
                </svg>
                <?= mostrarDato($estadoIdentificacion) ?>
            </div>

            <h2 class="nombre-mascota">
                <?= mostrarDato($nombre) ?>
            </h2>

            <p class="descripcion-mascota">
                <?= mostrarDato($descripcion !== '' ? $descripcion : 'Mascota registrada en VetSys') ?>
            </p>

        </div>

        <div class="contenido-ficha">

            <?php if (!empty($telefono) || $mostrarUbicacion): ?>
                <section class="acciones-contacto" aria-label="Acciones">

                    <!-- WHATSAPP -->
                    <?php if ($urlWhatsApp !== null): ?>
                        <a
                            class="boton-contacto boton-whatsapp"
                            href="<?= htmlspecialchars($urlWhatsApp, ENT_QUOTES, 'UTF-8') ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <i class="fab fa-whatsapp" aria-hidden="true"></i>
                            Enviar WhatsApp al propietario
                        </a>
                    <?php endif; ?>

                    <!-- LLAMADA TELEFÓNICA -->
                    <?php if (!empty($telefono)): ?>
                        <a
                            class="boton-contacto boton-llamar"
                            href="tel:<?= htmlspecialchars(
                                preg_replace('/[^0-9+]/', '', $telefono),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                            <svg class="icono" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>
                            </svg>
                            Contactar al propietario
                        </a>
                    <?php endif; ?>

                    <!-- GOOGLE MAPS -->
                    <?php if ($mostrarUbicacion): ?>
                        <a
                            class="boton-contacto boton-mapa"
                            href="<?= htmlspecialchars($urlMaps, ENT_QUOTES, 'UTF-8') ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <svg class="icono" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/>
                                <circle cx="12" cy="9.5" r="2.5"/>
                            </svg>
                            Ver ubicación en Google Maps
                        </a>
                    <?php endif; ?>

                </section>
            <?php endif; ?>

            <section class="seccion-ficha" style="<?= (!empty($telefono) || $mostrarUbicacion) ? '' : 'margin-top:24px;' ?>">
                <h3 class="titulo-seccion">Información básica</h3>

                <div class="datos-mascota">
                    <div class="dato-mascota">
                        <span class="etiqueta-dato">Especie</span>
                        <span class="valor-dato">
                            <?= mostrarDato($mascota['nombre_especie'] ?? '') ?>
                        </span>
                    </div>

                    <div class="dato-mascota">
                        <span class="etiqueta-dato">Raza</span>
                        <span class="valor-dato">
                            <?= mostrarDato($mascota['raza'] ?? '') ?>
                        </span>
                    </div>

                    <div class="dato-mascota">
                        <span class="etiqueta-dato">Sexo</span>
                        <span class="valor-dato">
                            <?= mostrarDato($sexo) ?>
                        </span>
                    </div>

                    <div class="dato-mascota">
                        <span class="etiqueta-dato">Color</span>
                        <span class="valor-dato">
                            <?= mostrarDato($mascota['color'] ?? '') ?>
                        </span>
                    </div>
                </div>
            </section>

            <section class="seccion-ficha">
                <h3 class="titulo-seccion">Información importante</h3>

                <?php if (!empty($condiciones)): ?>
                    <div class="condiciones-mascota">
                        <?php foreach ($condiciones as $condicion): ?>
                            <span class="condicion-mascota">
                                <?= mostrarDato($condicion) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="sin-datos">
                        <svg class="icono" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9.5"/>
                            <path d="M12 11v5M12 8h.01"/>
                        </svg>
                        No se registraron condiciones especiales.
                    </div>
                <?php endif; ?>
            </section>

            <section class="seccion-ficha">
                <h3 class="titulo-seccion">¿Qué debe saber quien la encuentre?</h3>

                <?php if ($cuidados !== ''): ?>
                    <div class="alerta-cuidados">
                        <strong>
                            <svg class="icono" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3l10 18H2L12 3z"/>
                                <path d="M12 10v5M12 18h.01"/>
                            </svg>
                            Cuidados especiales
                        </strong>
                        <p><?= mostrarDato($cuidados) ?></p>
                    </div>
                <?php else: ?>
                    <div class="sin-datos">
                        <svg class="icono" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9.5"/>
                            <path d="M12 11v5M12 8h.01"/>
                        </svg>
                        No se registraron cuidados especiales para esta mascota.
                    </div>
                <?php endif; ?>
            </section>

            <div class="pie-ficha">
                Si encontraste esta mascota, ayudala a volver a casa.
            </div>

        </div>
    </main>

</div>

</body>
</html>
