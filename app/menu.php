<?php
require __DIR__ . '/../settings/conexion.php';

/*
| Obtiene la URL actual para identificar qué módulo está abierto.
*/
$uri = $_SERVER['REQUEST_URI'] ?? '';

/*
| isActive(): devuelve 'nav-active' si la URL actual contiene la ruta recibida.
*/
function isActive(string $path, string $uri): string {
    return str_contains($uri, $path) ? 'nav-active' : '';
}

/*
| isOpen(): devuelve 'show' si alguna de las rutas coincide con la URL actual.
| Se usa para abrir automáticamente el menú desplegable.
*/
function isOpen(array $paths, string $uri): string {
    foreach ($paths as $p) {
        if (str_contains($uri, $p)) {
            return 'show';
        }
    }
    return '';
}

/*
| renderSubmenu(): dibuja un menú desplegable completo
| (versión collapse + flyout para el modo solo íconos).
| $bases = carpetas de módulos que, al estar abiertas, expanden el menú.
| $badge = número a mostrar junto al título (0 = sin badge).
*/
function renderSubmenu(string $id, string $titulo, string $icono, array $items, array $bases, string $uri, int $badge = 0): void {
    $open = isOpen($bases, $uri);
    ?>
    <li class="nav-item has-sub">
        <a class="nav-link <?= $open ? '' : 'collapsed' ?>"
           href="#"
           data-toggle="collapse"
           data-target="#<?= $id ?>"
           aria-expanded="<?= $open ? 'true' : 'false' ?>"
           aria-controls="<?= $id ?>"
           data-label="<?= $titulo ?>">
            <i class="fas fa-fw <?= $icono ?> nav-icon"></i>
            <span class="nav-text"><?= $titulo ?></span>
            <?php if ($badge > 0): ?>
                <span class="menu-badge" title="<?= $badge ?> hoy"><?= $badge ?></span>
            <?php endif; ?>
        </a>

        <div id="<?= $id ?>" class="collapse <?= $open ?>" data-parent="#accordionSidebar">
            <div class="collapse-inner">
                <?php foreach ($items as $item): ?>
                    <a class="collapse-item <?= isActive($item['path'], $uri) ?>" href="/SoftwareVet<?= $item['path'] ?>">
                        <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="icons-flyout">
            <div class="flyout-title"><?= $titulo ?></div>
            <?php foreach ($items as $item): ?>
                <a class="flyout-item <?= isActive($item['path'], $uri) ?>" href="/SoftwareVet<?= $item['path'] ?>">
                    <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </li>
    <?php
}

/*
| Inicio de sesión
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
| Perfil del usuario (con escape para evitar XSS)
*/
$nombrePerfil       = $_SESSION['nombre_perfil'] ?? 'Sin perfil';
$nombrePerfilSeguro = htmlspecialchars($nombrePerfil);

/*
| Visibilidad del bloque Administración.
| Está DESACTIVADA por defecto (todos lo ven). Para ocultarlo a los perfiles
| que no correspondan, poné $restringirAdmin = true y ajustá los nombres de
| $perfilesAdmin tal cual figuran en tu tabla de perfiles.
| Es solo visual: la seguridad real debe validarse en cada página del servidor.
*/
$restringirAdmin = false;
$perfilesAdmin   = ['Administrador'];
$mostrarAdmin    = !$restringirAdmin || in_array($nombrePerfil, $perfilesAdmin, true);

$idPerfil = $_SESSION['id_perfil'];

$sql = "
SELECT
    m.id_modulo,
    m.nombre_modulo,
    m.ruta,
    m.icono,
    m.categoria,
    m.orden
FROM modulo m
INNER JOIN perfil_modulo pm
    ON pm.id_modulo = m.id_modulo
WHERE
    pm.id_perfil = ?
    AND m.estado = 1
ORDER BY
    m.categoria,
    m.orden
";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param($stmt, "i", $idPerfil);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$menu = [];

