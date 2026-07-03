<?php
require_once '../../app/menu.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Alta Historia Clí­nica</title>
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/style_system1.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h1 class="h3 page-title ">
                <i class="fas fa-notes-medical mr-2"></i> Nueva Historia Clí­nica
            </h1>
            <div class="page-title">Registro clínico y tratamientos asociados</div>
        </div>

        <a href="index.php" class="btn btn-light-pro">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="form-card">

        <form method="POST" id="frmAlta" novalidate>

            <div class="section-title">
                <i class="fas fa-paw mr-1"></i> Datos de la consulta
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Mascota <span style="color:#dc2626;">*</span></label>

                        <select 
                            name="id_mascota" 
                            id="selMascota" 
                            class="form-control <?= isset($erroresCampos['id_mascota']) ? 'is-invalid' : '' ?>"
                        >
                            <option value="">Seleccione una mascota</option>

                            <?php foreach ($mascotas as $m) { ?>
                                <option value="<?= $m['id_mascota'] ?>"
                                    <?= $postMascota == $m['id_mascota'] ? 'selected' : '' ?>>
                                    HC-<?= str_pad($m['id_mascota'], 4, '0', STR_PAD_LEFT) ?>
                                    |
                                    <?= htmlspecialchars($m['nombre_mascota'] . ' - ' . $m['apellido_persona'] . ', ' . $m['nombre_persona']) ?>
                                </option>
                            <?php } ?>
                        </select>

                        <?php if(isset($erroresCampos['id_mascota'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['id_mascota']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Fecha <span style="color:#dc2626;">*</span></label>

                        <input 
                            type="date" 
                            name="fecha" 
                            class="form-control <?= isset($erroresCampos['fecha']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($postFecha) ?>"
                        >

                        <?php if(isset($erroresCampos['fecha'])) { ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($erroresCampos['fecha']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                <i class="fas fa-clipboard-list mr-1"></i> Notas clínicas
            </div>

            <div class="form-group">
                <label>Descripción <span style="color:#dc2626;">*</span></label>

                <textarea 
                    name="descripcion" 
                    class="form-control <?= isset($erroresCampos['descripcion']) ? 'is-invalid' : '' ?>" 
                    rows="3"
                    placeholder="Ej: Control general, vacunación, revisión de herida..."
                ><?= htmlspecialchars($postDesc) ?></textarea>

                <?php if(isset($erroresCampos['descripcion'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['descripcion']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label>Observación</label>

                <textarea 
                    name="observacion" 
                    class="form-control <?= isset($erroresCampos['observacion']) ? 'is-invalid' : '' ?>" 
                    rows="3"
                    placeholder="Ej: El paciente se encuentra en buen estado general..."
                ><?= htmlspecialchars($postObs) ?></textarea>

                <?php if(isset($erroresCampos['observacion'])) { ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($erroresCampos['observacion']) ?>
                    </div>
                <?php } ?>
            </div>

            <div class="section-title mt-4">
                <i class="fas fa-pills mr-1"></i> Tratamientos
            </div>

            <?php if(isset($erroresCampos['tratamientos'])) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <?= htmlspecialchars($erroresCampos['tratamientos']) ?>
                </div>
            <?php } ?>

            <div id="tratList">
                <div class="text-center text-muted py-3" id="emptyTrat">
                    <i class="fas fa-pills fa-2x mb-2" style="color:#d8c2e8;"></i>
                    <br>
                    Ningún tratamiento agregado aún
                </div>
            </div>

            <button type="button" class="btn-add-trat mt-2" onclick="addTrat()">
                <i class="fas fa-plus"></i> Agregar tratamiento
            </button>

            <div class="d-flex justify-content-end mt-4">
                <a href="index.php" class="btn btn-light-pro mr-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>

                <button type="submit" class="btn btn-purple">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>

        </form>
    </div>

</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sb-admin-2.min.js"></script>

<script>
//tratIdx: funciona como contador para asignar un identificador único a cada tratamiento.
//addTrat(): crea un nuevo bloque de tratamiento con los campos:
//Duración.
//Dosis.
//Descripción.
//appendChild(): inserta el nuevo tratamiento dentro del contenedor principal.
//removeTrat(): elimina el tratamiento seleccionado.
//querySelectorAll('.trat-row'): verifica cuántos tratamientos quedan cargados.

//utilizaste JavaScript dinámico (DOM) para 
// permitir que el usuario agregue multiples tratamientos a una historia clínica de manera flexible 
// y sin necesidad de recargar la página.

// Variable que funciona como contador para identificar cada tratamiento agregado
let tratIdx = 0;

// =====================================================
// FUNCIÓN PARA AGREGAR UN NUEVO TRATAMIENTO
// =====================================================
function addTrat() {

    // Busca el mensaje que aparece cuando no hay tratamientos cargados
    const empty = document.getElementById('emptyTrat');

    // Si existe el mensaje, lo elimina
    if (empty) {
        empty.remove();
    }

    // Crea un nuevo contenedor para el tratamiento
    const div = document.createElement('div');

    // Asigna la clase CSS utilizada para el diseño
    div.className = 'trat-row';

    // Asigna un ID  único usando el contador
    div.id = 'trat-' + tratIdx;

    // Genera dinámicamente el contenido HTML del tratamiento
    div.innerHTML = `

        <!-- Botón para eliminar el tratamiento -->
        <button type="button" class="btn-del-trat" onclick="removeTrat(${tratIdx})">
            <i class="fas fa-times"></i>
        </button>

        <!-- Título del tratamiento -->
        <strong style="color:#52266E;">
            <i class="fas fa-pills mr-1"></i> Tratamiento
        </strong>

        <div class="row mt-3">

            <!-- Campo duración -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>Duración</label>
                    <input 
                        type="text" 
                        name="trat_duracion[]" 
                        class="form-control"
                        placeholder="Ej: 7 días"
                    >
                </div>
            </div>

            <!-- Campo dosis -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>Dosis</label>
                    <input 
                        type="text" 
                        name="trat_dosis[]" 
                        class="form-control"
                        placeholder="Ej: 1 comprimido cada 12 hs"
                    >
                </div>
            </div>

        </div>

        <!-- Campo descripción del tratamiento -->
        <div class="form-group mb-0">
            <label>Descripción del tratamiento</label>
            <textarea 
                name="trat_desc[]" 
                class="form-control" 
                rows="2"
                placeholder="Ej: Medicamento, indicaciones o cuidados..."
            ></textarea>
        </div>
    `;

    // Agrega el nuevo tratamiento dentro del contenedor principal
    document.getElementById('tratList').appendChild(div);

    // Incrementa el contador para el próximo tratamiento
    tratIdx++;
}

// =====================================================
// FUNCIÓN PARA ELIMINAR UN TRATAMIENTO
// =====================================================
function removeTrat(id) {

    // Busca el tratamiento seleccionado por su ID
    const row = document.getElementById('trat-' + id);

    // Si existe, lo elimina del documento
    if (row) {
        row.remove();
    }

    // Verifica si ya no quedan tratamientos cargados
    if (document.querySelectorAll('.trat-row').length === 0) {

        // Muestra nuevamente el mensaje de lista vacía
        document.getElementById('tratList').innerHTML = `
            <div class="text-center text-muted py-3" id="emptyTrat">

                <!-- Ícono decorativo -->
                <i class="fas fa-pills fa-2x mb-2" style="color:#d8c2e8;"></i>

                <br>

                <!-- Mensaje informativo -->
                Ningún tratamiento agregado aún

            </div>
        `;
    }
}

</script>
</body>
</html>

