<?php
require_once __DIR__ . '/../settings/conexion.php';

/*
| Obtiene la URL actual para identificar qué módulo está abierto.
*/
$uri = $_SERVER['REQUEST_URI'] ?? '';

/*
| Función isActive()
| Verifica si la ruta actual contiene la ruta recibida.
| Si coincide, devuelve la clase CSS 'nav-active'.
| Se utiliza para resaltar la opción activa del menú.
| Ahora recibe $uri como parámetro en vez de usar global.
*/
function isActive(string $path, string $uri): string {
    return (str_contains($uri, $path)) ? 'nav-active' : '';
}

/*
| Función isOpen()
| Recorre un conjunto de rutas y verifica si alguna coincide
| con la URL actual. Si coincide devuelve 'show' para abrir
| automáticamente el menú desplegable.
| Ahora recibe $uri como parámetro en vez de usar global.
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
| Inicio de sesión
| Verifica si existe una sesión activa. Si no existe,
| la crea para poder acceder a los datos del usuario.
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
| Obtención del perfil del usuario
| Recupera el nombre del perfil almacenado en la sesión.
| Si por algún motivo no existe, muestra "Sin perfil"
| como valor predeterminado.
*/
$nombrePerfil = $_SESSION['nombre_perfil'] ?? 'Sin perfil';

/*
| Seguridad de salida
| Convierte caracteres especiales para evitar problemas
| de visualización o posibles ataques XSS.
*/
$nombrePerfilSeguro = htmlspecialchars($nombrePerfil);


/*
| Contador de turnos del día
| Obtiene la cantidad de turnos registrados para la fecha actual.
| Se utiliza para mostrar la notificación en el menú lateral.
| Usa prepared statement como buena práctica.
*/
$turnosHoyCount = 0;

