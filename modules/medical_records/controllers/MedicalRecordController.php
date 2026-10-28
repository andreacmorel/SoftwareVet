<?php

require_once __DIR__ . '/../models/medicalRecordModel.php';

class MedicalRecordController{
    
    private $model;

    public function __construct($conexion){
        
        $this->model = new MedicalRecordModel($conexion);
    }

    public function index(){

    $buscar = $_GET['buscar'] ?? '';
    $fecha_desde = $_GET['fecha_desde'] ?? '';
    $fecha_hasta = $_GET['fecha_hasta'] ?? '';

    $result = $this->model->getAll($buscar, $fecha_desde, $fecha_hasta);

    require_once __DIR__ . '/../views/index.php';
    }

    public function create(){
        
    $erroresCampos = [];

    $mascotas = $this->model->getPets();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idMascota = (int)($_POST['id_mascota'] ?? 0);
        $fecha = trim($_POST['fecha'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $observacion = trim($_POST['observacion'] ?? '');

        if ($idMascota <= 0) {
            $erroresCampos['id_mascota'] = "Debe seleccionar una mascota.";
        }

        if ($fecha === '') {
            $erroresCampos['fecha'] = "La fecha es obligatoria.";
        } elseif ($fecha > date('Y-m-d')) {
            $erroresCampos['fecha'] = "La fecha no puede ser futura.";
        }

        if ($descripcion === '') {
            $erroresCampos['descripcion'] = "La descripción es obligatoria.";
        } elseif (strlen($descripcion) < 5) {
            $erroresCampos['descripcion'] = "Debe tener al menos 5 caracteres.";
        } elseif (strlen($descripcion) > 500) {
            $erroresCampos['descripcion'] = "No puede superar los 500 caracteres.";
        }

        if (strlen($observacion) > 500) {
            $erroresCampos['observacion'] = "No puede superar los 500 caracteres.";
        }

        $tDuraciones = $_POST['trat_duracion'] ?? [];
        $tDosis = $_POST['trat_dosis'] ?? [];
        $tDescs = $_POST['trat_desc'] ?? [];

        foreach ($tDescs as $i => $desc) {

            $duracion = trim($tDuraciones[$i] ?? '');
            $dosis = trim($tDosis[$i] ?? '');
            $desc = trim($desc ?? '');

            if ($duracion !== '' || $dosis !== '' || $desc !== '') {

                if ($desc === '') {
                    $erroresCampos['tratamientos'] = "Si agrega un tratamiento, debe completar la descripción.";
                    break;
                }

                if (strlen($desc) > 500) {
                    $erroresCampos['tratamientos'] = "La descripción del tratamiento no puede superar los 500 caracteres.";
                    break;
                }

                if (strlen($duracion) > 100) {
                    $erroresCampos['tratamientos'] = "La duración del tratamiento no puede superar los 100 caracteres.";
                    break;
                }

                if (strlen($dosis) > 100) {
                    $erroresCampos['tratamientos'] = "La dosis del tratamiento no puede superar los 100 caracteres.";
                    break;
                }
            }
        }
        
        if (empty($erroresCampos)) {

            $resultado = $this->model->create(
                $fecha,
                $descripcion,
                $observacion,
                $idMascota,
                $tDuraciones,
                $tDosis,
                $tDescs
            );

            if ($resultado) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al registrar la historia clínica. No se guardaron cambios.";
            }
        }

        $postMascota = $idMascota;
        $postFecha = $fecha;
        $postDesc = $descripcion;
        $postObs = $observacion;

    } else {

        $postMascota = (int)($_GET['mascota'] ?? 0);
        $postFecha = date('Y-m-d');
        $postDesc = '';
        $postObs = '';
    }

    require_once __DIR__ . '/../views/create.php';
    }

    public function edit($id){
        
    $id = (int)$id;

    if ($id <= 0) {
        header("Location: index.php");
        exit;
    }

    $mascotas = $this->model->getPets();

    $errors = [];

    $historia = $this->model->getById($id);

    if (!$historia) {
        header("Location: index.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idMascota = (int)($_POST['id_mascota'] ?? 0);
        $fecha = trim($_POST['fecha'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $observacion = trim($_POST['observacion'] ?? '');

        if ($idMascota <= 0) {
            $errors[] = 'Debe seleccionar una mascota.';
        }

        if ($fecha === '') {
            $errors[] = 'La fecha es obligatoria.';
        }

        if (empty($errors)) {
            $this->model->update($id, $idMascota, $fecha, $descripcion, $observacion);

            header("Location: index.php?ok=modificado");
            exit;
        }

        $historia['id_mascota'] = $idMascota;
        $historia['fecha'] = $fecha;
        $historia['descripcion'] = $descripcion;
        $historia['observacion'] = $observacion;
    }

    require_once __DIR__ . '/../views/edit.php';
    }
    
    public function delete($id){
        $id = (int)$id;

        if ($id <= 0) {
            die("ID de historia clinica no válido.");
        }

        if (!$this->model->delete($id)) {
            die("Error al eliminar historia clinica.");
        }

        header("Location: index.php");
        exit;
    }
}