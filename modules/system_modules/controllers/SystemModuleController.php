<?php

require_once __DIR__ . '/../models/SystemModuleModel.php';

class SystemModuleController{
    
    private $model;
    private $conexion;

    public function __construct($conexion){
        
        $this->conexion = $conexion;
        $this->model = new SystemModuleModel($conexion);
    }

    public function create(){
        
    $erroresCampos = [];

    if (!empty($_POST['btnGuardar'])) {

        $nombre_modulo = trim($_POST['nombre_modulo'] ?? '');
        $ruta = trim($_POST['ruta'] ?? '');
        $icono = trim($_POST['icono'] ?? '');

        if (empty($nombre_modulo)) {
            $erroresCampos['nombre_modulo'] = "El nombre del módulo es obligatorio.";
        } elseif (strlen($nombre_modulo) < 3) {
            $erroresCampos['nombre_modulo'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($nombre_modulo) > 50) {
            $erroresCampos['nombre_modulo'] = "No puede superar los 50 caracteres.";
        }

        if (empty($ruta)) {
            $erroresCampos['ruta'] = "La ruta es obligatoria.";
        } elseif (strlen($ruta) > 255) {
            $erroresCampos['ruta'] = "La ruta no puede superar los 255 caracteres.";
        }

        if (!empty($icono) && strlen($icono) > 100) {
            $erroresCampos['icono'] = "El icono no puede superar los 100 caracteres.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsName($nombre_modulo)) {
                $erroresCampos['nombre_modulo'] = "Ya existe un módulo activo con ese nombre.";
            }

            if ($this->model->existsRoute($ruta)) {
                $erroresCampos['ruta'] = "Ya existe un módulo activo con esa ruta.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->create($nombre_modulo, $ruta, $icono)) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al registrar módulo.";
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
    }

    public function edit($id){
        
    $erroresCampos = [];

    $id = (int)$id;

    if ($id <= 0) {
        header("Location: index.php");
        exit;
    }

    $modulo = $this->model->getById($id);

    if (!$modulo) {
        header("Location: index.php");
        exit;
    }

    if (!empty($_POST['btnModificar'])) {

        $nombre_modulo = trim($_POST['nombre_modulo'] ?? '');
        $ruta = trim($_POST['ruta'] ?? '');
        $icono = trim($_POST['icono'] ?? '');

        if (empty($nombre_modulo)) {
            $erroresCampos['nombre_modulo'] = "El nombre del módulo es obligatorio.";
        } elseif (strlen($nombre_modulo) < 3) {
            $erroresCampos['nombre_modulo'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($nombre_modulo) > 50) {
            $erroresCampos['nombre_modulo'] = "No puede superar los 50 caracteres.";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ0-9\s_-]+$/', $nombre_modulo)) {
        $erroresCampos['nombre_modulo'] = "Solo se permiten letras, números, espacios, guion bajo y guion medio.";
        }

        if (empty($ruta)) {
            $erroresCampos['ruta'] = "La ruta es obligatoria.";
        } elseif (strlen($ruta) > 255) {
            $erroresCampos['ruta'] = "La ruta no puede superar los 255 caracteres.";
        }

        if (!empty($icono) && strlen($icono) > 100) {
            $erroresCampos['icono'] = "El icono no puede superar los 100 caracteres.";
        }

        if (empty($erroresCampos)) {

            if ($this->model->existsNameForEdit($nombre_modulo, $id)) {
                $erroresCampos['nombre_modulo'] = "Ya existe un módulo activo con ese nombre.";
            }

            if ($this->model->existsRouteForEdit($ruta, $id)) {
                $erroresCampos['ruta'] = "Ya existe un módulo activo con esa ruta.";
            }
        }

        if (empty($erroresCampos)) {

            if ($this->model->update($id, $nombre_modulo, $ruta, $icono)) {
                header("Location: index.php?updated=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al modificar módulo.";
            }
        }

        $modulo->nombre_modulo = $nombre_modulo;
        $modulo->ruta = $ruta;
        $modulo->icono = $icono;
    }

    require_once __DIR__ . '/../views/edit.php';
    }

    public function index(){

    $buscar = trim($_GET['buscar'] ?? '');

    $modulos = $this->model->getAll($buscar);

    require_once __DIR__ . '/../views/index.php';
    }

    public function delete($id){

        $id = (int)$id;

        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $this->model->delete($id);

        header("Location: index.php");
        exit;
    }
}