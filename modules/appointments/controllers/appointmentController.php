<?php

require_once __DIR__ . '/../models/AppointmentModel.php';

class AppointmentController{

    private $model;

    public function __construct($conexion){

        $this->model = new AppointmentModel($conexion);
    }

    public function index(){

    $filtro_profesional = (int)($_GET['profesional'] ?? 0);
    $filtro_estado = $_GET['estado'] ?? '';
    $filtro_fecha_desde = $_GET['fecha_desde'] ?? '';
    $filtro_fecha_hasta = $_GET['fecha_hasta'] ?? '';

    $result = $this->model->getAll(
        $filtro_profesional,
        $filtro_estado,
        $filtro_fecha_desde,
        $filtro_fecha_hasta
    );

    $resProfiltro = $this->model->getProfessionalsFilter();

    require_once __DIR__ . '/../views/index.php';
    }

    public function create(){
    $erroresCampos = [];

    $fecha = '';
    $hora = '';
    $motivo = '';
    $id_profesional = 0;
    $id_mascota = 0;

    $resProfesionales = $this->model->getProfessionalsForSelect();
    $resMascotas = $this->model->getPetsForSelect();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $fecha = trim($_POST['fecha'] ?? '');
        $hora = trim($_POST['hora'] ?? '');
        $motivo = trim($_POST['motivo'] ?? '');

        $id_profesional = (int)($_POST['id_profesional'] ?? 0);
        $id_mascota = (int)($_POST['id_mascota'] ?? 0);

        if (empty($fecha)) {
            $erroresCampos['fecha'] = "La fecha es obligatoria.";
        } elseif ($fecha < date('Y-m-d')) {
            $erroresCampos['fecha'] = "La fecha no puede ser anterior a la actual.";
        }

        if (empty($hora)) {
            $erroresCampos['hora'] = "La hora es obligatoria.";
        } elseif ($hora < '08:00' || $hora > '21:00') {
            $erroresCampos['hora'] = "El horario debe estar entre 08:00 y 21:00.";
        } elseif ($fecha == date('Y-m-d') && $hora < date('H:i')) {
            $erroresCampos['hora'] = "La hora no puede ser anterior a la actual.";
        }

        if (empty($motivo)) {
            $erroresCampos['motivo'] = "El motivo es obligatorio.";
        } elseif (strlen($motivo) < 3) {
            $erroresCampos['motivo'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($motivo) > 150) {
            $erroresCampos['motivo'] = "No puede superar los 150 caracteres.";
        }

        if ($id_profesional <= 0) {
            $erroresCampos['id_profesional'] = "Debe seleccionar un profesional.";
        } elseif (!$this->model->professionalExists($id_profesional)) {
            $erroresCampos['id_profesional'] = "El profesional seleccionado no es válido.";
        }

        if ($id_mascota <= 0) {
            $erroresCampos['id_mascota'] = "Debe seleccionar una mascota.";
        } elseif (!$this->model->petExists($id_mascota)) {
            $erroresCampos['id_mascota'] = "La mascota seleccionada no es válida.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsAppointment($fecha, $hora, $id_profesional)) {
                $erroresCampos['hora'] = "El profesional ya tiene un turno asignado en esa fecha y hora.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->create($fecha, $hora, $motivo, $id_profesional, $id_mascota)) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al registrar el turno.";
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
    }

    public function edit($id){

    $erroresCampos = [];

    $id_turno = (int)$id;

    if ($id_turno <= 0) {
        header("Location: index.php?error=id");
        exit;
    }

    $datos = $this->model->getById($id_turno);

    if (!$datos) {
        header("Location: index.php?error=noexiste");
        exit;
    }

    if ($datos->estado == 'Completado' || $datos->estado == 'Cancelado') {
        header("Location: index.php?error=estado");
        exit;
    }

    $fecha = $datos->fecha;
    $hora = substr($datos->hora, 0, 5);
    $motivo = $datos->motivo;
    $id_profesional = (int)$datos->id_profesional;
    $id_mascota = (int)$datos->id_mascota;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $fecha = trim($_POST["fecha"] ?? '');
        $hora = trim($_POST["hora"] ?? '');
        $motivo = trim($_POST["motivo"] ?? '');
        $id_profesional = (int)($_POST["id_profesional"] ?? 0);
        $id_mascota = (int)($_POST["id_mascota"] ?? 0);

        if (empty($fecha)) {
            $erroresCampos['fecha'] = "La fecha es obligatoria.";
        } elseif ($fecha < date('Y-m-d')) {
            $erroresCampos['fecha'] = "La fecha no puede ser anterior a la actual.";
        }

        if (empty($hora)) {
            $erroresCampos['hora'] = "La hora es obligatoria.";
        } elseif ($hora < '08:00' || $hora > '20:00') {
            $erroresCampos['hora'] = "El horario debe estar entre 08:00 y 20:00.";
        } elseif ($fecha == date('Y-m-d') && $hora < date('H:i')) {
            $erroresCampos['hora'] = "La hora no puede ser anterior a la actual.";
        }

        if (empty($motivo)) {
            $erroresCampos['motivo'] = "El motivo es obligatorio.";
        } elseif (strlen($motivo) < 3) {
            $erroresCampos['motivo'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($motivo) > 150) {
            $erroresCampos['motivo'] = "No puede superar los 150 caracteres.";
        }

        if ($id_profesional <= 0) {
            $erroresCampos['id_profesional'] = "Debe seleccionar un profesional.";
        }

        if ($id_mascota <= 0) {
            $erroresCampos['id_mascota'] = "Debe seleccionar una mascota.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsForEdit($fecha, $hora, $id_profesional, $id_turno)) {
                $erroresCampos['hora'] = "El profesional ya posee un turno asignado para esa fecha y horario.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->update(
                $id_turno,
                $fecha,
                $hora,
                $motivo,
                $id_profesional,
                $id_mascota
            )) {
                header("Location: index.php?updated=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al modificar el turno.";
            }
        }
    }

    $profesionales = $this->model->getProfessionals();
    $mascotas = $this->model->getPets();

    require_once __DIR__ . '/../views/edit.php';
    }

    public function changeStatus(){

    $estados_validos = ['pendiente', 'confirmado', 'en_atencion', 'completado', 'cancelado'];

    $id_turno = (int)($_POST['id_turno'] ?? 0);
    $estado = $_POST['estado'] ?? '';

    if ($id_turno > 0 && in_array($estado, $estados_validos)) {
        $this->model->updateStatus($id_turno, $estado);
    }

    header("Location: index.php?status=1");
    exit;
    }

    public function exportExcel(){
    $filename = "listado_turnos_" . date('Ymd') . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    $turnos = $this->model->getAllForExcel();

    echo "ID\tFecha\tHora\tMotivo\tProfesional\tMascota\n";

    while ($datos = $turnos->fetch_object()) {
        echo "{$datos->id_turno}\t{$datos->fecha}\t{$datos->hora}\t{$datos->motivo}\t{$datos->profesional}\t{$datos->mascota}\n";
    }

    exit;
    }

    public function delete($id){

        $id = (int)$id;

        if ($id > 0) {
            $this->model->delete($id);
        }

        header("Location: index.php?deleted=1");
        exit;
    }
}