while ($fila = mysqli_fetch_assoc($resultado)) {

    $menu[$fila['categoria']][] = $fila;

}
/*
| Definición centralizada de subitems.
*/
$menuTurnos = [
    [
        'path'  => '/modules/appointments/index.php',
        'icon'  => 'fa-list-ul',
        'label' => 'Listado de Turnos'
    ]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VETSYS</title>

    <link rel="icon" href="/SoftwareVet/img/logoMenu.ico" type="image/x-icon">
    <link href="/SoftwareVet/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet">
    <link href="/SoftwareVet/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/SoftwareVet/css/style_menu.css" rel="stylesheet">
</head>

<body id="page-top" data-sidebar="full">

<div id="wrapper">

    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/SoftwareVet/app/inicio.php">
            <div class="sidebar-brand-icon mr-2">
                <i class="fas fa-paw"></i>
            </div>
            <div>
                <div class="brand-name">VETSYS</div>
                <div class="brand-sub">Sistema Veterinario</div>
            </div>
        </a>

        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link <?= isActive('/app/inicio.php', $uri) ?>" href="/SoftwareVet/app/inicio.php" data-label="Dashboard">
                <i class="fas fa-fw fa-th-large nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

       <hr class="sidebar-divider">

<div class="sidebar-heading">Gestión</div>

<?php
if (!empty($menu['Gestión'])):

    foreach ($menu['Gestión'] as $modulo):
?>

<li class="nav-item <?= isActive(parse_url($modulo['ruta'], PHP_URL_PATH), $uri) ?>">

    <a class="nav-link"
       href="<?= htmlspecialchars($modulo['ruta']) ?>"
       data-label="<?= htmlspecialchars($modulo['nombre_modulo']) ?>">

        <i class="fas fa-fw <?= htmlspecialchars($modulo['icono']) ?> nav-icon"></i>

        <span class="nav-text">
            <?= htmlspecialchars($modulo['nombre_modulo']) ?>
        </span>

    </a>

</li>

<?php
    endforeach;

endif;
?>

<hr class="sidebar-divider">

<div class="sidebar-heading">Atención</div>

<?php if (!empty($menu['Atención'])): ?>

<?php foreach ($menu['Atención'] as $modulo): ?>

<li class="nav-item <?= isActive(parse_url($modulo['ruta'], PHP_URL_PATH), $uri) ?>">

    <a class="nav-link"
       href="<?= htmlspecialchars($modulo['ruta']) ?>"
       data-label="<?= htmlspecialchars($modulo['nombre_modulo']) ?>">

        <i class="fas fa-fw <?= htmlspecialchars($modulo['icono']) ?> nav-icon"></i>

        <span class="nav-text">
            <?= htmlspecialchars($modulo['nombre_modulo']) ?>
        </span>

    </a>

</li>

<?php endforeach; ?>

<?php endif; ?>
<hr class="sidebar-divider">

<div class="sidebar-heading">
    Administración
</div>

<?php if (!empty($menu['Administración'])): ?>

<?php foreach ($menu['Administración'] as $modulo): ?>

<li class="nav-item <?= isActive(parse_url($modulo['ruta'], PHP_URL_PATH), $uri) ?>">

    <a class="nav-link"
       href="<?= htmlspecialchars($modulo['ruta']) ?>"
       data-label="<?= htmlspecialchars($modulo['nombre_modulo']) ?>">

        <i class="fas fa-fw <?= htmlspecialchars($modulo['icono']) ?> nav-icon"></i>

        <span class="nav-text">
            <?= htmlspecialchars($modulo['nombre_modulo']) ?>
        </span>

    </a>

</li>

<?php endforeach; ?>

<?php endif; ?>



<hr class="sidebar-divider">

<div class="sidebar-heading">Soporte</div>

<?php if (!empty($menu['Soporte'])): ?>

<?php foreach ($menu['Soporte'] as $modulo): ?>

<li class="nav-item <?= isActive(parse_url($modulo['ruta'], PHP_URL_PATH), $uri) ?>">

    <a class="nav-link"
       href="<?= htmlspecialchars($modulo['ruta']) ?>"
       data-label="<?= htmlspecialchars($modulo['nombre_modulo']) ?>">

        <i class="fas fa-fw <?= htmlspecialchars($modulo['icono']) ?> nav-icon"></i>

        <span class="nav-text">
            <?= htmlspecialchars($modulo['nombre_modulo']) ?>
        </span>

    </a>

</li>

<?php endforeach; ?>

<?php endif; ?>

    </ul>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php
            $usuarioNombre = htmlspecialchars($_SESSION['usuario'] ?? 'Usuario');

            // Filtra partes vacías (espacios dobles, etc.) antes de obtener iniciales.
            $partes       = array_filter(explode(' ', $usuarioNombre), fn($p) => $p !== '');
            $inicialesArr = array_map(fn($p) => strtoupper($p[0]), array_values($partes));
            $iniciales    = implode('', array_slice($inicialesArr, 0, 2));

            if (!$iniciales) {
                $iniciales = strtoupper(substr($usuarioNombre, 0, 2));
            }
            ?>

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">

                <button class="btn-sidebar-toggle d-none d-md-flex mr-3" id="sidebarModeBtn" title="Cambiar vista del menú">
                    <span class="bar bar1"></span>
                    <span class="bar bar2"></span>
                    <span class="bar bar3"></span>
                </button>

                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars text-gray-500"></i>
                </button>

                <div class="ml-auto d-flex align-items-center">

                    <div class="topbar-clock d-none d-md-block mr-3 text-right">
                        <div class="clock-time" id="topbar-hora">--:--</div>
                        <div class="clock-date" id="topbar-fecha"></div>
                    </div>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <div class="nav-item dropdown no-arrow">

                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                            href="#"
                            id="userDropdown"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">

                            <div class="user-avatar mr-2"><?= $iniciales ?></div>

                            <div class="d-none d-lg-block text-left mr-1">
                                <div style="font-size:.85rem; font-weight:700; color:#52266E; line-height:1.2;">
                                    <span id="usuarioLogueadoTop"></span>
                                </div>
                                <div style="font-size:.7rem; color:#aaa; line-height:1;">
                                    <?= $nombrePerfilSeguro ?>
                                </div>
                            </div>

                            <i class="fas fa-chevron-down fa-xs text-gray-400 ml-1"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in user-dropdown"
                            aria-labelledby="userDropdown">

                            <div class="user-info">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar mr-2" style="width:40px;height:40px;font-size:1rem;">
                                        <?= $iniciales ?>
                                    </div>
                                    <div>
                                        <div class="user-nombre"><span id="usuarioLogueado"></span></div>
                                        <div class="user-rol"><?= $nombrePerfilSeguro ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item py-2" href="/SoftwareVet/app/logout.php"
                               onclick="
                                   localStorage.removeItem('nombre');
                                   localStorage.removeItem('apellido');
                               ">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                                <span class="text-danger">Cerrar Sesión</span>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <script>
                /*
                | Reloj y fecha en tiempo real (cada segundo).
                */
                (function tickClock() {
                    var now   = new Date();
                    var hh    = now.getHours().toString().padStart(2, '0');
                    var mm    = now.getMinutes().toString().padStart(2, '0');
                    var ss    = now.getSeconds().toString().padStart(2, '0');
                    var dias  = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                    var meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
                    var eH    = document.getElementById('topbar-hora');
                    var eF    = document.getElementById('topbar-fecha');

                    if (eH) eH.textContent = hh + ':' + mm + ':' + ss;
                    if (eF) eF.textContent = dias[now.getDay()] + ' ' + now.getDate() + ' ' + meses[now.getMonth()] + ' ' + now.getFullYear();

                    setTimeout(tickClock, 1000);
                })();

                /*
                | Modo del menú lateral: full -> icons -> hidden.
                | Guarda la preferencia en localStorage.
                */
                (function() {
                    var MODES  = ['full', 'icons', 'hidden'];
                    var LABELS = ['Menú completo', 'Solo íconos', 'Menú oculto'];
                    var body   = document.body;
                    var btn    = document.getElementById('sidebarModeBtn');

                    var currentMode = localStorage.getItem('sidebarMode');
                    if (MODES.indexOf(currentMode) === -1) currentMode = 'full';
                    applyMode(currentMode);

                    if (btn) {
                        btn.addEventListener('click', function() {
                            var next = MODES[(MODES.indexOf(currentMode) + 1) % MODES.length];
                            applyMode(next);
                            localStorage.setItem('sidebarMode', next);
                        });
                    }

                    function applyMode(mode) {
                        currentMode = mode;
                        body.setAttribute('data-sidebar', mode);
                        if (btn) btn.setAttribute('title', 'Modo actual: ' + LABELS[MODES.indexOf(mode)] + ' → click para cambiar');
                    }
                })();

                /*
                | Menú responsive para móviles.
                */
                (function() {
                    var toggleTop = document.getElementById('sidebarToggleTop');
                    var overlay   = document.getElementById('sidebarOverlay');
                    var body      = document.body;

                    if (toggleTop) {
                        toggleTop.addEventListener('click', function(e) {
                            e.stopPropagation();
                            body.classList.toggle('sidebar-mobile-open');
                        });
                    }

                    if (overlay) {
                        overlay.addEventListener('click', function() {
                            body.classList.remove('sidebar-mobile-open');
                        });
                    }
                })();

                /*
                | Nombre del usuario logueado.
                | Lo toma de localStorage o, si no está, de session.php.
                */
                document.addEventListener('DOMContentLoaded', function() {
                    var nombreGuardado   = localStorage.getItem('nombre');
                    var apellidoGuardado = localStorage.getItem('apellido');

                    if (nombreGuardado && apellidoGuardado) {
                        mostrarUsuario(nombreGuardado, apellidoGuardado);
                    } else {
                        fetch('/SoftwareVet/app/session.php')
                            .then(function(response) { return response.json(); })
                            .then(function(data) {
                                localStorage.setItem('nombre',   data.nombre);
                                localStorage.setItem('apellido', data.apellido);
                                mostrarUsuario(data.nombre, data.apellido);
                            });
                    }

                    function mostrarUsuario(nombre, apellido) {
                        var nombreCompleto = nombre + ' ' + apellido;
                        var top  = document.getElementById('usuarioLogueadoTop');
                        var drop = document.getElementById('usuarioLogueado');
                        if (top)  top.textContent  = nombreCompleto;
                        if (drop) drop.textContent = nombreCompleto;
                    }
                });
            </script>

<!-- FOOTER GENERAL DEL SISTEMA -->
<footer class="footer-vetsys">
    <span>© 2026 VetSys · Todos los derechos reservados</span>

    <span class="footer-vetsys-right">
        Software de Gestión Veterinaria
    </span>
</footer>
