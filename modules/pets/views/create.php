<?php

require_once '../../app/menu.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de Mascota</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/createpet.css" rel="stylesheet">
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
            Nueva mascota
        </li>

    </ol>

</div>


<div class="container-fluid">

    <h1 class="h3 titulo-pagina">

        <i class="fas fa-paw mr-2"></i>

        Registro de Mascota

    </h1>

    <div class="subtitulo-pagina">

        Completá los datos para registrar un nuevo paciente.

    </div>


    <div class="card card-form mb-4">

        <div class="card-header-form">

            <h5>

                <i class="fas fa-plus-circle mr-2"></i>

                Nueva Mascota

            </h5>

        </div>


        <div class="card-body">

            <?php if (isset($erroresCampos['general'])) { ?>

                <div class="alert alert-danger">

                    <?= htmlspecialchars($erroresCampos['general']) ?>

                </div>

            <?php } ?>


            <?php

            // Guardamos todas las combinaciones de especie y raza
            // para utilizarlas en los combos dinámicos.

            $especiesRazas = [];

            while ($e = mysqli_fetch_assoc($resEspecies)) {

                $especiesRazas[] = $e;

            }

            ?>


            <form method="POST" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="form-group col-md-6">

                        <label>Nombre<span style="color:#dc2626;">*</span></label>

                        <input type="text" name="nombre_mascota"
                            class="form-control <?= isset($erroresCampos['nombre_mascota']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['nombre_mascota'] ?? '') ?>">

                        <?php if(isset($erroresCampos['nombre_mascota'])) { ?>

                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['nombre_mascota']) ?>
                            </div>

                        <?php } ?>

                    </div>


                    <div class="form-group col-md-6">

                        <label for="fecha_nacimiento">Fecha de nacimiento</label>

                        <input
                            type="text"
                            id="fecha_nacimiento"
                            name="fecha_nacimiento"
                            class="form-control calendario-vetsys <?= isset($erroresCampos['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                            placeholder="Seleccionar fecha"
                            autocomplete="off"
                            value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        >

                        <?php if (isset($erroresCampos['fecha_nacimiento'])) { ?>

                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['fecha_nacimiento']) ?>
                            </div>

                        <?php } ?>

                    </div>

                </div>

                <!-- SEXO / PESO -->
                <div class="row">        
                    <!-- SEXO -->
                    <div class="form-group col-md-6">

                        <label>
                            Sexo
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <div class="sexo-options">
                            <label class="sexo-option sexo-macho">
                                <input type="radio" name="sexo"
                                    value="M"
                                    <?= (($_POST['sexo'] ?? '') == 'M') ? 'checked' : '' ?>>
                                <span>
                                    <i class="fas fa-mars"></i>
                                    MACHO
                                </span>
                            </label>

                            <label class="sexo-option sexo-hembra">

                                <input type="radio"
                                    name="sexo"
                                    value="H"
                                    <?= (($_POST['sexo'] ?? '') == 'H') ? 'checked' : '' ?>>

                                <span>
                                    <i class="fas fa-venus"></i>
                                    HEMBRA
                                </span>
                            </label>
                        </div>


                        <?php if(isset($erroresCampos['sexo'])) { ?>

                            <div class="invalid-feedback d-block">

                                <?= htmlspecialchars($erroresCampos['sexo']) ?>

                            </div>

                        <?php } ?>

                    </div>


                    <!-- PESO -->

                    <div class="form-group col-md-6">

                        <label>Peso (kg) <span style="color:#dc2626;">*</span> </label>

                        <input type="number" step="0.01" min="0.1"
                            name="peso"
                            class="form-control <?= isset($erroresCampos['peso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>">

                        <?php if(isset($erroresCampos['peso'])) { ?>

                            <div class="invalid-feedback">

                                <?= htmlspecialchars($erroresCampos['peso']) ?>

                            </div>

                        <?php } ?>

                    </div>

                </div>


                <!-- COLOR / EDAD -->

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Color</label>

                        <input type="text" name="color"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">

                    </div>

                    <div class="form-group col-md-6">

                        <label>Edad</label>

                        <div class="row">
                            <div class="col-md-6">

                                <input type="number" min="0"
                                    name="edad"
                                    class="form-control <?= isset($erroresCampos['edad']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>"
                                    placeholder="Ej: 3">

                            </div>

                            <div class="col-md-6">

                                <select
                                    name="unidad_edad"
                                    class="form-control <?= isset($erroresCampos['unidad_edad']) ? 'is-invalid' : '' ?>">

                                    <option value="">
                                        Unidad
                                    </option>

                                    <option
                                        value="dias"
                                        <?= (($_POST['unidad_edad'] ?? '') == 'dias') ? 'selected' : '' ?>>
                                        Día(s)
                                    </option>

                                    <option
                                        value="meses"
                                        <?= (($_POST['unidad_edad'] ?? '') == 'meses') ? 'selected' : '' ?>>
                                        Mes(es)
                                    </option>

                                    <option
                                        value="años"
                                        <?= (($_POST['unidad_edad'] ?? '') == 'años') ? 'selected' : '' ?>>
                                        Año(s)
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ESPECIE -->

                <div class="form-group">

                    <label>Especie
                        <span style="color:#dc2626;">*</span>
                    </label>

                    <select id="selectEspecie" class="form-control">

                    <option value="">
                            Seleccione una especie
                        </option>


                        <?php

                        // Evita repetir Canino, Felino, etc.

                        $nombresEspecies = [];


                        foreach ($especiesRazas as $e) {

                            $nombreEspecie = $e['nombre_especie'];


                            if (!in_array($nombreEspecie, $nombresEspecies)) {

                                $nombresEspecies[] = $nombreEspecie;

                        ?>

                                <option
                                    value="<?= htmlspecialchars($nombreEspecie) ?>"
                                >

                                    <?= htmlspecialchars($nombreEspecie) ?>

                                </option>

                        <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <!-- RAZA -->

                <div class="form-group">
                    <label>
                        Raza
                        <span style="color:#dc2626;">*</span>
                    </label>


                    <select id="selectRaza"
                        name="id_especie"
                        class="form-control <?= isset($erroresCampos['id_especie']) ? 'is-invalid' : '' ?>"
                        disabled>

                        <option value="">
                            Primero seleccione una especie
                        </option>

                    </select>


                    <?php if (isset($erroresCampos['id_especie'])) { ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars(
                                $erroresCampos['id_especie']
                            ) ?>

                        </div>

                    <?php } ?>

                </div>


                <!-- CLIENTE -->

                <div class="form-group">
                    <label>Cliente
                        <span style="color:#dc2626;">*</span>
                    </label>

                    <div class="cliente-selector">

                        <select name="id_cliente"
                            id="id_cliente"
                            class="form-control <?= isset($erroresCampos['id_cliente']) ? 'is-invalid' : '' ?>">

                            <option value="">
                                Seleccione un cliente
                            </option>


                            <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>

                                <option
                                    value="<?= $c['id_cliente'] ?>"
                                    <?= (($_POST['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>
                                >

                                    <?= htmlspecialchars(
                                        $c['apellido_persona'] . ", " . $c['nombre_persona']
                                    ) ?>

                                </option>

                            <?php } ?>

                        </select>


                        <button
                            type="button"
                            class="btn btn-purple btn-nuevo-cliente"
                            data-toggle="modal"
                            data-target="#modalNuevoCliente">

                            <i class="fas fa-user-plus mr-1"></i>

                            Nuevo cliente

                        </button>

                    </div>


                    <?php if(isset($erroresCampos['id_cliente'])) { ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($erroresCampos['id_cliente']) ?>

                        </div>

                    <?php } ?>

                </div>
                
                <!-- FOTO DE LA MASCOTA-->
                <div class="form-group mt-3">
                    <label for="foto">
                        <i class="fas fa-camera mr-1" style="color:#52266E;"></i>
                        Fotografía de la mascota
                    </label>
                    <input type="file" id="foto" name="foto"
                           class="form-control-file"
                           accept="image/jpeg,image/png,image/webp">
                    </small>
                    <img id="fotoVistaPrevia" alt="Vista previa de la mascota"
                         style="display:none;width:120px;height:120px;object-fit:cover;border-radius:12px;margin-top:12px;border:1px solid #e5dbea;">
                    <?php if (isset($erroresCampos['foto'])): ?>
                        <div class="text-danger small mt-2">
                            <?= htmlspecialchars($erroresCampos['foto']) ?>
                        </div>
                    <?php endif; ?>
                </div>


                <?php
                    $opciones = [
                        'Ceguera', 'Sordera', 'No vocaliza / dificultad para vocalizar',
                        'Movilidad reducida', 'Epilepsia', 'Requiere medicación',
                        'Alergias', 'Se asusta con extraños', 'Otra'
                    ];

                    $seleccionadas = $_POST['condicion_especial'] ?? [];
                    if (!is_array($seleccionadas)) {
                        $seleccionadas = [];
                    }
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
                                        value="<?= htmlspecialchars($opcion) ?>"
                                        <?= in_array($opcion, $seleccionadas, true) ? 'checked' : '' ?>>

                                    <span><?= htmlspecialchars($opcion) ?></span>
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
                                value="<?= htmlspecialchars($_POST['condicion_otra'] ?? '') ?>">
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
                                    placeholder="Ej: Acercarse despacio y hablarle antes de tocarlo."><?= htmlspecialchars($_POST['cuidados_especiales'] ?? '') ?></textarea>

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
                                    <?= !empty($_POST['ficha_publica']) ? 'checked' : '' ?>>

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
                                    <?= !empty($_POST['mostrar_telefono']) ? 'checked' : '' ?>>

                                <span>Mostrar el teléfono del dueño en la ficha</span>
                            </label>

                            <label class="vet-qr-toggle">
                                <input type="checkbox"
                                    name="mostrar_domicilio"
                                    id="mostrar_domicilio"
                                    value="1"
                                    <?= !empty($_POST['mostrar_domicilio']) ? 'checked' : '' ?>>

                                <span>Mostrar el domicilio en Google Maps</span>
                            </label>

                            <label class="vet-qr-toggle">
                                <input type="checkbox"
                                    name="confirmar_consentimiento"
                                    id="confirmar_consentimiento"
                                    value="1"
                                    <?= !empty($_POST['confirmar_consentimiento']) ? 'checked' : '' ?>>

                                <span>
                                    Confirmo que el propietario autorizó la publicación
                                    de los datos seleccionados
                                </span>
                            </label>

                        </div>
                    </section>

                <hr>

                
                <!-- BOTONES -->

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


<!-- =========================================================
     MODAL NUEVO CLIENTE
     ========================================================= -->

<div class="modal fade" id="modalNuevoCliente" tabindex="-1"role="dialog"aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2"></i>
                    Nuevo Cliente
                </h5>

                <button type="button" class="close"data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input type="text" id="cliente_nombre" class="form-control">
                    </div>

                    <div class="form-group col-md-6">

                        <label>Apellido
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input type="text" id="cliente_apellido"class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Teléfono
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input type="text" id="cliente_telefono" class="form-control">

                    </div>

                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" id="cliente_email"class="form-control">
                    </div>

                </div>


                <hr>


                <h6 class="font-weight-bold mb-3">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    Dirección
                </h6>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Calle</label>

                        <input type="text" id="cliente_calle" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Número</label>

                        <input type="text" id="cliente_numero_calle" class="form-control">

                    </div>

                </div>


                <div class="row">

                    <div class="form-group col-md-6">

                        <label>
                            Barrio
                        </label>

                        <input
                            type="text"
                            id="cliente_barrio"
                            class="form-control"
                        >

                    </div>


                    <div class="form-group col-md-6">

                        <label>
                            Manzana
                        </label>

                        <input
                            type="text"
                            id="cliente_manzana"
                            class="form-control"
                        >

                    </div>

                </div>


                <!-- ERRORES -->

                <div
                    id="errorNuevoCliente"
                    class="alert alert-danger d-none"
                ></div>

            </div>


            <!-- PIE -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-cancelar"
                    data-dismiss="modal"
                >

                    <i class="fas fa-times mr-1"></i>

                    Cancelar

                </button>


                <button
                    type="button"
                    class="btn btn-purple"
                    id="btnGuardarCliente"
                >

                    <i class="fas fa-save mr-1"></i>

                    Guardar cliente

                </button>

            </div>

        </div>

    </div>

</div>


<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>

<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="/SoftwareVet/js/sb-admin-2.min.js"></script>


<script>

    // =====================================================
    // GUARDAR CLIENTE DESDE EL MODAL
    // =====================================================

    $('#btnGuardarCliente').click(function () {

        // Ocultamos cualquier error anterior

        $('#errorNuevoCliente')
            .addClass('d-none')
            .text('');


        // Tomamos los datos escritos en el modal

        const datos = {

            nombre_persona: $('#cliente_nombre').val(),

            apellido_persona: $('#cliente_apellido').val(),

            telefono: $('#cliente_telefono').val(),

            email: $('#cliente_email').val(),

            calle: $('#cliente_calle').val(),

            numero_calle: $('#cliente_numero_calle').val(),

            barrio: $('#cliente_barrio').val(),

            manzana: $('#cliente_manzana').val()

        };


        // Enviamos los datos a PHP

        $.post(

            '/SoftwareVet/modules/clients/createModal.php',

            datos,

            function (respuesta) {

                // Separamos la respuesta de PHP

                const partes = respuesta.trim().split('|');


                // Si el cliente se guardó correctamente

                if (partes[0] === 'OK') {

                    const idCliente = partes[1];

                    const nombreCliente = partes[2];


                    // Creamos una nueva opción en el select

                    const nuevaOpcion = new Option(

                        nombreCliente,

                        idCliente,
                        true,
                        true

                    );


                    // Agregamos el cliente y lo dejamos seleccionado

                    $('#id_cliente').append(nuevaOpcion);


                    // Cerramos el modal

                    $('#modalNuevoCliente').modal('hide');


                    // Limpiamos los campos del modal

                    $('#cliente_nombre').val('');

                    $('#cliente_apellido').val('');

                    $('#cliente_telefono').val('');

                    $('#cliente_email').val('');

                    $('#cliente_calle').val('');

                    $('#cliente_numero_calle').val('');

                    $('#cliente_barrio').val('');

                    $('#cliente_manzana').val('');


                } else {

                    // Si PHP devuelve un error, lo mostramos

                    $('#errorNuevoCliente')
                        .removeClass('d-none')
                        .text(partes[1]);

                }

            }

        );

    });


    // =====================================================
    // COMBO DINÁMICO ESPECIE → RAZA
    // =====================================================

    // Pasamos las especies/razas de PHP a JavaScript

    const especiesRazas = <?= json_encode(
        $especiesRazas,
        JSON_UNESCAPED_UNICODE
    ) ?>;


    // Selects

    const selectEspecie =
        document.getElementById('selectEspecie');

    const selectRaza =
        document.getElementById('selectRaza');


    selectEspecie.addEventListener(
        'change',
        function () {

            const especieSeleccionada =
                this.value;


            // Limpiamos las razas anteriores

            selectRaza.innerHTML =
                '<option value="">Seleccione una raza</option>';


            // Si no seleccionó especie

            if (!especieSeleccionada) {

                selectRaza.innerHTML =
                    '<option value="">Primero seleccione una especie</option>';

                selectRaza.disabled = true;

                return;
            }


            // Habilitamos el combo raza

            selectRaza.disabled = false;


            // Buscamos solamente las razas
            // correspondientes a la especie elegida

            const razasFiltradas =
                especiesRazas.filter(function (item) {

                    return item.nombre_especie ===
                        especieSeleccionada;

                });


            // Agregamos las opciones

            razasFiltradas.forEach(function (item) {

                const opcion =
                    document.createElement('option');


                // IMPORTANTE:
                // guardamos el ID del registro especie/raza

                opcion.value =
                    item.id_especie;


                opcion.textContent =
                    item.raza;


                selectRaza.appendChild(opcion);

            });

        }
    );

</script>


<!-- Calendario Flatpickr -->
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

    const checkboxes = document.querySelectorAll(
        'input[name="condicion_especial[]"]'
    );

    const campoOtra = document.getElementById('campo_condicion_otra');
    const inputOtra = document.getElementById('condicion_otra');

    function actualizarOtra() {

        const otraSeleccionada = Array.from(checkboxes).some(
            checkbox => checkbox.value === 'Otra' && checkbox.checked
        );

        campoOtra.hidden = !otraSeleccionada;

        if (inputOtra) {
            inputOtra.required = otraSeleccionada;
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', actualizarOtra);
    });

    actualizarOtra();
});

const cuidados = document.getElementById('cuidados_especiales');
const contador = document.getElementById('contador_cuidados');

function actualizarContador() {
    contador.textContent = cuidados.value.length;
}

cuidados.addEventListener('input', actualizarContador);
actualizarContador();

const inputFoto = document.getElementById('foto');
const vistaPrevia = document.getElementById('fotoVistaPrevia');
inputFoto.addEventListener('change', function () {
    const archivo = this.files[0];
    if (archivo) {
        vistaPrevia.src = URL.createObjectURL(archivo);
        vistaPrevia.style.display = 'block';
    } else {
        vistaPrevia.style.display = 'none';
    }
});

</script>
</body>

</html>