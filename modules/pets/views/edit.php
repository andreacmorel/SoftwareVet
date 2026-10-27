<?php
require_once '../../app/menu.php';
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Modificar Mascota</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/editpet.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    
<div class="vetsys-breadcrumb-container">
    <ol class="vetsys-breadcrumb">

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/app/inicio.php">
                <i class="fas fa-home"></i> Inicio
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="/SoftwareVet/modules/pets/index.php">
                Mascotas
            </a>
        </li>

        <li class="breadcrumb-item active">
            Editar mascota
        </li>

    </ol>
</div>

<div class="container-fluid">

    <h1 class="h3 titulo-pagina">
        <i class="fas fa-pen mr-2"></i> Editar Mascota
    </h1>

    <div class="subtitulo">
        Modifica los datos del paciente.
    </div>

    <div class="card card-form mb-4">

        <div class="card-header-form">
            <h5><i class="fas fa-edit mr-2"></i> Datos de la Mascota</h5>
        </div>

        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erroresCampos['general']) ?>
                </div>
            <?php } ?>

            <?php

            $especiesRazas = [];

            while ($e = mysqli_fetch_assoc($resEspecies)) {
                $especiesRazas[] = $e;
            }

            ?>

            <form method="POST" enctype="multipart/form-data" novalidate>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="nombre_mascota"
                            class="form-control <?= isset($erroresCampos['nombre_mascota']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($mascota['nombre_mascota'] ?? '') ?>">

                        <?php if(isset($erroresCampos['nombre_mascota'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['nombre_mascota']) ?></div>
                        <?php } ?>
                    </div>

              
                    <div class="form-group col-md-6">

                        <label for="fecha_nacimiento">Fecha de nacimiento</label>

                        <input type="text" id="fecha_nacimiento" name="fecha_nacimiento"
                            class="form-control calendario-vetsys <?= isset($erroresCampos['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                            placeholder="Seleccionar fecha"
                            autocomplete="off"
                            value="<?= htmlspecialchars(
                                $_POST['fecha_nacimiento'] ?? $mascota['fecha_nacimiento'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>">

                        <?php if (isset($erroresCampos['fecha_nacimiento'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['fecha_nacimiento']) ?>
                            </div>
                        <?php } ?>

                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sexo <span style="color:#dc2626;">*</span></label>

                        <select name="sexo"
                                class="form-control <?= isset($erroresCampos['sexo']) ? 'is-invalid' : '' ?>">
                            <option value="">Seleccione</option>
                            <option value="M" <?= (($mascota['sexo'] ?? '') == 'M') ? 'selected' : '' ?>>Macho</option>
                            <option value="H" <?= (($mascota['sexo'] ?? '') == 'H') ? 'selected' : '' ?>>Hembra</option>
                        </select>

                        <?php if(isset($erroresCampos['sexo'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['sexo']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso (kg) <span style="color:#dc2626;">*</span></label>

                        <input type="number" step="0.01" min="0.1" name="peso"
                            class="form-control <?= isset($erroresCampos['peso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($mascota['peso'] ?? '') ?>">

                        <?php if(isset($erroresCampos['peso'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['peso']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Color</label>

                        <input type="text" name="color"
                            class="form-control"
                            value="<?= htmlspecialchars($mascota['color'] ?? '') ?>">
                    </div>

                <div class="form-group col-md-6">
                    <label>Edad</label>

                    <div class="row">

                        <div class="col-md-6">
                            <input type="number"
                                min="0"
                                name="edad"
                                class="form-control <?= isset($erroresCampos['edad']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($mascota['edad'] ?? '') ?>"
                                placeholder="Ej: 3">

                            <?php if(isset($erroresCampos['edad'])) { ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($erroresCampos['edad']) ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-md-6">
                            <select name="unidad_edad"
                                class="form-control <?= isset($erroresCampos['unidad_edad']) ? 'is-invalid' : '' ?>">

                                <option value="">Seleccione</option>

                                <option value="dias" <?= (($mascota['unidad_edad'] ?? '') == 'dias') ? 'selected' : '' ?>>
                                    Día(s)
                                </option>

                                <option value="meses" <?= (($mascota['unidad_edad'] ?? '') == 'meses') ? 'selected' : '' ?>>
                                    Mes(es)
                                </option>

                                <option value="años" <?= (($mascota['unidad_edad'] ?? '') == 'años') ? 'selected' : '' ?>>
                                    Año(s)
                                </option>

                            </select>

                            <?php if(isset($erroresCampos['unidad_edad'])) { ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($erroresCampos['unidad_edad']) ?>
                                </div>
                            <?php } ?>

                        </div>

                    </div>
                </div>

            </div>

            <div class="form-group">

                <label>
                    Especie
                    <span style="color:#dc2626;">*</span>
                </label>

                <select
                    id="selectEspecie"
                    class="form-control">
                    <option value="">
                        Seleccione una especie
                    </option>

                    <?php

                    $nombresEspecies = [];
                    foreach ($especiesRazas as $e) {
                        $nombreEspecie = $e['nombre_especie'];
                        if (!in_array($nombreEspecie, $nombresEspecies)) {
                            $nombresEspecies[] = $nombreEspecie;
                    ?>

                        <option
                            value="<?= htmlspecialchars($nombreEspecie) ?>">
                            <?= htmlspecialchars($nombreEspecie) ?>
                        </option>

                    <?php
                        }
                    }
                    ?>

                </select>

            </div>

                <div class="form-group">

                    <label>Raza
                        <span style="color:#dc2626;">*</span>
                    </label>

                    <select id="selectRaza" name="id_especie"
                        class="form-control <?= isset($erroresCampos['id_especie']) ? 'is-invalid' : '' ?>">

                        <option value="">
                            Seleccione una raza
                        </option>
                    </select>


                    <?php if (isset($erroresCampos['id_especie'])) { ?>

                        <div class="invalid-feedback">
                            <?= htmlspecialchars($erroresCampos['id_especie']) ?>
                        </div>

                    <?php } ?>

                </div>

                <div class="form-group">
                    <label>Cliente <span style="color:#dc2626;">*</span></label>

                    <select name="id_cliente"
                            class="form-control <?= isset($erroresCampos['id_cliente']) ? 'is-invalid' : '' ?>">
                        <option value="">Seleccione un cliente</option>

                        <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>
                            <option value="<?= $c['id_cliente'] ?>"
                                <?= (($mascota['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['apellido_persona'].", ".$c['nombre_persona']) ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if(isset($erroresCampos['id_cliente'])) { ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['id_cliente']) ?></div>
                    <?php } ?>
                </div>

                  <!-- FOTO DE LA MASCOTA -->
                <div class="form-group mt-3">
                    <label for="foto">
                        <i class="fas fa-camera mr-1" style="color:#52266E;"></i>
                        Fotografía de la mascota
                    </label>

                    <?php if (!empty($mascota['foto'])): ?>
                        <div class="mb-2">
                            <img id="fotoActual"
                                 src="/SoftwareVet/<?= htmlspecialchars(ltrim($mascota['foto'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                                 alt="Foto actual de la mascota"
                                 style="width:120px;height:120px;object-fit:cover;border-radius:12px;border:1px solid #e5dbea;">
                            <small class="form-text text-muted">Fotografía actual</small>
                        </div>
                    <?php endif; ?>

                    <input type="file" id="foto" name="foto"
                           class="form-control-file"
                           accept="image/jpeg,image/png,image/webp">
                    <img id="fotoVistaPrevia" alt="Vista previa de la nueva fotografía"
                         style="display:none;width:120px;height:120px;object-fit:cover;border-radius:12px;margin-top:12px;border:1px solid #e5dbea;">
                    <?php if (isset($erroresCampos['foto'])): ?>
                        <div class="text-danger small mt-2">
                            <?= htmlspecialchars($erroresCampos['foto']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php
                // Condiciones disponibles
                $opciones = [
                    'Ceguera', 'Sordera', 'No vocaliza / dificultad para vocalizar',
                    'Movilidad reducida', 'Epilepsia', 'Requiere medicación',
                    'Alergias', 'Se asusta con extraños', 'Otra'
                ];

                // Condiciones guardadas en MySQL (JSON o valor anterior)
                $guardadas = $mascota['condicion_especial'] ?? '';
                $seleccionadas = json_decode($guardadas, true);

                if (!is_array($seleccionadas)) {
                    $seleccionadas = $guardadas !== '' ? [$guardadas] : [];
                }

                // Si hubo errores al guardar, mantener lo enviado
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $seleccionadas = $_POST['condicion_especial'] ?? [];
                    if (!is_array($seleccionadas)) {
                        $seleccionadas = [];
                    }
                }

                // Recuperar permisos de publicación
                $esPost = $_SERVER['REQUEST_METHOD'] === 'POST';

                $publica = $esPost
                    ? isset($_POST['ficha_publica'])
                    : !empty($mascota['ficha_publica']);

                $telefono = $esPost
                    ? isset($_POST['mostrar_telefono'])
                    : !empty($mascota['mostrar_telefono']);

                $domicilio = $esPost
                    ? isset($_POST['mostrar_domicilio'])
                    : !empty($mascota['mostrar_domicilio']);

                $consentimiento = $esPost
                    ? isset($_POST['confirmar_consentimiento'])
                    : !empty($mascota['consentimiento_qr']);
                ?>

                <section class="vet-qr-section mt-4 mb-4">

                    <h6 class="section-title mb-1">
                        <i class="fas fa-heartbeat mr-2"></i>
                        Condiciones y cuidados especiales
                    </h6>

                    <p class="help-text mb-3">
                        Información para identificar y asistir a la mascota si se extravía.
                    </p>

                    <p class="section-label mb-2">
                        Condiciones
                        <small class="text-muted">(puede marcar más de una)</small>
                    </p>

                    <div class="vet-qr-conditions">
                        <?php foreach ($opciones as $opcion) { ?>
                            <label class="vet-qr-check">
                                <input type="checkbox"
                                    name="condicion_especial[]"
                                    value="<?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>"
                                    <?= in_array($opcion, $seleccionadas, true) ? 'checked' : '' ?>>

                                <span><?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?></span>
                            </label>
                        <?php } ?>
                    </div>

                    <div class="form-group vet-qr-other"
                        id="campo_condicion_otra" hidden>

                        <label for="condicion_otra">Especificar otra condición</label>

                        <input type="text"
                            name="condicion_otra"
                            id="condicion_otra"
                            class="form-control"
                            maxlength="150"
                            placeholder="Ej: dificultad para caminar"
                            value="<?= htmlspecialchars($_POST['condicion_otra'] ?? $mascota['condicion_otra'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                        <?php if (isset($erroresCampos['condicion_otra'])) { ?>
                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($erroresCampos['condicion_otra']) ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="cuidados_especiales">
                            ¿Qué debe saber quien la encuentre?
                        </label>

                        <textarea name="cuidados_especiales"
                                id="cuidados_especiales"
                                class="form-control"
                                rows="3"
                                maxlength="300"
                                placeholder="Ej: Acercarse despacio y hablarle antes de tocarlo."><?= htmlspecialchars($_POST['cuidados_especiales'] ?? $mascota['cuidados_especiales'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

                        <div class="vet-qr-textarea-footer">
                            <small class="help-text">Usá frases cortas y claras.</small>
                            <small class="help-text">
                                <span id="contador_cuidados">0</span>/300
                            </small>
                        </div>
                    </div>

                    <div class="vet-qr-public-options">

                        <label class="vet-qr-toggle">
                            <input type="checkbox"
                                name="ficha_publica"
                                id="ficha_publica"
                                value="1"
                                <?= $publica ? 'checked' : '' ?>>

                            <span>Activar ficha pública con código QR</span>
                        </label>

                        <p class="help-text mb-2">
                            Si la activás, cualquiera que escanee el QR podrá ver
                            los datos identificatorios, condiciones y cuidados de
                            la mascota. No se muestra la historia clínica.
                        </p>

                        <label class="vet-qr-toggle">
                            <input type="checkbox"
                                name="mostrar_telefono"
                                id="mostrar_telefono"
                                value="1"
                                <?= $telefono ? 'checked' : '' ?>>

                            <span>Mostrar el teléfono del dueño en la ficha</span>
                        </label>

                        <label class="vet-qr-toggle">
                            <input type="checkbox"
                                name="mostrar_domicilio"
                                id="mostrar_domicilio"
                                value="1"
                                <?= $domicilio ? 'checked' : '' ?>>

                            <span>Mostrar el domicilio en Google Maps</span>
                        </label>

                        <label class="vet-qr-toggle">
                            <input type="checkbox"
                                name="confirmar_consentimiento"
                                id="confirmar_consentimiento"
                                value="1"
                                <?= $consentimiento ? 'checked' : '' ?>>

                            <span>
                                Confirmo que el propietario autorizó la publicación
                                de los datos seleccionados
                            </span>
                        </label>

                    </div>
                </section>


                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-cancelar">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-purple">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>

<script>

// =====================================================
// COMBO DINÁMICO ESPECIE → RAZA - EDITAR MASCOTA
// =====================================================

// Datos enviados desde PHP
const especiesRazas = <?= json_encode(
    $especiesRazas,
    JSON_UNESCAPED_UNICODE
) ?>;


// ID actualmente guardado en la mascota
const idEspecieActual = <?= json_encode(
    (string)($mascota['id_especie'] ?? '')
) ?>;


// Selects
const selectEspecie =
    document.getElementById('selectEspecie');

const selectRaza =
    document.getElementById('selectRaza');


// =====================================================
// CARGAR RAZAS
// =====================================================

function cargarRazas(
    nombreEspecie,
    idSeleccionado = ''
) {

    // Limpiar combo
    selectRaza.innerHTML =
        '<option value="">Seleccione una raza</option>';


    // Si no hay especie
    if (!nombreEspecie) {

        selectRaza.innerHTML =
            '<option value="">Primero seleccione una especie</option>';

        selectRaza.disabled = true;

        return;
    }


    selectRaza.disabled = false;


    // Filtrar las razas
    const razasFiltradas =
        especiesRazas.filter(function (item) {

            return item.nombre_especie ===
                nombreEspecie;

        });


    // Crear opciones
    razasFiltradas.forEach(function (item) {

        const opcion =
            document.createElement('option');

        opcion.value =
            item.id_especie;

        opcion.textContent =
            item.raza;


        // Seleccionar raza actual
        if (
            String(item.id_especie) ===
            String(idSeleccionado)
        ) {

            opcion.selected = true;
        }


        selectRaza.appendChild(opcion);

    });

}


// =====================================================
// CARGAR DATOS ACTUALES AL ABRIR EDITAR
// =====================================================

if (idEspecieActual) {

    // Buscar el registro actual
    const registroActual =
        especiesRazas.find(function (item) {

            return String(item.id_especie) ===
                String(idEspecieActual);

        });


    if (registroActual) {

        // Seleccionar especie actual
        selectEspecie.value =
            registroActual.nombre_especie;


        // Cargar razas y seleccionar la actual
        cargarRazas(
            registroActual.nombre_especie,
            idEspecieActual
        );

    }

}


// =====================================================
// CUANDO EL USUARIO CAMBIA LA ESPECIE
// =====================================================

selectEspecie.addEventListener(
    'change',
    function () {

        cargarRazas(
            this.value
        );

    }
);

</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
const calendario = flatpickr('#fecha_nacimiento', {
    locale: 'es',
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
    maxDate: 'today',
    allowInput: false,
    disableMobile: true,
    monthSelectorType: 'dropdown',

    // Evitamos que Flatpickr abra automáticamente
    // el calendario al hacer clic en el campo.
    clickOpens: false
});

// El campo visible controla la apertura y el cierre.
calendario.altInput.addEventListener('click', function () {
    if (calendario.isOpen) {
        calendario.close();
    } else {
        calendario.open();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const casillas = Array.from(document.querySelectorAll('input[name="condicion_especial[]"]'));
    const otra = casillas.find(c => c.value === 'Otra');
    const campo = document.getElementById('campo_condicion_otra');
    const detalle = document.getElementById('condicion_otra');
    function actualizarOtra() {
        const visible = Boolean(otra && otra.checked);
        campo.hidden = !visible;
        detalle.required = visible;
        if (!visible) detalle.value = '';
    }
    otra?.addEventListener('change', actualizarOtra);
    actualizarOtra();

    const cuidados = document.getElementById('cuidados_especiales');
    const contador = document.getElementById('contador_cuidados');
    function actualizarContador() { contador.textContent = cuidados.value.length; }
    cuidados.addEventListener('input', actualizarContador);
    actualizarContador();

    const ficha = document.getElementById('ficha_publica');
    const secundarios = ['mostrar_telefono', 'mostrar_domicilio', 'confirmar_consentimiento']
        .map(id => document.getElementById(id));
    function actualizarFicha() {
        secundarios.forEach(casilla => {
            casilla.disabled = !ficha.checked;
            if (!ficha.checked) casilla.checked = false;
        });
    }
    ficha.addEventListener('change', actualizarFicha);
    actualizarFicha();
});

</script>
</body>
</html>

