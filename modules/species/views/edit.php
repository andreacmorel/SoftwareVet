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
    <link href="../../css/editspe.css" rel="stylesheet">

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
                        Guardar
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

