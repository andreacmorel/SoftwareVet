<?php
require_once '../../app/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Asignar Módulos</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/assign_modules_style.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <!-- Encabezado de la vista con título y subtítulo -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title">
                <i class="fas fa-lock mr-2"></i> Asignar Módulos
            </h1>
            <div class="page-subtitle">
                Seleccioná los módulos que tendrá acceso este perfil
            </div>
        </div>
    </div>

    <div class="form-card">

        <!-- Muestra el nombre del perfil al que se le asignan los módulos -->
        <div class="section-label">Perfil seleccionado</div>

        <div class="mb-4">
            <span class="perfil-badge">
                <i class="fas fa-user-shield mr-1"></i>
                <?= htmlspecialchars($perfil->nombre_perfil) ?> <!-- htmlspecialchars previene XSS -->
            </span>
        </div>

        <div class="section-label">Módulos disponibles</div>

        <!-- Muestra el error si no se seleccionó ningún módulo al enviar -->
        <?php if(isset($erroresCampos['modulos'])) { ?>
            <div class="alert-danger-vet">
                <i class="fas fa-exclamation-circle mr-1"></i>
                <?php echo htmlspecialchars($erroresCampos['modulos']); ?>
            </div>
        <?php } ?>

        <!-- novalidate desactiva la validación nativa del navegador -->
        <form method="POST" novalidate>

            <!-- Grilla de módulos: cada tarjeta es un <label> que envuelve su checkbox -->
                <div class="modulos-grid">

                <?php while ($modulo = $modulos->fetch_object()) {

                /*
                * Determina si el checkbox debe aparecer marcado:
                * - Prioridad 1: si el POST ya tiene valores
                * - Prioridad 2: si el módulo estaba asignado previamente
                */
                $checked = in_array($modulo->id_modulo, $_POST['modulos'] ?? $asignados);

                ?>

        <!-- La clase "checked" aplica el estilo visual de seleccionado -->
        <label class="modulo-item <?= $checked ? 'checked' : '' ?>" for="modulo<?= $modulo->id_modulo ?>">

            <!-- Array de módulos enviado al POST -->
            <input
                type="checkbox"
                name="modulos[]"
                value="<?= $modulo->id_modulo ?>"
                id="modulo<?= $modulo->id_modulo ?>"
                <?= $checked ? 'checked' : '' ?>>

            <div>
                <div class="modulo-nombre">
                    <?= htmlspecialchars($modulo->nombre_modulo ?? '') ?>
                </div>
            </div>

        </label>

    <?php } ?>

</div>
    

            <!-- Acciones: cancelar vuelve al listado, guardar envía el formulario -->
            <div class="actions-bar">
                <a href="index.php" class="btn btn-cancelar">
                    <i class="fas fa-times mr-1"></i>
                    Cancelar
                </a>

                <!-- name="btnGuardar" value="1" permite identificar este botón en el POST -->
                <button type="submit" name="btnGuardar" value="1" class="btn btn-guardar">
                    <i class="fas fa-save mr-1"></i>
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

<!-- Scripts: jQuery, Bootstrap y tema SB Admin 2 -->
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
    /*
     * Comportamiento visual de los checkboxes:
     * Al cambiar el estado de un checkbox, agrega o quita la clase "checked"
     * en la tarjeta contenedora (.modulo-item) para reflejar visualmente la selección.
     * Esto complementa el estado inicial que PHP ya imprime con la clase en el HTML.
     */
    document.querySelectorAll('.modulo-item input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            this.closest('.modulo-item').classList.toggle('checked', this.checked);
        });
    });
</script>

</body>
</html>