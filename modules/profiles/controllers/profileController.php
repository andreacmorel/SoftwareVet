<?php

require_once __DIR__ . '/../models/ProfileModel.php';

class ProfileController
{
    private $model;
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->model = new ProfileModel($conexion);
    }

    public function create(){
    $erroresCampos = [];

    if (!empty($_POST['btnGuardar'])) {

        $nombre_perfil = trim($_POST['nombre_perfil'] ?? '');

        if (empty($nombre_perfil)) {
            $erroresCampos['nombre_perfil'] = "El nombre del perfil es obligatorio.";
        } elseif (strlen($nombre_perfil) < 3) {
            $erroresCampos['nombre_perfil'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($nombre_perfil) > 50) {
            $erroresCampos['nombre_perfil'] = "No puede superar los 50 caracteres.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->exists($nombre_perfil)) {
                $erroresCampos['nombre_perfil'] = "Ya existe un perfil con ese nombre.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->create($nombre_perfil)) {
                header("Location: index.php?success=1");
                exit;
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
}

    public function edit($id){
    $erroresCampos = [];

    $id = (int)$id;

    if ($id <= 0) {
        die("ID de perfil no válido.");
    }

    $perfilEditar = $this->model->getById($id);

    if (!$perfilEditar) {
        die("Perfil no encontrado.");
    }

    if (!empty($_POST['btnModificar'])) {

        $nombre_perfil = trim($_POST['nombre_perfil'] ?? '');

        if (empty($nombre_perfil)) {
            $erroresCampos['nombre_perfil'] = "El nombre del perfil es obligatorio.";
        } elseif (strlen($nombre_perfil) < 3) {
            $erroresCampos['nombre_perfil'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($nombre_perfil) > 50) {
            $erroresCampos['nombre_perfil'] = "No puede superar los 50 caracteres.";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/', $nombre_perfil)) {
            $erroresCampos['nombre_perfil'] = "Solo se permiten letras y espacios.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsForEdit($nombre_perfil, $id)) {
                $erroresCampos['nombre_perfil'] = "Ya existe otro perfil con ese nombre.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->update($id, $nombre_perfil)) {
                header("Location: index.php?updated=1");
                exit;
            }
        }

        $perfilEditar->nombre_perfil = $nombre_perfil;
    }

    require_once __DIR__ . '/../views/edit.php';
}

    public function assignModules($id){
    $erroresCampos = [];

    $id_perfil = (int)$id;

    $perfil = $this->model->getProfileById($id_perfil);

    if (!$perfil) {
        die("Perfil no encontrado.");
    }

    if (!empty($_POST['btnGuardar'])) {

        if (empty($_POST['modulos'])) {

            $erroresCampos['modulos'] = "Debe seleccionar al menos un módulo.";

        } else {

            $this->model->saveModules(
                $id_perfil,
                $_POST['modulos']
            );

            header("Location: index.php?updated=1");
            exit;
        }
    }

    $modulos = $this->model->getModules();
    $asignados = $this->model->getAssignedModules($id_perfil);

    require_once __DIR__ . '/../views/assign_modules.php';
    }
    
    public function index(){
        
    $buscar = trim($_GET['buscar'] ?? '');

    $perfiles = $this->model->getAll($buscar);

    require_once __DIR__ . '/../views/index.php';
    }

    public function delete($id)
    {
        $id = (int)$id;

        if ($id <= 0) {
            header("Location: index.php?error=id");
            exit;
        }

        if ($id == 1) {
            header("Location: index.php?error=admin");
            exit;
        }

        if ($this->model->hasActiveUsers($id) > 0) {
            header("Location: index.php?error=usuarios");
            exit;
        }

        if ($this->model->delete($id)) {
            header("Location: index.php?deleted=1");
            exit;
        }

        header("Location: index.php?error=delete");
        exit;
    }
}