<?php
require_once __DIR__ . '/../../settings/conexion.php';
require_once __DIR__ . '/../../app/menu.php';

?>

<link href="/SoftwareVet/css/help_style.css" rel="stylesheet">

<div class="container-fluid">

    <!-- Breadcrumb -->
    <div class="vetsys-breadcrumb-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb vetsys-breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/SoftwareVet/app/inicio.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item active">Centro de Ayuda</li>
            </ol>
        </nav>
    </div>

    <!-- CABECERA -->
    <div class="help-header">
        <div class="help-header-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <div>
            <h2>Centro de Ayuda</h2>
            <p>
                Bienvenido al Centro de Ayuda de VetSys.
                Si es su primera vez, empiece por <b>Primeros pasos</b>.
                También puede buscar por módulo.
            </p>
        </div>
    </div>

    <!-- BUSCADOR -->
    <div class="help-search" id="zonaBuscador">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <input
                type="text"
                class="form-control"
                id="buscarModulo"
                placeholder="Buscar módulo (ej: turno, cliente, botón)..."
                aria-label="Buscar en el Centro de Ayuda"
                autocomplete="off">
        </div>
    </div>

    <!-- MÓDULOS (se generan desde JS) -->
    <div id="modulosAyuda"></div>

    <!-- SIN RESULTADOS -->
    <div class="text-center text-muted py-5" id="sinResultados">
        <i class="fas fa-search fa-3x mb-3"></i>
        <h5>No se encontraron resultados</h5>
        <p>Pruebe con otra palabra, por ejemplo "turno", "cliente" o "historia".</p>
    </div>

    <!-- PANEL DE INFORMACIÓN -->
    <div class="card shadow border-0 mt-4 mb-4" id="panelAyuda" style="display:none;">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center">
                <div id="panelIcono" class="mr-3 help-panel-icon"></div>
                <div>
                    <h3 id="panelTitulo" class="mb-1 font-weight-bold"></h3>
                    <small class="text-muted" id="panelSubtitulo">Información del módulo</small>
                </div>
            </div>

            <button class="btn btn-outline-secondary btn-sm" id="cerrarPanel" type="button">
                <i class="fas fa-times"></i> Cerrar
            </button>

        </div>

        <div class="card-body">
            <div id="panelContenido"></div>
        </div>

    </div>

</div>

<script>

/* ======================================================
   CONFIGURACIÓN
====================================================== */
const BASE_URL = "/SoftwareVet";


/* ======================================================
   MUESTRAS VISUALES (réplicas de los botones del sistema)
====================================================== */
const M = {
    nuevo:    t => `<span class="ref-btn ref-nuevo"><i class="fas fa-plus"></i> ${t}</span>`,
    lupa:     () => `<span class="ref-btn ref-lupa"><i class="fas fa-search"></i></span>`,
    excel:    () => `<span class="ref-btn ref-exp ref-excel"><i class="fas fa-file-excel"></i> Excel</span>`,
    pdf:      () => `<span class="ref-btn ref-exp ref-pdf"><i class="fas fa-file-pdf"></i> PDF</span>`,
    imprimir: () => `<span class="ref-btn ref-exp ref-print"><i class="fas fa-print"></i> Imprimir</span>`,
    ver:      () => `<span class="ref-btn ref-accion ref-ver"><i class="fas fa-file-medical"></i></span>`,
    editar:   () => `<span class="ref-btn ref-accion ref-edit"><i class="fas fa-pen"></i></span>`,
    eliminar: () => `<span class="ref-btn ref-accion ref-del"><i class="fas fa-trash"></i></span>`,
    atender:  () => `<span class="ref-btn ref-atender"><i class="fas fa-stethoscope"></i></span>`,
    guardar:  t => `<span class="ref-btn ref-guardar"><i class="fas fa-save"></i> ${t}</span>`,
    cancelar: () => `<span class="ref-btn ref-cancelar"><i class="fas fa-times"></i> Cancelar</span>`,
    nuevoCli: () => `<span class="ref-btn ref-nuevo"><i class="fas fa-user-plus"></i> Nuevo cliente</span>`,
    estado:   (clave, texto, flecha) =>
        `<span class="ref-estado ref-e-${clave}">${texto}${flecha ? ' <i class="fas fa-chevron-down"></i>' : ''}</span>`
};

