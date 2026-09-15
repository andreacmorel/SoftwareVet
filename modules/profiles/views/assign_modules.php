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
    <link href="../../css/style_system.css" rel="stylesheet">
    <link href="../../css/assign_modules_style.css" rel="stylesheet">
</head>

<body>

<div class="vetsys-breadcrumb-container">

    <ol class="vetsys-breadcrumb">

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i>
                Inicio
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/modules/profiles/index.php">
                Perfiles
            </a>
        </li>

        <li class="breadcrumb-item active">
            Asignación de módulos
        </li>

    </ol>

</div>

<div class="container-fluid">

        <h1 class="h3 titulo-pagina">
            <i class="fas fa-lock mr-2"></i> Asignación de Módulos
        </h1>

        <div class="subtitulo-pagina">
            Selecciona los módulos que tendra acceso este perfil
        </div>

    <div class="card card-form mb-4">
        <div class="card-header-form">
            <h5>
                <i class="fas fa-user-shield mr-2"></i>
                Permisos del Perfil
            </h5>
        </div>

    <div class="card-body">

        <!-- Muestra el nombre del perfil al que se le asignan los módulos -->
        <div class="section-label">Perfil seleccionado</div>

        <div class="mb-4">
            <span class="perfil-badge">
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
            <input type="checkbox"  name="modulos[]" value="<?= $modulo->id_modulo ?>"
                id="modulo<?= $modulo->id_modulo ?>"
                <?= $checked ? 'checked' : '' ?>>

            <div class="modulo-content">
                
                <div class="modulo-nombre">
                    <?= htmlspecialchars($modulo->nombre_modulo ?? '') ?>
                </div>

                <div class="modulo-estado">

                    <?= $checked ? 'Habilitado' : 'Disponible' ?>

                </div>
        </div>

                <div class="modulo-check">

                    <i class="fas fa-check-circle"></i>

                </div>

        </label>

    <?php } ?>

</div>
    
            <!-- Acciones: cancelar vuelve al listado, guardar envía el formulario -->
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-cancelar">
                    <i class="fas fa-times mr-1"></i>
                    Cancelar
                </a>

                <!-- name="btnGuardar" value="1" permite identificar este botón en el POST -->
                <button type="submit" name="btnGuardar" value="1" class="btn btn-purple">
                    <i class="fas fa-save mr-1"></i>
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>

    /* 
     * Comportamiento visual de los checkboxes:
     * Al cambiar el estado de un checkbox, agrega o quita la clase "checked"
     * en la tarjeta contenedora (.modulo-item) para reflejar visualmente la selección.
     * También cambia el texto entre "Habilitado" y "Disponible".
     */

    document.querySelectorAll('.modulo-item input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Guarda la tarjeta del módulo seleccionado
            const item = this.closest('.modulo-item');
            // Agrega o quita la clase checked
            item.classList.toggle('checked', this.checked);
            // Busca el texto del estado dentro de la tarjeta
            const estado = item.querySelector('.modulo-estado');
            // Cambia el texto según el checkbox esté marcado o no
            if (estado) {
                estado.textContent = this.checked
                    ? 'Habilitado'
                    : 'Disponible';
            }
        }); // cierra addEventListener
    }); // cierra forEach
</script>

</body>
</html>