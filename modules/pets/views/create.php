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


            <form method="POST" novalidate>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre <span style="color:#dc2626;">*</span></label>

                        <input type="text" name="nombre_mascota"
                            class="form-control <?= isset($erroresCampos['nombre_mascota']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['nombre_mascota'] ?? '') ?>">

                        <?php if(isset($erroresCampos['nombre_mascota'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['nombre_mascota']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha nacimiento</label>

                        <input type="date" name="fecha_nacimiento"
                            class="form-control <?= isset($erroresCampos['fecha_nacimiento']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>">

                        <?php if(isset($erroresCampos['fecha_nacimiento'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['fecha_nacimiento']) ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Sexo <span style="color:#dc2626;">*</span></label>

                        <select name="sexo"
                            class="form-control <?= isset($erroresCampos['sexo']) ? 'is-invalid' : '' ?>">
                            <option value="">Seleccione</option>
                            <option value="M" <?= (($_POST['sexo'] ?? '') == 'M') ? 'selected' : '' ?>>Macho</option>
                            <option value="H" <?= (($_POST['sexo'] ?? '') == 'H') ? 'selected' : '' ?>>Hembra</option>
                        </select>

                        <?php if(isset($erroresCampos['sexo'])) { ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($erroresCampos['sexo']) ?></div>
                        <?php } ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Peso (kg) <span style="color:#dc2626;">*</span></label>

                        <input type="number" step="0.01" min="0.1" name="peso"
                            class="form-control <?= isset($erroresCampos['peso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>">

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
                            value="<?= htmlspecialchars($_POST['color'] ?? '') ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Edad</label>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" min="0" name="edad"
                                    class="form-control <?= isset($erroresCampos['edad']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>"
                                    placeholder="Ej: 3">
                            </div>

                            <div class="col-md-6">
                                <select name="unidad_edad"
                                    class="form-control <?= isset($erroresCampos['unidad_edad']) ? 'is-invalid' : '' ?>">
                                    <option value="">Unidad</option>
                                    <option value="dias" <?= (($_POST['unidad_edad'] ?? '') == 'dias') ? 'selected' : '' ?>>Días</option>
                                    <option value="meses" <?= (($_POST['unidad_edad'] ?? '') == 'meses') ? 'selected' : '' ?>>Meses</option>
                                    <option value="años" <?= (($_POST['unidad_edad'] ?? '') == 'años') ? 'selected' : '' ?>>Años</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
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

                    <div class="form-group">

                        <label>Raza
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <select id="selectRaza" name="id_especie"
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

                <div class="form-group">

                    <label>Cliente
                        <span style="color:#dc2626;">*</span>
                    </label>

                    <div class="cliente-selector">

                        <select name="id_cliente" id="id_cliente"
                            class="form-control <?= isset($erroresCampos['id_cliente']) ? 'is-invalid' : '' ?>">

                            <option value="">Seleccione un cliente</option>

                            <?php while($c = mysqli_fetch_assoc($resClientes)) { ?>

                                <option value="<?= $c['id_cliente'] ?>"
                                    <?= (($_POST['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>>

                                    <?= htmlspecialchars(
                                        $c['apellido_persona'] . ", " . $c['nombre_persona']
                                    ) ?>

                                </option>

                            <?php } ?>

                        </select>


                        <button type="button" class="btn btn-purple btn-nuevo-cliente"
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

<!-- MODAL NUEVO CLIENTE -->
<div class="modal fade"
    id="modalNuevoCliente"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <div class="modal-content">

            <!-- Encabezado -->
            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2"></i>
                    Nuevo Cliente
                </h5>

                <button type="button"
                    class="close"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <!-- Cuerpo -->
            <div class="modal-body">

                <div class="row">

                    <div class="form-group col-md-6">
                        <label>
                            Nombre
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input type="text"
                            id="cliente_nombre"
                            class="form-control">
                    </div>


                    <div class="form-group col-md-6">
                        <label>
                            Apellido
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input type="text"
                            id="cliente_apellido"
                            class="form-control">
                    </div>

                </div>


                <div class="row">

                    <div class="form-group col-md-6">
                        <label>
                            Teléfono
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input type="text"
                            id="cliente_telefono"
                            class="form-control">
                    </div>


                    <div class="form-group col-md-6">
                        <label>Email</label>

                        <input type="email"
                            id="cliente_email"
                            class="form-control">
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

                        <input type="text"
                            id="cliente_calle"
                            class="form-control">
                    </div>


                    <div class="form-group col-md-6">
                        <label>Número</label>

                        <input type="text"
                            id="cliente_numero_calle"
                            class="form-control">
                    </div>

                </div>


                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Barrio</label>

                        <input type="text"
                            id="cliente_barrio"
                            class="form-control">
                    </div>


                    <div class="form-group col-md-6">
                        <label>Manzana</label>

                        <input type="text"
                            id="cliente_manzana"
                            class="form-control">
                    </div>

                </div>


                <!-- Errores -->
                <div id="errorNuevoCliente"
                    class="alert alert-danger d-none">
                </div>

            </div>


            <!-- Pie -->
            <div class="modal-footer">

                <button type="button"
                    class="btn btn-cancelar"
                    data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>
                    Cancelar

                </button>


                <button type="button"
                    class="btn btn-purple"
                    id="btnGuardarCliente">

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

    // Al hacer clic en "Guardar cliente"
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
</body>
</html>