/* ======================================================
   CATEGORÍAS DEL CENTRO DE AYUDA
====================================================== */

const categorias = [

    {
        nombre: "Para empezar",
        modulos: ["primerospasos", "botones"]
    },

    {
        nombre: "Gestión",
        modulos: [
            "inicio",
            "mascotas",
            "clientes",
            "profesionales"
        ]
    },

    {
        nombre: "Atención",
        modulos: [
            "turnos",
            "historiaclinica"
        ]
    },

    {
        nombre: "Catálogos",
        modulos: [
            "especies"
        ]
    },

    {
        nombre: "Administración",
        modulos: [
            "usuarios",
            "perfiles",
            "modulos",
            "auditoria"
        ]
    }

];
/* ======================================================
   CONTENIDO DE LAS GUÍAS (un solo lugar para todo)

   Campos opcionales:
   - requisitos:   qué debe existir antes de usar el módulo
   - relacionados: ids de otras guías (se muestran como enlaces)
   - flujo:        HTML con un diagrama de pasos
   - referencia:   grupos de botones con su muestra visual
   - subtitulo, itemsTitulo, pasosTitulo, flujoTitulo: encabezados
   - url:          si se omite, no se muestra el botón "Ir a..."
====================================================== */
const ayuda = {

    primerospasos: {
        titulo: "Primeros pasos",
        icono: "fa-rocket",
        descripcion: "El recorrido completo, desde cargar datos hasta la historia clínica.",
        subtitulo: "Guía general del sistema",
        itemsTitulo: "Qué debe tener cargado antes de dar un turno",
        pasosTitulo: "Recorrido recomendado",
        items: [
            "Las especies con las que trabaja la veterinaria.",
            "El cliente (propietario de la mascota).",
            "La mascota, asociada a su cliente y su especie.",
            "Al menos un profesional que atienda."
        ],
        flujoTitulo: "Cómo se conectan los módulos",
        flujo: `
            <div class="help-flujo">
                <span class="help-flujo-paso"><i class="fas fa-address-book"></i> Cliente</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="help-flujo-paso"><i class="fas fa-paw"></i> Mascota</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="help-flujo-paso"><i class="fas fa-calendar-check"></i> Turno</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="help-flujo-paso"><i class="fas fa-user-md"></i> Atención</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="help-flujo-paso"><i class="fas fa-stethoscope"></i> Historia clínica</span>
            </div>`,
        pasos: [
            "Cargue las especies en Catálogos > Especies (si todavía no están).",
            "Cargue los profesionales en Gestión > Profesionales.",
            "Registre la mascota en Gestión > Mascotas con el botón <b>Nueva Mascota</b>. Si su dueño todavía no existe, créelo desde el mismo formulario con el botón <b>Nuevo cliente</b>.",
            "Cree el turno en Atención > Turnos con el botón <b>Nuevo Turno</b>.",
            "El día de la consulta, cuando el turno esté <b>En atención</b>, haga clic en el botón del estetoscopio para registrar la atención.",
            "Consulte lo registrado en Atención > Historia Clínica."
        ],
        consejo: "Al registrar la atención médica el turno pasa solo a Completado y la consulta queda guardada en la historia clínica de la mascota. Si no reconoce algún botón, consulte la guía Botones y estados.",
        relacionados: ["botones", "especies", "profesionales", "clientes", "mascotas", "turnos", "historiaclinica"],
        faq: [
            { p: "¿Por qué no puedo guardar una mascota?", r: "Revise que estén completos todos los campos marcados con * (Nombre, Sexo, Peso, Especie, Raza y Cliente). La raza se habilita recién después de elegir la especie." },
            { p: "¿Cómo se crea una historia clínica?", r: "No se crea a mano: se genera al registrar una atención desde un turno." }
        ]
    },

    botones: {
        titulo: "Botones y estados",
        icono: "fa-hand-pointer",
        descripcion: "Qué hace cada botón y cada color del sistema.",
        subtitulo: "Guía visual",
        referencia: [
            {
                titulo: "Arriba de cada listado",
                lista: [
                    { muestra: M.nuevo("Nueva Mascota"), nombre: "Botón de registro", desc: "Abre el formulario para crear un registro nuevo. El texto cambia según el módulo (Nueva Mascota, Nuevo Turno...)." }
                ]
            },
            {
                titulo: "Filtros y exportación",
                lista: [
                    { muestra: M.lupa(),     nombre: "Lupa", desc: "Aplica los filtros que eligió para actualizar el listado." },
                    { muestra: M.excel(),    nombre: "Excel", desc: "Descarga el listado en una planilla de Excel." },
                    { muestra: M.pdf(),      nombre: "PDF", desc: "Descarga el listado en un archivo PDF." },
                    { muestra: M.imprimir(), nombre: "Imprimir", desc: "Abre la vista de impresión del listado." }
                ]
            },
            {
                titulo: "Acciones de cada fila",
                lista: [
                    { muestra: M.ver(),      nombre: "Historia clínica", desc: "En Mascotas, abre las consultas de esa mascota." },
                    { muestra: M.editar(),   nombre: "Editar", desc: "Permite modificar los datos del registro." },
                    { muestra: M.eliminar(), nombre: "Eliminar", desc: "Pide una confirmación antes de borrar. El registro no se elimina hasta que confirme." },
                    { muestra: M.atender(),  nombre: "Registrar atención", desc: "En Turnos, aparece solo cuando el turno está En atención. Abre el formulario de la consulta." }
                ]
            },
            {
                titulo: "En los formularios",
                lista: [
                    { muestra: M.guardar("Guardar"), nombre: "Guardar", desc: "Graba los datos cargados." },
                    { muestra: M.cancelar(),         nombre: "Cancelar", desc: "Sale del formulario sin guardar los cambios." },
                    { muestra: M.nuevoCli(),         nombre: "Nuevo cliente", desc: "En el formulario de mascotas, permite registrar un cliente sin salir de la pantalla." }
                ]
            },
            {
                titulo: "Estados de un turno",
                lista: [
                    { muestra: M.estado("pendiente", "Pendiente", true), nombre: "Pendiente", desc: "Turno registrado, todavía sin confirmar. La flecha indica que puede cambiarlo haciendo clic." },
                    { muestra: M.estado("confirmado", "Confirmado"),     nombre: "Confirmado", desc: "Turno confirmado." },
                    { muestra: M.estado("en_atencion", "En atención"),   nombre: "En atención", desc: "La mascota está siendo atendida. En este estado aparece el botón del estetoscopio." },
                    { muestra: M.estado("completado", "Completado"),     nombre: "Completado", desc: "La atención ya fue registrada. El turno no muestra más acciones." },
                    { muestra: M.estado("cancelado", "Cancelado"),       nombre: "Cancelado", desc: "El turno fue cancelado." }
                ]
            }
        ],
        consejo: "Si duda de qué hace un botón de la tabla, deje el mouse encima unos segundos: el sistema muestra su nombre.",
        relacionados: ["mascotas", "turnos"],
        faq: []
    },

    inicio: {
        titulo: "Dashboard",
        icono: "fa-th-large",
        descripcion: "Resumen general del sistema.",
        url: "/app/inicio.php",
        items: [
            "Ver estadísticas de mascotas.",
            "Ver clientes registrados.",
            "Ver profesionales.",
            "Ver los turnos del día.",
            "Consultar gráficos informativos."
        ],
        pasos: [
            "Haga clic en Dashboard en el menú lateral.",
            "Revise los indicadores del resumen.",
            "Use el menú lateral para ir al módulo que necesite."
        ],
        consejo: "Es la pantalla ideal para empezar el día: muestra de un vistazo cuántos turnos hay hoy.",
        relacionados: ["turnos"],
        faq: []
    },

    mascotas: {
        titulo: "Mascotas",
        icono: "fa-paw",
        descripcion: "Gestión completa de mascotas.",
        url: "/modules/pets/index.php",
        requisitos: [
            "Una especie cargada en el catálogo (después de elegirla se habilita la raza).",
            "El cliente propietario. Si todavía no existe, puede crearlo desde el mismo formulario con el botón <b>Nuevo cliente</b>."
        ],
        items: [
            "Registrar mascotas con el botón <b>Nueva Mascota</b>.",
            "Buscar por nombre o propietario y filtrar por especie y sexo.",
            "Editar los datos con el botón del lápiz.",
            "Eliminar un registro con el botón del tacho (pide confirmación).",
            "Acceder a la historia clínica con el botón azul.",
            "Exportar el listado a Excel o PDF, o imprimirlo."
        ],
        pasos: [
            "Ingrese a Gestión > Mascotas y haga clic en <b>Nueva Mascota</b>.",
            "Complete Nombre, Sexo (Macho o Hembra) y Peso (kg): son obligatorios.",
            "Si lo desea, complete Fecha de nacimiento, Color y Edad con su unidad.",
            "Elija la Especie. Luego se habilita la Raza, que también es obligatoria.",
            "Elija el Cliente. Si no está en la lista, use <b>Nuevo cliente</b>.",
            "Haga clic en <b>Guardar</b>, o en <b>Cancelar</b> para salir sin guardar."
        ],
        consejo: "Los campos marcados con * son obligatorios. La raza depende de la especie: si el selector aparece bloqueado, elija primero la especie.",
        relacionados: ["botones", "clientes", "especies", "historiaclinica"],
        faq: [
            { p: "No puedo elegir la raza, ¿qué hago?", r: "Primero seleccione una especie. La lista de razas depende de la especie elegida." },
            { p: "El cliente no aparece en la lista, ¿qué hago?", r: "Haga clic en el botón Nuevo cliente, a la derecha del selector, para registrarlo sin salir del formulario." },
            { p: "No aparece la especie de mi mascota.", r: "Debe cargarla antes en Catálogos > Especies." }
        ]
    },

    clientes: {
        titulo: "Clientes",
        icono: "fa-address-book",
        descripcion: "Registro y administración de clientes.",
        url: "/modules/clients/index.php",
        items: [
            "Registrar propietarios de mascotas.",
            "Editar información.",
            "Buscar registros.",
            "Eliminar clientes."
        ],
        pasos: [
            "Ingrese a Gestión > Clientes.",
            "Haga clic en el botón morado de arriba a la derecha para registrar un cliente.",
            "Complete los datos y guarde.",
            "Luego registre sus mascotas desde el módulo Mascotas."
        ],
        consejo: "También puede registrar un cliente nuevo mientras carga una mascota, con el botón Nuevo cliente del formulario.",
        relacionados: ["botones", "mascotas"],
        faq: []
    },

    profesionales: {
        titulo: "Profesionales",
        icono: "fa-user-md",
        descripcion: "Administración de profesionales.",
        url: "/modules/professionals/index.php",
        items: [
            "Registrar profesional.",
            "Editar información.",
            "Buscar profesionales.",
            "Eliminar profesionales."
        ],
        pasos: [
            "Ingrese a Gestión > Profesionales.",
            "Haga clic en el botón morado de arriba a la derecha para registrar un profesional.",
            "Complete sus datos y guarde."
        ],
        consejo: "Mantenga actualizados los datos del personal veterinario para asignar turnos correctamente.",
        relacionados: ["botones", "turnos"],
        faq: []
    },

    turnos: {
        titulo: "Turnos",
        icono: "fa-calendar-check",
        descripcion: "Agenda y seguimiento de turnos.",
        url: "/modules/appointments/index.php",
        requisitos: [
            "Una mascota registrada.",
            "Un profesional registrado."
        ],
        items: [
            "Crear turnos con el botón <b>Nuevo Turno</b>.",
            "Filtrar por profesional, estado y rango de fechas.",
            "Cambiar el estado de un turno.",
            "Registrar la atención médica con el botón del estetoscopio.",
            "Editar o eliminar turnos.",
            "Exportar el listado a Excel o PDF, o imprimirlo."
        ],
        flujoTitulo: "Estados de un turno",
        flujo: `
            <div class="help-flujo">
                ${M.estado("pendiente", "Pendiente")}
                <i class="fas fa-arrow-right text-muted"></i>
                ${M.estado("confirmado", "Confirmado")}
                <i class="fas fa-arrow-right text-muted"></i>
                ${M.estado("en_atencion", "En atención")}
                <i class="fas fa-arrow-right text-muted"></i>
                ${M.estado("completado", "Completado")}
            </div>
            <p class="text-center text-muted small mb-0">
                Un turno también puede quedar ${M.estado("cancelado", "Cancelado")}
            </p>`,
        pasos: [
            "Ingrese a Atención > Turnos y haga clic en <b>Nuevo Turno</b>.",
            "Complete los datos del turno (mascota, profesional, fecha y hora, motivo) y guarde.",
            "El turno aparece en el listado. Para cambiar su estado, haga clic sobre el estado: los que muestran una flecha se pueden modificar.",
            "Cuando el turno esté <b>En atención</b>, aparece el botón del estetoscopio. Haga clic para abrir el formulario de la consulta.",
            "Complete los datos de la consulta (los campos con * son obligatorios) y el monto total.",
            "Haga clic en <b>Guardar</b> para registrar la atención. El turno pasa a Completado."
        ],
        consejo: "El botón del estetoscopio solo aparece mientras el turno está En atención. Los turnos Completados ya no muestran acciones.",
        faq: [
            { p: "No veo el botón del estetoscopio, ¿por qué?", r: "Solo aparece cuando el turno está En atención. Si está Pendiente o Confirmado, cambie primero su estado haciendo clic sobre él." },
            { p: "¿Qué pasa cuando registro la atención médica?", r: "El turno pasa automáticamente a Completado y la consulta queda guardada en la historia clínica de la mascota." },
            { p: "¿Dónde veo las consultas ya realizadas?", r: "En el módulo Historia Clínica, buscando a la mascota atendida." }
        ],
        relacionados: ["botones"]
    },

    historiaclinica: {
        titulo: "Historia Clínica",
        icono: "fa-stethoscope",
        descripcion: "Registro de consultas veterinarias.",
        url: "/modules/medical_records/index.php",
        items: [
            "Consultar el diagnóstico.",
            "Consultar el tratamiento.",
            "Consultar la medicación.",
            "Consultar las observaciones.",
            "Consultar el importe."
        ],
        pasos: [
            "Ingrese a Historia Clínica desde el menú lateral, o use el botón azul de la fila de la mascota en Mascotas.",
            "Busque la mascota que necesita.",
            "Abra el registro para ver el detalle de cada consulta."
        ],
        consejo: "La historia clínica se genera automáticamente cuando se registra una atención desde el módulo Turnos.",
        relacionados: ["turnos", "mascotas"],
        faq: [
            { p: "¿Cómo se crea una consulta nueva?", r: "Se crea al registrar la atención médica desde un turno." }
        ]
    },

    especies: {
        titulo: "Especies",
        icono: "fa-cat",
        descripcion: "Catálogo de especies.",
        url: "/modules/species/index.php",
        items: [
            "Crear especie.",
            "Editar especie.",
            "Eliminar especie."
        ],
        pasos: [
            "Ingrese a Catálogos > Especies.",
            "Haga clic en el botón morado de arriba a la derecha para crear una especie.",
            "Escriba el nombre y guarde."
        ],
        consejo: "Las especies registradas estarán disponibles al crear una mascota.",
        relacionados: ["botones", "mascotas"],
        faq: []
    },

    usuarios: {
        titulo: "Usuarios",
        icono: "fa-users",
        descripcion: "Administración de usuarios del sistema.",
        url: "/modules/users/index.php",
        subtitulo: "Módulo de Administración",
        requisitos: [
            "Al menos un perfil creado, para poder asignarlo."
        ],
        items: [
            "Registrar usuarios.",
            "Editar información.",
            "Asignar perfiles.",
            "Activar o desactivar usuarios."
        ],
        pasos: [
            "Ingrese a Administración > Usuarios.",
            "Use la opción para crear un usuario.",
            "Complete los datos y asigne un perfil.",
            "Guarde los cambios."
        ],
        consejo: "Utilice perfiles para controlar los permisos de acceso.",
        relacionados: ["perfiles"],
        faq: []
    },

    perfiles: {
        titulo: "Perfiles",
        icono: "fa-user-shield",
        descripcion: "Gestión de perfiles y permisos.",
        url: "/modules/profiles/index.php",
        subtitulo: "Módulo de Administración",
        items: [
            "Crear perfiles.",
            "Asignar módulos.",
            "Modificar permisos."
        ],
        pasos: [
            "Ingrese a Administración > Perfiles.",
            "Cree un perfil nuevo o edite uno existente.",
            "Seleccione los módulos a los que tendrá acceso.",
            "Guarde los cambios."
        ],
        consejo: "Asigne a cada perfil únicamente los módulos que necesita para trabajar.",
        relacionados: ["usuarios", "modulos"],
        faq: []
    },

    modulos: {
        titulo: "Módulos",
        icono: "fa-cubes",
        descripcion: "Organización y acceso a los módulos.",
        url: "/modules/system_modules/index.php",
        subtitulo: "Módulo de Administración",
        items: [
            "Crear módulos.",
            "Editar módulos.",
            "Activar o desactivar módulos.",
            "Asignar módulos a perfiles."
        ],
        pasos: [
            "Ingrese a Administración > Módulos.",
            "Cree o edite el módulo que necesite.",
            "Active o desactive su disponibilidad.",
            "Guarde los cambios."
        ],
        consejo: "Antes de desactivar un módulo, confirme que ningún perfil lo necesite para trabajar.",
        relacionados: ["perfiles"],
        faq: []
    },

    auditoria: {
        titulo: "Auditoría",
        icono: "fa-history",
        descripcion: "Registro de acciones del sistema.",
        url: "/modules/audit/index.php",
        subtitulo: "Módulo de Administración",
        items: [
            "Ver el usuario que realizó la acción.",
            "Ver el módulo afectado.",
            "Ver el tipo de acción.",
            "Ver la fecha y hora.",
            "Ver valores anteriores y nuevos."
        ],
        pasos: [
            "Ingrese a Administración > Auditoría.",
            "Revise el listado de acciones.",
            "Compare los valores anteriores con los nuevos."
        ],
        consejo: "Utilice la auditoría para rastrear cambios y detectar acciones no autorizadas.",
        relacionados: ["usuarios"],
        faq: []
    }

};

