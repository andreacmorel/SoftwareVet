<?php 
// Incluye el archivo menu.php que contiene el menú principal del sistema.
require_once __DIR__ . '/menu.php';

// Incluye la validación de rutas y permisos.
// Actualmente está comentada, por lo que no se ejecuta.
//require_once 'validateRoute.php';

?>
<!-- Muestra un mensaje de error cuando el usuario intenta acceder
        a un módulo para el cual no tiene permisos. -->
<?php if(isset($_GET['error']) && $_GET['error'] == 'sin_permiso') { ?>

<div class="vet-alert-danger">

    <div class="vet-alert-icon">
        <i class="fas fa-lock"></i>
    </div>

    <div class="vet-alert-content">
        <h5>Acceso denegado</h5>
        <p>Su perfil no posee permisos para acceder a este módulo.</p>
    </div>

</div>

<?php } ?>
<style>
    :root {
        --vet-primary: #52266E;
        --vet-primary-soft: #F3EDF7;
        --vet-dark: #2f2f38;
        --vet-muted: #858796;
    }

    .dashboard-title {
        font-weight: 800;
        color: var(--vet-dark);
    }

    .welcome-card {
        background: linear-gradient(135deg, #52266E, #7d3fa3);
        color: white;
        border-radius: 18px;
        border: none;
        overflow: hidden;
    }

    .welcome-card i {
        font-size: 70px;
        opacity: .18;
    }

    .kpi-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 22px rgba(0,0,0,.07);
        transition: .2s;
        overflow: hidden;
        position: relative;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
    }

    .kpi-card::before {
        content: "";
        width: 7px;
        height: 100%;
        background: var(--vet-primary);
        position: absolute;
        left: 0;
        top: 0;
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--vet-primary-soft);
        color: var(--vet-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .kpi-label {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--vet-muted);
    }

    .kpi-number {
        font-size: 25px;
        font-weight: 800;
        color: var(--vet-dark);
    }

    .kpi-delta {
        font-size: 12px;
        font-weight: 700;
    }

    .card-pro {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 22px rgba(0,0,0,.07);
        overflow: hidden;
    }

    .card-pro .card-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        color: var(--vet-primary);
        font-weight: 800;
    }

    thead th {
        background-color: var(--vet-primary) !important;
        color: white !important;
        border: none !important;
        font-size: 13px;
    }

    .table td {
        vertical-align: middle;
        font-size: 14px;
    }

    .pet-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--vet-primary-soft);
        color: var(--vet-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
    }

    .badge-soft-success {
        background: #e6f8f1;
        color: #1cc88a;
        padding: 7px 11px;
        border-radius: 20px;
        font-weight: 700;
    }

    .badge-soft-warning {
        background: #fff5d6;
        color: #b8860b;
        padding: 7px 11px;
        border-radius: 20px;
        font-weight: 700;
    }

    .badge-soft-info {
        background: #e8f7fb;
        color: #36b9cc;
        padding: 7px 11px;
        border-radius: 20px;
        font-weight: 700;
    }

    .badge-soft-danger {
        background: #fde8e8;
        color: #e74a3b;
        padding: 7px 11px;
        border-radius: 20px;
        font-weight: 700;
    }

    .quick-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--vet-primary-soft);
        color: var(--vet-primary);
        border-radius: 14px;
        padding: 13px 15px;
        margin-bottom: 10px;
        text-decoration: none;
        font-weight: 800;
        transition: .2s;
    }

    .quick-link:hover {
        background: var(--vet-primary);
        color: #fff;
        text-decoration: none;
    }

    .mini-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 0;
        border-bottom: 1px solid #eee;
    }

    .mini-item:last-child {
        border-bottom: none;
    }

    .progress {
        height: 9px;
        border-radius: 20px;
    }

    .progress-bar {
        background-color: var(--vet-primary);
    }

    .vet-alert-danger{
    display:flex;
    align-items:flex-start;
    gap:12px;
    background:#fdecec;
    border:1px solid #f5c6cb;
    color:#c0392b;
    border-radius:12px;
    padding:15px 18px;
    margin-bottom:20px;
    animation:fade .3s ease;
    }

    .vet-alert-icon{
    font-size:22px;
    margin-top:2px;
    }

    .vet-alert-content h5{
        margin:0;
        font-size:15px;
        font-weight:800;
    }

    .vet-alert-content p{
        margin:4px 0 0;
        font-size:14px;
    }
