<?php
require_once __DIR__ . '/../settings/conexion.php';

// Obtiene la URL actual para identificar qué módulo está abierto.
$uri = $_SERVER['REQUEST_URI'] ?? '';
/*
| Función isActive()
| Verifica si la ruta actual contiene la ruta recibida.
| Si coincide, devuelve la clase CSS 'nav-active'.
| Se utiliza para resaltar la opción activa del menú.
*/
function isActive(string $path): string {
    global $uri;
    return (strpos($uri ?? '', $path) !== false) ? 'nav-active' : '';
}
/*
| Función isOpen()
| Recorre un conjunto de rutas y verifica si alguna coincide
| con la URL actual. Si coincide devuelve 'show' para abrir
| automáticamente el menú desplegable.
*/
function isOpen(array $paths): string {
    global $uri;

    foreach ($paths as $p) {
        if (strpos($uri ?? '', $p) !== false) {
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
| Inicializa el nombre del perfil y luego consulta la base
| de datos para obtener el perfil correspondiente al usuario
| que inició sesión.
*/
$nombrePerfil = 'Sin perfil';

if (isset($_SESSION['id_perfil'])) {

    $id_perfil = (int) $_SESSION['id_perfil'];

    if (isset($conexion)) {

        $resPerfil = $conexion->query("
            SELECT nombre_perfil
            FROM perfil
            WHERE id_perfil = $id_perfil
        ");

        if ($resPerfil && $perfilMenu = $resPerfil->fetch_object()) {
            $nombrePerfil = $perfilMenu->nombre_perfil;
        }
    }
}

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
*/
$turnosHoyCount = 0;

if (isset($conexion)) {

    $resTurnos = $conexion->query("
        SELECT COUNT(*) AS total
        FROM turnos
        WHERE fecha = CURDATE()
    ");

    if ($resTurnos && $rowTurnos = $resTurnos->fetch_object()) {
        $turnosHoyCount = (int) $rowTurnos->total;
    }
}
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

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/SoftwareVet/php/inicio.php">
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
            <a class="nav-link <?= isActive('/php/inicio') ?>" href="/SoftwareVet/app/inicio.php" data-label="Dashboard">
                <i class="fas fa-fw fa-th-large nav-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Gestión</div>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/pets/') ?>" href="/SoftwareVet/modules/pets/index.php" data-label="Mascotas">
                <i class="fas fa-fw fa-paw nav-icon"></i>
                <span>Mascotas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/clients/') ?>" href="/SoftwareVet/modules/clients/index.php" data-label="Clientes">
                <i class="fas fa-fw fa-users nav-icon"></i>
                <span>Clientes</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/professionals/') ?>" href="/SoftwareVet/modules/professionals/index.php" data-label="Profesionales">
                <i class="fas fa-fw fa-user-md nav-icon"></i>
                <span>Profesionales</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Agenda</div>

        <li class="nav-item has-sub">
            <?php $turnosOpen = isOpen(['/modules/appointments/']); ?>

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

                    <a class="collapse-item <?= isActive('/modules/appointments/index.php') ?>" href="/SoftwareVet/modules/appointments/index.php">
                        <i class="fas fa-fw fa-list-ul"></i> Listado
                    </a>

                    <a class="collapse-item <?= isActive('/modules/appointments/calendario.php') ?>" href="/SoftwareVet/modules/appointments/calendario.php">
                        <i class="fas fa-fw fa-calendar-alt"></i> Calendario
                    </a>

                    <a class="collapse-item <?= isActive('/modules/appointments/create.php') ?>" href="/SoftwareVet/modules/appointments/create.php">
                        <i class="fas fa-fw fa-plus-circle"></i> Nuevo Turno
                    </a>

                </div>
            </div>

            <div class="icons-flyout">
                <div class="flyout-title">Turnos</div>

                <a class="flyout-item <?= isActive('/modules/appointments/index.php') ?>" href="/SoftwareVet/modules/appointments/index.php">
                    <i class="fas fa-fw fa-list-ul"></i> Listado
                </a>

                <a class="flyout-item <?= isActive('/modules/appointments/calendario.php') ?>" href="/SoftwareVet/modules/appointments/calendario.php">
                    <i class="fas fa-fw fa-calendar-alt"></i> Calendario
                </a>

                <a class="flyout-item <?= isActive('/modules/appointments/create.php') ?>" href="/SoftwareVet/modules/appointments/create.php">
                    <i class="fas fa-fw fa-plus-circle"></i> Nuevo Turno
                </a>
            </div>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Clínica</div>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/medical_records/') ?>" href="/SoftwareVet/modules/medical_records/index.php" data-label="Historia Clínica">
                <i class="fas fa-fw fa-stethoscope nav-icon"></i>
                <span>Historia Clínica</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= isActive('/modules/species/') ?>" href="/SoftwareVet/modules/species/index.php" data-label="Especies">
                <i class="fas fa-fw fa-dna nav-icon"></i>
                <span>Especies</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Administración</div>

        <li class="nav-item has-sub">
            <?php $adminOpen = isOpen([
                '/modules/users/',
                '/modules/profiles/',
                '/modules/system_modules/'
            ]); ?>

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

                    <a class="collapse-item <?= isActive('/modules/users/') ?>" href="/SoftwareVet/modules/users/index.php">
                        <i class="fas fa-users"></i> Usuario
                    </a>

                    <a class="collapse-item <?= isActive('/modules/profiles/') ?>" href="/SoftwareVet/modules/profiles/index.php">
                        <i class="fas fa-user-shield"></i> Perfil
                    </a>

                    <a class="collapse-item <?= isActive('/modules/system_modules/') ?>" href="/SoftwareVet/modules/system_modules/index.php">
                        <i class="fas fa-layer-group"></i> Módulos
                    </a>

                </div>
            </div>
        </li>

    </ul>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php
            $usuarioNombre = htmlspecialchars($_SESSION['usuario'] ?? 'Usuario');
            $inicialesArr  = array_filter(array_map(fn($p) => strtoupper($p[0] ?? ''), explode(' ', $usuarioNombre)));
            $iniciales     = implode('', array_slice(array_values($inicialesArr), 0, 2));

            if (!$iniciales) {
                $iniciales = strtoupper(substr($usuarioNombre, 0, 2));
            }

            $notifTurnos = [];

            if (isset($conexion)) {
                $resNotif = $conexion->query("
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

                if ($resNotif) {
                    while ($n = $resNotif->fetch_object()) {
                        $notifTurnos[] = $n;
                    }
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
                                    <?= $usuarioNombre ?>
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
                                        <div class="user-nombre"><?= $usuarioNombre ?></div>
                                        <div class="user-rol">
                                            <i class="fas fa-shield-alt mr-1"></i><?= $nombrePerfilSeguro ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item py-2" href="/SoftwareVet/php/logout.php">
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
                    // Obtiene la fecha y hora actual.
                    var now = new Date();

                    // Obtiene horas, minutos y segundos con dos dígitos.
                    var hh = now.getHours().toString().padStart(2, '0');
                    var mm = now.getMinutes().toString().padStart(2, '0');
                    var ss = now.getSeconds().toString().padStart(2, '0');

                    // Array con los nombres de los días.
                    var dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

                    // Array con los nombres abreviados de los meses.
                    var meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

                    // Obtiene los elementos donde se mostrará la hora y la fecha.
                    var eH = document.getElementById('topbar-hora');
                    var eF = document.getElementById('topbar-fecha');

                    // Muestra la hora actual.
                    if (eH) eH.textContent = hh + ':' + mm + ':' + ss;

                    // Muestra la fecha actual.
                    if (eF) eF.textContent = dias[now.getDay()] + ' ' + now.getDate() + ' ' + meses[now.getMonth()] + ' ' + now.getFullYear();

                    // Vuelve a ejecutar la función cada segundo.
                    setTimeout(tickClock, 1000);
                })();

                /*
                | Cambio de modo del menú lateral
                | Permite alternar entre:
                | - Menú completo
                | - Solo íconos
                | - Menú oculto
                | Además guarda la preferencia en localStorage.
                */
                (function() {
                    // Modos disponibles del menú.
                    var MODES = ['full', 'icons', 'hidden'];

                    // Descripción de cada modo.
                    var LABELS = ['Menú completo', 'Solo íconos', 'Menú oculto'];

                    // Obtiene el body y el botón que cambia el modo.
                    var body = document.body;
                    var btn = document.getElementById('sidebarModeBtn');

                    // Recupera el último modo guardado.
                    var currentMode = localStorage.getItem('sidebarMode') || 'full';

                    // Aplica el modo inicial.
                    applyMode(currentMode);

                    // Verifica que exista el botón.
                    if (btn) {
                        btn.addEventListener('click', function() {
                            // Escucha el clic para cambiar entre modos.

                            // Obtiene la posición actual dentro del array.
                            var idx = MODES.indexOf(currentMode);

                            // Calcula el siguiente modo.
                            var next = MODES[(idx + 1) % MODES.length];

                            // Aplica el nuevo modo.
                            applyMode(next);

                            // Guarda la preferencia en el navegador.
                            localStorage.setItem('sidebarMode', next);
                        });
                    }

                    // Función que aplica el modo seleccionado.
                    function applyMode(mode) {
                        currentMode = mode;

                        // Actualiza el atributo data-sidebar.
                        body.setAttribute('data-sidebar', mode);

                        // Obtiene el índice del modo actual.
                        var idx = MODES.indexOf(mode);

                        // Actualiza el tooltip del botón.
                        if (btn) btn.setAttribute('title', 'Modo actual: ' + LABELS[idx] + ' → click para cambiar');
                    }
                })();

                /*
                | Menú responsive para dispositivos móviles
                | Permite abrir y cerrar el sidebar en pantallas pequeñas.
                */
                (function() {
                    var toggleTop = document.getElementById('sidebarToggleTop');
                    var overlay = document.getElementById('sidebarOverlay');
                    var body = document.body;

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
            </script>
        