/* ======================================================
   GUÍAS VISIBLES PARA ESTE PERFIL
====================================================== */
const visibles = new Set();
categorias.forEach(function (cat) {
    cat.modulos.forEach(function (id) {
        if (ayuda[id]) visibles.add(id);
    });
});

/* ======================================================
   UTILIDADES
====================================================== */
function normalizar(texto) {
    return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");
}

function escaparAttr(texto) {
    return texto
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}

function textoBusqueda(g) {

    let partes = [g.titulo, g.descripcion, g.consejo]
        .concat(g.items || [], g.pasos || [], g.requisitos || []);

    (g.referencia || []).forEach(function (grupo) {
        partes.push(grupo.titulo);
        grupo.lista.forEach(function (b) { partes.push(b.nombre, b.desc); });
    });

    g.faq.forEach(function (f) { partes.push(f.p, f.r); });

    // Se quitan las etiquetas HTML antes de buscar
    return normalizar(partes.join(" ").replace(/<[^>]*>/g, ""));
}

/* ======================================================
   GENERAR TARJETAS DESDE EL OBJETO ayuda
====================================================== */
const contenedor = document.getElementById("modulosAyuda");

categorias.forEach(function (cat) {

    let seccion = document.createElement("div");
    seccion.className = "help-seccion";

    let html = `<div class="help-categoria">${cat.nombre}</div><div class="row">`;

    cat.modulos.forEach(function (id) {

        let g = ayuda[id];
        if (!g) return;

        html += `
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 modulo-card"
             data-buscar="${escaparAttr(textoBusqueda(g))}">
            <div class="help-card" data-modulo="${id}" role="button" tabindex="0"
                 aria-label="Ver guía de ${g.titulo}">
                <div class="help-icon"><i class="fas ${g.icono}"></i></div>
                <h5>${g.titulo}</h5>
                <p>${g.descripcion}</p>
                <span>Ver guía <i class="fas fa-arrow-right"></i></span>
            </div>
        </div>`;
    });

    html += `</div>`;
    seccion.innerHTML = html;
    contenedor.appendChild(seccion);
});

