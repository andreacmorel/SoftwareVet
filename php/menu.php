<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VETSYS - Inicio</title>
    <link href="/SoftwareVet/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet">
    <link href="/SoftwareVet/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: #52266E !important;
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="SoftwareVet/inicio.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-paw"></i>
                </div>
                <div class="sidebar-brand-text mx-3">VETSYS</div>
            </a>

            <hr class="sidebar-divider">

            <li class="nav-item active">
                <a class="nav-link" href="SoftwareVet/inicio.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Gestión</div>

            <li class="nav-item">
                <a class="nav-link" href="/SoftwareVet/modulos/mascotas/listadoMascota.php">
                    <i class="fas fa-dog"></i>
                    <span>Mascotas</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/SoftwareVet/modulos/clientes/listadoCliente.php">
                    <i class="fas fa-user"></i>
                    <span>Clientes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/SoftwareVet/modulos/profesionales/listadoProfesional.php">
                    <i class="fas fa-user-md"></i>
                    <span>Profesionales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/SoftwareVet/modulos/turnos/listado.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Turnos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="listadoTratamientos.php">
                    <i class="fas fa-stethoscope"></i>
                    <span>Historia Clinica</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                                <span class="mr-2 text-gray-600 small">Usuario</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="perfil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Perfil
                                </a>
                                <a class="dropdown-item" href="configuracion.php">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Configuración
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="CerrarSesion.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
    </tbody>

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>