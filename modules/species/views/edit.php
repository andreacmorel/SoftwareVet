<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificar Especie</title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .titulo-pagina {
            font-weight:800;
            color:#1f2937;
        }

        .titulo-pagina i {
            color:#52266E;
        }

        .subtitulo-pagina {
            color:#9ca3af;
            font-size:14px;
            margin-top:-8px;
            margin-bottom:25px;
        }

        .card-form {
            border:none;
            border-radius:15px;
            box-shadow:0 4px 18px rgba(0,0,0,.06);
            overflow:hidden;
        }

        .card-header-form {
            background:#fbf7ff;
            border-bottom:1px solid #eee1f6;
            padding:18px 22px;
        }

        .card-header-form h5 {
            color:#52266E;
            font-weight:800;
            margin:0;
        }

        .card-body {
            padding:25px;
        }

        label {
            color:#52266E;
            font-size:12px;
            font-weight:800;
            text-transform:uppercase;
        }

        .form-control {
            border-radius:8px;
            border:1px solid #d8c2e8;
            font-size:14px;
        }

        .form-control:focus {
            border-color:#52266E;
            box-shadow:0 0 0 3px rgba(82,38,110,.12);
        }

        .form-control.is-invalid {
            border-color:#dc2626 !important;
            box-shadow:0 0 0 3px rgba(220,38,38,.12) !important;
        }

        .invalid-feedback {
            display:block;
            font-size:13px;
            font-weight:600;
        }

        .section-title {
            color:#52266E;
            font-weight:800;
            font-size:15px;
            margin-bottom:18px;
        }

        .btn-purple {
            background:#52266E;
            color:white;
            border-radius:8px;
            font-weight:700;
            padding:8px 22px;
        }

        .btn-purple:hover {
            background:#3f1d55;
            color:white;
        }

        .btn-cancelar {
            background:#e5e7eb;
            color:#374151;
            border-radius:8px;
            font-weight:700;
            padding:8px 22px;
        }

        .btn-cancelar:hover {
            background:#d1d5db;
            color:#111827;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-dna mr-2"></i>
        Modificar Especie
    </h1>

    <div class="subtitulo-pagina">
        Actualizá los datos de la especie y su raza.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5>
                <i class="fas fa-edit mr-2"></i>
                Datos de la Especie
            </h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($erroresCampos['general']); ?>
                </div>
            <?php } ?>

            <form method="POST" novalidate>

                <h5 class="section-title">
                    <i class="fas fa-paw mr-2"></i>
                    Información principal
                </h5>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre de especie <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="text" 
                            name="nombre_especie" 
                            class="form-control <?php echo isset($erroresCampos['nombre_especie']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['nombre_especie'] ?? ''); ?>"
                        >

                        <?php if(isset($erroresCampos['nombre_especie'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($erroresCampos['nombre_especie']); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Raza <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="text" 
                            name="raza" 
                            class="form-control <?php echo isset($erroresCampos['raza']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($row['raza'] ?? ''); ?>"
                        >

                        <?php if(isset($erroresCampos['raza'])) { ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($erroresCampos['raza']); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-purple">
                        <i class="fas fa-save mr-1"></i>
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

</body>
</html>