if (isset($conexion)) {

    $stmtCount = $conexion->prepare("
        SELECT COUNT(*) AS total
        FROM turnos
        WHERE fecha = CURDATE()
    ");

    if ($stmtCount && $stmtCount->execute()) {
        $resCount = $stmtCount->get_result();
        if ($rowTurnos = $resCount->fetch_object()) {
            $turnosHoyCount = (int) $rowTurnos->total;
        }
        $stmtCount->close();
    }
}

/*
| Definición centralizada de subitems de menús desplegables.
| Centraliza los ítems para evitar duplicación entre collapse y flyout.
| Si se agrega o modifica un ítem, solo se cambia en un lugar.
*/
$menuTurnos = [
    ['path' => '/modules/appointments/index.php',     'icon' => 'fa-list-ul',      'label' => 'Listado'],
    ['path' => '/modules/appointments/calendario.php','icon' => 'fa-calendar-alt', 'label' => 'Calendario'],
    ['path' => '/modules/appointments/create.php',    'icon' => 'fa-plus-circle',  'label' => 'Nuevo Turno'],
];

$menuAdmin = [
    ['path' => '/modules/users/index.php',          'icon' => 'fa-users',       'label' => 'Usuarios'],
    ['path' => '/modules/profiles/index.php',       'icon' => 'fa-user-shield', 'label' => 'Perfiles'],
    ['path' => '/modules/system_modules/index.php', 'icon' => 'fa-layer-group', 'label' => 'Módulos'],
    ['path' => '/modules/audit/index.php',          'icon' => 'fa-clipboard-list', 'label' => 'Auditoría'],
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var m = localStorage.getItem('sidebarMode');
            if (m && m !== 'full') document.body.setAttribute('data-sidebar', m);
        });
    </script>
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
            <a class="nav-link <?= isActive('/php/inicio', $uri) ?>" href="/SoftwareVet/app/inicio.php" data-label="Dashboard">
                <i class="fas fa-fw fa-th-large nav-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Gestión</div>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/pets/', $uri) ?>" href="/SoftwareVet/modules/pets/index.php" data-label="Mascotas">
                <i class="fas fa-fw fa-paw nav-icon"></i>
                <span>Mascotas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/clients/', $uri) ?>" href="/SoftwareVet/modules/clients/index.php" data-label="Clientes">
                <i class="fas fa-fw fa-users nav-icon"></i>
                <span>Clientes</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/professionals/', $uri) ?>" href="/SoftwareVet/modules/professionals/index.php" data-label="Profesionales">
                <i class="fas fa-fw fa-user-md nav-icon"></i>
                <span>Profesionales</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Agenda</div>

        <?php
        $turnosOpen = isOpen(['/modules/appointments/'], $uri);
        $turnosPaths = array_column($menuTurnos, 'path');
        ?>

        <li class="nav-item has-sub">

            <a class="nav-link <?= $turnosOpen ? '' : 'collapsed' ?>"
                href="#"
                data-toggle="collapse"
                data-target="#collapseTurnos"
                aria-expanded="<?= $turnosOpen ? 'true' : 'false' ?>"
                aria-controls="collapseTurnos"
                data-label="Turnos">

                <i class="fas fa-fw fa-calendar-check nav-icon"></i>
                <span>Turnos</span>

                <?php if ($turnosHoyCount > 0): ?>
                    <span class="menu-badge"><?= $turnosHoyCount ?></span>
                <?php endif; ?>
            </a>

            <div id="collapseTurnos" class="collapse <?= $turnosOpen ?>" data-parent="#accordionSidebar">
                <div class="collapse-inner">
                    <?php foreach ($menuTurnos as $item): ?>
                        <a class="collapse-item <?= isActive($item['path'], $uri) ?>" href="/SoftwareVet<?= $item['path'] ?>">
                            <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="icons-flyout">
                <div class="flyout-title">Turnos</div>
                <?php foreach ($menuTurnos as $item): ?>
                    <a class="flyout-item <?= isActive($item['path'], $uri) ?>" href="/SoftwareVet<?= $item['path'] ?>">
                        <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Clínica</div>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/medical_records/', $uri) ?>" href="/SoftwareVet/modules/medical_records/index.php" data-label="Historia Clínica">
                <i class="fas fa-fw fa-stethoscope nav-icon"></i>
                <span>Historia Clínica</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/species/', $uri) ?>" href="/SoftwareVet/modules/species/index.php" data-label="Especies">
                <i class="fas fa-fw fa-dna nav-icon"></i>
                <span>Especies</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Administración</div>

        <?php
        $adminOpen = isOpen([
            '/modules/users/',
            '/modules/profiles/',
            '/modules/system_modules/'
        ], $uri);
        ?>

        <li class="nav-item has-sub">

            <a class="nav-link <?= $adminOpen ? '' : 'collapsed' ?>"
                href="#"
                data-toggle="collapse"
                data-target="#collapseAdministracion"
                aria-expanded="<?= $adminOpen ? 'true' : 'false' ?>"
                aria-controls="collapseAdministracion"
                data-label="Administración">

                <i class="fas fa-fw fa-sliders-h nav-icon"></i>
                <span>Administración</span>
            </a>

            <div id="collapseAdministracion" class="collapse <?= $adminOpen ?>" data-parent="#accordionSidebar">
                <div class="collapse-inner">
                    <?php foreach ($menuAdmin as $item): ?>
                        <a class="collapse-item <?= isActive($item['path'], $uri) ?>" href="/SoftwareVet<?= $item['path'] ?>">
                            <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Flyout para el modo solo íconos (antes faltaba) -->
            <div class="icons-flyout">
                <div class="flyout-title">Administración</div>
                <?php foreach ($menuAdmin as $item): ?>
                    <a class="flyout-item <?= isActive($item['path'], $uri) ?>" href="/SoftwareVet<?= $item['path'] ?>">
                        <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </li>

    </ul>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php
            $usuarioNombre = htmlspecialchars($_SESSION['usuario'] ?? 'Usuario');

            // Filtra partes vacías (espacios dobles, etc.) antes de obtener iniciales.
            $partes = array_filter(explode(' ', $usuarioNombre), fn($p) => $p !== '');
            $inicialesArr = array_map(fn($p) => strtoupper($p[0]), array_values($partes));
            $iniciales = implode('', array_slice($inicialesArr, 0, 2));

            if (!$iniciales) {
                $iniciales = strtoupper(substr($usuarioNombre, 0, 2));
            }

            $notifTurnos = [];

            if (isset($conexion)) {
                $stmtNotif = $conexion->prepare("
                    SELECT t.hora, t.motivo, t.estado, m.nombre_mascota,
                    CONCAT(per.nombre_persona, ' ', per.apellido_persona) AS profesional
                    FROM turnos t
                    INNER JOIN mascota m     ON t.id_mascota = m.id_mascota
                    INNER JOIN profesional p ON t.id_profesional = p.id_profesional
                    INNER JOIN persona per   ON p.id_persona = per.id_persona
                    WHERE t.fecha = CURDATE()
                    AND t.estado IN ('pendiente','confirmado','en_atencion')
                    ORDER BY t.hora ASC
                    LIMIT 8
                ");

                if ($stmtNotif && $stmtNotif->execute()) {
                    $resNotif = $stmtNotif->get_result();
                    while ($n = $resNotif->fetch_object()) {
                        $notifTurnos[] = $n;
                    }
                    $stmtNotif->close();
                }
            }

            $notifCount = count($notifTurnos);

            $estadoColors = [
                'pendiente'   => '#f6c23e',
                'confirmado'  => '#36b9cc',
                'en_atencion' => '#4e73df',
            ];
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
                                        <div class="user-rol">
                                            <?= $nombrePerfilSeguro ?>
                                        </div>
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
                | Reloj y fecha en tiempo real
                | Actualiza la hora y la fecha mostradas en la barra superior
                | del sistema cada segundo.
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
                | Cambio de modo del menú lateral
                | Permite alternar entre:
                | - Menú completo
                | - Solo íconos
                | - Menú oculto
                | Guarda la preferencia en localStorage.
                */
                (function() {
                    var MODES  = ['full', 'icons', 'hidden'];
                    var LABELS = ['Menú completo', 'Solo íconos', 'Menú oculto'];
                    var body   = document.body;
                    var btn    = document.getElementById('sidebarModeBtn');

                    var currentMode = localStorage.getItem('sidebarMode') || 'full';
                    applyMode(currentMode);

                    if (btn) {
                        btn.addEventListener('click', function() {
                            var idx  = MODES.indexOf(currentMode);
                            var next = MODES[(idx + 1) % MODES.length];
                            applyMode(next);
                            localStorage.setItem('sidebarMode', next);
                        });
                    }

                    function applyMode(mode) {
                        currentMode = mode;
                        body.setAttribute('data-sidebar', mode);
                        var idx = MODES.indexOf(mode);
                        if (btn) btn.setAttribute('title', 'Modo actual: ' + LABELS[idx] + ' → click para cambiar');
                    }
                })();

                /*
                | Menú responsive para dispositivos móviles
                | Permite abrir y cerrar el sidebar en pantallas pequeñas.
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
                | Nombre del usuario logueado
                | Recupera nombre y apellido desde localStorage o desde session.php
                | y los muestra en la topbar y en el dropdown.
                */
                document.addEventListener('DOMContentLoaded', function() {
                    //Espera a que la página termine de cargar antes de ejecutar el código.
                    var nombreGuardado   = localStorage.getItem('nombre');
                    var apellidoGuardado = localStorage.getItem('apellido');
                    //Busca en el localStorage si el navegador ya tiene guardados el nombre y el apellido del usuario.
                    if (nombreGuardado && apellidoGuardado) {
                        mostrarUsuario(nombreGuardado, apellidoGuardado);
                        //Si esos datos ya existen, los muestra directamente sin hacer ninguna consulta al servidor.
                    } else {
                        fetch('/SoftwareVet/app/session.php')
                        //Si el navegador todavía no tiene esos datos, hace una petición a session.php, 
                        // que devuelve la información del usuario que está en la sesión de PHP.
                            .then(function(response) { return response.json(); })
                            .then(function(data) {
                                localStorage.setItem('nombre',   data.nombre);
                                localStorage.setItem('apellido', data.apellido);
                                mostrarUsuario(data.nombre, data.apellido);
                                //Guarda el nombre y el apellido en el navegador para reutilizarlos en las próximas páginas.
                            });
                    }

                    function mostrarUsuario(nombre, apellido) {
                        //Muestra el nombre completo.
                        var nombreCompleto = nombre + ' ' + apellido;
                        var top  = document.getElementById('usuarioLogueadoTop');
                        var drop = document.getElementById('usuarioLogueado');
                        if (top)  top.textContent  = nombreCompleto;
                        if (drop) drop.textContent = nombreCompleto;
                    }
                });
            </script>