/* ======================================================
   CONSTRUIR EL CONTENIDO DE UNA GUÍA
====================================================== */
function construirContenido(g) {

    let requisitos = "";
    if (g.requisitos && g.requisitos.length) {
        requisitos = `
        <div class="help-requisitos">
            <b><i class="fas fa-info-circle text-purple"></i> Antes de empezar, necesita:</b>
            <ul>${g.requisitos.map(function (r) { return `<li>${r}</li>`; }).join("")}</ul>
        </div>`;
    }

    let items = "";
    if (g.items && g.items.length) {
        items = `
        <h5 class="font-weight-bold mb-3">${g.itemsTitulo || "¿Qué permite este módulo?"}</h5>
        <ul class="help-list">
            ${g.items.map(function (i) {
                return `<li><i class="fas fa-check-circle text-success"></i> ${i}</li>`;
            }).join("")}
        </ul>`;
    }

    let flujo = g.flujo
        ? `<hr><h5 class="font-weight-bold mb-2">${g.flujoTitulo || "Flujo"}</h5>${g.flujo}`
        : "";

    let referencia = "";
    if (g.referencia && g.referencia.length) {
        referencia = g.referencia.map(function (grupo) {
            return `
            <div class="ref-grupo-titulo">${grupo.titulo}</div>
            ${grupo.lista.map(function (b) {
                return `
                <div class="ref-fila">
                    <div class="ref-muestra">${b.muestra}</div>
                    <div>
                        <b>${b.nombre}</b>
                        <div class="ref-desc">${b.desc}</div>
                    </div>
                </div>`;
            }).join("")}`;
        }).join("");
    }

    let pasos = "";
    if (g.pasos && g.pasos.length) {
        pasos = `
        <hr>
        <h5 class="font-weight-bold mb-3">${g.pasosTitulo || "Cómo hacerlo"}</h5>
        <ol class="help-steps">${g.pasos.map(function (p) { return `<li>${p}</li>`; }).join("")}</ol>`;
    }

    let faq = "";
    if (g.faq.length) {
        faq = `<hr><h5 class="font-weight-bold mb-3">Preguntas frecuentes</h5><div class="help-faq">` +
            g.faq.map(function (f) {
                return `<details><summary>${f.p}</summary><div>${f.r}</div></details>`;
            }).join("") + `</div>`;
    }

    let relacionados = "";
    let rel = (g.relacionados || []).filter(function (id) { return visibles.has(id); });
    if (rel.length) {
        relacionados = `<hr><h5 class="font-weight-bold mb-3">Guías relacionadas</h5>` +
            rel.map(function (id) {
                return `<a class="help-chip" data-abrir="${id}" tabindex="0" role="button">
                            <i class="fas ${ayuda[id].icono}"></i> ${ayuda[id].titulo}
                        </a>`;
            }).join("");
    }

    let boton = g.url
        ? `<div><a class="btn btn-primary mt-3" href="${BASE_URL}${g.url}" style="background:#52266E;border-color:#52266E;">
               <i class="fas fa-arrow-right"></i> Ir a ${g.titulo}
           </a></div>`
        : "";

    return `
    <div class="row">

        <div class="col-lg-8">

            ${requisitos}
            ${items}
            ${flujo}
            ${referencia}
            ${pasos}

            <hr>

            <h5 class="font-weight-bold">Consejo</h5>
            <p>${g.consejo}</p>

            ${faq}
            ${relacionados}
            ${boton}

        </div>

        <div class="col-lg-4 text-center d-none d-lg-block">
            <i class="fas ${g.icono} fa-6x text-purple"></i>
        </div>

    </div>`;
}

