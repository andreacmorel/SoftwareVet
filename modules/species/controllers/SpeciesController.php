<?php

require_once __DIR__ . '/../models/speciesModel.php';

class SpeciesController
{
    private $model;
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->model = new SpeciesModel($conexion);
    }

    public function create(){
    $erroresCampos = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre_especie = trim($_POST['nombre_especie'] ?? '');
        $raza = trim($_POST['raza'] ?? '');

        if (empty($nombre_especie)) {
            $erroresCampos['nombre_especie'] = "El nombre de la especie es obligatorio.";
        } elseif (strlen($nombre_especie) < 3) {
            $erroresCampos['nombre_especie'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($raza)) {
            $erroresCampos['raza'] = "La raza es obligatoria.";
        } elseif (strlen($raza) < 3) {
            $erroresCampos['raza'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($erroresCampos['nombre_especie']) && strlen($nombre_especie) > 50) {
            $erroresCampos['nombre_especie'] = "No puede superar los 50 caracteres.";
        }

        if (empty($erroresCampos['raza']) && strlen($raza) > 50) {
            $erroresCampos['raza'] = "No puede superar los 50 caracteres.";
        }

        if (empty($erroresCampos['nombre_especie']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/', $nombre_especie)) {
            $erroresCampos['nombre_especie'] = "Solo se permiten letras, espacios y guiones.";
        }

        if (empty($erroresCampos['raza']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s\-]+$/', $raza)) {
            $erroresCampos['raza'] = "Solo se permiten letras, espacios y guiones.";
        }

        $nombre_especie = ucfirst(strtolower($nombre_especie));
        $raza = ucfirst(strtolower($raza));

        if (empty($erroresCampos)) {
            if ($this->model->exists($nombre_especie, $raza)) {
                $erroresCampos['raza'] = "Esta especie y raza ya están registradas.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->create($nombre_especie, $raza)) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al registrar especie.";
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
    }

    public function edit($id){
    $erroresCampos = [];

    $id = (int)$id;

    if ($id <= 0) {
        die("ID de especie no válido.");
    }

    $row = $this->model->getById($id);

    if (!$row) {
        die("Especie no encontrada.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre_especie = trim($_POST['nombre_especie'] ?? '');
        $raza = trim($_POST['raza'] ?? '');

        if (empty($nombre_especie)) {
            $erroresCampos['nombre_especie'] = "El nombre de la especie es obligatorio.";
        } elseif (strlen($nombre_especie) < 3) {
            $erroresCampos['nombre_especie'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($raza)) {
            $erroresCampos['raza'] = "La raza es obligatoria.";
        } elseif (strlen($raza) < 3) {
            $erroresCampos['raza'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsForEdit($nombre_especie, $raza, $id)) {
                $erroresCampos['raza'] = "Esta especie y raza ya están registradas.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->update($id, $nombre_especie, $raza)) {
                header("Location: index.php?updated=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al modificar especie.";
            }
        }

        $row['nombre_especie'] = $nombre_especie;
        $row['raza'] = $raza;
    }

    require_once __DIR__ . '/../views/edit.php';
    }

    public function index(){
    $buscar = trim($_GET['buscar'] ?? '');

    $especies = $this->model->getAll($buscar);

    require_once __DIR__ . '/../views/index.php';
    }

    public function delete($id){
        $id = (int)$id;

        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $this->model->delete($id);

        header("Location: index.php?deleted=1");
        exit;
    }
}