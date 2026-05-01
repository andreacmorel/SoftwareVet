<?php 
require_once 'menu.php'; 
?>

<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Bienvenido a VETSYS</h1>
    <p class="mb-4 text-muted">Dashboard del sistema veterinario</p>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Mascotas Registradas
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">120</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Clientes Registrados
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">85</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Profesionales
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">4</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Turnos de Hoy
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold" style="color:#52266E;">Próximos Turnos</h6>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Mascota</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12/05/2026</td>
                                <td>09:00</td>
                                <td>Bruna</td>
                                <td>Control general</td>
                            </tr>
                            <tr>
                                <td>12/05/2026</td>
                                <td>10:30</td>
                                <td>Polo</td>
                                <td>Consulta</td>
                            </tr>
                            <tr>
                                <td>13/05/2026</td>
                                <td>16:00</td>
                                <td>Almendra</td>
                                <td>Seguimiento</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold" style="color:#52266E;">Accesos Rápidos</h6>
                </div>

                <div class="card-body">
                    <a href="/SoftwareVet/modulos/clientes/altaCliente.php" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-user-plus"></i> Nuevo Cliente
                    </a>

                    <a href="/SoftwareVet/modulos/mascotas/altaMascota.php" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-paw"></i> Nueva Mascota
                    </a>

                    <a href="/SoftwareVet/modulos/turnos/altaTurno.php" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-calendar-plus"></i> Nuevo Turno
                    </a>

                    <a href="/SoftwareVet/modulos/tratamientos/listadoTratamientos.php" class="btn btn-warning btn-block">
                        <i class="fas fa-notes-medical"></i> Historia Clínica
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

</body>
</html>