/* ======================================================
   ABRIR / CERRAR PANEL
====================================================== */
const panel = document.getElementById("panelAyuda");

function abrirGuia(id, actualizarHash) {

    if (!visibles.has(id)) return;

    let g = ayuda[id];

    document.querySelectorAll(".help-card").forEach(function (c) {
        c.classList.toggle("active", c.dataset.modulo === id);
    });

    document.getElementById("panelTitulo").textContent    = g.titulo;
    document.getElementById("panelSubtitulo").textContent = g.subtitulo || "Información del módulo";
    document.getElementById("panelIcono").innerHTML       = `<i class="fas ${g.icono}"></i>`;
    document.getElementById("panelContenido").innerHTML   = construirContenido(g);

    panel.style.display = "block";
    panel.scrollIntoView({ behavior: "smooth", block: "start" });

    if (actualizarHash) {
        history.replaceState(null, "", "#" + id);
    }
}

function cerrarGuia() {

    panel.style.display = "none";

    document.querySelectorAll(".help-card.active").forEach(function (c) {
        c.classList.remove("active");
    });

    history.replaceState(null, "", location.pathname + location.search);

    document.getElementById("zonaBuscador").scrollIntoView({ behavior: "smooth", block: "start" });
}

/* Tarjetas */
document.querySelectorAll(".help-card").forEach(function (card) {

    card.addEventListener("click", function () {
        abrirGuia(this.dataset.modulo, true);
    });

    card.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            abrirGuia(this.dataset.modulo, true);
        }
    });

});

