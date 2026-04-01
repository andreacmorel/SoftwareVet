<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VETSYS - Inicio</title>

    <!-- FontAwesome -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Color personalizado del menú */
        .bg-gradient-primary {
            background: #52266E !important;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-paw"></i>
                </div>
                <div class="sidebar-brand-text mx-3">VETSYS</div>
            </a>

            <hr class="sidebar-divider">

            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Gestión</div>

            <li class="nav-item">
                <a class="nav-link" href="listadoMascota.php">
                    <i class="fas fa-dog"></i>
                    <span>Mascotas</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="listadoCliente.php">
                    <i class="fas fa-user"></i>
                    <span>Clientes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="listadoProfesional.php">
                    <i class="fas fa-user-md"></i>
                    <span>Profesionales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="listadoTurnos.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Turnos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="listadoTratamientos.php">
                    <i class="fas fa-stethoscope"></i>
                    <span>Historia Clínica</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Topbar SIN BUSCADOR -->
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

                <!-- Contenido principal -->
       <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Listado de Historia Clínica</h1>

                    <a href="#" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Agregar Historia Clínica
                    </a>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Mascota</th>
                                            <th>Descripción</th>
                                            <th>Fecha</th>
                                            <th>Observación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Luna</td>
                                            <td>Control general</td>
                                            <td>18-11-2025</td>
                                            <td>Se encuentra en buen estado</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                                <a href="#" class="btn btn-danger btn-sm">Borrar</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>Milo</td>
                                            <td>Vacunación Refuerzo anual</td>
                                            <td>22-11-2025</td>
                                            <td>No presentó reacciones adversas.Próxima vacuna en 1 año</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                                <a href="#" class="btn btn-danger btn-sm">Borrar</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td>Rocky</td>
                                            <td>Extracción de cuerpo extraño</td>
                                            <td>05-12-2025</td>
                                            <td>La cirugía fue exitosa.Se debe continuar antibióticos por 7 días</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                                <a href="#" class="btn btn-danger btn-sm">Borrar</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
                </div><!-- Fin container -->
            </div><!-- Fin content -->
        </div><!-- Fin content wrapper -->
    </div><!-- Fin wrapper -->

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
