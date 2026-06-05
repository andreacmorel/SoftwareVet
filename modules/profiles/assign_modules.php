<?php
// Incluye la conexión a la BD y la validación de rutas permitidas
require_once '../../settings/conexion.php';
require_once '../../php/validateRoute.php';

// Array que acumulará errores de validación del formulario
$erroresCampos = [];

// Obtiene el id del perfil desde la URL (?id=X) y lo castea a entero para evitar inyección SQL
$id_perfil = (int)($_GET['id'] ?? 0);

// Busca el perfil en la BD; si no existe, corta la ejecución con mensaje de error
$perfil = $conexion->query("
    SELECT id_perfil, nombre_perfil
    FROM perfil
    WHERE id_perfil = $id_perfil
")->fetch_object();

if (!$perfil) {
    die("Perfil no encontrado.");
}

// Solo procesa si el formulario fue enviado mediante el botón "btnGuardar"
if (!empty($_POST['btnGuardar'])) {

    // Valida que se haya marcado al menos un módulo
    if (empty($_POST['modulos'])) {

        $erroresCampos['modulos'] = "Debe seleccionar al menos un módulo.";

    } else {

        // Primero elimina todos los módulos asignados al perfil para evitar duplicados
        $conexion->query("
            DELETE FROM perfil_modulo
            WHERE id_perfil = $id_perfil
        ");

        // Luego recorre los módulos tildados en el formulario y los reasigna uno por uno
        foreach ($_POST['modulos'] as $id_modulo) {

            $id_modulo = (int)$id_modulo; // Casteo a entero por seguridad
            // Inserta la relación entre el perfil y cada módulo seleccionado
            $conexion->query("
                INSERT INTO perfil_modulo (id_perfil, id_modulo)
                VALUES ($id_perfil, $id_modulo)
            ");
        }

        // Redirige al listado con parámetro que indica operación exitosa
        header("Location: index.php?updated=1");
        exit;
    }
}

// Consulta todos los módulos activos para mostrarlos como opciones en la grilla
$modulos = $conexion->query("
    SELECT id_modulo, nombre_modulo, ruta
    FROM modulo
    WHERE estado = 1
    ORDER BY nombre_modulo
");

// Array que contendrá los ids de módulos ya asignados a este perfil
$asignados = [];

// Consulta los módulos actualmente asignados al perfil
$resAsignados = $conexion->query("
    SELECT id_modulo
    FROM perfil_modulo
    WHERE id_perfil = $id_perfil
");

// Llena el array $asignados con los ids para usarlo luego al marcar checkboxes
while ($row = $resAsignados->fetch_object()) {
    $asignados[] = $row->id_modulo;
}

// Incluye el menú de navegación lateral
require_once '../../php/menu.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Asignar Módulos</title>

    <!-- Íconos Font Awesome -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Estilos del tema SB Admin 2 -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Título principal de la pantalla */
        .page-title { font-weight: 800; color: #1f2937; margin-bottom: 2px; }
        .page-title i { color: #52266E; }

        /* Subtítulo descriptivo bajo el título */
        .page-subtitle { color: #9ca3af; font-size: 14px; }

        /* Pastilla/badge que muestra el nombre del perfil seleccionado */
        .perfil-badge {
            display: inline-block;
            background: #f0e6f6;
            color: #52266E;
            font-weight: 800;
            font-size: 15px;
            padding: 6px 16px;
            border-radius: 20px;
            margin-left: 8px;
        }

        /* Grilla responsiva de tarjetas de módulos; auto-fill ajusta columnas según el ancho */
        .modulos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 14px;
            margin-top: 20px;
        }

        /* Cada tarjeta de módulo actúa como label para activar su checkbox interno */
        .modulo-item {
            background: white;
            border: 2px solid #eee1f6;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            transition: all .2s;
        }

        /* Efecto hover: eleva la tarjeta y resalta el borde */
        .modulo-item:hover {
            border-color: #52266E;
            background: #faf5ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(82,38,110,.1);
        }

        /* Checkbox estilizado con color corporativo */
        .modulo-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            margin-top: 2px;
            accent-color: #52266E;
            cursor: pointer;
        }

        /* Clase aplicada por JS cuando el checkbox está marcado */
        .modulo-item.checked { border-color: #52266E; background: #faf5ff; }

        .modulo-nombre { font-weight: 700; color: #1f2937; font-size: 14px; }

        /* Tarjeta blanca contenedora de todo el formulario */
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            padding: 28px 30px;
        }

        /* Etiqueta de sección en mayúsculas, estilo igual al de otros formularios del sistema */
        .section-label { font-size: 12px; font-weight: 800; text-transform: uppercase; color: #52266E; margin-bottom: 4px; }

        /* Botón principal de guardado */
        .btn-guardar { background: #52266E; color: white; border-radius: 8px; font-weight: 700; padding: 10px 24px; border: none; }
        .btn-guardar:hover { background: #3f1d55; color: white; }

        /* Botón secundario de cancelación */
        .btn-cancelar { border-radius: 8px; font-weight: 600; padding: 10px 20px; color: #6b7280; border: 1px solid #e5e7eb; background: white; }
        .btn-cancelar:hover { background: #f9fafb; color: #374151; }

        /* Barra inferior con los botones de acción, separada por línea */
        .actions-bar { display: flex; gap: 10px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #f3f4f6; }

        /* Alerta de error personalizada (reemplaza la clase Bootstrap) */
        .alert-danger-vet {
            background: #fdecec;
            border: 1px solid #f5c6cb;
            color: #c0392b;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 14px;
            font-weight: 700;
            margin-top: 12px;
            margin-bottom: 15px;
        }
    </style>
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
                     * - Prioridad 1: si el POST ya tiene valores (reenvío por error de validación)
                     * - Prioridad 2: si el módulo estaba asignado previamente en la BD ($asignados)
                     */
                    $checked = in_array($modulo->id_modulo, $_POST['modulos'] ?? $asignados);
                ?>

                    <!-- La clase "checked" aplica el estilo visual de seleccionado -->
                    <label class="modulo-item <?= $checked ? 'checked' : '' ?>" for="modulo<?= $modulo->id_modulo ?>">
                        <input 
                            type="checkbox"
                            name="modulos[]"    <!-- Array de módulos enviado al POST -->
                            value="<?= $modulo->id_modulo ?>"
                            id="modulo<?= $modulo->id_modulo ?>"
                            <?= $checked ? 'checked' : '' ?>
                        >

                        <div>
                            <div class="modulo-nombre">
                                <?= htmlspecialchars($modulo->nombre_modulo) ?>
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
                    Guardar asignación
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