</style>

<div class="container-fluid">

    <div class="card welcome-card mb-4 shadow">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 font-weight-bold">Bienvenido a VETSYS</h1>
                <p class="mb-0">Panel general del sistema veterinario</p>
            </div>
            <i class="fas fa-paw"></i>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Mascotas registradas</div>
                        <div class="kpi-number">120</div>
                        <span class="kpi-delta text-success">
                            <i class="fas fa-arrow-up"></i> +8% este mes
                        </span>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-paw"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Clientes registrados</div>
                        <div class="kpi-number">85</div>
                        <span class="kpi-delta text-success">
                            <i class="fas fa-arrow-up"></i> +5% este mes
                        </span>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Profesionales</div>
                        <div class="kpi-number">4</div>
                        <span class="kpi-delta text-info">
                            <i class="fas fa-check-circle"></i> activos
                        </span>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Turnos de hoy</div>
                        <div class="kpi-number">8</div>
                        <span class="kpi-delta text-warning">
                            <i class="fas fa-clock"></i> 3 pendientes
                        </span>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-8 mb-4">

            <div class="card card-pro mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-alt mr-2"></i> Próximos Turnos
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Mascota</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>12/05/2026</td>
                                <td>09:00</td>
                                <td>
                                    <span class="pet-avatar">
                                        <i class="fas fa-dog"></i>
                                    </span>
                                    Bruna
                                </td>
                                <td>
                                    <span class="badge-soft-info">
                                        Control general
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-soft-success">
                                        Confirmado
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td>12/05/2026</td>
                                <td>10:30</td>
                                <td>
                                    <span class="pet-avatar">
                                        <i class="fas fa-cat"></i>
                                    </span>
                                    Polo
                                </td>
                                <td>
                                    <span class="badge-soft-warning">
                                        Consulta
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-soft-warning">
                                        Pendiente
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td>13/05/2026</td>
                                <td>18:00</td>
                                <td>
                                    <span class="pet-avatar">
                                        <i class="fas fa-dog"></i>
                                    </span>
                                    Toby
                                </td>
                                <td>
                                    <span class="badge-soft-danger">
                                        Urgencia
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-soft-info">
                                        En espera
                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-lg-4 mb-4">

            <div class="card card-pro mb-4">
                <div class="card-header">
                    <i class="fas fa-bolt mr-2"></i> Accesos rápidos
                </div>

                <div class="card-body">

                    <a href="/SoftwareVet/modules/clients/create.php" class="quick-link">
                        <span>
                            <i class="fas fa-user-plus mr-2"></i>
                            Nuevo Cliente
                        </span>

                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <a href="/SoftwareVet/modules/pets/create.php" class="quick-link">
                        <span>
                            <i class="fas fa-paw mr-2"></i>
                            Nueva Mascota
                        </span>

                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <a href="/SoftwareVet/modules/appointments/create.php" class="quick-link">
                        <span>
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Nuevo Turno
                        </span>

                        <i class="fas fa-chevron-right"></i>
                    </a>

                </div>
            </div>

            <div class="card card-pro mb-4">
                <div class="card-header">
                    <i class="fas fa-exclamation-circle mr-2"></i> Alertas del día
                </div>

                <div class="card-body">

                    <div class="mini-item">
                        <div>
                            <strong>Vacunas pendientes</strong>
                            <br>
                            <small class="text-muted">
                                3 mascotas requieren control
                            </small>
                        </div>

                        <span class="badge-soft-warning">3</span>
                    </div>

                    <div class="mini-item">
                        <div>
                            <strong>Turnos sin confirmar</strong>
                            <br>
                            <small class="text-muted">
                                Revisar agenda del día
                            </small>
                        </div>

                        <span class="badge-soft-danger">2</span>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>