/* Enlaces "Guías relacionadas" (dentro del panel) */
const panelContenido = document.getElementById("panelContenido");

panelContenido.addEventListener("click", function (e) {
    let chip = e.target.closest("[data-abrir]");
    if (chip) abrirGuia(chip.dataset.abrir, true);
});

panelContenido.addEventListener("keydown", function (e) {
    if (e.key !== "Enter" && e.key !== " ") return;
    let chip = e.target.closest("[data-abrir]");
    if (chip) {
        e.preventDefault();
        abrirGuia(chip.dataset.abrir, true);
    }
});

document.getElementById("cerrarPanel").addEventListener("click", cerrarGuia);

document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && panel.style.display === "block") {
        cerrarGuia();
    }
});

/* ======================================================
   BUSCADOR (sin acentos, busca dentro de las guías)
====================================================== */
document.getElementById("buscarModulo").addEventListener("input", function () {

    let texto = normalizar(this.value.trim());
    let total = 0;

    document.querySelectorAll(".help-seccion").forEach(function (seccion) {

        let enSeccion = 0;

        seccion.querySelectorAll(".modulo-card").forEach(function (card) {
            let coincide = card.dataset.buscar.includes(texto);
            card.style.display = coincide ? "" : "none";
            if (coincide) enSeccion++;
        });

        seccion.style.display = enSeccion ? "" : "none";
        total += enSeccion;
    });

    document.getElementById("sinResultados").style.display = total ? "none" : "block";
});

/* ======================================================
   ENLACE DIRECTO (index.php#mascotas)
====================================================== */
function abrirDesdeHash() {
    let id = location.hash.replace("#", "");
    if (id) abrirGuia(id, false);
}

window.addEventListener("hashchange", abrirDesdeHash);
abrirDesdeHash();

</script>

<!-- ======================================================
     CIERRE DE LA ESTRUCTURA ABIERTA EN menu.php
     Si tus otros módulos usan un footer.php, reemplazá
     este bloque por ese include.
====================================================== -->
        </div><!-- /#content -->
    </div><!-- /#content-wrapper -->
</div><!-- /#wrapper -->

<script src="/SoftwareVet/vendor/jquery/jquery.min.js"></script>
<script src="/SoftwareVet/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>